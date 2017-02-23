<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use App\Model\Entity\Session;
use App\Model\Behavior\PaymentBehavior;
use App\Model\Entity\Liability;
use Cake\Routing\Router;

/**
 * Sessions Controller
 *
 * @property \App\Model\Table\SessionsTable $Sessions
 */
class SessionsController extends AppController
{
    const APPROVED_SESSIONS_FINDER = "approvedSessions";
    const PENDING_SESSIONS_FINDER = "pendingSessions";
    const HISTORIC_SESSIONS_FINDER = "historicSessions";
    const PAID_SESSIONS_FINDER = "paidCoach";
    const UNPAID_SESSIONS_FINDER = "unpaidCoach";
    const REJECT_FROM_CALENDAR = "reject";
    const ACCEPT_FROM_CALENDAR = "accept";


    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->helpers(['TinyMCE.TinyMCE']);

    }
    /**
     * Before filter callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->config('unlockedActions', ['updateStartTime']);
        $this->Auth->allow('updateStartTime');
    }

    /**
     * List of Sesisons
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function sessionList($finder, $columnOrder = 'schedule', $order = 'asc')
    {
        $user = $this->getUser();
        $user = $this->Sessions->Users->get($user['id'], [
            'contain' => ['UserImage']
        ]);
        $this->paginate = [
            'limit' => 5,
            'finder' => [
                $finder => [
                    'userId' => $user["id"],
                    'role' => $user["role"],
                ]
            ],
            'order' =>[
                'Sessions.' . $columnOrder => $order
            ]
        ];
        $sessions = $this->paginate($this->Sessions);
        $this->set('user', $user);
        $this->set('timezone', $this->request->cookies['timezone']);
        $this->set(compact('sessions'));
        $this->set('_serialize', ['sessions','users']);
    }
    /**
     * Approved Sessions
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function approved($id = null)
    {
        $this->sessionList(self::APPROVED_SESSIONS_FINDER);
        if ($this->isCoach($this->getUser())){ 
            $this->render("approved_coach");
        }
        else{
            $this->render("approved_user");
        }
    }

    /**
     * View of Pending Sessions.
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function pending($id = null)
    {   
        $this->sessionList(self::PENDING_SESSIONS_FINDER);
        if ($this->isCoach($this->getUser())){ 
            $this->render("pending_coach");
        }
        else{
            $this->render("pending_user");
        }
    }

    /**
     * View of the historic of the Sessions.
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function historic($id = null)
    {   
        $this->set('statusArray', $this->Sessions->getStatusArrayHistoric());
        $this->sessionList(self::HISTORIC_SESSIONS_FINDER, 'modified', 'desc');
        if ($this->isCoach($this->getUser())) {
            $this->render("historic_coach");
        }
        else{
            $this->render("historic_user");
        }
    }

    /**
     * View method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
    	$user = $this->getUser();
        $session = $this->Sessions->find('data',[
            'id' => $id,
            'userId' => $user["id"],
            'role' => $user["role"]
        ])
        ->first();
        $this->set('timezone', $this->request->cookies['timezone']);
        $this->set('user', $user);
        $this->set('session', $session);
        $this->set('isCoach', $this->isCoach($user));
        $this->set('_serialize', ['session']);
        if ($this->Sessions->isCanceledRejectedNotPerformed($session)){
            $this->set('statusArray', $this->Sessions->getStatusArrayHistoric());
            $this->render("view_canceled_rejected");
        } else {
            if ($this->isCoach($user)){
                $this->renderCoachView($session, $user);
            } else {
                $this->renderUserView($session, $user);
            }
        }
    }

    /**
     * Render the appropriate template for viewing the session for coach
     *
     * @param int $status session status
     * @return \Cake\Network\Response|null
     */
    public function renderCoachView($session, $user)
    {
        switch ($session->status) {
            case Session::STATUS_PENDING:
                $this->render("view_pending_coach");
                break;
            case Session::STATUS_APPROVED:
                $response = $this->Sessions->getUrl($session,$user);
                $this->set('url',$response);
                $this->render("view_approved_coach");
                break;
            case Session::STATUS_RUNNING:
                $response = $this->Sessions->getUrl($session,$user);
                $this->set('url',$response);
                $this->render("view_approved_coach");
                break;
            case Session::STATUS_PAST:
                $this->render("view_historic_coach");
                break;
        }
    }

    /**
     * Render the appropriate template for viewing the session for user
     *
     * @param int $status session status
     * @return \Cake\Network\Response|null
     */
    public function renderUserView($session, $user)
    {
        switch ($session->status) {
            case Session::STATUS_PENDING:
                $this->render("view_pending_user");
                break;
            case Session::STATUS_APPROVED:
                $response = $this->Sessions->getUrl($session,$user);
                $this->set('url',$response);
                $this->render("view_approved_user");
                break;
            case Session::STATUS_RUNNING:
                $response = $this->Sessions->getUrl($session,$user);
                $this->set('url',$response);
                $this->render("view_approved_user");
                break;
            case Session::STATUS_PAST:
                $this->render("view_historic_user");
                break;
        }
    }

    /**
     * rate method
     *
     * @param string|null $id Session id.
     */
    public function rate($id = null)
    {
        if($this->isCoach($this->getUser())) {
            return $this->redirect([
                'controller' => 'Sessions', 
                'action' => 'rateCoach'
            ]);
        }
        else {
            return $this->redirect([
                'controller' => 'Sessions', 
                'action' => 'rateUser'
            ]);
        }
    }
    
    /**
     * rate method for coaches
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function rateCoach($id = null)
    {

        $user =$this->getUser();
        $appSession = $this->request->session();
        $startTime = $id ? null: $appSession->read('Class.startTime');
        $id = $id ? $id: $appSession->read('Class.id');
        
        if (!$id) {
            $this->Flash->error(__('Invalid Action'));
            return $this->redirect(['action' => 'display','controller' => 'Pages']);
        }
        $session = $this->Sessions->get($id);
        $session['duration'] = $session['duration'] ?: $this->Sessions->setTime($startTime);
        $session['status'] = Session::STATUS_PAST;
        $this->Sessions->save($session);
        if ($this->request->is('post')) {      
            $session = $this->Sessions->patchEntity($session,$this->request->data);
            if ($this->Sessions->save($session)) {
                $appSession->delete('Class.id');
                $this->Flash->success(__('Thank you for your rating.'));
                return $this->redirect(['action' => 'display','controller' => 'Pages']);
            } else {
                $this->Flash->error(__('Your rating could not be saved. Please, try again.'));
            }
        }
        $this->set('session', $session);
        $this->set('_serialize', ['session']);
    }

    /**
     * rate method for users
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function rateUser($id = null)
    {
        $user =$this->getUser();
        $appSession = $this->request->session();
        $id = $id ? $id: $appSession->read('Class.id');
        if (!$id) {
            $this->Flash->error(__('Invalid Action'));
            return $this->redirect(['action' => 'display','controller' => 'Pages']);
        }

        $session = $this->Sessions->get($id);
        if ($this->request->is('post')) {      
            $session = $this->Sessions->patchEntity($session,$this->request->data);
            if ($this->Sessions->save($session)) {
                $this->Sessions->afterUserRate($session, $appSession);
                $this->Flash->success(__('Thank you for your rating.'));
                return $this->redirect(['action' => 'display','controller' => 'Pages']);
            } else {
                $this->Flash->error(__('Your rating could not be saved. Please, try again.'));
            }
        }

        $this->set('session', $session);
        $this->set('_serialize', ['session']);
    }

    /**
     * Add method
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */

    ##&&me comunico con el calendario en checkAvailability, no se maneja un error de conexión
    ##&&me comunico con el calendario en listBusy, solamente cuando checkAvailabily retorna no vacío. no se maneja un error de conexión
    ##&&me comunico con el calendario en shceduleand send emails. debo guardar de nuevo la sesión ahí por el id. no se maneja error de conexión
    public function add($coachId = null, $topicId = null)
    {   
        $timezone = $this->request->cookies['timezone'];
        $user = $this->getUser();
        if(!$this->Sessions->checkUserCard($user)){
            $this->Flash->error(__('Please, add your payment information first so you can purchase a session.'));
            return $this->redirect(['controller' => 'PaymentInfos','action' => 'add', 
                serialize(['controller' => 'sessions', 'action' => 'add', $topicId])]);
        }
        $topics = $this->Sessions->Topics->getTopicsList($coachId);
        $topic = !$topicId ? null : $this->Sessions->Topics->get($topicId, ['contain' => ['TopicImage']]);
        $session = $this->Sessions->newEntity();
        if ($this->request->is('post')) {
            $data = $this->Sessions->fixData($session, $topic, $user['id'], $this->request->data);
            list($coachBusyList, $userBusyList) = $this->Sessions->Users->checkAvailability($topic->coach_id, $user['id'], 
                $data['schedule'], $topic->duration, $timezone);
            if ($userBusyList) {
                $this->Flash->error(__('You have already scheduled or requested a session in that time. Please select another time'));
            } elseif ($coachBusyList){
                $this->Flash->error(__('The coach is not available in that time. Please select another time'));
                $this->set('listBusy', $this->Sessions->Users->listBusy($topic->coach_id, $data['schedule'], $timezone));
            } else {
                $session = $this->Sessions->patchEntity($session, $data);
                if ($this->Sessions->save($session)) {
                    if ($this->Sessions->scheduleAndSendEmails($session, $data['schedule'], $topic->duration, $timezone)) {
                        $this->Flash->success(__('The session has been requested.'));
                        return $this->redirect(['action' => 'pending', $user['id'], 'controller' => 'Sessions']);
                    } else {
                        $this->Flash->error(__('The session could not be saved. Please, try again.'));
                    } 
                }
            }
        }
        $this->set('topic', $topic);
        $this->set('coachId', $coachId);
        $this->set('topicSelector', $topics);
        $this->set('session', $session);
        $this->set('_serialize', ['session', 'topicSelector']);
    }

    /**
     * reject session method
     *
     * @param string|null $id Session id.
     * @param $controller the controller to redirect
     * @param the action to redirect
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    ##&&Mecomunico con el caledario en rejectSession para eliminar el evento. No se maneja errores
    public function rejectSession($id = null, $controller = 'Sessions', $action = 'pending')
    {
        $this->request->allowMethod(['post','get']);
        $session = $this->Sessions->get($id);
        $session = $this->Sessions->rejectSession($session);
        if ($this->Sessions->save($session)) {
            $this->Sessions->sendEmail($session,'rejectMail');
            $this->Flash->success(__('The session has been rejected.'));
        } else {
            $this->Flash->error(__('The session could not be rejected. Please try again later'));
        }

        return $this->redirect(
            ['controller' => $controller, 'action' => $action, $this->getUser()['id']] 
        );
    }

    /**
     * approve session method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    ##&&Me comunico con el calendario en confirmEvent. Son 2 request, uno para asegurarse que no tiene nada agendado y otro para confirmar
    ##&&Me comunico con stripe y el calendario en paySession. manejo errores para el pago pero no para el calendario.
    ##&&Me counico con la plataforma en scheduleSession, no manejo errores
    public function approveSession($id = null, $controller = 'Sessions', $action = 'pending')
    {
        $session = $this->Sessions->find('containUserTopic', [
            'id' => $id
        ])
        ->first();
        if (!$this->Sessions->confirmEvent($session, $this->request->cookies['timezone'])) {
            $this->Flash->error(__('The event could not be scheduled. It is possible that you have another session scheduled in that time.'));
        } else {
            $response = $this->Sessions->paySession($session);
            if($response['status'] === PaymentBehavior::ERROR_STATUS){
                $this->Flash->error(__('Payment error, we will conctact the cochee. Please try again later'));
                $this->Sessions->sendEmail($session,'paymentErrorMail',$response['message']);
    
            } else {
                $this->Sessions->createLiability($session);
                $session['status'] = Session::STATUS_APPROVED;
                $session['external_class_id'] = $this->Sessions->scheduleSession($session);
    
                if ($this->Sessions->save($session)) {
                    $this->Sessions->sendEmail($session,'approveMail');
                    $this->Flash->success(__('The session has been confirmed.'));
                } else {
                    $this->Flash->error(__('The session could not be confirmed. Please try again later'));
                }
            }
        }
        return $this->redirect([
            'controller' => $controller, 'action' => $action, $this->getUser()['id']
        ]);
    }

    /**
     * cancel a approved session method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    ##&& me comunico con la plataforma y el calendario en cancelSession. No manejo los errores de ninguna
    public function cancelSession($id, $action = 'approved')
    {
        $this->request->allowMethod(['post','get']);
        $session = $this->Sessions->find('containUserPendingLiability', [
            'id' => (int) $this->request->data['id'],
        ])
        ->first();
        $session = $this->Sessions->cancelSession($session, $this->request->data['observation']);
        if ($this->Sessions->save($session)) {
            $this->Sessions->sendEmail($session, $this->getUser()['role'] . 'CancelMail', $session->coach_comments);
            $this->Flash->success(__('The session has been Canceled.'));
        } else {
            $this->Flash->error(__('The session could not be canceled. Please try again later'));
        }
        return $this->redirect(
            ['action' => $action, $this->getUser()['id']]
        );
        
    }

    /**
     * cancel a requested session method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    ##&&ME comunico con el calendario en canRequestSession. No manejo errores
    public function cancelRequest($id)
    {
        $this->request->allowMethod(['post','get']);
        $session = $this->Sessions->get($id);
        $this->Sessions->cancelRequestSession($session);
        if ($this->Sessions->save($session)) {
            $this->Flash->success(__('The request has been Canceled.'));
        } else {
            $this->Flash->error(__('The request could not be canceled. Please try again later'));
        }
        return $this->redirect(
            ['action' => 'pending', $this->getUser()['id']]
        );
        
    }

    /**
     * Stores in app session the startime and id of the class session that was attended
     *
     * @param string|null $id Session id.
     */
    public function updateStartTime($id = NULL)
    {   
        $this->autoRender = false;
        $this->request->allowMethod(['post','get']);
        $appSession = $this->request->session();
        $session = $this->Sessions->get($id);
        $session['status'] = Session::STATUS_RUNNING;
        $this->Sessions->save($session);
        $appSession->write('Class.id', $id);
        $appSession->write('Class.startTime',(string) time());
    }

    /**
     * Show paid sessions of a coach
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function paidSessions($id = NULL)
    {
        $this->set('statusArray',$this->Sessions->getStatusArray());
        $this->sessionList(self::PAID_SESSIONS_FINDER);
    }

    /**
     * Show unpaid sessions of a coach
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function unpaidSessions($id = NULL)
    {
        $this->set('statusArray',$this->Sessions->getStatusArray());
        $this->sessionList(self::UNPAID_SESSIONS_FINDER,null);
    }

    /**
     * reject session from calendar method
     *
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function calendarRequestSession()
    {
        $this->request->allowMethod(['post']);
        $id = $this->request->data['id'];
        if ($this->request->data['method'] === self::REJECT_FROM_CALENDAR) {
            $this->rejectSession($id, 'AppUsers', 'agenda');
        } else {
            $this->approveSession($id, 'AppUsers', 'agenda');
        }
        
    }
}
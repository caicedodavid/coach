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
        $this->set('statusArray',$this->getStatusArray());
        $this->sessionList(self::HISTORIC_SESSIONS_FINDER,'modified','desc');
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
        $this->set('user', $user);
        $this->set('session', $session);
        $this->set('isCoach', $this->isCoach($user));
        $this->set('_serialize', ['session']);
        if (($session['status'] === Session::STATUS_CANCELED) or ($session['status'] === Session::STATUS_REJECTED)){
            $this->set('statusArray',$this->getStatusArray());
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
                $appSession->delete('Class.id');
                $this->Sessions->Users->updateCoachRating($session->coach_id);
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
    public function add($coachId = null, $topicId = null)
    {   
        $user = $this->getUser();
        if(!$this->Sessions->checkUserCard($user)){
            $this->Flash->error(__('Please, add your payment information first so you can purchase a session.'));
            return $this->redirect(['controller' => 'PaymentInfos','action' => 'add', 
                serialize(['controller' => 'sessions', 'action' => 'add', $topicId])]);
        }
        $topics = $this->Sessions->Topics->getTopicsList($coachId);
        $topic = !$topicId ? null : $this->Sessions->Topics->get($topicId, [
            'contain' => ['TopicImage']
        ]);
        $session = $this->Sessions->newEntity();
        $session->subject = $topic['name'] ? $topic['name'] : null;
        if ($this->request->is('post')) {
            $data = $this->Sessions->fixData($session, $topic, $user['id'], $this->request->data);      
            $session = $this->Sessions->patchEntity($session, $data);
            if ($this->Sessions->save($session)) {
                $this->Sessions->sendRequestEmails($session);
                $this->Flash->success(__('The session has been requested.'));
                return $this->redirect(['action' => 'pending', $user['id'], 'controller' => 'Sessions']);
            } else {
                $this->Flash->error(__('The session could not be saved. Please, try again.'));
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
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function rejectSession($id = null)
    {
        $this->request->allowMethod(['post','get']);
        $session = $this->Sessions->get($id);
        $session['status'] = Session::STATUS_REJECTED;
        if ($this->Sessions->save($session)) {
            $this->Sessions->sendEmail($session,'rejectMail');
            $this->Flash->success(__('The session has been rejected.'));
        } else {
            $this->Flash->error(__('The session could not be rejected. Please try again later'));
        }

        return $this->redirect(
            ['action' => 'pending',$this->getUser()['id']] 
        );
    }

    /**
     * approve session method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function approveSession($id)
    {
        $session = $this->Sessions->find('containUserCoach', [
            'id' => $id
        ])
        ->first();
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
        return $this->redirect([
            'action' => 'pending', $this->getUser()['id']
        ]);
    }

    /**
     * cancel a approved session method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function cancelSession($id, $action = 'approved')
    {
        $this->request->allowMethod(['post','get']);
        $session = $this->Sessions->find('containUserPendingLiability', [
            'id' => (int) $this->request->data['id'],
        ])
        ->first();
        $session->coach_comments = $this->request->data['observation'];
        $session->status = Session::STATUS_CANCELED;
        $this->Sessions->refundSession($session, $this->isCoach($this->getUser()));
        $this->Sessions->removeClass($session);
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
        $this->set('statusArray',$this->getStatusArray());
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
        $this->set('statusArray',$this->getStatusArray());
        $this->sessionList(self::UNPAID_SESSIONS_FINDER,null);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->helpers(['TinyMCE.TinyMCE']);

    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->config('unlockedActions', ['updateStartTime']);
        $this->Auth->allow('updateStartTime');
    }

    /**
     * Returns Array with Key/Value StatusValue/StatusString
     *
     * @return Array
     */
    public function getStatusArray() {

        return [
            Session::STATUS_PENDING => 'Pending',
            Session::STATUS_APPROVED => 'Approved',
            Session::STATUS_RUNNING => 'Running',
            Session::STATUS_REJECTED => 'Rejected',
            Session::STATUS_CANCELED => 'Canceled',
            Session::STATUS_PAST => 'Past'
        ];
    }
}

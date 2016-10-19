<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use App\Model\Entity\Session;

/**
 * Sessions Controller
 *
 * @property \App\Model\Table\SessionsTable $Sessions
 */
class SessionsController extends AppController
{
    /**
     * Approved Sessions view
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function approved()
    {
    	$user = $this->getUser();
		$this->paginate = [
            'limit' => 2,
            'finder' => [
            	'approvedSessions' => ['user' => $user]
            ],
        ];
        $approvedSessions = $this->paginate($this->Sessions);
        $this->set(compact('approvedSessions'));
        $this->set('_serialize', ['approvedSessions']);
    }

    /**
     * View of Pending Sessions.
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function pending()
    {   
        $user =$this->getUser();
        $this->paginate = [
            'limit' => 2,
            'finder' => [
                'pendingSessions' => ['user' => $user]
            ],
        ];
        $pendingSessions = $this->paginate($this->Sessions);
        $this->set(compact('pendingSessions'));
        $this->set('_serialize', ['pendingSessions']);
        if ($this->isCoach($user)): 
            $this->render("pending_coach");
        else:
            $this->render("pending_user");
        endif;
    }


    /**
     * View of the historic of the Sessions.
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function historic()
    {   
        $user =$this->getUser();
        $this->paginate = [
            'limit' => 2,
            'finder' => [
                'historicSessions' => ['user' => $user]
            ],
        ];
        $historicSessions = $this->paginate($this->Sessions);
        $this->set(compact('historicSessions'));
        $this->set('_serialize', ['historicSessions']);
        $this->set('statusArray',$this->getStatusArray());
        if ($this->isCoach($user)) {
            $this->render("historic_coach");
        }
    }

    /**
     * View method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null, $status = null)
    {
    	$user = $this->getUser();
        $session = $this->Sessions->find('data',[
            'id' => $id,
            'user' => $user
        ])
        ->first();
        $response = $this->Sessions->getUrl($session,$user);
        $this->set('url',$response);
        $this->set('session', $session);
        $this->set('_serialize', ['session']);
        if ($status == Session::STATUS_PAST) {
            $this->render("view_historic");
        }

    }
    /**
     * View a past, rejected or canceled session
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function viewHistoric($id = null)
    {
        $user =$this->view($id);
    }


    /**
     * View pending sessions method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function viewPending($id = null)
    {
        $user = $this->view($id); 
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
            ]
        }
        else {
            return $this->redirect([
                'controller' => 'Sessions', 
                'action' => 'rateUser'
            ]
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
        $session["coach_time"] = $session["coach_time"] ?: $this->Sessions->setTime($startTime);
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
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function rateUser()
    {

        $user =$this->getUser();
        $appSession = $this->request->session();
        $id = $appSession->read('Class.id');
        if (!$id) {
            $this->Flash->error(__('Invalid Action'));
            return $this->redirect(['action' => 'display','controller' => 'Pages']);
        }

        $session = $this->Sessions->get($id);
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
     * Add method
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($coachId,$coachName)
    {   
        $session = $this->Sessions->newEntity();
        if ($this->request->is('post')) {         
            $session["user_id"] = $this->getUser()['id'];
            $session["coach_id"] = $coachId;
            $data = $this->Sessions->fixSchedule($this->request->data);
            $session = $this->Sessions->patchEntity($session,$data);
            
            if ($this->Sessions->save($session)) {
                $this->Sessions->sendRequestEmails($session);
                $this->Flash->success(__('The session has been requested.'));
                return $this->redirect(['action' => 'display','controller' => 'Pages']);
            } else {
                $this->Flash->error(__('The session could not be saved. Please, try again.'));
            }
        }
        $this->set('coach',$coachName);
        $this->set('session',$session);
        $this->set('_serialize', ['session']);
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
            ['action' => 'pending']
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
        $session = $this->Sessions->get($id);
        $session['status'] = Session::STATUS_APPROVED;
        $session['external_class_id'] = $this->Sessions->scheduleSession($session);
        if ($this->Sessions->save($session)) {
            $this->Sessions->sendEmail($session,'approveMail');
            $this->Flash->success(__('The session has been confirmed.'));
        } else {
            $this->Flash->error(__('The session could not be confirmed. Please try again later'));
        }
        return $this->redirect([
            'action' => 'pending'
        ]);
    }

    /**
     * cancel a approved session method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function cancelSession($id)
    {
        $this->request->allowMethod(['post','get']);
        $session = $this->Sessions->get($id);
        $session['status'] = Session::STATUS_CANCELED;
        $this->Sessions->removeClass($session);
        if ($this->Sessions->save($session)) {
            $this->Sessions->sendEmail($session,$this->getUser()['role'] . 'CancelMail');
            $this->Flash->success(__('The session has been Canceled.'));
        } else {
            $this->Flash->error(__('The session could not be canceled. Please try again later'));
        }
        return $this->redirect(
            ['action' => 'approved']
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
        $appSession->write('Class.id',$id);
        $appSession->write('Class.startTime',(string) time());
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

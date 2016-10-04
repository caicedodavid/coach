<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

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
    	$user =$this->getUser();
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
    	$user =$this->getUser();
        $session = $this->Sessions->get($id, [
            'contain' => [
            	($this->isCoach($user) ? 'Users' : 'Coaches')
            ]
        ]);
        $response = $this->Sessions->getUrl($session,$user);
        $this->set('url',$response);
        $this->set('session', $session);
        $this->set('_serialize', ['session']);
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
        $user =$this->getUser();
        $session = $this->Sessions->get($id, [
            'contain' => [
                ($this->isCoach($user) ? 'Users' : 'Coaches')
            ]
        ]);
        $this->set('session', $session);
        $this->set('_serialize', ['session']);
    }

    /**
     * rate method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function rate()
    {
        $user =$this->Auth->user();
        $appSession = $this->request->session();
        $id = $appSession->read('Class.id');
        $startTime = $appSession->read('Class.startTime');
        if (!$id){
            $this->Flash->error(__('Invalid Action'));
            return $this->redirect(['action' => 'display','controller' => 'Pages']);
        }
        $session = $this->Sessions->get($id);
        $session = $this->Sessions->setTime($session,$this->isCoach($user),$startTime);
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
        if($this->isCoach($user)):
            $this->render("rate_coach");
        else:
            $this->render("rate_user");
        endif;
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
                $this->Sessions->sendEmails($session);
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
        $session['status'] = STATUS_REJECTED;
        if ($this->Sessions->save($session)) {
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
        //$this->autoRender = false;
        //$this->request->allowMethod(['post','get']);
        $session = $this->Sessions->get($id);
        $session['status'] = STATUS_APPROVED;
        $session['external_class_id'] = $this->Sessions->scheduleSession($session);
        if ($this->Sessions->save($session)) {
            $this->Flash->success(__('The session has been confirmed.'));
        } else {
            $this->Flash->error(__('The session could not be confirmed. Please try again later'));
        }
        return $this->redirect(
            ['action' => 'pending']
        );
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
        $session['status'] = STATUS_CANCELED;
        $this->Sessions->removeClass($session);
        if ($this->Sessions->save($session)) {
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

        $this->request->allowMethod(['post','get']);
        $appSession = $this->request->session();
        $appSession->write('Class.id',$id);
        $appSession->write('Class.startTime',(string) time());
        $this->autoRender = false;
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
}

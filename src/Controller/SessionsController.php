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
    	$user =$this->Auth->user();
		$this->paginate = [
            'limit' => 2,
            'finder' => [
            	'ApprovedSessions' => ['user' => $user]
            ],
        ];
        $approvedSessions = $this->paginate($this->Sessions);
        $this->set(compact('approvedSessions'));
        $this->set('_serialize', ['approvedSessions']);
        $this->set('coach',$user['role']==='coach');
        if ($user["role"] === 'coach'): 
            $this->render("approved_coach");
        elseif($user["role"] === 'user'):
            $this->render("approved_user");
        endif;

    }

    /**
     * View of Pending Sessions.
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function pending()
    {   
        $user =$this->Auth->user();
        $this->paginate = [
            'limit' => 2,
            'finder' => [
                'PendingSessions' => ['user' => $user]
            ],
        ];
        $pendingSessions = $this->paginate($this->Sessions);
        $this->set(compact('pendingSessions'));
        $this->set('_serialize', ['pendingSessions']);
        $this->set('coach',$user['role']==='coach');
        if ($user["role"] === 'coach'): 
            $this->render("pending_coach");
        elseif($user["role"] === 'user'):
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
        $user =$this->Auth->user();
        $this->paginate = [
            'limit' => 2,
            'finder' => [
                'HistoricSessions' => ['user' => $user]
            ],
        ];
        $historicSessions = $this->paginate($this->Sessions);
        $this->set(compact('historicSessions'));
        $this->set('_serialize', ['historicSessions']);
        $this->set('coach',$user['role']==='coach');
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
    	$user =$this->Auth->user();
        $session = $this->Sessions->get($id, [
            'contain' => [
            	($user["role"] === 'coach' ? 'Users' : 'Coaches')
            ]
        ]);
        $response = $this->Sessions->getUrl($session,$user);
        $this->set('url',$response);
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
            $session["user_id"] = $this->Auth->user()['id'];
            $session["coach_id"] = $coachId;
            $session = $this->Sessions->scheduleSession($session, $this->request->data);

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
        $session['status'] ='rejected';
        echo $this->Sessions->removeClass($session);
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
        $session['status'] ='approved';
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
        $session['status'] ='canceled';
        echo $this->Sessions->removeClass($session);
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

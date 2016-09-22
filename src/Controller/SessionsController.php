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
     * Index method
     * @return \Cake\Network\Response|null
     * Show the sessions scheduled by a user/coach
     */ 
    public function index()
    {
    	$user =$this->Auth->user();
		$this->paginate = [
            'limit' => 10,
            'finder' => [
            	'Sessions' => ['user' => $user]
            ],
        ];

        $sessions = $this->paginate($this->Sessions);
        $this->set(compact('sessions'));
        $this->set('_serialize', ['session']);
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
    public function rate($id = null)
    {
        $user =$this->Auth->user();
        $session = $this->Sessions->find('all')
            ->where(['Sessions.external_class_id'=>$id])
            ->contain($user["role"] === 'coach' ? 'Users' : 'Coaches')
            ->first();

        $response = $this->Sessions->getSessionData($session);
        debug($response);
        $fu = $foo;
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
            $session["user_id"] = $this->Auth->user()['id'];
            $session["coach_id"] = $coachId;
            $session = $this->Sessions->patchEntity($session, $this->request->data);

            if ($this->Sessions->save($session)) {
                $this->Flash->success(__('The session has been saved.'));
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
     * Edit method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.

    public function edit($id = null)
    {
        $session = $this->Sessions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $session = $this->Sessions->patchEntity($session, $this->request->data);
            if ($this->Sessions->save($session)) {
                $this->Flash->success(__('The session has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The session could not be saved. Please, try again.'));
            }
        }
        $users = $this->Sessions->Users->find('list', ['limit' => 200]);
        $coaches = $this->Sessions->Coaches->find('list', ['limit' => 200]);
        $this->set(compact('session', 'users', 'coaches'));
        $this->set('_serialize', ['session']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Session id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $session = $this->Sessions->get($id);
        if ($this->Sessions->delete($session)) {
            $this->Flash->success(__('The session has been deleted.'));
        } else {
            $this->Flash->error(__('The session could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->helpers(['TinyMCE.TinyMCE']);
    }
}

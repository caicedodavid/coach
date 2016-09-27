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
     * @return \Cake\Network\Response|null Refresh page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        debug($id);
        $this->request->allowMethod(['post', 'delete']);
        $session = $this->Sessions->get($id);
        $session['status'] ='rejected';
        if ($this->Sessions->save($session)) {
            $this->Flash->success(__('The session has been rejected.'));
        } else {
            $this->Flash->error(__('The session could not be rejected. Please try again later'));
        }

        return $this->redirect($this->here);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->helpers(['TinyMCE.TinyMCE']);
    }
}

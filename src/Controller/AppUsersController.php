<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Error\Debugger;
use Cake\Event\Event;

class AppUsersController extends AppController
{

    /**
     * Index method for non-coach users
     *
     * @return \Cake\Network\Response|null
     */
    public function coaches()
    {
        $this->paginate = [
            'limit' => 5,
            'finder' => 'Coaches',
        ];
        $users = $this->paginate($this->AppUsers);
        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
        if ($this->request->is('ajax')) {
            $this->render('pagination_container');
        }

    }

    /**
     * Override loadModel to load specific users table
     * @param string $modelClass model class
     * @param string $type type
     * @return Table
     */
    public function loadModel($modelClass = null, $type = 'Table')
    {
        return parent::loadModel('Users');
    }

    public function view($id = null)
    {
        $user = $this->AppUsers->get($id, [
            'contain' => ['UserImage']
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
        
    }
    /**
     * Edit method
     *
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $user = $this->AppUsers->find()
            ->where(['Users.id' => $this->Auth->user()['id']])
            ->contain('UserImage')
            ->first();
        
        $user["birthdate"] = date('Y-m-d',strtotime($user["birthdate"]));
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;

            if(!$data["user_image"]["file"]["size"]){
                unset($data["user_image"]);
            }
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {

                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'display','controller' => 'Pages']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }
    /**
     * beforeRender, loading TinyMce editor
     *
     * @param Event $event
     * @return void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->helpers(['TinyMCE.TinyMCE']);
    }
    /**
     * beforeFilter, allowing coaches view
     *
     * @param Event $event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['coaches','view']);
    }
}

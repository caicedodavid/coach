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
        $this->set('blank', $this->blankImage());
        $users = $this->paginate($this->AppUsers);
        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
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
        $this->set('blank', $this->blankImage());
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
            ->contain('UserImage');
            
        $user=$user->first();
        //$user = $this->AppUsers->get($this->Auth->user()['id']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {

                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'display','controller' => 'Pages']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set('blank', $this->blankImage());
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }
    /**
     * beforeRender, loading bbcode editor
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
     *blanckImage, retrning blank avatar
     *
     * @param Event $event
     * @return image
     */
    protected function blankImage()
    {
        $super = $this->AppUsers->find()
            ->where(['Users.username' => 'superadmin'])
            ->contain('UserImage')
            ->first();
        return $super['user_image'];
    }
}

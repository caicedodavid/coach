<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Utility\Hash;
use CakeDC\Users\Controller\Component\UsersAuthComponent;
use CakeDC\Users\Controller\UsersController;
//use App\Controller\UsersController as User;
//use Cake\ORM\TableRegistry;
//use Cake\ORM\Query;


/**
 * Class AppUsersController
 *
 * @package App\Controller
 *
 * @property \App\Model\Table\AppUsersTable $AppUsers
 */
class AppUsersController extends UsersController
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
            'finder' => 'coaches',
            'order' =>[
                'AppUsers.rating' => 'desc'
            ]
        ];
        $users = $this->paginate($this->AppUsers);
        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
        if ($this->request->is('ajax')) {
            $this->render('list');
        }

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
    public function edit($id = NULL)
    {
        $user = $this->AppUsers->find()
            ->where(['AppUsers.id' => $this->getUser()["id"]])
            ->contain('UserImage')
            ->first();
        
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data["birthdate"] = date('Y-m-d',strtotime($data["birthdate"]));
            if(!$data["user_image"]["file"]["size"]){
                unset($data["user_image"]);
            }
            $user = $this->AppUsers->patchEntity($user, $data);
            if ($this->AppUsers->save($user)) {

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
     * Register a new user
     *
     * @throws NotFoundException
     * @return type
     */
    public function register()
    {

        if ($this->request->is('post')) {
            $role = $this->request->data['role'];
            $this->eventManager()->on(UsersAuthComponent::EVENT_AFTER_REGISTER,[],function(Event $event) use ($role) {
                    $user = Hash::get($event->data, 'user');
                    $this->AppUsers->setRole($user->id, $role);
                    $this->redirect('/');
                });
        }
        $this->set('role', $this->request->pass[0]);
        parent::register();
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
        parent::beforeFilter($event);
        $this->Auth->allow(['coaches','view']);
    }
}

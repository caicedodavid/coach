<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Utility\Hash;
use CakeDC\Users\Controller\Component\UsersAuthComponent;
use CakeDC\Users\Controller\UsersController;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Event\EventManager;
use App\CalendarAdapters\Calendar;
use Cake\Core\Configure;


/**
 * Class AppUsersController
 *
 * @package App\Controller
 *
 * @property \App\Model\Table\AppUsersTable $AppUsers
 */
class AppUsersController extends UsersController
{
    const PROFILE_TABS_PROFILE = 1;
    const PROFILE_TABS_SESSIONS = 2;
    const PROFILE_TABS_TOPICS = 3;
    const PROFILE_TABS_PAYMENT_INFOS = 4;
    const PROFILE_TABS_LIABILITIES = 5;
    const PROFILE_TABS_PURCHASES = 6;
    const PROFILE_TABS_AGENDA = 7;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['coaches','coachProfile', 'myProfile', 'loginError']);
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
     * Index method for non-coach users
     *
     * @return \Cake\Network\Response|null
     */
    public function coaches()
    {
        $this->paginate = [
            'limit' => 8,
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
    /**
     * View method 
     *
     * @return \Cake\Network\Response|null
     */

    public function view($id = null)
    {
        $user = $this->AppUsers->get($id, [
            'contain' => ['UserImage']
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
        
    }
    /**
     * View profile of a user
     *
     * @return \Cake\Network\Response|null
     */
    public function userProfile($id)
    {
        $user = $this->getUser();
        if($user['id'] === $id) {
             return $this->redirect(['action' => 'myProfile']);
        }
        $this->set('editProfile', false);
        $this->view($id);    
    }

    /**
     * View profile of a coach
     *
     * @return \Cake\Network\Response|null
     */

    public function coachProfile($id)
    {
        $user = $this->getUser();
        if($user['id'] === $id) {
            return $this->redirect(['action' => 'myProfile']);
        }
        $this->set('isCoach', $this->isCoach($this->getUser()));
        $this->set('editProfile', false);
        $this->view($id);
    }

    /**
     * View my profile 
     *
     * @return \Cake\Network\Response|null
     */
    public function myProfile()
    {
        $user = $this->getUser();
        $this->set('editProfile', true);
        if(!$user) {
             return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }
        if($user['role'] === ROLE_ADMIN) {
            return $this->redirect(['controller' => 'AppUsers', 'action' => 'index', 'prefix'=>'admin', 'plugin' => false]);
        }
        if($this->isCoach($user)) {
            $this->set('isCoach', true);
            $this->view($user['id']);
            $this->render("coach_profile");
        }
        else {
            $this->view($user['id']);
            $this->render("user_profile");
        }    
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
            $user = $this->AppUsers->patchEntity($user, $data);
            if ($this->AppUsers->save($user)) {
                if(!$this->AppUsers->saveImage(array('file' => $data['avatar_file'], 'data' => $data['avatar_data']), $user->id)) {
                    $this->Flash->error(__('There was a problem saving the image.'));
                }; 
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'myProfile', 'controller' => 'AppUsers']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }

        //Having trouble setting the defaultdate with datetimepicker
        $this->set('defaultDate', $user->birthdate? $user->birthdate->timeAgoInWords(['format'=>'Y-MM-dd', 'absoluteString' =>'%s' ]) : null);
        $this->set('ratio', Configure::read('FileStorage.imageSizes.AppUsers.medium.thumbnail.width')/
            Configure::read('FileStorage.imageSizes.AppUsers.medium.thumbnail.height'));
        $user->birthdate = null;
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
     * login Error
     *
     * Displays the error when trying to login with inactive account 
     * @throws NotFoundException
     * @return type
     */
    public function loginError()
    {
        $this->Flash->error(__('Please, activate your account first.'));
        return $this->redirect(['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login']);
    }

    /**
     * Agenda
     *
     * Displays the users agenda
     * @param $userID the user's id
     * @throws NotFoundException
     * @return void
     */
    public function agenda($userId)
    {
        $timezone = $this->request->cookies['timezone'];
        $user = $this->AppUsers->get($userId, [
            'contain' => ['UserImage']
        ]);
        $this->set('user', $user);
        $this->set('events', $user->external_calendar_id ? json_encode($this->AppUsers->getAgenda($userId, $timezone)) : null);
        $this->set('timezone', $timezone);
        $this->set('url', $this->AppUsers->generateCalendarUrl());
    }

    /**
     * SaveToken
     *
     * Saves the token from the calendarApi
     * @param $userID the user's id
     * @throws NotFoundException
     * @return void
     */
    public function saveCalendarToken() 
    {
        $user = $this->getUser();
        if ($this->AppUsers->storeToken($user['id'], $this->request->query['code'])) {
            $this->Flash->success(__('The calendar has been saved.'));
        
        } else {
            $this->Flash->error(__('The calendar could not be saved. Please, try again.'));
        }
        return $this->redirect(['action' => 'agenda', $user['id']]);
    }

    /**
     * change Password of user
     *
     * Saves the token from the calendarApi
     * @param changePassword
     * @return void
     */
     public function changedPassword($id) 
    {
        $user = $this->AppUsers->get($this->getUser()['id']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $user = $this->AppUsers->patchEntity($user, $data, ['validate' => 'PasswordConfirm']);
            if ($this->AppUsers->save($user)) {
                $this->Flash->success(__('The password has been changed'));
                return $this->redirect(['action' => 'myProfile', 'controller' => 'AppUsers']);
            } else {
                $this->Flash->error(__('The password could not be changed.'));
            }
        }
        $this->set('user', $user);
    }

}

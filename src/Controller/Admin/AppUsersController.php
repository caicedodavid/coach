<?php
namespace App\Controller\Admin;

use CakeDC\Users\Controller\UsersController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\AppUsers $Users
 */
class AppUsersController extends UsersController
{
    public $paginate = [
        'limit' => 20
    ];

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */

    public function index()
    {
        $users = $this->paginate($this->AppUsers);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->AppUsers->get($id, [
            'contain' => ['SocialAccounts']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->AppUsers->newEntity();
        $user['is_superuser'] = false;
        if ($this->request->is('post')) {
            $data= $this->request->data;
            $data['is_superuser'] =false;
            $user = $this->AppUsers->patchEntity($user, $data,  ['accessibleFields' => [
                    '*' => true,
                    'id' => false,
                    'is_superuser' => false,
                    'role' => true,
                ]]);
            if ($this->AppUsers->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set('rolesList',$this->AppUsers->getRoleList());
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->AppUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->AppUsers->patchEntity($user, $this->request->data, ['accessibleFields' => [
                    '*' => true,
                    'id' => false,
                    'is_superuser' => false,
                    'role' => true,
                ]]);
            if ($this->AppUsers->save($user)) {

                $this->Flash->success(__('The user has been saved.'));
                debug($user);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set('rolesList',$this->AppUsers->getRoleList());
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->AppUsers->get($id);
        if ($this->AppUsers->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

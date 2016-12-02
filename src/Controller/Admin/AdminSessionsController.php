<?php
namespace App\Controller\Admin;

use App\Controller\SessionsController;

/**
 * Sessions Controller
 *
 * @property \App\Model\Table\SessionsTable $Sessions
 */
class AdminSessionsController extends SessionsController {


	/**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    const UNPAID_COACHES_FINDER = 'unpaidCoaches';

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Sessions');     
    }

	/**
     * Show unpaid coaches
     *
     * Method to show the coaches with unpaid sessions
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function unpaidCoaches()
    {
        $this->paginate = [
            'limit' => 15,
            'finder' => self::UNPAID_COACHES_FINDER,
            'order' =>[
                'Coaches.username'=> 'asc'
            ]
        ];
        $coaches = $this->paginate($this->Sessions);
        $this->set(compact('coaches'));
        $this->set('_serialize', ['coaches']);
    }

	/**
     * Pay coach
     *
     * Method to pay sessions to a coach
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function payCoach($id = NULL)
    {
    	$user = $this->Sessions->Users->get($id);
        $this->paginate = [
            'limit' => 15,
            'finder' => [
                self::UNPAID_SESSIONS_FINDER => ['userId' => $user['id']]
            ],
            'order' =>[
                'Coaches.username'=> 'asc'
            ]
        ];
        if ($this->request->is('post')) {      
            $this->makePayments($user);
        }
        $sessions = $this->paginate($this->Sessions);
        $this->set(compact('sessions'));
        $this->set('coach', $user->full_name);
        $this->set('_serialize', ['sessions']);
    }

    /**
     * Make the payments
     *
     * Method to pay sessions to a coach
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function makePayments($user)
    {
        if($this->Sessions->payCoach($this->request->data, $user)){
            $this->Flash->success(__('All the sessions were payed'));
            return $this->redirect(['action' => 'unpaidCoaches']); 
        } else {
            $this->Flash->error(__('Some sessions could not be paid'));
        }  	
    }
}
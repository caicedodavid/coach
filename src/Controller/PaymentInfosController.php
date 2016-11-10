<?php
namespace App\Controller;

use App\Controller\AppController;
use Omnipay\Omnipay;

/**
 * PaymentInfos Controller
 *
 * @property \App\Model\Table\PaymentInfosTable $PaymentInfos
 */
class PaymentInfosController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function cards()
    {
        $user = $this->getUser();
        $this->loadModel('AppUsers');
        $user = $this->AppUsers->get($user['id'],[
            'contain' => ['UserImage']
        ]);
        $this->paginate = [
            'limit' => 2,
            'finder' => [
                'userCards' => ['user' => $user]
            ],
            'order' =>[
                'PaymentInfos.created' => 'asc'
            ]
        ];
        $paymentInfos = $this->paginate($this->PaymentInfos);
        $cardsArray = $this->PaymentInfos->getCardsData($user->external_payment_id);
        $this->set(compact('paymentInfos'));
        $this->set('user', $user);
        $this->set('cardsArray', $cardsArray);
        $this->set('_serialize', ['paymentInfos','user','cardsArray']);
    }

    /**
     * View method
     *
     * @param string|null $id Payment Info id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $paymentInfo = $this->PaymentInfos->get($id, [
            'contain' => ['AppUsers']
        ]);

        $this->set('paymentInfo', $paymentInfo);
        $this->set('_serialize', ['paymentInfo']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->getUser();
        $user = $this->PaymentInfos->AppUsers->get($user['id']);
        $paymentInfo = $this->PaymentInfos->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $response = $this->PaymentInfos->addCard($user,$data);
            if ($response['status'] === 'error'){
                $this->Flash->error(__($response['message'] ));
            } else {
                $data = $this->PaymentInfos->setData($user, $data, $response);
                $paymentInfo = $this->PaymentInfos->patchEntity($paymentInfo, $data);
                if ($this->PaymentInfos->save($paymentInfo)) {
                    $this->Flash->success(__('The payment info has been saved.'));
                    return $this->redirect(['action' => 'cards',$user->id]);
                } else {
                    $this->Flash->error(__('The payment info could not be saved. Please, try again.'));
                }
            }
        }
        $this->set(compact('paymentInfo'));
        $this->set('_serialize', ['paymentInfo']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Payment Info id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $paymentInfo = $this->PaymentInfos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $paymentInfo = $this->PaymentInfos->patchEntity($paymentInfo, $this->request->data);
            if ($this->PaymentInfos->save($paymentInfo)) {
                $this->Flash->success(__('The payment info has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The payment info could not be saved. Please, try again.'));
            }
        }
        $users = $this->PaymentInfos->Users->find('list', ['limit' => 200]);
        $tokens = $this->PaymentInfos->Tokens->find('list', ['limit' => 200]);
        $this->set(compact('paymentInfo', 'users', 'tokens'));
        $this->set('_serialize', ['paymentInfo']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Payment Info id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $paymentInfo = $this->PaymentInfos->get($id);
        if ($this->PaymentInfos->delete($paymentInfo)) {
            $this->Flash->success(__('The payment info has been deleted.'));
        } else {
            $this->Flash->error(__('The payment info could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

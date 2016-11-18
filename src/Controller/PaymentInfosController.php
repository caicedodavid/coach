<?php
namespace App\Controller;

use App\Controller\AppController;
use Omnipay\Omnipay;
use Cake\Routing\Router;

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
            'limit' => 10,
            'finder' => [
                'userCards' => ['user' => $user]
            ],
            'order' =>[
                'PaymentInfos.created' => 'asc'
            ]
        ];
        $paymentInfos = $this->paginate($this->PaymentInfos);
        $cards = $this->PaymentInfos->getCardsData($user->external_payment_id);
        $this->set(compact('paymentInfos'));
        $this->set('user', $user);
        $this->set('cards', $cards);
        $this->set('_serialize', ['paymentInfos','user','cards']);
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
    public function add($url = null)
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
                    return $this->redirect($url ? unserialize($url) : ['action' => 'cards', $user->id]);
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
    public function edit($id = null, $card)
    {
        $paymentInfo = $this->PaymentInfos->get($id, [
            'contain' => ['AppUsers']
        ]);
        $card = unserialize($card);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            if($this->PaymentInfos->cardChange($data, $card)){
                $response = $this->PaymentInfos->updateCard($paymentInfo, $data);
                if ($response['status'] === 'error'){
                    $this->Flash->error(__($response['message'] ));
                } else {
                    $data = $this->PaymentInfos->setUpdateData($paymentInfo, $data, $response);
                    $this->saveShippingDetails($paymentInfo, $data);
                }        
            } else {
                $data = $this->PaymentInfos->setUpdateData($paymentInfo, $data);
                $this->saveShippingDetails($paymentInfo, $data);
            }
        }
        $this->set(compact('paymentInfo'));
        $this->set(compact('card'));
        $this->set('_serialize', ['paymentInfo','card']);
    }

    /**
     * Save shipping details method
     *
     * @param Payment Info entity.
     * @param form data 
     */
    public function saveShippingDetails($oldPaymentInfo, $data)
    {   
        #A new entity is created beacause we don't want to lose previous card information
        $paymentInfo = $this->PaymentInfos->newEntity();
        unset($data['cvc'], $data['card_number'], $data['exp_month'], $data['exp_year']);
        $paymentInfo = $this->PaymentInfos->patchEntity($paymentInfo, $data);
        if ($this->PaymentInfos->save($paymentInfo)) {
            $this->Flash->success(__('The payment info has been saved.'));
            $oldPaymentInfo->active = false;
            $this->PaymentInfos->save($oldPaymentInfo);
            return $this->redirect(['action' => 'cards', $this->getUser()['id']]);
        } else {
            $this->Flash->error(__('The payment info could not be saved. Please, try again.'));
        }
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

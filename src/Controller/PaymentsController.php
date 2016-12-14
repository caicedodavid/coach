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
class PaymentsController extends AppController
{

    /**
     * find the Purchases made by a user
     *
     * @param string|null $id User id.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function purchases($id = null)
    {
        $user = $this->Payments->Sessions->Users->get($id, [
            'contain' => ['UserImage']
        ]);
        $this->paginate = [
            'limit' => 15,
            'finder' => [
                'purchases' => [
                    'userId' => $id
                ]
            ]
        ];
        $payments = $this->paginate($this->Payments);
        $this->set('user', $user);
        $this->set(compact('payments'));
        $this->set('_serialize', ['payments','users']);

    }

}

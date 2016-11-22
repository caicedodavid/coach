<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PaymentInfos Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Tokens
 *
 * @method \App\Model\Entity\PaymentInfo get($primaryKey, $options = [])
 * @method \App\Model\Entity\PaymentInfo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PaymentInfo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PaymentInfo|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PaymentInfo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PaymentInfo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PaymentInfo findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PaymentInfosTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('payment_infos');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Payment');

        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('address1', 'create')
            ->notEmpty('address1');

        $validator
            ->allowEmpty('address2');

        $validator
            ->requirePresence('country', 'create')
            ->notEmpty('country');

        $validator
            ->requirePresence('state', 'create')
            ->notEmpty('state');

        $validator
            ->requirePresence('city', 'create')
            ->notEmpty('city');

        $validator
            ->requirePresence('zipcode', 'create')
            ->notEmpty('zipcode');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'AppUsers'));

        return $rules;
    }

    /**
     * Query for finding all the cards related to a user
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findUserCards(Query $query, array $options)
    {
        $user = $options["user"];
        return $query
            ->where(['PaymentInfos.user_id' => $user['id']])
            ->find('activeCards');
    }

    /**
     * Query for finding all the cards related to a user
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findContainUser(Query $query, array $options)
    {
        return $query
            ->contain(['AppUsers']);

    }

    /**
     * Query for finding all the active cards
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findActiveCards(Query $query, array $options)
    {
        return $query
            ->where(['PaymentInfos.active' => true]);
    }

    /**
     * Query for geting the data of all cards
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function getCardsData($userPaymentId)
    {
        if (is_null($userPaymentId)) {
            return null;
        }

        $cards = $this->getUserCards($userPaymentId);
        $cardsArray = [];
        foreach ($cards as $card) {
            $cardsArray[$card['id']] = [
                'last4' => $card['last4'],
                'exp_month' => $card['exp_month'],
                'exp_year' => $card['exp_year']
            ];
        }
        return $cardsArray;
    }

     /**
     * Method to determine if a User in the payment Api has to be
     * created or just a card
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function addCard($user, $data)
    {
        return $user->external_payment_id ? $this->addCreditCard($data['token_id'],$user->external_payment_id) : 
                $this->createUser($data['token_id'],$user->email);
    }

    /**
     * Method to set the data for patch entity
     * created or just a card
     * @param $data form data
     * @param $reponse payment api response
     * @return $data Array
     */
    public function setData($user, $data, $response)
    {
        $data['external_card_id'] = $response['card_id']; 
        $data['user_id'] = $user->id;
        $data['is_default'] = true;
        $this->updateDefaultCard($user->id);
        $user->external_payment_id = $user->external_payment_id ? $user->external_payment_id : $response['user_token'];
        $this->AppUsers->save($user);
        return $data;
    }

    /**
     * Method to find a card by externalId
     * created or just a card
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findCardByExternalId(Query $query, array $options)
    {
        $user = $options["user"];
        $externalCardId = $options["externalCardId"];
        return $this->find('userCards', [
            'user' => $user
        ])
        ->find('externalId', [
            'externalCardId' => $externalCardId
        ]);
    }

    /**
     * Method to find a card by externalId
     * created or just a card
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findExternalId(Query $query, array $options)
    {
        $externalCardId = $options["externalCardId"];
        return $query
            ->where(['PaymentInfos.external_card_id' => $externalCardId]);

    }

    /**
     * Method for finding the default card
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findDefaultCard(Query $query, array $options)
    {
        return $query
            ->where(['PaymentInfos.is_default' => true]);
    }

    /**
     * check if the information of the card was updated
     * @param $data form data
     * @param $card card data
     * @return Query
     */
    public function cardChange($data, $card)
    { 
        return $data['card_number'] or $data ['cvc'] or ($data['exp_month'] != $card['exp_month']) or ($data['exp_year'] != $card['exp_year']); 

    }

    /**
     * Method to copy the data of another paymentInfo
     * 
     * @param $paymentInfo entity
     * @param $data form data
     * @param $reponse payment api response
     * @return $data Array
     */
    public function setUpdateData($paymentInfo, $data, $response = null)
    {
        $data['external_card_id'] = $response? $response['card_id'] : $paymentInfo->external_card_id; 
        $data['user_id'] = $paymentInfo->app_user->id;
        if ($data['is_default']){
            $this->setAsDefaultCard($paymentInfo->app_user->external_payment_id, $data['external_card_id']);
            $this->updateDefaultCard($paymentInfo->app_user->id);
        }
        return $data;
    }

    /**
     * Return card data from payment infos form
     * @param $data data from form
     * @return Array
     */
    public function getCardData(&$data)
    {
        $cardData = array_intersect_key($data, array_flip(['cvc','card_number','exp_month','exp_year']));
        unset($data['cvc'], $data['card_number'], $data['exp_month'], $data['exp_year']);
        return $cardData;
    }

    /**
     * check if data has changed
     * @param $paymentInfo entity
     * @return boolean
     */
    public function isDataChanged($paymentInfo, $data)
    {
        $newPaymentInfo = clone($paymentInfo);
        $newPaymentInfo = $this->patchEntity($newPaymentInfo, $data);
        $data = $newPaymentInfo->extract($newPaymentInfo->visibleProperties(), true);
        unset($data['is_default']);
        return $data;
    }

    /**
     * SetDefaultCard
     *
     * If card is default update in payment API
     *
     * @param $customerId Id of customer in payment API
     * @param $cardId card data form the form
     * @return $response Array
     */
    public function setDefaultCard($user, $cardId, $data)
    {
        if($data['is_default']){
            $this->setAsDefaultCard($user->external_payment_id, $cardId);
            $this->updateDefaultCard($user->id);
        }
    }

    /**
     * Change Default Card
     *
     * if card is default, makes another card default
     *
     * @param $paymentInfo entity
     * @return boolean
     */
    public function changeDefaultCard($paymentInfo)
    {
        $paymentInfo->active = false;
        if(!$paymentInfo->is_default){
            return $paymentInfo;
        }
        $newDefaultCard = $this->find('userCards',['user' => $paymentInfo->app_user])
            ->find('containUser')
            ->where(['PaymentInfos.is_default' => false])
            ->first();
        
        $paymentInfo->is_default = false;
        if(!$newDefaultCard){
            return $paymentInfo;
        }
        $this->setAsDefaultCard($newDefaultCard->app_user->external_payment_id, $newDefaultCard->external_card_id);
        $newDefaultCard->is_default = true;
        $this->save($newDefaultCard);
        return $paymentInfo;
    }

    /**
     * Update default card
     *
     * looks for the default card and changes is_default to false
     *
     * @return boolean
     */
    public function updateDefaultCard($userId)
    {
        $defaultCard = $this->find('defaultCard')
            ->find('activeCards')
            ->where(['PaymentInfos.user_id' => $userId])
            ->first();
        if($defaultCard){
            $defaultCard->is_default = false;
            $this->save($defaultCard);
        }
        return true;
    }

}

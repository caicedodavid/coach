<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\Payment;

/**
 * Payments Model
 *
 * @property \Cake\ORM\Association\BelongsTo $PaymentInfos
 * @property \Cake\ORM\Association\BelongsTo $Fks
 *
 * @method \App\Model\Entity\Payment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Payment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Payment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Payment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Payment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Payment findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PaymentsTable extends Table
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

        $this->table('payments');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('PaymentInfos', [
            'foreignKey' => 'payment_infos_id',
            'joinType' => 'INNER'
        ]);
        $assocOptions = [
            'foreignKey' => 'fk_id',
            'conditions' => [
                'fk_table' => 'Sessions',
            ],
            'joinType' => 'INNER',
            'className' => 'Sessions',
        ];
        $this->belongsTo('Sessions', $assocOptions);
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
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        $validator
            ->requirePresence('fk_table', 'create')
            ->notEmpty('fk_table');

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
        $rules->add($rules->existsIn(['payment_infos_id'], 'PaymentInfos'));

        return $rules;
    }

    /**
     * Finder method for finding the payments Infos
     *
     * @return Query
     */
    public function findContainPaymentInfosUser(Query $query, array $options)
    {
        if (empty($options['userId'])) {
            throw new \InvalidArgumentException(__('userId is not defined'));
        }
        $userId = $options['userId'];
        return $query->contain([
            'PaymentInfos' => function ($q) use ($userId) {
                return $q->where(['PaymentInfos.user_id' => $userId]);
            }
        ]);
    }

    /**
     * Finder method for finding the information of a payed session
     *
     * @return Query
     */
    public function findContainSessions(Query $query, array $options)
    {
        return $query->contain(['Sessions']);
    }

    /**
     * Finder method for finding the purchases of a user
     *
     * @return Query
     */
    public function findPurchases(Query $query, array $options)
    {
        return $query->find('containSessions')
            ->find('containPaymentInfosUser', $options)
            ->where([$this->aliasfield('payment_type') =>  Payment::PAYMENT_TYPE_CREDIT]);
    }
}

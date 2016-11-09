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
    public function findHistoricSessions(Query $query, array $options)
    {
        $user = $options["user"];
        return $query
            ->where(['PaymentInfos.user_id' => $user['id']])
            ->find('activeCards');
    }

    /**
     * Query for finding all the active cards
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findHistoricSessions(Query $query, array $options)

        return $query
            ->where(['PaymentInfos.active' => true])
    }
}

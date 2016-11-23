<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\Liability;

/**
 * Liabilities Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Fks
 *
 * @method \App\Model\Entity\Liability get($primaryKey, $options = [])
 * @method \App\Model\Entity\Liability newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Liability[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Liability|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Liability patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Liability[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Liability findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LiabilitiesTable extends Table
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

        $this->table('liabilities');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

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
            ->integer('commission')
            ->requirePresence('commission', 'create')
            ->notEmpty('commission');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('fk_table', 'create')
            ->notEmpty('fk_table');

        return $validator;
    }

    /**
     * Query for finding the user linked to a session
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findPending(Query $query, array $options)
    {
        return $query->where(['Liabilities.status' => Liability::STATUS_PENDING]); 
    }

    /**
     * Query for finding the user linked to a session
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findPaid(Query $query, array $options)
    {
        return $query->where(['Liabilities.status' => Liability::STATUS_PENDING]); 
    }
}

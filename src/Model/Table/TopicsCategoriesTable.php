<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TopicsCategories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Topics
 * @property \Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\TopicsCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\TopicsCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TopicsCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TopicsCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TopicsCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TopicsCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TopicsCategory findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TopicsCategoriesTable extends Table
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

        $this->table('topics_categories');
        $this->primaryKey(['topic_id','category_id']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Topics', [
            'foreignKey' => 'topic_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER'
        ]);
        $this->addBehavior('CounterCache', [
            'Categories' => ['topic_count']
        ]);
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
        $rules->add($rules->existsIn(['topic_id'], 'Topics'));
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }
}

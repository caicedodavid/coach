<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use App\Error\AssociatedCategoryException;

/**
 * Categories Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Topics
 *
 * @method \App\Model\Entity\Category get($primaryKey, $options = [])
 * @method \App\Model\Entity\Category newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Category[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Category|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Category patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Category[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Category findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoriesTable extends Table
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

        $this->table('categories');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Topics', [
            'foreignKey' => 'category_id',
            'targetForeignKey' => 'topic_id',
            'joinTable' => 'topics_categories'
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
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        $validator
            ->add('name', [
                'unique' => [
                    'rule' => 'validateUnique', 
                    'provider' => 'table', 
                    'message' => 'The category name has to be unique'
                ]
            ]);

        return $validator;
    }

    /**
     * Query for finding the visible categories
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findVisibleCategories(Query $query, array $options)
    {
        return $query
            ->find('activeCategories')
            ->find('categoriesWithTopics');
    }

    /**
     * Query for finding the active categories
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findActiveCategories(Query $query, array $options)
    {
        return $query
            ->where([$this->aliasField('active') => true]);
    }

     /**
     * Query for finding the active categories
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findCategoriesWithTopics(Query $query, array $options)
    {
        return $query
            ->where([$query->newExpr()->gt($this->aliasField('topic_count'), 0)]);
    }

    /**
     * Method to be called before the deleting a category
     * @param $coachId
     * @return Array
     */
    public function beforeDelete(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        if ($entity->topic_count) {
            throw new AssociatedCategoryException(__('This category cannot be deleted because it has associated topics.'), 501);
            $event->stopPropagation();
        }     
    }      
}

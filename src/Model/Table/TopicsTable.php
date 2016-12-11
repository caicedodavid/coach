<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Topics Model
 *
 * @method \App\Model\Entity\Topic get($primaryKey, $options = [])
 * @method \App\Model\Entity\Topic newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Topic[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Topic|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Topic patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Topic[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Topic findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TopicsTable extends Table
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

        $this->table('topics');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Burzum/Imagine.Imagine');

        $this->hasOne('TopicImage', [
            'className' => 'TopicImage',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'TopicImage.model' => 'file_storage'
            ]
        ]);
        $this->belongsTo('Coach', [
            'foreignKey' => 'coach_id',
            'joinType' => 'INNER',
            'className' => 'AppUsers',
        ]);
        $this->belongsToMany('Categories', [
            'foreignKey' => 'topic_id',
            'targetForeignKey' => 'category_id',
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
            ->integer('duration')
            ->requirePresence('duration', 'create')
            ->notEmpty('duration');

        return $validator;
    }

    /**
     * Query for finding the topics of a coach
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findTopicsByCoach(Query $query, array $options)
    {
        $coachId = $options["coachId"];
        return $query->where([
                'Topics.coach_id' =>  $coachId
        ])
        ->find('topicsImage');
    }

    /**
     * Query for finding the public topics
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findPublicTopics(Query $query, array $options)
    {
        return $query->where([
                'Topics.active' => true
        ]);
    }

    /**
     * Query for finding the public topics
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findPublicTopicsImage(Query $query, array $options)
    {
        return  $query->find('publicTopics')
            ->find('topicsImage');
    }

    /**
     * Query for finding the public topics by coach
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findPublicTopicsByCoach(Query $query, array $options)
    {
        return  $query->find('publicTopics')
            ->find('topicsByCoach', $options);
    }

    /**
     * Query for finding a topic with its image
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findTopicsImage(Query $query, array $options)
    {
        return  $query->contain('TopicImage');
    }

    /**
     * Query for finding a topic with the coach data and image
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findTopicCoach(Query $query, array $options)
    {
        $id = $options["id"];
        return $query->where([
                'Topics.id' =>  $id
        ])
        ->find('topicsImage')
        ->find('containCoach');
    }

    /**
     * Query for containing a topic with the coach info
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findContainCoach(Query $query, array $options)
    {
        return  $query->contain([
            'Coach'=> [
                'UserImage'
            ]
        ]);
    }

    /**
     * Query for containing a topic with the coach info
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findIndexTopics(Query $query, array $options)
    {
        return  $query->find('publicTopics')
            ->find('containCoach')
            ->find('topicsImage');
    }

    /**
     * Method that returns the topics for the selection box
     * @param $coachId
     * @return Array
     */
    public function getTopicsList($coachId)
    {
        return $this->find('publicTopicsByCoach', [
            'coachId' => $coachId
        ])
        ->find('list')
        ->toArray();     
    }
}

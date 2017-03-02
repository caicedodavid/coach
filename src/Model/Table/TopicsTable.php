<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use App\Error\AssociatedTopicException;

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
        $this->addBehavior('PlumSearch.Filterable');
        $this->addFilter('category_id', [
            'className' => 'Custom',
            'method' => function ($q, $field, $value) {
                return $q->where(['Categories.id' => $value]);
            }
        ]);

        $this->hasMany('TopicImage', [
            'className' => 'TopicImage',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'TopicImage.model' => 'Topics'
            ],
            'sort' => [
                'TopicImage.created' => 'desc',
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
            'joinTable' => 'topics_categories',
            'through' => 'TopicsCategories'
        ]);
        $this->hasMany('Sessions', [
            'foreignKey' => 'topic_id',
            'joinType' => 'INNER',
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

        $validator->add('categories', 'custom', [
            'rule' => function($value, $context) {
                return ($value['_ids'] != '');
            },
            'message' => 'Please choose at least one Category'
        ]);

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
     * Query for finding the top rated topics
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findTopRated(Query $query, array $options)
    {
        return  $query->find('publicTopics')
            ->find('topicsImage')
            ->order(['rating' => 'DESC'])
            ->limit(10)
            ->cache('top_topics');
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
     * Query for containing a topic with the related sessions
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findContainSession(Query $query, array $options)
    {
        $id = $options['id'];
        return  $query->contain('Sessions')
            ->where([$this->aliasField('id') => $id]);
    }

    /**
     * Query for containing a topic with the coach info
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findIndexTopics(Query $query, array $options)
    {
        return $query->find('publicTopics')
            ->find('containCoach')
            ->find('topicsImage')
            ->innerJoinWith(
                'TopicsCategories.Categories', function ($q) {
                    return $q->where(['Categories.active' => true])
                        ->where(['Categories.topic_count >' => 0]);
                }
            )
            ->group([$this->aliasField('id')]);
    }

    /**
     * Query for Conataining the categories of a topic
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findContainCategories(Query $query, array $options)
    {
        return $query
            ->contain(['Categories' => function (Query $query) {
                return $query->where(['active' => true])
                    ->select(['id']);
            }]);
    }

    /**
     * Query for Conataining the categories and image of a topic
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findContainImageCategories(Query $query, array $options)
    {
        if (empty($options['topicId'])) {
            throw new \InvalidArgumentException(__('topicId is not defined'));
        }
        $topicId = $options["topicId"];
        return $query
            ->find('containCategories', $options)
            ->find('topicsImage')
            ->where([$this->aliasField('id') =>  $topicId]);
    }

    /**
     * Finder method for finding the rated sessions of a topic
     *
     * @return Query
     */
    public function findRatedByTopic(Query $query, array $options)
    {
        if (empty($options['topicId'])) {
            throw new \InvalidArgumentException(__('topicId is not defined'));
        }
        $topicId = $options["topicId"];
        return $query->where([
                $this->aliasField("id") => $topicId
            ])
            ->find('containSessionsRated');
    }

    /**
     * Finder method for finding the rated sessions
     *
     * @return Query
     */
    public function findContainSessionsRated(Query $query, array $options)
    {
        return $query->contain(['Sessions' => function (Query $query) {
            return $query->select(['user_rating', 'topic_id'])
                ->where(['Sessions.user_rating IS NOT' => null]);
        }]);
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

    /**
     * Update the rating of a topic
     *
     * @return boolean
     */
    public function updateTopicRating($topicId)
    {
        $topic = $this->find('ratedByTopic', ['topicId' => $topicId])
            ->first();

        $ratings = Hash::extract($topic->sessions,'{n}.user_rating');
        $topic->rating = array_sum($ratings) / count($ratings); 
        return $this->save($topic); 
    }
    
    /*
     * Method to be called before the deleting a topic
     * @param $coachId
     * @return Array
     */
    public function beforeDelete(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        if ($this->Sessions->find('byTopic', ['topicId' => $entity->id])->count()) {
            throw new AssociatedTopicException(__('This topic cannot be deleted because it has associated sessions.'), 501);
            $event->stopPropagation();
        }     
    }

    /**
     * save topic image 
     *
     * @param Array post data 
     * @return Array
     */
    public function saveImage($data, $topicId)
    {
        if(!$data['file']['size']) {
            return;
        }
        $entity = $this->TopicImage->newEntity();
        $entity = $this->TopicImage->patchEntity($entity, $data);
        return $this->TopicImage->uploadImage($topicId, $entity);
    }

    /**
     * save topic image 
     *
     * @param userId
     * @param data Array
     * @param image topic image
     * @return Array
     */
    public function patchTopic($userId, $topic, &$data, &$image)
    {
        $topic["coach_id"] = $userId;
        $image = $data["topic_image"];
        unset($data["topic_image"]);
        $topic = $this->patchEntity($topic, $data);
        $topic->dirty('categories', true);
        return $topic;
    }

    /**
     * Making an arary for selection the duration of a cless when adding a topic
     *
     * @return Array
     */
    public function getDurationArray()
    {
        $array = array();
        for ($i = 1; $i <= 4; $i++) {
            $array[$i*30] = ((string)$i*30) . ' min';
        }
        return $array;
    }

    /**
     * isFree
     * check if topic is free
     *
     * @return boolean
     */
    public function isFree($topicId)
    {
        return ($this->get($topicId)->price === 0);
    }
              
}
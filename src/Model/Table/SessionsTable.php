<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use ArrayObject;
use Cake\Event\Event;
use Cake\Orm\Entity;
use Cake\Mailer\Email;
use Cake\Datasource\EntityInterface;
use Cake\Mailer\MailerAwareTrait;
use App\SessionAdapters\Braincert;

/**
 * Sessions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Coaches
 *
 * @method \App\Model\Entity\Session get($primaryKey, $options = [])
 * @method \App\Model\Entity\Session newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Session[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Session|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Session patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Session[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Session findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SessionsTable extends Table
{
    use MailerAwareTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('sessions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Users',
        ]);
        $this->belongsTo('Coaches', [
            'foreignKey' => 'coach_id',
            'joinType' => 'INNER',
            'className' => 'Users',
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
            ->dateTime('schedule')
            ->requirePresence('schedule', 'create')
            ->notEmpty('schedule');

        $validator
            ->requirePresence('subject', 'create')
            ->notEmpty('subject');

        $validator
            ->allowEmpty('comments');

        $validator
            ->add('schedule','validSchedule',[
                'rule' => 'validSchedule',
                'provider' => 'table',
                'message'=>'The session date must be at least 24 hours from now.',
                ]
            );

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['coach_id'], 'Users'));

        return $rules;
    }
    /**
     * Returns true if schedule is at least a day after requesting a
     * session
     *
     * @param datetime object and context
     * @return boolean
     */
    public function validSchedule($check, array $context)
    {   
        return (date('Y-m-d H:i',strtotime($check)) > date('Y-m-d H:i',strtotime("+1 day")));
    }
    /**
     * Logic after saving an event (send emails)
     *
     * @param datetime object and context
     * @return boolean
     */
    public function afterSave(Event $event, Entity $entity, ArrayObject $options)
    {
        $session = $this->get($entity['id'], [
                    'contain' => ['Users', 'Coaches']
                ]);
        $coach = $session["coach"];
        $user = $session["user"];
        $this->getMailer('Session')->send('userMail', [$user,$coach,$session]);            
        $this->getMailer('Session')->send('coachMail', [$user,$coach,$session]);
    }
    /**
     * Override patchEntity method from Table class
     *
     * Ajusting Datetime format
     *
     * @return Session Entity
     */
    public function patchEntity(EntityInterface $entity , array $data , array $options = [])
    {
        $data["schedule"] = $data["schedule"] . " ". $data["time"] . ":00";
        unset($data["time"]);

        $liveSession = new Braincert("g4cPvYO3AdSNEUh7zag5");
        $response = $liveSession->scheduleSession($data);
        $data["class_id"] = $response["class_id"];
        return parent::patchEntity($entity, $data);

    }
    /**
     * Query for finding sessions linked to a user
     * @return Query
     */
    public function findSessions(Query $query, array $options)
    {
        $user = $options["user"];
        $role = $user["role"];
        $query = $query->where([
                'Sessions.' . $role . "_id" => $user["id"],
            ]);
        //if I am a coach I want the data of my cochees
        if($role === 'coach'):
            return $query->contain([
                'Users'=> [
                    'UserImage'
                ]
            ]);
        endif;

        return $query->contain([
            'Coaches'=> [
                'UserImage'
            ]
        ]); 
    }

}


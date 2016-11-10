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
use App\SessionAdapters\LiveSession;
use App\Model\Entity\Session;

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
        $this->addBehavior('Payment');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'AppUsers',
        ]);
        $this->belongsTo('Coaches', [
            'foreignKey' => 'coach_id',
            'joinType' => 'INNER',
            'className' => 'AppUsers',
        ]);
        $this->belongsTo('Topics', [
            'foreignKey' => 'topic_id',
        ]);
        $assocOptions = [
            'foreignKey' => 'fk_id',
            'conditions' => ['Payments.fk_table' => 'Sessions'],
        ];
        $this->hasMany('Payments', $assocOptions);
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
            ->notEmpty('user_rating');

        $validator
            ->notEmpty('coach_rating');
/*
        $validator
            ->add('schedule','validSchedule',[
                'rule' => 'validSchedule',
                'provider' => 'table',
                'message'=>'The session date must be at least 24 hours from now.',
                ]
            );
*/
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
     * @param $check datetime object
     * @param $context context object
     * @return boolean
     */
    public function validSchedule($check, array $context)
    {   
        return (date('Y-m-d H:i',strtotime($check)) > date('Y-m-d H:i',strtotime("+1 day")));
    }

    /**
     * Logic after requesting a session (send emails)
     *
     * @param session | session entity
     */
    public function sendRequestEmails($session)
    {
        $session = $this->get($session['id'], [
                    'contain' => ['Users', 'Coaches']
                ]);
        $coach = $session["coach"];
        $user = $session["user"];
        try{
            $this->getMailer('Session')->send('userMail', [$user,$coach,$session]);            
            $this->getMailer('Session')->send('coachMail', [$user,$coach,$session]);
        }
        catch(Exception $e){
        }
    }

    /**
     * Logic after acepting a session (send email)
     *
     * @param session | session entity
     */
    public function sendEmail($session,$action)
    {
        $session = $this->get($session['id'], [
                    'contain' => ['Users', 'Coaches']
                ]);
        $coach = $session["coach"];
        $user = $session["user"];
        try{
            $this->getMailer('Session')->send($action, [$user,$coach,$session]);
        }
        catch(Exception $e){
        }        
    }

    /**
     * Shedule a class with the server
     *
     * Ajusting Datetime format
     * @param  $entity Session enttity interface
     * @param  $data array of data to be aptched in the entity
     * @param  $options options array
     * @return Session Entity
     */
    public function scheduleSession($session)
    {

        $liveSession = LiveSession::getInstance();
        $response = $liveSession->scheduleSession($session);
        return $response["class_id"];
    }

    /**
     * Fix Schedule
     *
     * Ajusting Datetime format
     * @param  $data array of data to be patched in the entity
     * @return $data array of data to be patched in the entity
     */
    public function fixSchedule(array $data)
    {
        $data["schedule"] = $data["schedule"] . ":00";
        return $data;

    }

    /**
     * Query for finding pending sessions linked to a user
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findPendingSessions(Query $query, array $options)
    {
        $user = $options["user"];
        $role = $user["role"];
        return $query
            ->where(['Sessions.' . $role . "_id" => $user["id"]])
            ->find('pending')
            ->find('contain', ['role'=>$role]);
    }

    /**
     * Query for finding the historical of sessions linked to a user
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findHistoricSessions(Query $query, array $options)
    {
        $user = $options["user"];
        $role = $user["role"];
        return $query
            ->where(['Sessions.' . $role . "_id" => $user["id"]])
            ->find('past')
            ->find('contain', ['role'=>$role]);
    }

    /**
     * Query for finding all the session data
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findData(Query $query, array $options)
    {
        $id = $options["id"];
        $user = $options["user"];
        $role = $user["role"];
        return $query
            ->where(['Sessions.id' => $id])
            ->find('contain', ['role'=>$role])
            ->find('containTopic');
    }

    /**
     * Query for finding the Approved of sessions linked to a user
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findApprovedSessions(Query $query, array $options)
    {
        $user = $options["user"];
        $role = $user["role"];
        return $query
            ->where(['Sessions.' . $role . "_id" => $user["id"]])
            ->find('approved')
            ->find('contain', ['role' => $role]);
    }

    /**
     * Query for finding the Approved of sessions
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findApproved(Query $query, array $options)
    {
        return  $query = $query->where([
            'OR'=>[
                ['Sessions.status' => session::STATUS_APPROVED],
                ['Sessions.status' => session::STATUS_RUNNING]
            ]
        ]);
    }

    /**
     * Query for finding pending sessions
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findPending(Query $query, array $options)
    {
        return  $query = $query->where([
                'Sessions.status' => session::STATUS_PENDING
        ]);
    }

    /**
     * Query for finding past sessions
     * @param $query query object
     * @param $options options array
     * @return Query
     */
    public function findPast(Query $query, array $options)
    {
        return  $query = $query->where([
            'OR'=>[
                ['Sessions.status' => session::STATUS_PAST],
                ['Sessions.status' => session::STATUS_REJECTED],
                ['Sessions.status' => session::STATUS_CANCELED]
            ]
        ]);
    }

    /**
     * Query for finding the user data linked to a session
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findContain(Query $query, array $options)
    {
        $role = $options["role"];
        if ($role === 'coach') {
            return $query->contain([
                'Users'=> [
                    'UserImage'
                ]
            ]);
        }
        return $query->contain([
            'Coaches'=> [
                'UserImage'
            ]
        ]); 
    }

    /**
     * Query for finding the topic data linked to a session
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findContainTopic(Query $query, array $options)
    {
        return $query->contain([
            'Topics' => [
                'TopicImage'
            ]
        ]); 
    }

    /**
     * Query for finding the user linked to a session
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findContainUser(Query $query, array $options)
    {
        return $query->contain([
            'Users'
        ]); 
    }

    /**
     * Query for finding the user and topic
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findContainUserTopic(Query $query, array $options)
    {
        $id = $options['id'];
        return $query->where(['Sessions.id' => $id])
            ->find('containUser')
            ->find('containTopic');
    }

    /**
     * method for returning a Url if the LiveSession returnes one, if not return null
     * @param $session session entity
     * @param $user user object
     * @return string url| null
     */
    public function getUrl($session, $user)
    {
        $liveSession = LiveSession::getInstance();
        $response = $liveSession->requestSession($session, $user);
        return $response['encryptedlaunchurl'];
    }

    /**
     * method for returning the data of a session
     * @param $session session entity
     * @param $user user object
     * @return string url| null
     */
    public function getSessionData($session)
    {
        $liveSession = LiveSession::getInstance();
        return $liveSession->getSessionData($session);
    }

    /**
     * method for removing a class from the LiveSession server database
     * @return string url| null
     */
    public function removeClass($session)
    {
        $liveSession = LiveSession::getInstance();
        $response = $liveSession->removeSession($session);
        return $response['status'];
    }

    /**
     * method for setting the time in a session of a user or coach
     * @return $data Array
     */
    public function setTime($startTime)
    {
        $startTime = $startTime ? $startTime: strtotime("now");
        return strtotime("now") - (int) $startTime;
    }

    /**
     * method for paying a session
     * @return $data Array
     */
    public function paySession($session)
    {
        $payment = $this->Payments->newEntity();
        $data['amount'] = $session->topic->price ? $session->topic->price : 10;
        
        $data['fk_table'] = 'sessions';
        $data['fk_id'] = $session->id;
        $response = $this->chargeUser($session->user->external_payment_id, $data['amount']);
        debug($response);

        $data['payment_infos_id'] = $this->Payments->PaymentInfos->find('cardByExternalId', [
            'externalCardId' => $response['card_id'],
            'user' => $session->user
        ])
        ->first()
        ->id;
        $payment = $this->Payments->patchEntity($payment,$data);

        if ($response['status'] === 'error') {
            return $response;
        }
        $this->Payments->save($payment);
    }
}
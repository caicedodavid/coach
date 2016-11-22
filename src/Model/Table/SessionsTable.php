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
use App\Model\Behavior\PaymentBehavior;


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

        $assocOptions = [
            'foreignKey' => 'fk_id',
            'conditions' => ['Liabilities.fk_table' => 'Sessions'],
            'joinType' => 'INNER'
        ];
        $this->hasOne('Liabilities', $assocOptions);
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
     * Send Emails method.
     * Method that sends an email to the user or coach depening on the action
     *
     * @param session | session entity
     */
    public function sendEmail($session, $action, $message = null)
    {

        $session = $this->get($session['id'], [
                    'contain' => ['Users', 'Coaches','Topics']
                ]);
        $coach = $session["coach"];
        $user = $session["user"];
        try{
            $this->getMailer('Session')->send($action, [$user,$coach,$session,$message]);
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
        debug($response);
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
     * Query for finding the user linked to a session
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findContainCoach(Query $query, array $options)
    {
        return $query->contain([
            'Coaches'
        ]); 
    }


    /**
     * Query for finding the session with its liability
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findContainLiability(Query $query, array $options)
    {
        $finder = $options["finderName"];
        return $query->contain([
            'Liabilities' => [
                'finder' => [$finder]
            ]
        ]);
    }
    /**
     * Query for finding the paid sessions to a coach
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findPaidCoach(Query $query, array $options)
    {
        $user = $options["user"];
        $query->find('containLiability', ['finderName' => 'paid'])
        ->find('containUser')
        ->find('containTopic')
        ->where(['Sessions.coach_id' => $user['id']])
        ->where(['Sessions.status' => session::STATUS_PAST]); 
        return($query);
    }

    /**
     * Query for finding the unpaid sessions to a coach
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findUnpaidCoach(Query $query, array $options)
    {
        $user = $options["user"];
        $query->find('containLiability', ['finderName' => 'pending'])
        ->find('containUser')
        ->find('containTopic')
        ->where(['Sessions.coach_id' => $user['id']])
        ->where(['Sessions.status' => session::STATUS_PAST]); 
        return($query);
    }


    /**
     * Query for finding the user and topic
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findContainUserTopicLiability(Query $query, array $options)
    {
        return $query->find('containUserTopic', $options)
            ->find('containLiability', $options);
    }

    /**
     * Query for finding the user, topic and liability
     * @param $query query object
     * @param $role string role of user
     * @return Query
     */
    public function findContainUserTopic(Query $query, array $options)
    {
        $id = $options['id'];
        return $query->where(['Sessions.id' => $id])
            ->find('containUser')
            ->find('containCoach')
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
     * @param $session session entity
     * @return $data Array
     */
    public function paySession($session)
    {
        if(!$this->Users->hasActiveCards($session->user)) {
            $response = ['status' => PaymentBehavior::ERROR_STATUS,
                'message' => 'You have no registered cards'
            ];
            return $response;
        }
        $price  = isset($session->topic->price) ? $session->topic->price : 10;
        $amount = $price - $session->user->balance;
        $response['status'] = PaymentBehavior::SUCCESSFUL_STATUS; 
        if ($amount > 0) {
            $response = $this->saveSessionPaymentCredit($session, $amount);
        } 
        if (($session->user->balance > 0) and ($response['status'] === PaymentBehavior::SUCCESSFUL_STATUS)) {
            $response = $this->saveSessionPaymentBalance($session, min($price, $session->user->balance));
            $session->user->balance = number_format(abs(min(0,$amount)), 2);
            $this->Users->save($session->user);
        }
        return $response;
    }

    /**
     * method for refunding the session price to a user
     * @param $session session entity
     * @return $data Array
     */
    public function refundSession($session,$isCoach)
    {
        if($isCoach){
            $session->liability->status = $session->liability::STATUS_REFUND;
            $session->liability->observation = 'The session was refunded to the user';
            $this->Liabilities->save($session->liability);
            $session->user->balance += isset($session->topic->price) ? $session->topic->price : 10;
            $this->Users->save($session->user);
        }
    }
    /**
     * Fix Data
     *
     * Setting the data for a new session
     * @param  $session entity
     * @param  $coachId id of a coach
     * @param  $userId id of the user
     * @param  $topicId id of the topic
     * @param $data data array of form
     * @return $data array of data to be patched in the entity
     */
    public function fixData(&$session, $topic, $userId, array $data)
    {
        $session['user_id'] = $userId;
        $session['coach_id'] = $topic->coach_id;
        $session['topic_id'] = $topic->id;
        return $this->fixSchedule($data);
    }

    /**
     * check userCards
     *
     * check if the user has an associated card
     * @param  $session entity
     * @return boolean 
     */
    public function checkUserCard($user)
    {
        return $this->Users->hasActiveCards($user);
    }

    /**
     * set Paymet Data
     *
     * sets the data that will be stored after paying for a session
     * @param  $session entity
     * @return data to be stored
     */
    public function setPaymentData($session, $amount)
    {
        $data['amount'] = number_format($amount, 2); 
        $data['fk_table'] = $this->table();
        $data['fk_id'] = $session->id;
        return $data;
    }

    /**
     * set Paymet Data Credit
     *
     * sets the data that will be stored after paying for a session with
     * the users balance
     * @param  $session entity
     * @return 
     */
    public function saveSessionPaymentCredit($session, $amount)
    {
        $payment = $this->Payments->newEntity();
        $data = $this->setPaymentData($session, $amount);
        $data['payment_type'] = $payment::PAYMENT_TYPE_CREDIT;
        $response = $this->chargeUser($session->user->external_payment_id, $data['amount']);
        if ($response['status'] === PaymentBehavior::ERROR_STATUS) {
            return $response;
        }
        $data['payment_infos_id'] = $this->Payments->PaymentInfos->find('cardByExternalId', [
            'externalCardId' => $response['card_id'],
            'user' => $session->user
        ])
        ->first()
        ->id;
        $payment = $this->Payments->patchEntity($payment, $data);
        $this->Payments->save($payment);
        return $response;
    }

    /**
     * set Paymet Data Balance
     *
     * sets the data that will be stored after paying for a session with
     * credit card
     * @param  $session entity
     * @param  $amount float
     * @return response 
     */
    public function saveSessionPaymentBalance($session, $amount)
    {
        $payment = $this->Payments->newEntity();
        $data = $this->setPaymentData($session, $amount);
        $data['payment_type'] = $payment::PAYMENT_TYPE_BALANCE;
        $payment = $this->Payments->patchEntity($payment, $data);
        $this->Payments->save($payment);
        $response['status'] = PaymentBehavior::SUCCESSFUL_STATUS;
        return $response;
    }

    /**
     * create Liability
     *
     * creates the Liability associated with the session
     * @param  $session entity
     * @return void
     */
    public function createLiability($session)
    {
        $liability = $this->Liabilities->newEntity();
        $liability->fk_table = 'Sessions';
        $liability->fk_id = $session->id;
        $liability->amount = $session->topic->price * 0.01 *(100 - $session->coach->commission);
        $liability->commission = $session->coach->commission;
        $liability->status = $liability::STATUS_PENDING;
        $this->Liabilities->save($liability); 
    }

}
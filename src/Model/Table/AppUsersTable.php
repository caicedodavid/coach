<?php
namespace App\Model\Table;

use Cake\Core\Configure as Config;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use CakeDC\Users\Model\Table\UsersTable;
use Cake\Utility\Hash;
use Burzum\FileStorage\Storage\StorageManager;
use App\CalendarAdapters\Calendar;
use \DateTime;
use \DateTimeZone;
use \DateInterval;
use App\Error\AgendaRequestException;
use App\Utils\Logger;
use App\Utils\CropAvatar;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $SocialAccounts
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AppUsersTable extends UsersTable
{

	public function initialize(array $config) 
    {
        parent::initialize($config);
        $this->addBehavior('Burzum/Imagine.Imagine');

        $this->hasMany('UserImage', [
            'className' => 'UserImage',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'UserImage.model' => 'AppUsers'
            ],
            'sort' => [
                'UserImage.created' => 'desc',
            ]
        ]);

        $this->hasMany('Users', [
            'foreignKey' => 'user_id',
            'className' => 'Sessions',
        ]);
        $this->hasMany('CoachSessions', [
            'foreignKey' => 'coach_id',
            'className' => 'Sessions',
        ]);
        $this->hasMany('Coach', [
            'foreignKey' => 'coach_id',
            'className' => 'Topics',
        ]);
        $this->hasMany('PaymentInfos', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->allowEmpty('first_name');

        $validator
            ->allowEmpty('last_name');

        $validator
            ->allowEmpty('token');

        $validator
            ->add('token_expires', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('token_expires');

        $validator
            ->allowEmpty('api_token');

        $validator
            ->add('activation_date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('activation_date');

        $validator
            ->add('tos_date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('tos_date');

        $validator
            ->add('birthdate','custom',[
                    'rule' => 'adultValidation',
                    'provider' => 'table',
                    'message'=>'You must be at least 18 years old',
                ]
            );

        return $validator;
    }

    /**
     * Set role to the user given t
     *
     * @param $userId
     * @param $role
     * @return array
     */
    public function setRole($userId, $role)
    {
        $user = $this->get($userId);
        $user = $this->patchEntity($user, ['role' => $role], ['accessibleFields' => [
                '*' => true,
                'id' => false,
                'is_superuser' => false,
                'role' => true,
            ]]);
        return $this->save($user);
    }

    /**
     * Return the role list
     *
     * @return array
     */
    public function getRoleList()
    {
        $roles = (array)Configure::read('Users.roles');

        return Hash::combine($roles, '{n}.role', '{n}.description');
    }

    /**
     * Finder method for finding coches
     *
     * @return Query
     */
    public function findCoaches(Query $query, array $options)
    {
        return $query->where([
                'AppUsers.role' => 'coach',
                'AppUsers.active' => true
            ])
            ->contain('UserImage');
    }

    /**
     * Finder a user by its username
     *
     * @return Query
     */
    public function findByUsername(Query $query, array $options)
    {
        if (empty($options['username'])) {
            throw new \InvalidArgumentException(__('username is not defined'));
        }
        $username = $options['username'];
        return $query->where([
            'OR' => [
                [$this->aliasField('username') => $username],
                [$this->aliasField('email') => $username]
            ]
       ]);
    }

    /**
     * Finder method for finding the rated sessions of a coach
     *
     * @return Query
     */
    public function findRatedByCoach(Query $query, array $options)
    {
        if (empty($options['userId'])) {
            throw new \InvalidArgumentException(__('userId is not defined'));
        }
        $userId = $options["userId"];
        return $query->where([
                $this->aliasField("id") => $userId
            ])
            ->find('containCoachesRated');
    }

    /**
     * Finder method for finding the rated sessions
     *
     * @return Query
     */
    public function findContainCoachesRated(Query $query, array $options)
    {
        return $query->contain(['CoachSessions' => function (Query $query) {
            return $query->select(['user_rating', 'coach_id'])
                ->where(['CoachSessions.user_rating IS NOT' => null]);
        }]);
    }

    /**
     * Validator function to check if user is out of age
     *
     * @return boolean
     */
    public function adultValidation($check, array $context)
    {
        if ($this->get($context['data']['id'])['role'] ==='coach'){
            return date('Y-m-d',strtotime($check)) <= date('Y-m-d',strtotime("-18 years"));
        }
        return true;
    }

    /**
     * Check if user has active cards
     *
     * @return boolean
     */
    public function hasActiveCards($user)
    {
        return $this->PaymentInfos->find('userCards',['user' => $user])
            ->count() > 0;
    }

    /**
     * Update the rating of a coach
     *
     * @return boolean
     */
    public function updateCoachRating($userId)
    {
        $user = $this->find('ratedByCoach', ['userId' => $userId])
            ->first();

        $ratings = Hash::extract($user->coach_sessions,'{n}.user_rating');
        $user->rating = array_sum($ratings) / count($ratings); 
        return $this->save($user); 
    }

    /**
     * save user image 
     *
     * @param Array post data 
     * @return Array
     */
    public function saveImage($data, $userId)
    {
        if(!$data['file']['size']) {
            return true;
        }
        
        $crop = new CropAvatar(null, $data['data'], $data['file'], Config::read('FileStorage.imageSizes.AppUsers.medium.thumbnail.width'),
            Config::read('FileStorage.imageSizes.AppUsers.medium.thumbnail.height'));
        $data['file']['tmp_name'] = $crop->getResult();
        $entity = $this->UserImage->newEntity();
        $entity = $this->UserImage->patchEntity($entity, array('file' => $data['file']));

        if($this->UserImage->uploadImage($userId, $entity)) {
            @unlink($crop->getResult());
            return true;
        };
        return false;
    }

    /**
     * get calendar
     *
     * @param $coachId id fo coach
     * @return Calendar Calendar instance
     */
    public function getCalendar($token = null, $calendarId = null, $calendarProvider = Calendar::GOOGLE_CALENDAR)
    {
        return Calendar::getInstance($token, $calendarId, $calendarProvider);
    }

    /**
     * getAgenda
     *
     * get the busy time of a coach 12 hours after and 12 hours before the
     * proposed time of the session
     *
     * @param $coachId id of coach
     * @return json string
     */
    public function generateCalendarUrl()
    {
        $calendar = $this->getCalendar();
        return $calendar->generateAuthUrl();
    }

    /**
     * check availability of coach and user
     *
     * @param $coachId id of coach
     * @param $userId id of user
     * @param $selectedTime the selectedTime of the session
     * @param $duration the duration in minutes of the session
     * @param $timezone timezone of the user
     * @return Array
     */
    public function checkAvailability($coachId, $userId, $selectedTime, $duration, $timezone)
    {
        $coach = $this->get($coachId);
        $user = $this->get($userId);
        $coachCalendar = $this->getCalendar($coach->external_calendar_token, $coach->external_calendar_id);
        $userCalendar = $this->getCalendar($user->external_calendar_token, $user->external_calendar_id);
        $startTime = new DateTime($selectedTime, new DateTimeZone($timezone));
        $endTime = clone $startTime;
        $endTime->add(new DateInterval('PT' . $duration . 'M'));
        #une uses get busy and the other uses getEvents because getBusy only gets confirmed events
        try {
        return array($coachCalendar->listBusy($startTime->format('c'), $endTime->format('c'), $timezone),
            $userCalendar->listEvents($startTime->format('c'), $endTime->format('c'), $timezone));
        }
        catch (AgendaRequestException $e) {
            Logger::apiWarning($e, ['table' => $this->alias(), 'action' => 'checkAvailability', 'id' => $userId]);
            return array(null,null);
        }
    }

    /**
     * check availability just for the coach
     *
     * @param $coachId id fo coach
     * @param $selectedTime the time to check
     * @param $duration the duration in minutes of the session
     * @param $timezone timezone of the user
     * @return Array
     */
    public function checkCoachAvailability($coachId, $selectedTime, $duration, $timezone)
    {
        $coach = $this->get($coachId);
        $coachCalendar = $this->getCalendar($coach->external_calendar_token, $coach->external_calendar_id);
        $startTime = new DateTime($selectedTime, new DateTimeZone($timezone));
        $endTime = clone $startTime;
        $endTime->add(new DateInterval('PT' . $duration . 'M'));
        return $coachCalendar->listBusy($startTime->format('c'), $endTime->format('c'), $timezone);
    }

    /**
     * listBusy
     *
     * get the busy time of a coach 12 hours after and 12 hours before the
     * proposed time of the session
     *
     * @param $coachId id of coach
     * @param $selectedTime the time to check
     * @param $timezone timezone of the user
     * @return json string
     */
    public function listBusy($coachId, $selectedTime, $timezone)
    {
        $coach = $this->get($coachId);
        $calendar = $this->getCalendar($coach->external_calendar_token, $coach->external_calendar_id);
        $startTime = new DateTime($selectedTime, new DateTimeZone($timezone));
        $endTime = clone $startTime;
        $listBusy = $calendar->listBusy($startTime->sub(new DateInterval('PT12H'))->format('c'), $endTime->add(new DateInterval('PT12H'))->format('c'), $timezone);
        foreach ($listBusy as $busy) {
            $start = DateTime::createFromFormat("Y-m-d\TH:i:sT", $busy['start']);
            $start->setTimezone(new DateTimeZone($timezone));
            $end = DateTime::createFromFormat("Y-m-d\TH:i:sT", $busy['end']);
            $end->setTimezone(new DateTimeZone($timezone));
            $results[] = __("{0}, from {1} to {2}", $start->format('M d'), $start->format('h:i a'), $end->format('h:i a'));
        }
        return $results;
    }

    /**
     * getAgenda
     *
     * get the busy time of a coach 12 hours after and 12 hours before the
     * proposed time of the session
     *
     * @param $coachId id of coach
     * @param $timezone timezone of the user
     * @return json string
     */
    public function getAgenda($coachId, $timezone)
    {
        $coach = $this->get($coachId);
        if (!$coach->external_calendar_id) {
            return null;
        }
        $calendar = $this->getCalendar($coach->external_calendar_token, $coach->external_calendar_id);
        $startTime = new DateTime("today", new DateTimeZone($timezone));
        $endTime = clone $startTime;
        return $calendar->listEvents($startTime->format('c'), $endTime->add(new DateInterval('P1M'))->format('c'), $timezone);
    }

    /**
     * scheduleEvent
     *
     * Schedule the session in the users calendar
     *
     * @param $data data of the event
     * @param $userId id of user
     * @return Event entity
     */
    public function scheduleEvent($data, $userId)
    {
        $user = $this->get($userId);
        $calendar = $this->getCalendar($user->external_calendar_token, $user->external_calendar_id);
        $startTime = new DateTime($data['schedule'], new DateTimeZone($data['timezone']));
        $endTime = clone $startTime;
        $data['startTime'] = $startTime->format('c');
        $data['endTime'] = $endTime->add(new DateInterval('PT' . $data['duration'] . 'M'))->format('c');
        $data['coachFullName'] = $this->get($data['coachId'])->full_name;
        $data['userFullName'] = $this->get($data['userId'])->full_name;
        return $calendar->createEvent($data);
    }

    /**
     * scheduleExternalEvents
     *
     * Schedule the session in the users calendar
     *
     * @param $data data of the event
     * @return Array
     */
    public function scheduleExternalEvents($data)
    {
        return array($this->scheduleEvent($data, $data['coachId']), $this->scheduleEvent($data, $data['userId']));
    }


    /**
     * delete Event
     *
     * Delete the session in the users calendar
     *
     * @param $coachId id fo coach
     * @param $eventId the Id of the agenda event
     * @return Event entity
     */ 
    public function deleteEvent($coachId, $eventId)
    {
        $coach = $this->get($coachId);
        $calendar = $this->getCalendar($coach->external_calendar_token, $coach->external_calendar_id);
        return $calendar->deleteEvent($eventId);
    }

    /**
     * confirm Event
     *
     * confirm the session in the users calendar
     *
     * @param $coachId id fo coach
     * @param $eventId the Id of the agenda event
     * @return Event entity
     */
    public function confirmEvent($coachId, $eventId)
    {
        $coach = $this->get($coachId);
        $calendar = $this->getCalendar($coach->external_calendar_token, $coach->external_calendar_id);
        return $calendar->confirmEvent($eventId);
    }

    /**
     * unconfirm Event
     *
     * change the status of the event to not confirmed
     *
     * @param $coachId id fo coach
     * @param $eventId the Id of the agenda event
     * @return Event entity
     */
    public function unconfirmEvent($coachId, $eventId)
    {
        $coach = $this->get($coachId);
        $calendar = $this->getCalendar($coach->external_calendar_token, $coach->external_calendar_id);
        return $calendar->unconfirmEvent($eventId);
    }

    /**
     * confirm Event
     *
     * Schedule the session in the users calendar
     *
     * @param $userId id of user
     * @param $code the code to confirm agenda subscrption
     * @return string 
     */
    public function storeToken($userId, $code)
    {
        $calendar = $this->getCalendar();
        $token = $calendar->getToken($code);
        $user = $this->get($userId);
        $user->external_calendar_token = json_encode($token);
        $user->external_calendar_id = $calendar->createCalendar('Coach Calendar');
        $user->calendar_provider = Calendar::GOOGLE_CALENDAR;
        return $this->save($user);
    }

    /**
     * check calendar
     *
     * check if user has already synced the calendar
     *
     * @param $userId id of user
     * @return calendarId
     */
    public function checkCalendar($userId)
    {
        return $this->get($userId)->external_calendar_id;
    }

}

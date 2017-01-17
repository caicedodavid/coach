<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Google_Service_Calendar;
use Google_Client;
use Google_Service_Calendar_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_FreeBusyRequest;
use Google_Service_Calendar_FreeBusyRequestItem;

/**
 * Topics Controller
 *
 * @property \App\Model\Table\TopicsTable $Topics
 */
class CalendarsController extends AppController
{   

	const APPLICATION_NAME = 'Coach';
	const PRIMARY_CALENDAR_ID = 'primary';
	// If modifying these scopes, delete your previously saved credentials
	// at ~/.credentials/calendar-php-quickstart.json
	

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
    	$this->loadModel('AppUsers');
    	parent::initialize();
    	require ROOT . '/vendor/autoload.php';
        define('SCOPES', implode(' ', array(
  			Google_Service_Calendar::CALENDAR)
		));
    }
        
	private function getClient($userId = null) {
	  	$client = new Google_Client();
	  	$client->setApplicationName(self::APPLICATION_NAME);
	  	$client->setScopes(SCOPES);
	  	$client->setAuthConfig(Configure::read('Calendar.clientSecret'));
	  	$client->setAccessType('offline');
	  	$user = $this->AppUsers->get($userId ? $userId : $this->getUser()['id']);
	  	if($user->is_primary_calendar){
	  		$accessToken = json_decode($user->external_calendar_id, true);
	  	} else {
	  		$user = $this->AppUsers->find('byUsername',['username' => 'superadmin'])->first();
		  	$accessToken = json_decode($user->external_calendar_id, true);
	  	}
		$client->setAccessToken($accessToken);
		// Refresh the token if it's expired.
		if ($client->isAccessTokenExpired()) {
			$refreshToken = $client->getRefreshToken();
		  	$client->fetchAccessTokenWithRefreshToken($refreshToken);
		  	$newToken = $client->getAccessToken();
			$newToken['refresh_token'] = $refreshToken;
			$user->external_calendar_id = json_encode($newToken);
            if ($this->AppUsers->save($user)) {
                $this->Flash->success(__('The token has been renewed'));
            } else {
                $this->Flash->error(__('The token could not be renewed. Please, try again.'));
            }
		}
		return $client;
	}

	public function getToken() {
	  	$client = new Google_Client();
	  	$client->setScopes(SCOPES);
	  	$client->setAuthConfig(Configure::read('Calendar.clientSecret'));
	  	$client->setAccessType('offline');
	  	$client->setRedirectUri(Router::url(['controller' => 'Calendars', 'action' => 'saveToken'],true));
	  	$this->redirect(filter_var($client->createAuthUrl(), FILTER_SANITIZE_URL));
	}

	public function saveToken() {
		$client = new Google_Client();
		$client->setAuthConfig(Configure::read('Calendar.clientSecret'));
        $accessToken = $client->authenticate($this->request->query['code']);
        $user = $this->AppUsers->get($this->getUser()['id']);
		$user->external_calendar_id = json_encode($accessToken);
		$user->is_primary_calendar = true;
		if ($this->AppUsers->save($user)) {
            $this->Flash->success(__('The calendar has been saved.'));
        
        } else {
            $this->Flash->error(__('The calendar could not be saved. Please, try again.'));
        }
        return $this->redirect(['action' => 'listEvents']);

	}

	private function getCalendarId($userId = null) {
		$userId = $userId ? $userId : $this->getUser()['id'];
		$user = $this->AppUsers->get($userId);
		return $user['is_primary_calendar'] ? self::PRIMARY_CALENDAR_ID : $user["external_calendar_id"];
	}

	public function createCalendar() {
		$client = $this->getClient();
		$service = new Google_Service_Calendar($client);
		$calendar = new Google_Service_Calendar_Calendar();
		$calendar->setSummary('David10 caledar');
		$calendar->setTimeZone('America/Caracas');
		
		$createdCalendar = $service->calendars->insert($calendar);
		$user = $this->AppUsers->get($this->getUser()['id']);
		$user->external_calendar_id = $createdCalendar->getId();
		if ($this->AppUsers->save($user)) {
            $this->Flash->success(__('The calendar has been saved.'));
        
        } else {
            $this->Flash->error(__('The calendxar could not be saved. Please, try again.'));
        }
        return $this->redirect(['action' => 'listEvents']);
	}

	public function createEvent() {
		$client = $this->getClient();
		$service = new Google_Service_Calendar($client);
		$event = new Google_Service_Calendar_Event(array(
  			'summary' => 'Test Event',
  			'location' => '800 Howard St., San Francisco, CA 94103',
  			'description' => 'Evento 1',
  			'start' => array(
  			  'dateTime' => '2017-01-21T10:00:00',
  			  'timeZone' => 'America/Caracas',
  			),
  			'end' => array(
  			  'dateTime' => '2017-01-21T12:00:00',
  			  'timeZone' => 'America/Caracas',
  			),
		));

		$calendarId = $this->getCalendarId();
		$event = $service->events->insert($calendarId, $event);
		return $this->redirect(['action' => 'listEvents']);
	}

	public function listEvents() {
		if (!$this->getUser()['external_calendar_id']) {
			$this->set('events', null);
		} else {
	
			$client = $this->getClient();
			$service = new Google_Service_Calendar($client);
			
			// Print the next 10 events on the user's calendar.
			$calendarId = $this->getCalendarId();
			$optParams = array(
			  	'maxResults' => 10,
			  	'orderBy' => 'startTime',
			  	'singleEvents' => TRUE,
			  	'timeMin' => date('c'),
			);
			$results = $service->events->listEvents($calendarId, $optParams);
			$this->set('events', $results->getItems);
			$this->set('events', null);
        	$this->set('_serialize', ['events']);
		}
	}

	public function getCalendar($userId) {
		$client = $this->getClient($userId);
		$service = new Google_Service_Calendar($client);
		$freebusy_req = new Google_Service_Calendar_FreeBusyRequest();
		$freebusy_req->setTimeMin(date(\DateTime::ATOM, strtotime('2017-01-17')));
		$freebusy_req->setTimeMax(date(\DateTime::ATOM, strtotime('2017-01-23')));
		$freebusy_req->setTimeZone('America/Caracas');
		$item = new Google_Service_Calendar_FreeBusyRequestItem();
		$calendarId = $this->getCalendarId($userId);
		$item->setId($calendarId);
		$freebusy_req->setItems(array($item));
		$query = $service->freebusy->query($freebusy_req);
		$this->set('events', $query->getCalendars()[$calendarId]->getBusy());
		$this->set('_serialize', ['events']);
	}


}
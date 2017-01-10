<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Routing\Router;
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
	const CREDENTIALS_PATH = '~/.credentials/calendar-php-quickstart.json';
	const CLIENT_SECRET_PATH = ROOT . '/webroot/js/client_secret_135345448683-g6t7c5u9lbvr9lkkfr902q45i2imn5an.apps.googleusercontent.com.json';
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
    	require ROOT . '/vendor/autoload.php';;
        define('SCOPES', implode(' ', array(
  			Google_Service_Calendar::CALENDAR)
		));
    }

    /**
	 * Expands the home directory alias '~' to the full path.
	 * @param string $path the path to expand.
	 * @return string the expanded path.
	 */
	private function expandHomeDirectory($path) {
		$homeDirectory = getenv('HOME');
		if (empty($homeDirectory)) {
		 	$homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
		}
		return str_replace('~', realpath($homeDirectory), $path);
	}
        
	private function getClient($user = null) {
	  	$client = new Google_Client();
	  	$client->setApplicationName(self::APPLICATION_NAME);
	  	$client->addScopes(SCOPES);
	  	$client->setAuthConfig(self::CLIENT_SECRET_PATH);
	  	$client->setAccessType('offline');
	  	$user = $user ? $user : $this->getUser();
	  	if($user['is_primary_calendar']){
	  		$accessToken = json_decode($user['external_calendar_id']);
	  	} else {
	  		$credentialsPath = $this->expandHomeDirectory(self::CREDENTIALS_PATH);
	  		if (file_exists($credentialsPath)) {
		  		$accessToken = json_decode(file_get_contents($credentialsPath), true);
			}
	  	}
		$client->setAccessToken($accessToken);
		// Refresh the token if it's expired.
		if ($client->isAccessTokenExpired()) {
			debug($client->getRefreshToken());
		  	$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		  	file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
		}
		return $client;
	}

	public function getToken() {
	  	$client = new Google_Client();
	  	$client->setScopes(SCOPES);
	  	$client->setClientId('229082176425-7gj807o0a2mmhde3gucfqdcdlh18nmuo.apps.googleusercontent.com');
	  	$client->setAccessType('offline');
	  	$client->setRedirectUri(Router::url(['controller' => 'Calendars', 'action' => 'saveToken'],true));
	  	debug($client->createAuthUrl());
	  	exit();
	  	$this->redirect(filter_var($client->createAuthUrl(), FILTER_SANITIZE_URL));
	}

	public function saveToken() {
		$client = new Google_Client();
        $accessToken = $client->authenticate($this->request->query['code']);
        $user = $this->AppUsers->get($this->getUser()['id']);
        debug($accessToken);
        exit();
		$user->external_calendar_id = json_encode($accessToken);
		if ($this->AppUsers->save($user)) {
            $this->Flash->success(__('The calendar has been saved.'));
        
        } else {
            $this->Flash->error(__('The calendar could not be saved. Please, try again.'));
        }
        return $this->redirect(['action' => 'listEvents']);

	}

	private function getCalendarId($user = null) {
		$user = $user ? $user : $this->getUser();
		return $user['is_primary_calendar'] ? self::PRIMARY_CALENDAR_ID : $user->external_calendar_id;
	}

	public function createCalendar() {
		$client = $this->getClient();
		$service = new Google_Service_Calendar($client);
		$calendar = new Google_Service_Calendar_Calendar();
		$calendar->setSummary('Test calendar');
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
  			'description' => 'Testing the events',
  			'start' => array(
  			  'dateTime' => '2017-01-11T09:00:00-07:00',
  			  'timeZone' => 'America/Caracas',
  			),
  			'end' => array(
  			  'dateTime' => '2017-01-11T12:00:00-07:00',
  			  'timeZone' => 'America/Caracas',
  			),
		));

		$calendarId = $this->getCalendarId();
		$event = $service->events->insert($calendarId, $event);
		return $this->redirect(['action' => 'listEvents']);
	}

	public function listEvents() {
		if ($this->getUser()['external_calendar_id']) {
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
			$this->set('events', $results->getItems());
        	$this->set('_serialize', ['events']);
		}
	}

	public function getCalendar($userId) {
		$user = $this->AppUsers->get($userId);
		$client = $this->getClient($user);
		$service = new Google_Service_Calendar($client);
		$freebusy_req = new Google_Service_Calendar_FreeBusyRequest();
		$freebusy_req->setTimeMin(date(\DateTime::ATOM, strtotime('2017-01-09')));
		$freebusy_req->setTimeMax(date(\DateTime::ATOM, strtotime('2017-01-14')));
		$freebusy_req->setTimeZone('America/Caracas');
		$item = new Google_Service_Calendar_FreeBusyRequestItem();
		$item->setId($this->getCalendarId($user));
		$freebusy_req->setItems(array($item));
		$query = $service->freebusy->query($freebusy_req);
		$this->set('events', $query->getCalendars()[$user->external_calendar_id]->getBusy());
		$this->set('_serialize', ['events']);
	}


}
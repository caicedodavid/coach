<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
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
	const CLIENT_SECRET_PATH = ROOT . '/webroot/js/client_secret_135345448683-diubgddapsep3f5022n882mkdt1qpdmn.apps.googleusercontent.com.json';
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
  			Google_Service_Calendar::CALENDAR_READONLY)
		));
    }
        
	private function getClient() {
	  	$client = new Google_Client();
	  	$client->setApplicationName(self::APPLICATION_NAME);
	  	$client->setScopes(SCOPES);
	  	$client->setAuthConfig(self::CLIENT_SECRET_PATH);
	  	$client->setAccessType('offline');
	
	  	// Load previously authorized credentials from a file.
		$credentialsPath = $this->expandHomeDirectory(self::CREDENTIALS_PATH);
		if (file_exists($credentialsPath)) {
		  	$accessToken = json_decode(file_get_contents($credentialsPath), true);
		} else {
		  	// Request authorization from the user.
		  	$authUrl = $client->createAuthUrl();
		  	printf("Open the following link in your browser:\n%s\n", $authUrl);
		  	print 'Enter verification code: ';
		  	$authCode = trim(fgets(STDIN));
		
		  	// Exchange authorization code for an access token.
		  	$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
		
		  	// Store the credentials to disk.
		  	if(!file_exists(dirname($credentialsPath))) {
		  	  mkdir(dirname($credentialsPath), 0700, true);
		  	}
		  	file_put_contents($credentialsPath, json_encode($accessToken));
		  	printf("Credentials saved to %s\n", $credentialsPath);
		}
		$client->setAccessToken($accessToken);
	
		// Refresh the token if it's expired.
		if ($client->isAccessTokenExpired()) {
		  	$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		  	file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
		}
		return $client;
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

	public function listEvents() {
		if (!$this->getUser()['external_calendar_id']) {
			$this->set('events', null);
		} else {
	
			$client = $this->getClient();
			$service = new Google_Service_Calendar($client);
			
			// Print the next 10 events on the user's calendar.
			$calendarId = $this->getUser()['external_calendar_id'];
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

	public function createCalendar() {
		$client = $this->getClient();
		$service = new Google_Service_Calendar($client);
		$calendar = new Google_Service_Calendar_Calendar();
		$calendar->setSummary('Test calendar');
		$calendar->setTimeZone('America/Caracas');
		
		$createdCalendar = $service->calendars->insert($calendar);
		$user = $this->AppUsers->get($this->getUser()['id']);
		$user->external_calendar_id = $createdCalendar->getId();;
		if ($this->AppUsers->save($user)) {
            $this->Flash->success(__('The topic has been saved.'));
        
        } else {
            $this->Flash->error(__('The topic could not be saved. Please, try again.'));
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

		$calendarId = $this->getUser()['external_calendar_id'];
		$event = $service->events->insert($calendarId, $event);
		return $this->redirect(['action' => 'listEvents']);
	}

	public function getCalendar($userId) {
		$user = $this->AppUsers->get($userId);
		$client = $this->getClient();
		$service = new Google_Service_Calendar($client);
		$freebusy_req = new Google_Service_Calendar_FreeBusyRequest();
		$freebusy_req->setTimeMin(date(\DateTime::ATOM, strtotime('2017-01-09')));
		$freebusy_req->setTimeMax(date(\DateTime::ATOM, strtotime('2017-01-14')));
		$freebusy_req->setTimeZone('America/Caracas');
		$item = new Google_Service_Calendar_FreeBusyRequestItem();
		$item->setId($user->external_calendar_id);
		$freebusy_req->setItems(array($item));
		$query = $service->freebusy->query($freebusy_req);
		debug($query);
		$this->set('events', $query->getCalendars()[$user->external_calendar_id]->getBusy());
		$this->set('_serialize', ['events']);
	}


}
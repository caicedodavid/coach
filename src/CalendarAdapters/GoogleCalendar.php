<?php
namespace App\CalendarAdapters;

use App\CalendarAdapters\CalendarAdapter;
use Cake\Network\Exception\InternalErrorException;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Google_Service_Calendar;
use Google_Client;
use Google_Service_Calendar_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_FreeBusyRequest;
use Google_Service_Calendar_FreeBusyRequestItem;
use Google_Service_Exception;
require ROOT . '/vendor/autoload.php';

/*
 * Implementation of the Live Session with Braincert
 *
 */
class GoogleCalendar implements CalendarAdapter
{
	const APPLICATION_NAME = 'Coach';
    const PRIMARY_CALENDAR_ID = 'primary';
    const EVENT_STATUS_CONFIRMED = "confirmed";
    const EVENT_STATUS_TENTATIVE = "tentative";
    private $client;
    private $calendarId;

    /**
     * constructor de la agenda.
     *
     * @param $userToken token del usuario.
     * @param $calendarID
     */
    public function __construct($userToken = null, $calendarId = null)
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(Configure::read('Calendar.clientSecret'));
        $this->client->setRedirectUri(Router::url(['controller' => 'AppUsers', 'action' => 'saveCalendarToken'],true));
        
        if (!$userToken) {
            return null;
        }
        $this->calendarId = $calendarId ? $calendarId : self::PRIMARY_CALENDAR_ID;
        return $this->getClient($userToken);
    }

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @param $userToken user calendar token.
     * @return $newToken user calendar token.
     */
    private function getClient($userToken) 
    {
        $accessToken = json_decode($userToken, true);
        $this->client->setAccessToken($accessToken);
        // Refresh the token if it's expired.
        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            $newToken = $this->client->getAccessToken();
            $newToken['refresh_token'] = $refreshToken;
            $userToken = $newToken;
        }
        return $userToken;
    }

    /**
     * Generate auth url
     *
     * generates the url for the user to give permissions to the calendar app
     *
     * @return string url to be redirected
     */
    public function generateAuthUrl()
    {
        $this->client->setApplicationName(self::APPLICATION_NAME);
        $this->client->setScopes(implode(' ', array(Google_Service_Calendar::CALENDAR)));
        $this->client->setAccessType('offline');
        return filter_var($this->client->createAuthUrl(), FILTER_SANITIZE_URL);
    }

    /**
     * Get Token
     * 
     * Get the token api using the code received
     *
     * @return string POST response
     */
    public function getToken($code)
    {
        $token = $this->client->authenticate($code);
        return $this->getClient(json_encode($token));
    }

    /**
     * Creat event
     *
     * Method to creat an event in the calendar
     *
     * @param $data data array for the event.
     * @return event id
     */
    public function createEvent($topicName, $startTime, $endTime, $timezone)
    {
        if (!$this->calendarId) {
            throw new NotImplementedException("No calendar defined", 501);  
        }
        $data = [
            'summary' => $topicName,
            'description' => $topicName,
            'start' => [
              'dateTime' => $startTime,
              'timeZone' => $timezone,
            ],
            'end' => [
              'dateTime' => $endTime,
              'timeZone' => $timezone,
            ],
            'status' => self::EVENT_STATUS_TENTATIVE
        ];
        $service = new Google_Service_Calendar($this->client);
        $event = new Google_Service_Calendar_Event($data);
        return $service->events->insert($this->calendarId, $event)['id'];
    }

    /**
     * confirm event
     *
     * Method to confirm an event from the calendar
     *
     * @param $eventId event id
     * @return string POST response
     */
    public function confirmEvent($eventId)
    {
        return $this->changeEventStatus('7'. substr($eventId), self::EVENT_STATUS_CONFIRMED);

    }

    /**
     * unconfirm event
     *
     * Method to unconfirm an event from the calendar
     *
     * @param $eventId event id
     * @return string POST response
     */
    public function unconfirmEvent($eventId)
    {
        return $this->changeEventStatus($eventId, self::EVENT_STATUS_TENTATIVE);
    }

    /**
     * change status
     *
     * Method to change status of an event from the calendar
     *
     * @param $eventId event id
     * @return string POST response
     */
    private function changeEventStatus($eventId, $status)
    {
        $service = new Google_Service_Calendar($this->client);
        $event = $service->events->get($this->calendarId, $eventId);
        $event->setStatus($status);
        debug($service->events->update($this->calendarId, $event->getId(), $event));

    }

    /**
     * eliminar evento
     *
     * Method to remove an event from the calendar
     *
     * @param $eventId event id
     * @return string POST response
     */
    public function deleteEvent($eventId)
    {
        $service = new Google_Service_Calendar($this->client);
        return $service->events->delete($this->calendarId, $eventId);
    }

    /**
     * list events
     *
     * Method to get the events in a time period
     *
     * @param $startDate date and time of period
     * @param $endDate date and time of period to end
     * @param $timezone timezone of the period to list
     * @return json response
     */
    public function listEvents($startDate, $endDate, $timezone)
    {   
        if (!$this->calendarId) {
            throw new NotImplementedException("No calendar defined", 501);  
        }

        $service = new Google_Service_Calendar($this->client);
        $optParams = [
            'timeMin' => $startDate,
            'timeMax' => $endDate,
            'timeZone' => $timezone,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE
        ];
        $results = $service->events->listEvents($this->calendarId, $optParams);
        return $this->calendarFormat($results->getItems());
    }

    /**
     * list method
     * 
     * Method that returns the busy blocks in a period of time
     *
     * @param $startDate fecha y hora de inicio de período a solicitar
     * @param $endDate fecha y hora final de período a solicitar
     * @param $timezone timezone del período a solicitar
     * @return string POST response
     */
    public function listBusy($startDate, $endDate, $timezone)
    {
        if (!$this->calendarId) {
            throw new NotImplementedException("No calendar defined", 501);  
        }
        $this->client->setScopes(implode(' ', array(Google_Service_Calendar::CALENDAR)));
        $service = new Google_Service_Calendar($this->client);

        $freebusy_req = new Google_Service_Calendar_FreeBusyRequest();
        $freebusy_req->setTimeMin($startDate);
        $freebusy_req->setTimeMax($endDate);
        $freebusy_req->setTimeZone($timezone);
        $item = new Google_Service_Calendar_FreeBusyRequestItem();
        $item->setId($this->calendarId);
        $freebusy_req->setItems(array($item));
        $query = $service->freebusy->query($freebusy_req);
        return $query->getCalendars()[$this->calendarId]->getBusy();
    }

    /**
     * create calendar
     * 
     * Método que retorna un calendario secundario
     *
     * @param $calendarName
     * @return calendarId
     */
    public function createCalendar($calendarName) 
    {
        $service = new Google_Service_Calendar($this->client);
        $calendar = new Google_Service_Calendar_Calendar();
        $calendar->setSummary($calendarName);
        $calendar->setTimeZone('America/Caracas');
        $createdCalendar = $service->calendars->insert($calendar);
        return $createdCalendar->getId();
    }

    /**
     * calendarFormat
     *
     * Method to transfrom the google calendar events to the agenda format for
     * visualization
     *
     * @return json response
     */
    private function calendarFormat($events)
    {
        $results = [];
        foreach ($events as $event) {
            if ($event->status === self::EVENT_STATUS_TENTATIVE) {
                continue;
            }
            $formattedEvent['title'] = $event->getSummary();
            $formattedEvent['start'] = $event->start->dateTime;
            $formattedEvent['end'] = $event->end->dateTime;
            $formattedEvent['status'] = $event->status;
            $formattedEvent['color'] = $event->status === self::EVENT_STATUS_TENTATIVE ? 'red' : 'green';
            $formattedEvent['editable'] = false;
            $results[] = $formattedEvent;
        }
        return $results;
    }
}
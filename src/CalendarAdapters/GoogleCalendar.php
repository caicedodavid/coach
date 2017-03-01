<?php
namespace App\CalendarAdapters;

use App\CalendarAdapters\CalendarAdapter;
use App\Error\AgendaRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Network\Exception\BadRequestException;
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
    public function createEvent($data)
    {
        if (!$this->calendarId) {
            throw new BadRequestException("No calendar defined");  
        }
        $data = [
            'summary' => $data['subject'],
            'description' => $data['subject'],
            'start' => [
              'dateTime' => $data['startTime'],
            ],
            'end' => [
              'dateTime' => $data['endTime'],
            ],
            'status' => self::EVENT_STATUS_TENTATIVE,
            'extendedProperties' => [
                'private' => [
                    'sessionId' => $data['sessionId'],
                    'url' => Router::url(['controller' => 'Sessions', 'action' => 'view', $data['sessionId']],true),
                    'coachFullName' => $data['coachFullName'],
                    'userFullName' => $data['userFullName']
                ]
            ]
        ];
        $service = new Google_Service_Calendar($this->client);
        $event = new Google_Service_Calendar_Event($data);
        try {
            return $service->events->insert($this->calendarId, $event)['id'];
        } catch (\Exception $e) {
            throw new AgendaRequestException($e->getMessage());
        }
        
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
        return $this->changeEventStatus($eventId, self::EVENT_STATUS_CONFIRMED);

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
        try {
            $event = $service->events->get($this->calendarId, $eventId);
            $event->setStatus($status);
            $service->events->update($this->calendarId, $event->getId(), $event);
        } catch (\Exception $e) {
            throw new AgendaRequestException($e->getMessage());
        }
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
        try {
            return $service->events->delete($this->calendarId, $eventId);
        } catch (\Exception $e) {
            throw new AgendaRequestException($e->getMessage());
        }  
    }

    /**
     * get events
     *
     * Method to get the events in a time period
     *
     * @param $startDate date and time of period
     * @param $endDate date and time of period to end
     * @param $timezone timezone of the period to list
     * @return json response
     */
    private function getEvents($startDate, $endDate, $timezone)
    {   
        if (!$this->calendarId) {
            throw new BadRequestException("No calendar defined");  
        }
        $service = new Google_Service_Calendar($this->client);
        $optParams = [
            'timeMin' => $startDate,
            'timeMax' => $endDate,
            'timeZone' => $timezone,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE
        ];
        try {
            $results = $service->events->listEvents($this->calendarId, $optParams);
            return $results->getItems();
        } catch (\Exception $e) {
            throw new AgendaRequestException($e->getMessage());
        }  
    }

    /**
     * list all events
     *
     * Method to get all events in time period
     *
     * @param $startDate date and time of period
     * @param $endDate date and time of period to end
     * @param $timezone timezone of the period to list
     * @return json response
     */
    public function listEvents($startDate, $endDate, $timezone)
    {   
        return $this->formatEvents($this->getEvents($startDate, $endDate, $timezone));
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
        return $this->formatBusy($this->getEvents($startDate, $endDate, $timezone));
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
        try {
            $createdCalendar = $service->calendars->insert($calendar);
            return $createdCalendar->getId();
        } catch (\Exception $e) {
            throw new AgendaRequestException($e->getMessage());
        }  
        
    }

    /**
     * format busy
     *
     * Method to transfrom the google calendar events to the agenda format for
     * visualization
     *
     * @return events array
     */
    private function formatBusy($events)
    {
        $results = [];
        foreach ($events as $event) {
            if ($event->status === self::EVENT_STATUS_TENTATIVE) {
                continue;
            }
            $formattedEvent['title'] = $event->getSummary();
            $formattedEvent['start'] = $event->start->dateTime;
            $formattedEvent['end'] = $event->end->dateTime;
            $results[] = $formattedEvent;
        }
        return $results;
    }

    /**
     * format busy
     *
     * Method to transfrom the google calendar events to the agenda format for
     * visualization
     *
     * @return events array
     */
    private function formatEvents($events)
    {
        $results = [];
        foreach ($events as $event) {
            $formattedEvent['title'] = $event->getSummary();
            $formattedEvent['start'] = $event->start->dateTime;
            $formattedEvent['end'] = $event->end->dateTime;
            $formattedEvent['status'] = $event->status;
            $formattedEvent['color'] = $event->status === self::EVENT_STATUS_TENTATIVE ? '#fcf8e3' : '#dff0d8';
            $formattedEvent['textColor'] = $event->status === self::EVENT_STATUS_TENTATIVE ? '#8a6d3b' : '#3c763d';
            $formattedEvent['borderColor'] = $event->status === self::EVENT_STATUS_TENTATIVE ? '#faf2cc' : '#d0e9c6;';
            $formattedEvent['editable'] = false;
            $formattedEvent['sessionId'] = $event->extendedProperties->private['sessionId'];
            $formattedEvent['sessionUrl'] = $event->extendedProperties->private['url'];
            $formattedEvent['coachFullName'] = $event->extendedProperties->private['coachFullName'];
            $formattedEvent['userFullName'] = $event->extendedProperties->private['userFullName'];
            $results[] = $formattedEvent;
        }
        return $results;
    }
}
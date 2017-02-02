<?php
namespace App\CalendarAdapters;

use App\SessionAdapters\CalendarAdapter;
use Cake\Network\Exception\InternalErrorException
use Cake\Routing\Router;
use Cake\Core\Configure;
use Google_Service_Calendar;
use Google_Client;
use Google_Service_Calendar_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_FreeBusyRequest;
use Google_Service_Calendar_FreeBusyRequestItem;

/*
 * Implementation of the Live Session with Braincert
 *
 */
class Braincert implements SessionAdapter
{
	const APPLICATION_NAME = 'Coach';
    const PRIMARY_CALENDAR_ID = 'primary';
    require ROOT . '/vendor/autoload.php';
    private $client;
    private $calendarId;
    define('SCOPES', implode(' ', array(
        Google_Service_Calendar::CALENDAR)
    ));

    /**
     * constructor de la agenda.
     *
     * @param $userToken token del usuario.
     * @param $isPrimary si el calendario del usuario es primario o no
     */
    public function __construct($userToken = null, $isPrimary = null, $calendarId = null);
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(Configure::read('Calendar.clientSecret'));
        
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
     * @param $isPrimary if the calendar is primary or not.
     * @return $newToken user calendar token.
     */
    private function getClient($userToken) 
    {
        $accessToken = json_decode($userToken, true);
        $this->client->setAccessToken($accessToken);
        // Refresh the token if it's expired.
        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $client->getRefreshToken();
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
        $this->client->setScopes(self::SCOPES);
        $this->client->setAccessType('offline');
        $this->client->setRedirectUri(Router::url(['controller' => 'Calendars', 'action' => 'saveToken'],true));
        return filter_var($this->$client->createAuthUrl(), FILTER_SANITIZE_URL);
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
        return json_encode($this->client->authenticate($code));
    }

    /**
     * Creat event
     *
     * Method to creat an event in the calendar
     *
     * @param $data data array for the event.
     * @return string POST response
     */
    public function createEvent($data)
    {
        if(!$this->calendarId){
            throw new NotImplementedException("No calendar defined", 501);  
        }

        $service = new Google_Service_Calendar($this->client);
        $event = new Google_Service_Calendar_Event($data);
        $event = $service->events->insert($this->calendarId, $event);
        return $this->redirect(['action' => 'listEvents']);
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
        if(!$this->calendarId){
            throw new NotImplementedException("No calendar defined", 501);  
        }

        $service = new Google_Service_Calendar($this->client);
        $optParams = array(
            'timeMax' => $startDate,
            'timeMin' => $endDate,
            'timeZone' => $timezone,
        );
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
        if(!$this->calendarId){
            throw new NotImplementedException("No calendar defined", 501);  
        }

        $service = new Google_Service_Calendar($this->client);
        $freebusy_req = new Google_Service_Calendar_FreeBusyRequest();
        $freebusy_req->setTimeMin($startDate);
        $freebusy_req->setTimeMax($endDate);
        $freebusy_req->setTimeZone('America/Caracas');
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
    public function createCalendar($calendarName) {

        $service = new Google_Service_Calendar($this->client);
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
        foreach ($events as $event) {
            $formattedEvent['title'] = $event->getSummary();
            $formattedEvent['start'] = $event->start->dateTime;
            $formattedEvent['end'] = $event->end->dateTime;
            $formattedEvent['color'] = 'red';
            $formattedEvent['editable'] = false;
            $results[] = $formattedEvent;
        }
        return $results;
    }
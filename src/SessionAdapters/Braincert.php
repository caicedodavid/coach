<?php
namespace App\SessionAdapters;

use App\SessionAdapters\SessionAdapter;
use Cake\Network\Http\Client;
/*
 * Implementation of the Live Session with Braincert
 *
 */
class Braincert implements SessionAdapter
{
	const API_END_POINT = "https://api.braincert.com/v2/";

	public function __construct ($api_key = "g4cPvYO3AdSNEUh7zag5")
	{
		$this->api_key	= $api_key;
	}
    /**
     * scheduleSession method
     *
     * @param $session array of session info.
     * @return string POST response
     */
    public function scheduleSession($session)
    {
    	$startTimeValue = strtotime($session["schedule"]);
    	$endTimeValue = strtotime($session["schedule"] . ' +30 minutes');
    	$date = date('Y-m-d',$startTimeValue);
    	$startTime = date('h:ia',$startTimeValue);
    	$endTime = date('h:ia',$endTimeValue);
    	$fields= array(
    		'title' => $session["subject"],
    		'timezone' => 76,
    		'start_time' => $startTime,
    		'end_time' => $endTime,
    		'date' => $date
    		);
    	return ($this->postRequest($fields,'schedule'));
	}
    /**
     * requestSession method
     *
     * @param $session Session entity,
     * @param $session user entity,  
     * @return string POST response
     */
    public function requestSession($session, $user)
    {
    	$fields= array(
    		'class_id' => $session["external_class_id"],
    		'userId' => $user["id"],
    		'userName' => $user["username"],
    		'isTeacher' => (int)('coach'===$user['role']),
    		'lessonName' => $session["subject"],
    		'courseName' => $session["subject"]
    		);
    	return $this->postRequest($fields,'getclasslaunch');
    }
    /**
     * postRequest method
     *
     * @param $fields array of fields to send to request,
     * @param $request the request that will be sent,  
     * @return string POST response
     */
    private function postRequest($fields,$request)
    {
		$fields['apikey'] = $this->api_key;
		$http = new Client();
		$response = $http->post(self::API_END_POINT . $request, $fields);
		return json_decode($response->body,true);
    }
    
}

<?php
namespace App\SessionAdapters;

use App\SessionAdapters\SessionAdapter;
use Cake\Network\Http\Client;
use Cake\Network\Exception\NotImplementedException;
/*
 * Implementation of the Live Session with Braincert
 *
 */
class Braincert implements SessionAdapter
{
	const API_END_POINT = "https://api.braincert.com/v2/";
    const OK_STATUS = "ok"
    const BRAINCERT_SANTIAGO_TIMEZONE = 57;
    public $apiKey = NULL;

    /**
     * constructor LiveSession adapter
     *
     * @param $apiKey.
     */
    public function __construct($key)
    {
        $this->apiKey = $key;
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
    		'timezone' => self::BRAINCERT_SANTIAGO_TIMEZONE,
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
        $response = $this->postRequest($fields,'getclasslaunch');
    	return (response['status'] === self::OK_STATUS) ? $response : ['encryptedlaunchurl'=>null]; 
    }

    /**
     * getSessionData method
     *
     * @param $session array of session info.
     * @return string POST response
     */
    public function getSessionData($session)
    {
        $fields= array(
            'class_id' => $session["external_class_id"],
            );
        $savedKeyes = [
            'start_time',
            'end_time',
            'session_id',
            'status',
            'duration'
        ];
        return array_intersect_key($this->postRequest($fields,'getclass')['0'], array_flip($savedKeyes));
    }

    /**
     * removeSession method
     *
     * @param $session Session entity,
     * @param $session user entity,  
     * @return string POST response
     */
    public function removeSession($session)
    {
        $fields= array(
            'cid' => $session["external_class_id"],
        );
        return $this->postRequest($fields,'removeclass');
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
		$fields['apikey'] = $this->apiKey;
		$http = new Client();
		$response = $http->post(self::API_END_POINT . $request, $fields);
		return json_decode($response->body,true);
    }
    /**
     * manage request from endpoint
     *
     * @param $session user entity,  
     */
    public function manageRequest($requestArray){
        throw new NotImplementedException("This method is not implemented for this Adapter", 501);
    }
    
}

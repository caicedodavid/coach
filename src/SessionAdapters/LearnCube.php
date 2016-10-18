<?php
namespace App\SessionAdapters;

use App\SessionAdapters\SessionAdapter;
use Cake\Network\Http\Client;
/*
 * Implementation of the Live Session with Braincert
 *
 */
class LearnCube implements SessionAdapter
{
	const API_END_POINT = "https://cleteci.virtual-classes-online.com/classv3/?";

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
    	return ['class_id'=>$session['id']];
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
            'token' => $session["external_class_id"],
            'user_id' => $user['id'],
        );
        $this->getRequest($fields);
        $url =  (date('Y-m-d H:i',strtotime($session['schedule'])) > date('Y-m-d H:i',strtotime('now'))) ? $this->generateURL($fields): null;
    	return ['encryptedlaunchurl'=> $url];
    }

    /**
     * send data method
     *
     * @param $session user entity,  
     */
    public function sendData($user)
    {
        $fields = [
            "status" =>true,
            "is_tutor"=> 'coach'===$user['role'],
            "id" => $user['id'],
            "full_name" => $user['username'],
            "avatar" => null,
            "record" => false
        ];
        return json_encode($fields);
    }

    /**
     * getSessionData method
     *
     * @param $session array of session info.
     * @return string POST response
     */
    public function getSessionData($session)
    {
        return true;
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
        return true;
    }
    
    /**
     * getRequest method
     *
     * @param $fields array of fields to send to request,
     * @param $request the request that will be sent,  
     * @return string POST response
     */
    private function getRequest($fields)
    {
        $http = new Client();
        $response = $http->get($this->generateURL($fields));
    }

    /**
     * postRequest method
     *
     * @param $fields array of fields to send to request,
     * @param $request the request that will be sent,  
     * @return string POST response
     */
    private function generateURL($fields)
    {
        return self::API_END_POINT . http_build_query($fields, '','&');
    }
    
}

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
        $this->log('requestSession','debug');
        debug($this->getRequest($fields));
    	return ['encryptedlaunchurl'=> strtotime($session['schedule']) >= strtotime("now") ? $this->generateURL($fields): null];
    }

    /**
     * send data method
     *
     * @param $session user entity,  
     */
    public function sendData($user)
    {
        $this->autoRender = false;
        $this->response->type('json');
        $fields = [
            "status" =>true,
            "is_tutor"=> 'coach'===$user['role'],
            "id" => $user['id'],
            "full_name" => $user['full_name'],
            "avatar" => null,
            "record" => false
        ];
        $this->response->body(json_encode($fields));
        $this->response->send();
        $this->log('sendResponseee','debug');
        return true;
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

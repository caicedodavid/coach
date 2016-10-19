<?php
namespace App\SessionAdapters;

use App\SessionAdapters\SessionAdapter;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
/*
 * Implementation of the Live Session with Braincert
 *
 */
class LearnCube implements SessionAdapter
{

    const CONNECT_LESSON_REQUEST = "connect_lesson";

    const START_CLASS_REQUEST = "start_class";

    const END_LESSON_REQUEST = "end_lesson";

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
    	return ['class_id' => $session['id']];
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
        $isSessionTime = date('Y-m-d H:i',strtotime($session['schedule'])) <= date('Y-m-d H:i',strtotime('now'));
        if ($isSessionTime){
            $this->getRequest($fields);
        }
        $url =  $isSessionTime ? $this->generateURL($fields): null;
    	return ['encryptedlaunchurl'=> $url];
    }

    /**
     * manage request method for managing request coming from the endpoint
     *
     * @param $session user entity,  
     */
    public function manageRequest($requestArray)
    {
        $request = $requestArray->query['request'];
        switch ($request) {
            case self::CONNECT_LESSON_REQUEST:
                return $this->connectLesson($requestArray->query['user_id']);
            case self::START_CLASS_REQUEST:
                $this->startClass();
                break;
            case self::END_LESSON_REQUEST:
                $this->endLessson();
                break;
        }
    }

    /**
     * connect lesson method
     * used for making array to be sent
     *
     * @param $session user entity,  
     */
    private function connectLesson($userId)
    {
        $users = TableRegistry::get('AppUsers');
        $user = $users->get($userId);
        return [
            "response" => true,
            "type" => 'json',
            "content" => [
                "status" =>true,
                "is_tutor"=> 'coach' === $user['role'],
                "id" => $user['id'],
                "full_name" => $user['username'],
                "avatar" => null,
                "record" => false
            ]
        ];
    }

    /**
     * start class  method
     * logic to be done when starting a class
     *
     * @param $session user entity,  
     */
    private function startClass($user)
    {
        return [
            "response" => false,
        ];
    }
    /**
     * end lesson method
     * logic to be done after ending a class
     *
     * @param $session user entity,  
     */
    private function endLesson($user)
    {
        return [
            "response" => false,
        ];
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

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
use Cake\Event\Event;
use Cake\Routing\Router;


/**
 * Sessions Controller
 *
 * @property \App\Model\Table\SessionsTable $Sessions
 */
class VirtualclassroomController extends AppController
{
    const API_END_POINT = "https://cleteci.virtual-classes-online.com/classv3/?";

    const CONNECT_LESSON_REQUEST = "connect_lesson";

    const START_CLASS_REQUEST = "start_class";

    const END_LESSON_REQUEST = "end_lesson";


    /**
     * requestSession method
     *
     * @param $session Session entity,
     * @param $session user entity,
     * @return string POST response
     */
    public function requestSession($session = 121, $user_id = 2)
    {
        $this->request->allowMethod(['post','get']);
        $fields= array(
            'token' => $session,
            'user_id' => $user_id,
        );
        if ($this->request->is('post')) {
            $this->log('PostRequestSession','debug');
        }
        $this->log('requestSession','debug');
        debug($this->postRequest($fields,''));

    }

    public function index()
    {
        $this->request->allowMethod(['post','get']);
        $this->autoRender = false;
        $request = $this->request->query->request;

        switch ($request) {
            case $this->CONNECT_LESSON_REQUEST:
                $this->lessonRequest();
                break;
            case $this->START_CLASS_REQUEST:
                $this->log('startClass','debug');
                break;
            case $this->END_LESSON_REQUEST:
                $this->log('endLesson','debug');
                break;
        }

        $this->log($this->request->query->request,'debug');
    }

    private function lessonRequest()
    {
        $this->autoRender = false;
        $this->response->type('json');
        $fields = [
            "status" =>true, 
            "is_tutor" => false, 
            "id" => 111,
            "full_name" => "John Smith", 
            "avatar" => "https:\/\domain.com\/images\/avatar.png",
            "record" => false
        ]
        $this->response->body($fields);
    }

    private function postRequest($fields,$request)
    {
        $http = new Client();
        $response = $http->get(self::API_END_POINT . $request . http_build_query($fields, '','&'));
        //$response = $http->get(self::API_END_POINT . $request . $fields, ['headers'=>['Referer'=>Router::url(['controller' => 'Pages', 'action' => 'display', 'home'])]]);
        return $response->body;
    }
    public function beforeFilter(Event $event)
    {
        $this->eventManager()->off($this->Csrf);
        parent::beforeFilter($event);
        $this->Security->config('unlockedActions', ['index','requestSession']);
        $this->Auth->allow('index','requestSession');
    }
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }
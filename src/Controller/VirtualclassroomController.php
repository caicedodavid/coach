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
    const API_END_POINT = "https://cleteci.virtual-classes-online.com/classv3/";
    /**
     * requestSession method
     *
     * @param $session Session entity,
     * @param $session user entity,  
     * @return string POST response
     */
    public function requestSession($session = 111, $user_id = 1)
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
        debug($this->request);
    }

    public function index()
    {
        $this->request->allowMethod(['post','get']);
        $this->autoRender = false; 
        $data = $this->request->data;
        $this->log('index','debug');
        $this->log($this->request->data,'debug');
    }


    private function postRequest($fields,$request)
    {
        $http = new Client();
        $response = $http->post(self::API_END_POINT . $request, $fields, ['headers'=>['Referer'=>Router::url(['controller' => 'Pages', 'action' => 'display', 'home'])]]);
        return $response->body;
    }
    public function beforeFilter(Event $event)
    {
        //$this->eventManager()->off($this->Csrf);
        parent::beforeFilter($event);
        $this->Security->config('unlockedActions', ['index','requestSession']);
        $this->Auth->allow('index','requestSession');
    }
    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Csrf');
    }

}

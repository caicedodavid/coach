<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
use Cake\Event\Event;
use App\SessionAdapters\LiveSession;

/**
 * Controller for APi classes that require an endpoint
 *
 */
class ExternalClassroomController extends AppController
{
    /**
     * EndPoint Method
     * This is the end point for all the apps that require one for communication
     *
     * @return void
     */
    public function endPoint()
    {
        $this->request->allowMethod(['post']);
        $liveSession = LiveSession::getInstance();
        $this->autoRender = false;
        $responseArray = $liveSession->manageRequest($this->request);
        $this->log($responseArray->query,'debug');
        if ($responseArray['response']) {
            $this->response->type($responseArray['type']);
            $body = $responseArray['type'] === 'json' ? json_encode($responseArray['content']) : $responseArray['content'];
            $this->response->body($body);
            $this->response->send();
        }
    }

    /**
     * Before filter callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->eventManager()->off($this->Csrf);
        parent::beforeFilter($event);
        $this->Security->config('unlockedActions', ['endPoint']);
        $this->Auth->allow('endPoint');
    }

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }
}
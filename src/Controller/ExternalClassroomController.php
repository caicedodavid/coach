<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
use Cake\Event\Event;
use App\SessionAdapters\LiveSession;

/**
 * Controller for APi classes that require an endpoint
 *
 * @property \App\Model\Table\SessionsTable $Sessions
 */
class ExternlaClassroomController extends AppController
{
    const CONNECT_LESSON_REQUEST = "connect_lesson";

    const START_CLASS_REQUEST = "start_class";

    const END_LESSON_REQUEST = "end_lesson";


    public function endPoint()
    {
        $liveSession = LiveSession::getInstance();
        $this->request->allowMethod(['post','get']);
        $this->autoRender = false;
        $request = $this->request->query["request"];
        $this->log($request,'debug');

        switch ($request) {
            case self::CONNECT_LESSON_REQUEST:
                $liveSession->sendData($this->getUser());
                break;
            case self::START_CLASS_REQUEST:
                $this->log('startClass','debug');
                break;
            case self::END_LESSON_REQUEST:
                $this->log('endLesson','debug');
                break;
        }
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
}
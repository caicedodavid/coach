<?php
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use CakeDC\Users\Controller\Component\UsersAuthComponent;
use Cake\Routing\Router;

class Events implements EventListenerInterface
{

    public function implementedEvents()
    {
        return [
            UsersAuthComponent::EVENT_BEFORE_LOGIN => 'checkActiveUser',
        ];
    }

    public function checkActiveUser($event)
    {
        if ($event->subject()->request->data) {
            $user = TableRegistry::get('Users')->find('byUsername', ['username' => $event->subject()->request->data['username']])->first();
            if (!$user->active) {
                $event->result = Router::url(['controller' => 'AppUsers', 'plugin' => false, 'action' => 'login', 'prefix' => false],true);
                $event->stopPropagation();
            }
        }
    }
}
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
            UsersAuthComponent::EVENT_AFTER_LOGIN => 'loginRedirect',
        ];
    }

    public function checkActiveUser($event)
    {
        if ($event->subject()->request->data) {
            $user = TableRegistry::get('Users')->find('byUsername', ['username' => $event->subject()->request->data['username']])->first();
            if (!is_null($user) and !$user->active) {
                $event->result = Router::url(['controller' => 'AppUsers', 'action' => 'loginError', 'prefix' => false, 'plugin' => false],true);
                $event->stopPropagation();
            }
        }
    }
    public function loginRedirect($event)
    {
        if (!Router::getRequest()->session()->read('Auth.redirect')) {
            return ['controller' => 'AppUsers', 'action' => 'myProfile', 'plugin' => false, 'language' => Router::getRequest()->query('language')];
        }
    }
}
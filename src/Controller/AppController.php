<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    protected $availableLanguages = [
        'en' => 'en',
        'es' => 'es',
    ];

    public $helpers = ['AssetCompress.AssetCompress'];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Security');
        $this->loadComponent('Csrf');
        $this->loadComponent('CakeDC/Users.UsersAuth');
        $this->loadComponent('Paginator');
        $this->loadComponent('Cookie');
        $this->Auth->allow(['setLocale']);
        
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $this->set('userAuth',$this->Auth->user());
        $this->_setTheme();
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    /**
     * isCoach function
     *
     * @param $user
     * @return boolean
     */
    public function isCoach($user)
    {
        return $user["role"] === ROLE_COACH;
    }

    /**
     * getUser function
     * @return user array
     */
    public function getUser()
    {
        return $this->Auth->user();
    }


    /**
     * Sets the theme based on the section to render
     *
     * @return void
     */
    protected function _setTheme()
    {
        $this->viewBuilder()->theme('EducoTheme');
    }

    /**
     * before filter event
     *
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->setLocale();
        parent::beforeFilter($event);
    }

    /**
     * Sets the current locale based on url param and available languages
     *
     * @return void
     */
    protected function setLocale()
    {
        $selectedLanguage = 'en';
        $lang = $this->request->query('language') ? $this->request->query('language') : $this->request->param('language');
        if ($lang && isset($this->availableLanguages[$lang])) {
            I18n::locale($lang);
            $selectedLanguage = $this->availableLanguages[$lang];
        }
        $this->set('selectedLanguage', $selectedLanguage);
        $this->set('availableLanguages', $this->availableLanguages);
    }

}

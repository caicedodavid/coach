<?php
namespace App\SessionAdapters;

use Cake\Core\Configure;

class LiveSession
{
	public static function getInstance() {
        $provider = 'App\SessionAdapters\\' . Configure::read('Session.provider.name');
        return new $provider(Configure::read('Session.provider.key'));
    }


}
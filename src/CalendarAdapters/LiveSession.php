<?php
namespace App\CalendarAdapters;

class LiveSession
{
	public static function getInstance($provider, $userToken, $isPrimary)
	{
        return new 'App\SessionAdapters\\' . $provider($userToken, $isPrimary);
    }


}
<?php
namespace App\CalendarAdapters;

class Calendar
{
	public static function getInstance($providerName, $userToken = null, $calendarId = null)
	{
        $provider = 'App\CalendarAdapters\\' . $providerName;
        return new $provider($userToken, $calendarId);
    }
}
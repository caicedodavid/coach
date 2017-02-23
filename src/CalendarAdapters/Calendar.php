<?php
namespace App\CalendarAdapters;

use App\CalendarAdapters\GoogleCalendar;
use Cake\Network\Exception\NotImplementedException;

class Calendar
{
	const GOOGLE_CALENDAR = 1;
	const OUTLOOK_CALENDAR = 2;

	public static function getInstance($userToken = null, $calendarId = null, $provider)
	{
		if ($provider === self::GOOGLE_CALENDAR) {
			return new GoogleCalendar($userToken, $calendarId);
		} else {
			throw new NotImplementedException();
		}
    }
}
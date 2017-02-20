<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Core\Configure;
use \DateTime;
use \DateTimeZone;

class TimeConversionBehavior extends Behavior
{
	/**
     * Returns Array with Key/Value StatusValue/StatusString for historic view
     *
     * @return Array
     */
    public function setToUTC($schedule, $timezone) 
    {
        $startTime = new DateTime($schedule, new DateTimeZone($timezone));
        return $startTime->setTimezone(new DateTimeZone(UTC_TIMEZONE))->format('Y-m-d H:i');
    }        
      
}
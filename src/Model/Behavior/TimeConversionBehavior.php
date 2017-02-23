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
     * @param $datetime the datetime to convert
     * @param $timezone the datetime timezone
     * @return Array
     */
    public function setToUTC($datetime, $timezone) 
    {
        $startTime = new DateTime($datetime, new DateTimeZone($timezone));
        return $startTime->setTimezone(new DateTimeZone(UTC_TIMEZONE))->format('Y-m-d H:i');
    }        
      
}
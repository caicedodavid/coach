<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Core\Configure;
use \DateTime;
use \DateTimeZone;

class TimeConversionBehavior extends Behavior
{

	/**
     * Returns datetime in UTC
     *
     * @param $datetime the datetime to convert
     * @param $timezone the datetime timezone
     * @return datetime
     */
    public function setToUTC($datetime, $timezone) 
    {
        $startTime = new DateTime($datetime, new DateTimeZone($timezone));
        return $startTime->setTimezone(new DateTimeZone(UTC_TIMEZONE))->format('Y-m-d H:i');
    }         
}
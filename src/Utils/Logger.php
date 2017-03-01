<?php
namespace App\Utils;

use Cake\Log\Log;

class Logger
{
	/**
     * Method to call when an api called failed but does not require assistance
     *
     * @param $e Exception
     * @param $data data of exception ocurrence 
     * @return void
     */
	public function apiWarning($e, $data)
	{
		$message = self::makeMessage($e, $data);
		Log::warning($message, ['scope' => ['api']]);
	}
	/**
     * Method to call when an api called failed and requires inmidiate assistance
     *
     * @param $e Exception
     * @param $data data of exception ocurrence 
     * @return void
     */
	public function apiCritical($e, $data)
	{
		$message = self::makeMessage($e, $data);
		Log::critical($message, ['scope' => ['api']]);
	}
	/**
     * make message for log function
     * @param $e Exception
     * @param $data data of exception ocurrence 
     * @return string
     */
	private function makeMessage($e, $data)
	{
		return __('An {0} occurred in {1} {2} for the entity {3} with the following message: {4}', get_class($e), $data['table'], 
			$data['action'], $data['id'], trim(preg_replace('/\s+/', ' ', $e->getMessage())));
	}
}
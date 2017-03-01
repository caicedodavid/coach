<?php
namespace App\Utils;

use Cake\Log\Log;

class Logger
{
	/**
     * Method to call when an api called failed but does not require assistance
     *
     * @param $e array of session info.
     * @return string POST response
     */
	public function apiWarning($e, $data)
	{
		$message = self::makeMessage($e, $data);
		Log::warning($message, ['scope' => ['api']]);
	}
	public function apicritical($e, $data)
	{
		$message = self::makeMessage($e, $data);
		Log::critical($message, ['scope' => ['api']]);
	}
	private function makeMessage($e, $data)
	{
		return __('An {0} occurred in {1} {2} for the entity {3} with the following message: {4}', get_class($e), $data['table'], 
			$data['action'], $data['id'], trim(preg_replace('/\s\s+/', ' ', $e->getMessage())));
	}
}
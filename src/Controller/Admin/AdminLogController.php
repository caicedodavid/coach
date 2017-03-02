<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Sessions Controller
 *
 * @property \App\Model\Table\SessionsTable $Sessions
 */
class AdminLogController extends AppController{

    const APIS_LOG_FILE = 'apis.log';
	/**
     * viewApisLog
     *
     * Method to show the contents of apis log file
     *
     * @return \Cake\Network\Response|null
     */
    public function viewApisLog()
    {
        $lines = file(LOGS . self::APIS_LOG_FILE);
        $this->set('errors', array_reverse($lines));
    }

}
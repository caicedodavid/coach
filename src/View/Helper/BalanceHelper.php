<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;
use App\Controller\AppController;

class BalanceHelper extends Helper
{
    public $helpers = ['Number'];
    /**
     * Display profile image method
     *
     * @param image UserImage
     * @param size string
     * @return the Users image or blank image
     */
    public function display($user)
    {
        if ($_SESSION['Auth']['User']['id'] === $user['id']) {
            return sprintf("<p><span>Balance :- </span> %s </p>", $this->Number->currency($user['balance'], 'USD'));
        }
    }
}

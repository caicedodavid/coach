<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;
use App\Controller\AppController;

class BalanceHelper extends Helper
{
    public $helpers = ['Number','Html'];
    /**
     * Display profile image method
     *
     * @param image UserImage
     * @param size string
     * @return the Users image or blank image
     */
    public function display($user)
    {
        if ($this->request->session()->read('Auth.User.id') === $user['id']) {
            return $this->Html->tag('p') . $this->Html->tag('span',__('Balance :- ' . $this->Number->currency($user['balance'], 'USD')));
        }
    }
}

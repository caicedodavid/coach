<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;
use App\Model\Entity\AppUser;

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
        if (($this->request->session()->read('Auth.User.id') === $user['id']) and ($user['role'] === ROLE_USER)) {
            return $this->Html->tag('p') . $this->Html->tag('span',__('Balance :- ' . $this->Number->currency($user['balance'], 'USD')));
        }
    }

    /**
     * Display price
     *
     * @param image UserImage
     * @param size string
     * @return the Users image or blank image
     */
    public function price($price)
    {
        return $price !== 0 ? $this->Number->currency($price, 'USD') : __('Free');
    }
}
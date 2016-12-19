<?php
namespace App\Model\Entity;

use CakeDC\Users\Model\Entity\User;

/**
 * User Entity
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $token
 * @property \Cake\I18n\Time $token_expires
 * @property string $api_token
 * @property \Cake\I18n\Time $activation_date
 * @property \Cake\I18n\Time $tos_date
 * @property bool $active
 * @property bool $is_superuser
 * @property string $role
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\SocialAccount[] $social_accounts
 */
class AppUser extends User
{
    protected $_virtual = ['full_name'];

    protected function _getFullName() {
        return $this->_properties['first_name'] . ' ' . $this->_properties['last_name'];
    }
}

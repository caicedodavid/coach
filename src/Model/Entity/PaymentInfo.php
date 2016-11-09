<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PaymentInfo Entity
 *
 * @property int $id
 * @property string $user_id
 * @property string $name
 * @property string $address1
 * @property string $address2
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $zipcode
 * @property string $token_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Token $token
 */
class PaymentInfo extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payment Entity
 *
 * @property int $id
 * @property int $amount
 * @property int $payment_infos_id
 * @property int $fk_id
 * @property string $fk_table
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\PaymentInfo $payment_info
 * @property \App\Model\Entity\Fk $fk
 */
class Payment extends Entity
{
    const PAYMENT_TYPE_CREDIT = 1;
    const PAYMENT_TYPE_BALANCE = 2;
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

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Liability Entity
 *
 * @property int $id
 * @property float $amount
 * @property int $commission
 * @property string $type
 * @property int $fk_id
 * @property string $fk_table
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Fk $fk
 */
class Liability extends Entity
{

    const STATUS_PENDING = 1;
    const STATUS_REFUND = 2;
    const STATUS_PAID = 3;

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

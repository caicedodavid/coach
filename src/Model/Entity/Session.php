<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Session Entity
 *
 * @property int $id
 * @property \Cake\I18n\Time $schedule
 * @property string $subject
 * @property string $comments
 * @property string $user_id
 * @property string $coach_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Coach $coach
 */
class Session extends Entity
{
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_RUNNING = 3;
    const STATUS_REJECTED = 4;
    const STATUS_CANCELED = 5;
    const STATUS_PAST = 6;

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

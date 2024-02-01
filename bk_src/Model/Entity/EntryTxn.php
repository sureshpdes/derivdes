<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * EntryTxn Entity
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $2fa_key
 * @property string $2fa_status
 * @property string $photo
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $modified_by
 *
 * @property \App\Model\Entity\BlockedUsersIp[] $blocked_users_ips
 * @property \App\Model\Entity\LoginActivity[] $login_activities
 * @property \App\Model\Entity\Log[] $logs
 * @property \App\Model\Entity\Role[] $roles
 */
class EntryTxn extends Entity
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
        'entry_txn_id' => true,
        'entry_master_id' => true,
        'field_id' => true,
        'field_value' => true,
        'user_id' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */

}

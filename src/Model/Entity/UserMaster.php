<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * Admin Entity
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
class UserMaster extends Entity
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
        'user_name' => true,
        'user_pin' => true,
        'password' => true,
        'user_salutation' => true,
        'user_first_name' => true,
        'user_middle_name' => true,
        'user_last_name' => true,
        'user_email' => true,
        'user_phone' => true,
        'user_type_id' => true,
        'is_active' => true,
        '2fa_key' => true,
        '2fa_status' => true,
        'invite_link_status' => true,
        'creation_time' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

	protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }
}

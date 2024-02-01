<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DeletedFile Entity
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
class DeletedFile extends Entity
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
        'deleted_file_id' => true,
        'file_name' => true,
        'file_directory' => true,
        'user_id' => true,
        'delete_time' => true,
        'deleted_file_status' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */

}

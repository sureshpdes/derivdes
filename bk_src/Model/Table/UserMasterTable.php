<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Admins Model
 *
 * @property \App\Model\Table\BlockedUsersIpsTable|\Cake\ORM\Association\HasMany $BlockedUsersIps
 * @property \App\Model\Table\LoginActivitiesTable|\Cake\ORM\Association\HasMany $LoginActivities
 * @property \App\Model\Table\LogsTable|\Cake\ORM\Association\HasMany $Logs
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsToMany $Roles
 *
 * @method \App\Model\Entity\Admin get($primaryKey, $options = [])
 * @method \App\Model\Entity\Admin newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Admin[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Admin|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Admin|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Admin patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Admin[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Admin findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserMasterTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('user_master');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey('user_id');

        $this->addBehavior('Timestamp');
        $this->belongsTo('UserType', [
            'foreignKey' => 'user_type_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ExcOptMap', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('EntryMaster', [
            'foreignKey' => 'user_id'
        ]);



    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('user_id')
            ->allowEmpty('user_id', 'create');

        $validator
            ->scalar('user_name')
            ->maxLength('user_name', 255)
            ->notEmpty('user_name', 'Username is required');
        $validator
            ->scalar('user_salutation')
            ->maxLength('user_salutation', 255)
            ->notEmpty('user_salutation', 'Salutation is required');
        $validator
            ->scalar('user_first_name')
            ->maxLength('user_first_name', 255)
            ->notEmpty('user_first_name', 'Firstname is required');
        $validator
            ->scalar('user_middle_name')
            ->maxLength('user_middle_name', 255)
            ->allowEmpty('user_middle_name', 'Middlename is required');
        $validator
            ->scalar('user_last_name')
            ->maxLength('user_last_name', 255)
            ->notEmpty('user_last_name', 'Lastname is required');
        $validator
            ->scalar('user_pin')
            ->maxLength('user_pin', 255)
            ->allowEmpty('user_pin', 'pin is required');
        $validator
            ->email('user_email')
			->maxLength('user_email', 255)
            ->notEmpty('user_email', 'Email is required');

        $validator
            ->scalar('password')
            ->minLength('password', 6, 'Password must be 6 character long.')
            ->allowEmpty('password', 'update', 'Password is required');

        $validator
            ->scalar('user_phone')
            ->maxLength('user_phone', 50)
            ->allowEmpty('user_phone', 'Phone is required');

        $validator
            ->scalar('2fa_key')
            ->allowEmpty('2fa_key');

        $validator
            ->scalar('2fa_status')
            ->notEmpty('2fa_status', '2FA status is required');

        $validator
            ->scalar('is_active')
            ->notEmpty('is_active', 'Active status is required');
        $validator
            ->scalar('invite_link_status')
            ->notEmpty('invite_link_status', 'Invite link status is required');
        /*$validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->integer('modified_by')
            ->requirePresence('modified_by', 'create')
            ->notEmpty('modified_by');*/

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        //$rules->add($rules->isUnique(['username']), ['message' => "Username already exists"]);
        $rules->add($rules->isUnique(['user_email']), ['message' => "Email already exists"]);
        $rules->add($rules->isUnique(['user_name']), ['message' => "Username already exists"]);
		
        return $rules;
    }
}

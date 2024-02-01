<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CommonFields Model
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
class CommonFieldsTable extends Table
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

        $this->setTable('common_fields');
        $this->setDisplayField('common_field_id');
        $this->setPrimaryKey('common_field_id');

        $this->addBehavior('Timestamp');



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
            ->integer('common_field_id')
            ->allowEmpty('common_field_id', 'create');

        $validator
            ->scalar('common_field_name')
            ->notEmpty('common_field_name', 'Field Name is required');
        $validator
            ->scalar('common_field_type')
            ->maxLength('common_field_type', 255)
            ->notEmpty('common_field_type', 'Field type is required');
       


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
        //$rules->add($rules->isUnique(['user_email']), ['message' => "Email already exists"]);
        //$rules->add($rules->isUnique(['user_name']), ['message' => "Username already exists"]);
		
        return $rules;
    }
}

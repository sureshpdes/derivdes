<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExchangeMaster Model
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
class ExchangeMasterTable extends Table
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

        $this->setTable('exchange_master');
        $this->setDisplayField('exchange_master_id');
        $this->setPrimaryKey('exchange_master_id');

        //$this->addBehavior('Timestamp');
       /* $this->belongsTo('UserType', [
            'foreignKey' => 'user_type_id',
            'joinType' => 'INNER',
        ]);*/
        $this->hasMany('ExcOptMap', [
            'foreignKey' => 'exchange_id'
        ]);
        $this->hasMany('ViewFileMaster2', [
            'foreignKey' => 'exchange_id'
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
            ->integer('exchange_master_id')
            ->allowEmpty('exchange_master_id ', 'create');

        $validator
            ->scalar('exchange_master_code')
            ->maxLength('exchange_master_code', 255)
            ->notEmpty('exchange_master_code', 'Exchange Code is required');
        $validator
            ->scalar('exchange_master_name')
            ->maxLength('exchange_master_name', 255)
            ->notEmpty('exchange_master_name', 'Exchange Name is required');
        $validator
            ->scalar('exchange_master_location')
            ->maxLength('exchange_master_location', 255)
            ->notEmpty('exchange_master_location', 'Location is required');
        $validator
            ->scalar('exchange_master_desc')
            ->maxLength('exchange_master_desc', 255)
            ->allowEmpty('exchange_master_desc', 'Description is required');
        $validator
            ->scalar('exchange_master_directory')
            ->maxLength('exchange_master_directory', 255)
            ->notEmpty('exchange_master_directory', 'Directory is required');
        


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

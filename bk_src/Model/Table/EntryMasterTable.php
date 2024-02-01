<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EntryMaster Model
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
class EntryMasterTable extends Table
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

        $this->setTable('entry_master');
        $this->setDisplayField('entry_name');
        $this->setPrimaryKey('entry_master_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TemplateMaster', [
            'foreignKey' => 'template_master_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('UserMaster', [
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
            ->integer('entry_master_id')
            ->allowEmpty('entry_master_id', 'create');

        $validator
            ->integer('template_master_id')
            ->notEmpty('template_master_id', 'Template Master id is required');
        $validator
            ->scalar('entry_name')
            ->maxLength('entry_name', 255)
            ->notEmpty('entry_name', 'Entry Name is required');
        $validator
            ->scalar('primary_pdf_name')
            ->maxLength('primary_pdf_name', 255)
            ->notEmpty('primary_pdf_name', 'Primary PDF name is required');
        $validator
            ->integer('user_id')
            ->notEmpty('user_id', 'User ID is required');
        $validator
            ->scalar('entry_status')
            ->notEmpty('entry_status', 'Entry Status is required');

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
		
        return $rules;
    }
}

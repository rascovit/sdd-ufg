<?php
namespace App\Model\Table;

use App\Model\Entity\ProcessConfiguration;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProcessConfigurations Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Processes
 */
class ProcessConfigurationsTable extends Table
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

        $this->table('process_configurations');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsToMany('Processes', [
            'foreignKey' => 'process_configuration_id',
            'targetForeignKey' => 'process_id',
            'joinTable' => 'processes_process_configurations'
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->requirePresence('data_type', 'create')
            ->notEmpty('data_type');

        $validator
            ->add('type', 'enum', ['rule' => ['inList', ['CRITERIA', 'RESTRICTION'], true]])
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }
}

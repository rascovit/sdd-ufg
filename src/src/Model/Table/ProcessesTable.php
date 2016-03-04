<?php
namespace App\Model\Table;

use App\Model\Entity\Process;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Processes Model
 *
 * @property \Cake\ORM\Association\HasMany $Clazzes
 * @property \Cake\ORM\Association\BelongsToMany $ProcessConfigurations
 */
class ProcessesTable extends Table
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

        $this->table('processes');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('Clazzes', [
            'foreignKey' => 'process_id'
        ]);
		
		$this->hasMany('ProcessConfigurations', [
            'foreignKey' => 'process_id'
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
            ->add('initial_date', 'valid', ['rule' => ['date', 'dmy']])
            ->requirePresence('initial_date', 'create')
            ->notEmpty('initial_date');

        $validator
            ->add('teacher_intent_date', 'valid', ['rule' => ['date', 'dmy']])
            ->requirePresence('teacher_intent_date', 'create')
            ->notEmpty('teacher_intent_date');

        $validator
            ->add('teacher_intent_date', 'valid', ['rule' => function ($value, $context){
                if ($context['data']['initial_date'] >= $context['data']['teacher_intent_date']){
                    return false;
                }
                return true;
            },
                'message'=>'A data de término deve ser depois do data de início.',
            ]);

        $validator
            ->add('primary_distribution_date', 'valid', ['rule' => ['date', 'dmy']])
            ->requirePresence('primary_distribution_date', 'create')
            ->notEmpty('primary_distribution_date');

        $validator
            ->add('substitute_intent_date', 'valid', ['rule' => ['date', 'dmy']])
            ->requirePresence('substitute_intent_date', 'create')
            ->notEmpty('substitute_intent_date');

        $validator
            ->add('substitute_intent_date', 'valid', ['rule' => function ($value, $context){
                if ($context['data']['primary_distribution_date'] >= $context['data']['substitute_intent_date']){
                    return false;
                }
                return true;
            },
                'message'=>'A data de término deve ser depois do data de início.',
            ]);

        $validator
            ->add('secondary_distribution_date', 'valid', ['rule' => ['date', 'dmy']])
            ->requirePresence('secondary_distribution_date', 'create')
            ->notEmpty('secondary_distribution_date');

        $validator
            ->add('final_date', 'valid', ['rule' => ['date', 'dmy']])
            ->requirePresence('final_date', 'create')
            ->notEmpty('final_date');

        $validator
            ->add('final_date', 'valid', ['rule' => function ($value, $context){
                if ($context['data']['secondary_distribution_date'] >= $context['data']['final_date']){
                    return false;
                }
                return true;
            },
                'message'=>'A data de término deve ser depois do data de início.',
            ]);

        $validator
            ->add('status', 'enum', ['rule' => ['inList', ['OPENED', 'CLOSED'], true]])
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }

    /**
     * Finds Processes by filters
     *
     * @param $filters
     * @return Query
     */
    public function findByFilters($filters)
    {
        /** @var Query $processes */
        $processes = $this->find('all');

        $conditions = [];
        if(isset($filters) && is_array($filters)) {
            if(isset($filters['name']) && !empty(trim($filters['name']))) {
                $conditions['Processes.name LIKE'] = "%" . $filters['name'] . "%";
            }
        }

        $processes->where($conditions);

        return $processes;
    }
}

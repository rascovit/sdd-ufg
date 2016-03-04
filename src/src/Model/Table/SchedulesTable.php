<?php
namespace App\Model\Table;

use App\Model\Entity\Schedule;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Schedules Model
 *
 * @property \Cake\ORM\Association\HasMany $Clazzes
 */
class SchedulesTable extends Table
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

        $this->table('schedules');
        $this->displayField('period');
        $this->primaryKey('id');

        $this->hasMany('ClazzesSchedulesLocals', [
            'foreignKey' => 'local_id',
            'propertyName' => 'LocalClazzes'
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
            ->add('start_time', 'valid', ['rule' => 'time'])
            ->requirePresence('start_time', 'create')
            ->notEmpty('start_time');

        $validator
            ->add('end_time', 'valid', ['rule' => 'time'])
            ->requirePresence('end_time', 'create')
            ->notEmpty('end_time');

        $validator
            ->add('start_time', 'valid', ['rule' => function ($value, $context){
                if ($context['data']['start_time'] >= $context['data']['end_time']){
                    return false;
                }
                return true;
            },
            'message'=>' ',
        ]);

        $validator
            ->add('end_time', 'valid', ['rule' => function ($value, $context){
                if ($context['data']['start_time'] >= $context['data']['end_time']){
                    return false;
                }
                return true;
            },
            'message'=>'O horário de término deve ser depois do horário de início.',
        ]);

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
        $rules->add(
            new IsUnique(['start_time', 'end_time']),
            [
                'errorField' => 'start_time',
                'message' => __(' ')
            ]
        );

        $rules->add(
            new IsUnique(['start_time', 'end_time']),
            [
                'errorField' => 'end_time',
                'message' => __('Esse horário já está cadastrado')
            ]
        );

        return $rules;
    }
}

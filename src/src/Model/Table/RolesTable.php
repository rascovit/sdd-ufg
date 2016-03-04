<?php
namespace App\Model\Table;

use App\Model\Entity\Role;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

/**
 * Roles Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Teachers
 * @property \Cake\ORM\Association\BelongsTo $Knowledges
 */
class RolesTable extends Table
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

        $this->table('roles');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Knowledges', [
            'foreignKey' => 'knowledge_id'
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
            ->add('type', 'enum', ['rule' => ['inList', ['COORDINATOR', 'FACILITATOR'], true]])
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->add('teacher_id','custom',[
                'rule'=>  function($value, $context){
                    if ($context['data']['type'] == 'COORDINATOR') {
                        $roles = $this->find('all', [
                            'conditions' => ['type' => 'COORDINATOR', 'teacher_id' => $context['data']['teacher_id']]
                        ]);
                        if ($roles && count($roles->toArray()) > 0) {
                            return false;
                        }
                    }
                    return true;
                },
                'message'=>'Professor já vinculado como coordenador!',
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
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'));
        $rules->add($rules->existsIn(['knowledge_id'], 'Knowledges'));

        $rules->add(
            new IsUnique(['type', 'teacher_id', 'knowledge_id']),
            [
                'errorField' => 'knowledge_id',
                'message' => __('Professor já vinculado facilitador desse núcleo de conhecimento!')
            ]
        );

        return $rules;
    }

    /**
     * Finds facilitators
     *
     * @param Query $query
     * @param array $options
     * @return $this
     */
    public function findFacilitators(Query $query, array $options)
    {
        return $query->where(['Roles.type' => 'FACILITATOR']);
    }
}

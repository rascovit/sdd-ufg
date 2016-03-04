<?php
namespace App\Model\Table;

use App\Model\Entity\Knowledge;
use Cake\ORM\Query;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Knowledges Model
 *
 * @property \Cake\ORM\Association\HasMany $Roles
 * @property \Cake\ORM\Association\HasMany $Subjects
 * @property \Cake\ORM\Association\BelongsToMany $Teachers
 */
class KnowledgesTable extends Table
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

        $this->table('knowledges');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('Roles', [
            'foreignKey' => 'knowledge_id',
            'propertyName' => 'facilitators',
            'finder' => 'facilitators'
        ]);
        $this->hasMany('Subjects', [
            'foreignKey' => 'knowledge_id'
        ]);
        $this->hasMany('KnowledgesTeachers', [
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

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
            new IsUnique(['name']),
            [
                'errorField' => 'name',
                'message' => __('Núcleo de conhecimento já cadastrado')
            ]
        );

        return $rules;
    }

    /**
     * Finds knowledges by filters
     *
     * @param $filters
     * @return Query
     */
    public function findByFilters($filters)
    {
        /** @var Query $knowledges */
        $knowledges = $this->find('all');

        $conditions = [];
        if(isset($filters) && is_array($filters)) {
            if(isset($filters['name']) && !empty(trim($filters['name']))) {
                $conditions['Knowledges.name LIKE'] = "%" . $filters['name'] . "%";
            }
        }

        $knowledges->where($conditions);

        return $knowledges;
    }
}

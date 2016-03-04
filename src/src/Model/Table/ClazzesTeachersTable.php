<?php
namespace App\Model\Table;

use App\Model\Entity\ClazzesTeacher;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ClazzesTeachers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Clazzes
 * @property \Cake\ORM\Association\BelongsTo $Teachers
 */
class ClazzesTeachersTable extends Table
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

        $this->table('clazzes_teachers');
        $this->displayField('clazz_id');
        $this->primaryKey(['clazz_id', 'teacher_id']);

        $this->belongsTo('Clazzes', [
            'foreignKey' => 'clazz_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER'
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
            ->add('status', 'enum', ['rule' => ['inList', ['PENDING', 'SELECTED', 'REJECTED'], true]])
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['clazz_id'], 'Clazzes'));
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'));
        return $rules;
    }
	
	public function getIntentsByTeacher($teacherId){
		$clazzesTeachers = $this
			->find('all')
			->contain([
				'Clazzes'=> function($q) {
					return $q->select(['Clazzes.name']);
				},
				'Clazzes.Subjects' => function($q) {
					return $q->select(['Subjects.name']);
				},
				'Teachers' => function($q) {
					return $q->select(['id']);
				}
			])
			->where(['ClazzesTeachers.teacher_id' => $teacherId]);

		return $clazzesTeachers;
	}
}

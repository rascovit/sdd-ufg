<?php
namespace App\Model\Table;

use App\Model\Entity\Clazze;
use Cake\ORM\Query;
use Cake\ORM\Rule\ExistsIn;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * Clazzes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Subjects
 * @property \Cake\ORM\Association\BelongsTo $Schedules
 * @property \Cake\ORM\Association\BelongsTo $Locals
 * @property \Cake\ORM\Association\BelongsTo $Processes
 * @property \Cake\ORM\Association\BelongsToMany $Teachers
 */
class ClazzesTable extends Table
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

        $this->table('clazzes');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('Subjects', [
            'foreignKey' => 'subject_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Processes', [
            'foreignKey' => 'process_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('ClazzesTeachers', [
            'foreignKey' => 'clazz_id',
            'propertyName' => 'intents'
        ]);

        $this->hasMany('ClazzesSchedulesLocals', [
            'foreignKey' => 'clazz_id',
            'propertyName' => 'scheduleLocals'
        ]);

		$this->belongsToMany('Locals', [
            'foreignKey' => 'clazz_id',
            'targetForeignKey' => 'local_id',
            'joinTable' => 'clazzes_schedules_locals'
        ]);

		$this->belongsToMany('Schedules', [
            'foreignKey' => 'clazz_id',
            'targetForeignKey' => 'schedule_id',
            'joinTable' => 'clazzes_schedules_locals'
        ]);

		$this->belongsToMany('Teachers', [
            'foreignKey' => 'clazz_id',
            'targetForeignKey' => 'teacher_id',
            'joinTable' => 'clazzes_teachers'
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
            ->add('vacancies', 'valid', ['rule' => 'numeric'])
            ->add('vacancies', 'stocking', ['rule' => ['range', 1, 'null'],
                'message' => __('O número de vagas não pode menor ou igual a zero')])
            ->requirePresence('vacancies', 'create')
            ->notEmpty('vacancies');

        $validator
            ->requirePresence('subject_id', 'create')
            ->notEmpty('subject_id');

        $validator
            ->requirePresence('process_id', 'create')
            ->notEmpty('process_id');

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
            function ($entity, $options) {
                $rule = new ExistsIn(['subject_id'], 'Subjects');
                return $rule($entity, $options);
            },
            ['errorField' => 'subject_id', 'message' => __('Selecione uma disciplina')]
        );

        $rules->add(
            function ($entity, $options) {
                $rule = new ExistsIn(['process_id'], 'Processes');
                return $rule($entity, $options);
            },
            ['errorField' => 'process_id', 'message' => __('Selecione um processo de distribuição')]
        );

        $rules->add(
            new IsUnique(['name', 'process_id', 'subject_id']),
            [
                'errorField' => 'name',
                'message' => __('Essa turma já está cadastrada neste processo de distribuição')
            ]
        );

        return $rules;
    }

    public function isTeacherSubscribed($teacherId, $clazzId)
    {
        $isSubscribed = $this->find('all')->matching('ClazzesTeachers')
            ->where([
                'ClazzesTeachers.teacher_id' => $teacherId,
                'ClazzesTeachers.clazz_id' => $clazzId
            ])->toArray();

        return !empty($isSubscribed);
    }

    /**
     * Finds clazzes by filters
     *
     * @param $filters
     * @return Query
     */
    public function findByFilters($filters)
    {
        /** @var Query $clazzes */
        $clazzes = $this->find('all')->contain([
            'Processes', 'Subjects.Knowledges', 'ClazzesTeachers.Teachers.Users',
            'ClazzesSchedulesLocals.Locals', 'ClazzesSchedulesLocals.Schedules'
        ]);

        $conditions = [];
        if(isset($filters) && is_array($filters)) {
            if(isset($filters['process']) && $filters['process'] != 0) {
                $conditions['Clazzes.process_id'] = $filters['process'];
            }

            if(isset($filters['knowledge']) && $filters['knowledge'] != 0) {
                $conditions['Subjects.knowledge_id'] = $filters['knowledge'];
            }

            if(isset($filters['subject']) && $filters['subject'] != 0) {
                $conditions['Subjects.id'] = $filters['subject'];
            }

            if(isset($filters['status']) && !empty($filters['status'])) {
                $closedClazzesId = [0];
                $conflictClazzesId = [0];

                if($filters['status'] == 'CLOSED' || $filters['status'] == 'OPENED') {
                    $closedClazzes = $this->find()
                        ->select(['Clazzes.id'])
                        ->matching('ClazzesTeachers')
                        ->where([
                            'or' => [
                                [
                                    'ClazzesTeachers.status' => 'SELECTED'
                                ],
                                [
                                    'ClazzesTeachers.status' => 'REJECTED'
                                ]
                            ]
                        ])
                        ->group(['Clazzes.id']);

                    foreach($closedClazzes as $closedClazz) {
                        $closedClazzesId[] = $closedClazz->id;
                    }

                    if($filters['status'] == 'CLOSED') {
                        $conditions['Clazzes.id IN'] = $closedClazzesId;
                    }
                }

                if($filters['status'] == 'CONFLICT' || $filters['status'] == 'OPENED') {
                    $conflictClazzes = $this->find()
                        ->select(['Clazzes.id', 'Clazzes__count' => 'count(Clazzes.id)'])
                        ->matching('ClazzesTeachers')
                        ->where([
                            'ClazzesTeachers.status' => 'PENDING'
                        ])
                        ->having([
                            'Clazzes__count >' => 1
                        ])
                        ->group(['Clazzes.id']);

                    foreach($conflictClazzes as $conflictClazz) {
                        $conflictClazzesId[] = $conflictClazz->id;
                    }

                    if($filters['status'] == 'CONFLICT') {
                        $conditions['Clazzes.id IN'] = $conflictClazzesId;
                    }
                }

                if($filters['status'] == 'OPENED') {
                    $notOppened = array_merge($closedClazzesId, $conflictClazzesId);
                    $conditions['Clazzes.id NOT IN'] = $notOppened;
                }
            }

            if(isset($filters['teachers']) && is_array($filters['teachers'])) {
                $clazzes->matching('ClazzesTeachers')->group(['Clazzes.id']);
                $conditions['AND']['ClazzesTeachers.teacher_id IN'] = $filters['teachers'];

                if(isset($filters['only-selected']) && $filters['only-selected'] == true) {
                    $conditions['AND']['ClazzesTeachers.status'] = 'SELECTED';
                }
            }

            if(isset($filters['schedules']) && is_array($filters['schedules'])) {
                $clazzes->matching('ClazzesSchedulesLocals')->group(['Clazzes.id']);
                foreach($filters['schedules'] as $schedule) {
                    $schedule = explode(";", $schedule);
                    $conditions['AND']['OR'][] = [
                        'ClazzesSchedulesLocals.schedule_id' => $schedule[1],
                        'ClazzesSchedulesLocals.local_id' => $schedule[2]
                        //'ClazzesSchedulesLocals.week_day' => $schedule[3]
                    ];
                }
            }
        }

        $clazzes->where($conditions);

        return $clazzes;
    }

	public function getAllClazzesNotTeachers()
    {
		$clazzesTemp = $this
			->find('all')
			->contain([
				'Subjects' => function($q) {
					return $q->select(['id', 'knowledge_id']);
				}
			])
			->contain([
				'Teachers' => function($q) {
					return $q->select(['id']);
				}
			])
			->hydrate(false)->toArray();

		$clazzes = [];
		foreach($clazzesTemp as $clazzTemp){
			if($clazzTemp['teachers'] == null){
				$clazzes[] = $clazzTemp;
			}
		}

		return $clazzes;
	}

	public function getAllClazzesWithSubjctsTeachers()
    {
		return $this
			->find('all')
			->contain([
				'Subjects' => function($q) {
					return $q->select(['id', 'knowledge_id', 'theoretical_workload', 'practical_workload']);
				}
			])
			->contain([
				'Teachers' => function($q) {
					return $q->select(['id']);
				}
			])
            ->hydrate(false)->toArray();
	}

	public function setTeachersAllClazzes($clazzes){
		foreach($clazzes as $clazz){
			$this->save($clazz);
			//$result = $clazz->dirty('teachers', true);
			//echo $result . '<br>';
		}
	}
	
	/**
	 * Check if the file name is valid.
	 * @access public
	 * @param String $fileName
	 * @return if the image is valid
	*/ 
	public function checkName($fileName)
	{
		return (bool) ((preg_match("`^[-0-9A-Z_\.\\s]+$`i",$fileName) && mb_strlen($fileName,"UTF-8") < 225) ? true : false);
	}

	
	/**
	 * Check if the directory exists. If not, then the system will create it.
	 * @access public
	 * @param String $dir
	 * @return the selected folder
	*/ 
	public function checkDirectory($dir)
	{
		if (!is_dir($dir)){
			$folder = new Folder();
			$folder->create($dir);
			return $folder;
		} else {
			return new Folder($dir);
		}
	}
}

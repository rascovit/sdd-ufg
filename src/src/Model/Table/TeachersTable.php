<?php
namespace App\Model\Table;

use App\Model\Entity\Teacher;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use \DateTime;

/**
 * Teachers Model
 *
 * @property \Cake\ORM\Association\HasMany $Roles
 * @property \Cake\ORM\Association\HasMany $Users
 * @property \Cake\ORM\Association\BelongsToMany $Clazzes
 * @property \Cake\ORM\Association\BelongsToMany $Knowledges
 */
class TeachersTable extends Table
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

        $this->table('teachers');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('Roles', [
            'foreignKey' => 'teacher_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'teacher_id'
        ]);
        $this->belongsToMany('Clazzes', [
            'foreignKey' => 'teacher_id',
            'targetForeignKey' => 'clazz_id',
            'joinTable' => 'clazzes_teachers'
        ]);
        $this->belongsToMany('Knowledges', [
            'foreignKey' => 'teacher_id',
            'targetForeignKey' => 'knowledge_id',
            'joinTable' => 'knowledges_teachers'
        ]);

        $this->belongsTo('Users', [
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('registry', 'create')
            ->notEmpty('registry');

        $validator
            ->allowEmpty('url_lattes');

        $validator
            ->allowEmpty('formation');

        $validator
            ->add('workload', 'valid', ['rule' => 'numeric'])
            ->requirePresence('workload', 'create')
            ->notEmpty('workload');

        $validator
            ->allowEmpty('about');

        $validator
            ->requirePresence('rg', 'create')
            ->notEmpty('rg');

        $validator
            ->add('cpf','custom',[
                'rule' => function($value, $context){
                    $cpf = $context['data']['cpf'];

                    if (strlen($cpf) != 11) {
                        return false;
                    }
                    for ($t = 9; $t < 11; $t++) {
                        for ($d = 0, $c = 0; $c < $t; $c++) {
                            $d += $cpf{$c} * (($t + 1) - $c);
                        }

                        $d = ((10 * $d) % 11) % 10;

                        if ($cpf{$c} != $d) {
                            return false;
                        }
                    }
                    return true;
                },
                'message' => 'CPF inválido',
            ])
            ->notEmpty('cpf');

        $validator
            ->add('workload', 'valid', ['rule' => 'numeric'])
            ->add('workload', 'valid', ['rule' => ['range', 0, 512], 'message' => 'Carga horária não pode ser negativa!']);

        $validator
            ->add('birth_date', 'valid', ['rule' => ['date', 'dmy']])
            ->add('birth_date','custom',[
                'rule'=>  function($value, $context){
                    $currentDate = (new \DateTime())->getTimestamp();
                    $birthDate = strtotime($context['data']['birth_date']);
                    if (($currentDate - $birthDate) / (60*60*24*365) < 16) {
                        return false;
                    }
                    return true;
                },
                'message'=>'Professor deve ter pelo menos dezesseis anos de idade',
            ])
            ->requirePresence('birth_date', 'create')
            ->notEmpty('birth_date');

        $validator
            ->add('entry_date', 'valid', ['rule' => ['date', 'dmy']])
            ->add('entry_date','custom',[
                'rule'=>  function($value, $context){
                    $currentDate = (new \DateTime())->getTimestamp();
                    $entryDate = strtotime($context['data']['entry_date']);
                    $birthDate = strtotime($context['data']['birth_date']);
                    if ($entryDate > $currentDate || $entryDate < $birthDate) {
                        return false;
                    }
                    return true;
                },
                'message'=>'Data de entrada deve ser menor que a data atual e maior que a data de nascimento',
            ])
            ->notEmpty('entry_date');

        $validator
            ->allowEmpty('situation');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        $rules->add(
            new IsUnique(['cpf']),
            [
                'errorField' => 'cpf',
                'message' => __('Já existe um docente com esse CPF!')
            ]
        );
        return $rules;
    }

	public function getAllTeachersWithKnowledge(){
		return $this
			->find('all')
			->contain([
				'Knowledges' => function($q) {
					return $q->select(['id']);
				}
			])
			->hydrate(false)->toArray();
	}
}

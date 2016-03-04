<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Teachers
 */
class UsersTable extends Table
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

        $this->table('users');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasOne('Teachers', [
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);

        $this->hasMany('Notifications', [
            'foreignKey' => 'user_id',
            'propertyName' => 'latest_notifications',
            'finder' => 'latestByUser'
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
            ->requirePresence('login', 'create')
            ->notEmpty('login');

        $validator
            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->add('is_admin', 'valid', ['rule' => 'boolean']);

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
        $rules->add($rules->isUnique(['login']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }

    /**
     * Finds users by filters
     *
     * @param $filters
     * @return Query
     */
    public function findByFilters($filters)
    {
        /** @var Query $users */
        $users = $this->find('all');

        $conditions = [];
        if(isset($filters) && is_array($filters)) {
            if(isset($filters['login']) && !empty(trim($filters['login']))) {
                $conditions['Users.login LIKE'] = "%" . $filters['login'] . "%";
            }

            if(isset($filters['email']) && !empty(trim($filters['email']))) {
                $conditions['Users.email LIKE'] = "%" . $filters['email'] . "%";
            }

            if(isset($filters['name']) && !empty(trim($filters['name']))) {
                $conditions['Users.name LIKE'] = "%" . $filters['name'] . "%";
            }

            if(isset($filters['is_admin']) && $filters['is_admin'] == true) {
                $conditions['AND']['Users.is_admin'] = 1;
            }
        }

        $users->where($conditions);

        return [
            'data' => $users,
            'isFiltered' => !empty($conditions)
        ];
    }
}

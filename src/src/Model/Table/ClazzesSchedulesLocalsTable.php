<?php
namespace App\Model\Table;

use App\Model\Entity\ClazzesSchedulesLocal;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ClazzesSchedulesLocals Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Clazzes
 * @property \Cake\ORM\Association\BelongsTo $Schedules
 * @property \Cake\ORM\Association\BelongsTo $Locals
 */
class ClazzesSchedulesLocalsTable extends Table
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

        $this->table('clazzes_schedules_locals');
        $this->displayField('name');
        $this->primaryKey(['clazz_id', 'schedule_id', 'local_id', 'week_day']);

        $this->belongsTo('Clazzes', [
            'foreignKey' => 'clazz_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Schedules', [
            'foreignKey' => 'schedule_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Locals', [
            'foreignKey' => 'local_id',
            'joinType' => 'INNER'
        ]);
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
        $rules->add($rules->existsIn(['schedule_id'], 'Schedules'));
        $rules->add($rules->existsIn(['local_id'], 'Locals'));
        return $rules;
    }
}

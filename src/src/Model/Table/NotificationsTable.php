<?php
namespace App\Model\Table;

use App\Model\Entity\Notification;
use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Notifications Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class NotificationsTable extends Table
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

        $this->table('notifications');
        $this->displayField('description');
        $this->primaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->add('is_read', 'valid', ['rule' => 'boolean'])
            ->notEmpty('is_read');

        $validator
            ->notEmpty('link');

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
        return $rules;
    }

    /**
     * Finds Latest notifications by user
     *
     * @param Query $query
     * @param array $options
     * @return $this
     */
    public function findLatestByUser(Query $query, array $options)
    {
        $query->where(['Notifications.is_read' => false])
            ->limit(10)
            ->orderDesc('Notifications.id')
            ->formatResults(function($notifications) {
                $count = $this->find()->where(['Notifications.is_read' => false])->count();
                foreach($notifications as $notification) {
                    $notification->count = $count;
                }
                return $notifications;
            });
        return $query;
    }

    /**
     * Registers a new notification.
     * @param array $notification containing the notification data.
     * @return bool true if the notification has successfully saved or false if not.
     */
    public static function register($notification)
    {
        $notificationTable = TableRegistry::get('Notifications');
        $notification = $notificationTable->newEntity($notification);
        if($notificationTable->save($notification)) {
            return True;
        }

        return False;
    }
}

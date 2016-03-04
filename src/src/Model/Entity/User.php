<?php
namespace App\Model\Entity;

use App\Model\Table\ClazzesTable;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $login
 * @property string $email
 * @property string $name
 * @property string $password
 * @property bool $is_admin
 * @property int $teacher_id
 * @property \App\Model\Entity\Teacher $teacher
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }

    public function isCoordinator()
    {
        if(!isset($this->teacher) || !isset($this->teacher->roles)) {
            return False;
        }

        $roles = $this->teacher->roles;
        foreach($roles as $role) {
            if($role->type == 'COORDINATOR') {
                return True;
            }
        }

        return False;
    }

    public function isFacilitatorOf($knowledgeId)
    {
        if(!isset($this->teacher) || !isset($this->teacher->roles)) {
            return False;
        }

        $roles = $this->teacher->roles;
        foreach($roles as $role) {
            if($role->type == 'FACILITATOR' && $role->knowledge_id == $knowledgeId) {
                return True;
            }
        }

        return False;
    }

    public function _getTitle()
    {
        if($this->canAdmin()) {
            return __('Administrador');
        } elseif($this->isCoordinator()) {
            return __('Coordenador');
        }

        if(isset($this->teacher) && isset($this->teacher->roles)) {
            foreach($this->teacher->roles as $role) {
                if($role->type == 'FACILITATOR') {
                    return __('Facilitador');
                }
            }
        }

        if(isset($this->teacher) && $this->teacher != null) {
            return __('Docente');
        }

        return __('Usuário');
    }

    public function levelOf($knowledgeId)
    {
        if(!isset($this->teacher) || $this->teacher == null) {
            return 0;
        }

        if(!isset($this->teacher->knowledges) || !is_array($this->teacher->knowledges)) {
            $knowledgesTeachersModel = TableRegistry::get('KnowledgesTeachers');
            $knowledges = $knowledgesTeachersModel->find('all')->contain(['Knowledges'])
                ->where(['KnowledgesTeachers.teacher_id' => $this->teacher->id])->toArray();

            $this->teacher->knowledges = [];
            foreach($knowledges as $knowledge) {
                $this->teacher->knowledges[$knowledge->knowledge_id] = $knowledge;
            }
        }

        if(!isset($this->teacher->knowledges[$knowledgeId])) {
            return 0;
        }

        return $this->teacher->knowledges[$knowledgeId]->level;
    }

    public function isSubscribed($clazzId)
    {
        if(!isset($this->teacher) || $this->teacher == null) {
            return false;
        }

        /** @var ClazzesTable $clazzModel */
        $clazzModel = TableRegistry::get('Clazzes');
        return $clazzModel->isTeacherSubscribed($this->teacher->id, $clazzId);
    }

    public function isClazzAdmin($clazz)
    {
        $knowledge = (isset($clazz->subject) && isset($clazz->subject->knowledge_id)) ?
            $clazz->subject->knowledge_id : 0;

        return ($this->is_admin === true || $this->isCoordinator() || $this->isFacilitatorOf($knowledge));
    }

    public function canAdmin()
    {
        return ($this->is_admin === true || $this->isCoordinator());
    }
}

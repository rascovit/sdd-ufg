<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\HtmlHelper;
use Cake\View\View;
use Cake\Filesystem\Folder;

/**
 * Clazze Entity.
 *
 * @property int $id
 * @property string $name
 * @property int $vacancies
 * @property int $subject_id
 * @property \App\Model\Entity\Subject $subject
 * @property int $schedule_id
 * @property \App\Model\Entity\Schedule $schedule
 * @property int $local_id
 * @property \App\Model\Entity\Local $local
 * @property int $process_id
 * @property \App\Model\Entity\Process $process
 * @property \App\Model\Entity\Teacher[] $teachers
 */
class Clazze extends Entity
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

    public function _getDisplayName()
    {
        if(!isset($this->process)) {
            $table = TableRegistry::get($this->source());
            $this->process = $table->Processes->findById($this->process_id)->toArray()[0];
        }

        if(!isset($this->subject)) {
            $table = TableRegistry::get($this->source());
            $this->process = $table->Subjects->findById($this->subject_id)->toArray()[0];
        }

        return $this->process->name . " - " . $this->subject->name . " - " . $this->name;
    }

    public function _getStatus()
    {
        if(!isset($this->intents) || empty($this->intents)) {
            return "OPENED";
        }

        $status = (count($this->intents) > 1) ? "CONFLICT" : "OPENED";
        foreach($this->intents as $intent) {
            if($intent->status == "SELECTED") {
                $status = "CLOSED";
            }
        }

        return $status;
    }

    public function _getDisplayStatus()
    {
        switch($this->status) {
            case "CONFLICT":
                $displayName = __('Em conflito');
                $lblClass = 'danger';
                break;
            case "CLOSED":
                $displayName = __('Fechado');
                $lblClass = 'default';
                break;
            case "OPENED":
            default:
                $displayName = __('Aberto');
                $lblClass = 'success';
                break;
        }

        return '<span class="label label-'.$lblClass.'">'.$displayName.'</span>';
    }

    public function _getIsClosed()
    {
        return ($this->status == 'CLOSED');
    }

    public function _getSelectedTeachers()
    {
        if(!isset($this->intents) || empty($this->intents)) {
            return [];
        }

        $first = $this->intents[0];
        $teachersById = [];
        $externalTeachers = False;
        if(!isset($first->teacher) || !isset($first->teacher->user)) {
            $externalTeachers = True;
            if(isset($this->teachersById) && is_array($this->teachersById)) {
                $teachersById = $this->teachersById;
            } else {
                $teacherIds = [];
                foreach($this->intents as $intent) {
                    $teacherIds[] = $intent->teacher_id;
                }

                $teacherModel = TableRegistry::get('Teachers');
                $teachers = $teacherModel->find('all')->contain(['Users'])
                    ->where(['Teachers.id IN' => $teacherIds])->toArray();

                foreach($teachers as $teacher) {
                    $teachersById[$teacher->id] = $teacher;
                }

                $this->teachersById = $teachersById;
            }
        }

        $selectedTeachers = [];
        foreach($this->intents as $intent) {
            if($intent->status == "SELECTED") {
                if($externalTeachers) {
                    $selectedTeachers[] = $teachersById[$intent->teacher_id];
                } else {
                    $selectedTeachers[] = $intent->teacher;
                }
            }
        }

        return $selectedTeachers;
    }

    public function _getSelectedTeachersNames() {
        $display = '<a class="teachers-names">' . __("Sem docente atribuído") . '</a>';

        if(!empty($this->selectedTeachers)) {
            $display = "";
            $htmlHelper = new HtmlHelper(new View());
            foreach($this->selectedTeachers as $teacher) {
                $display .= $htmlHelper->link($teacher->user->name, [
                    'controller' => 'Teachers',
                    'action' => 'view', $teacher->id
                ], [
                    'class' => 'teachers-names'
                ]);
            }
        }

        return $display;
    }
	
	public function _getSelectedTeachersIds() {
		$ids = array();
        if(!empty($this->selectedTeachers)) {
            foreach($this->selectedTeachers as $teacher) {
                $ids[] = $teacher->id;
            }
        }
        return $ids;
    }
	
	public function _getFiles()
    {
        $files = array();
		$dir = new Folder(WWW_ROOT.'/finishedClazzes/clazz-' . $this->id);
		if ($dir) {
			$files = $dir->find();
		}
		
		return $files;
    }
}

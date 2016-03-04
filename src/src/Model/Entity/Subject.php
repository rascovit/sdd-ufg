<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Subject Entity.
 *
 * @property int $id
 * @property string $name
 * @property int $teoric_workload
 * @property int $practical_workload
 * @property int $knowledge_id
 * @property \App\Model\Entity\Knowledge $knowledge
 * @property int $course_id
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\Clazze[] $clazzes
 */
class Subject extends Entity
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

    public function _getWorkload()
    {
        return $this->theoretical_workload + $this->practical_workload;
    }
}

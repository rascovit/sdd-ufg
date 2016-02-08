<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Schedule Entity.
 *
 * @property int $id
 * @property string $code
 * @property \Cake\I18n\Time $initial_time
 * @property \Cake\I18n\Time $final_time
 * @property \App\Model\Entity\Clazze[] $clazzes
 */
class Schedule extends Entity
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

    public function _getPeriod()
    {
        return $this->start_time_formated . " ~ " . $this->end_time_formated;
    }

    public function _getStartTimeFormated()
    {
        return $this->formatTime($this->start_time);
    }

    public function _getEndTimeFormated()
    {
        return $this->formatTime($this->end_time);
    }

    private function formatTime($time)
    {
      return $time->format('H:i');
    }
}

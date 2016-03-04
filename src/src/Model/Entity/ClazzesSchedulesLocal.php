<?php
namespace App\Model\Entity;

use App\View\Helper\UtilsHelper;
use Cake\ORM\Entity;

/**
 * ClazzesSchedulesLocal Entity.
 *
 * @property int $clazz_id
 * @property \App\Model\Entity\Clazze $clazze
 * @property int $schedule_id
 * @property \App\Model\Entity\Schedule $schedule
 * @property int $local_id
 * @property \App\Model\Entity\Local $local
 */
class ClazzesSchedulesLocal extends Entity
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
        '*' => true
    ];

    public function _getName()
    {
        return $this->schedule->period . ': ' . $this->local->fullPath;
    }
}

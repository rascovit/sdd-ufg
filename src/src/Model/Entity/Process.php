<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Process Entity.
 *
 * @property int $id
 * @property \Cake\I18n\Time $initial_date
 * @property \Cake\I18n\Time $teacher_intent_date
 * @property \Cake\I18n\Time $primary_distribution_date
 * @property \Cake\I18n\Time $substitute_intent_date
 * @property \Cake\I18n\Time $secondary_distribution_date
 * @property \Cake\I18n\Time $final_date
 * @property \App\Model\Entity\Clazze[] $clazzes
 * @property \App\Model\Entity\ProcessConfiguration[] $process_configurations
 */
class Process extends Entity
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

    public function _getDisplayStatus() {
        if ($this->status == 'CANCELLED') {
            return '<span class="label label-danger">Cancelado</span>';
        } else if ($this->status == 'CLOSED') {
            return '<span class="label label-default">Fechado</span>';
        }
        return '<span class="label label-success">Aberto</span>';
    }

    public function _getInitialDateFormated()
    {
        return $this->formatDate($this->initial_date);
    }

    public function _getTeacherIntentDateFormated()
    {
        return $this->formatDate($this->teacher_intent_date);
    }

    public function _getPrimaryDistribuitionDateFormated()
    {
        return $this->formatDate($this->primary_distribution_date);
    }

    public function _getSubstituteIntentDateFormated()
    {
        return $this->formatDate($this->substitute_intent_date);
    }

    public function _getSecondaryDistribuitionDateFormated()
    {
        return $this->formatDate($this->secondary_distribution_date);
    }

    public function _getFinalDateFormated()
    {
        return $this->formatDate($this->final_date);
    }

    private function formatDate($date)
    {
        return $date->format('dd-MM-YYYY');
    }
}

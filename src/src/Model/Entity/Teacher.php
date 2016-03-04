<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Teacher Entity.
 *
 * @property int $id
 * @property string $registry
 * @property string $url_lattes
 * @property \Cake\I18n\Time $entry_date
 * @property string $formation
 * @property int $workload
 * @property string $about
 * @property string $rg
 * @property string $cpf
 * @property \Cake\I18n\Time $birth_date
 * @property string $situation
 * @property \App\Model\Entity\Role[] $roles
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\Clazze[] $clazzes
 * @property \App\Model\Entity\Knowledge[] $knowledges
 */
class Teacher extends Entity
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
		'knowledges' => true
    ];

    public function _getDisplayField() {
        return $this->user->name;
    }
}

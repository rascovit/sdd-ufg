<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * KnowledgesTeachersFixture
 *
 */
class KnowledgesTeachersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'teacher_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'knowledge_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'level' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '3', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'knowledge_id' => ['type' => 'index', 'columns' => ['knowledge_id', 'teacher_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['teacher_id', 'knowledge_id'], 'length' => []],
            'knowledges_teachers_ibfk_1' => ['type' => 'foreign', 'columns' => ['knowledge_id'], 'references' => ['knowledges', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'knowledges_teachers_ibfk_2' => ['type' => 'foreign', 'columns' => ['teacher_id'], 'references' => ['teachers', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'teacher_id' => 1,
            'knowledge_id' => 1,
            'level' => 1
        ],
    ];
}

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KnowledgesTeachersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KnowledgesTeachersTable Test Case
 */
class KnowledgesTeachersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.knowledges_teachers',
        'app.teachers',
        'app.roles',
        'app.knowledges',
        'app.subjects',
        'app.courses',
        'app.clazzes',
        'app.processes',
        'app.process_configurations',
        'app.processes_process_configurations',
        'app.clazzes_teachers',
        'app.clazzes_schedules_locals',
        'app.schedules',
        'app.locals',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('KnowledgesTeachers') ? [] : ['className' => 'App\Model\Table\KnowledgesTeachersTable'];
        $this->KnowledgesTeachers = TableRegistry::get('KnowledgesTeachers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->KnowledgesTeachers);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

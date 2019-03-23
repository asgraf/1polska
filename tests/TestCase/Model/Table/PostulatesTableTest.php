<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PostulatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PostulatesTable Test Case
 */
class PostulatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PostulatesTable
     */
    public $Postulates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Postulates',
        'app.Users',
        'app.Constituencies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Postulates') ? [] : ['className' => PostulatesTable::class];
        $this->Postulates = TableRegistry::getTableLocator()->get('Postulates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Postulates);

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

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RepresentativesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RepresentativesTable Test Case
 */
class RepresentativesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RepresentativesTable
     */
    public $Representatives;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Representatives',
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
        $config = TableRegistry::getTableLocator()->exists('Representatives') ? [] : ['className' => RepresentativesTable::class];
        $this->Representatives = TableRegistry::getTableLocator()->get('Representatives', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Representatives);

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

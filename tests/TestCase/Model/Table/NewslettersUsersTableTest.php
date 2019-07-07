<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NewslettersUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NewslettersUsersTable Test Case
 */
class NewslettersUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\NewslettersUsersTable
     */
    public $NewslettersUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.NewslettersUsers',
        'app.Newsletters',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('NewslettersUsers') ? [] : ['className' => NewslettersUsersTable::class];
        $this->NewslettersUsers = TableRegistry::getTableLocator()->get('NewslettersUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NewslettersUsers);

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

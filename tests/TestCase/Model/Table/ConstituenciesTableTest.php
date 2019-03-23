<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConstituenciesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConstituenciesTable Test Case
 */
class ConstituenciesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ConstituenciesTable
     */
    public $Constituencies;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Constituencies',
        'app.Postulates',
        'app.Representatives'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Constituencies') ? [] : ['className' => ConstituenciesTable::class];
        $this->Constituencies = TableRegistry::getTableLocator()->get('Constituencies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Constituencies);

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
}

<?php
require_once 'application/unit/newClasses.php';

/**
 * newClass test case.
 */
class TestSimple extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var newClass
     */
    private $newClass;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated TestSimple::setUp()
        
        $this->newClass = new newClass(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated TestSimple::tearDown()
        $this->newClass = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests newClass->addNumbers()
     */
    public function testAddNumbers()
    {
        // TODO Auto-generated TestSimple->testAddNumbers()
        //$this->markTestIncomplete("addNumbers test not implemented");
        
        //$this->newClass->addNumbers(/* parameters */);
        
        $this->assertEquals($this->newClass->addNumbers(1, 2), 3);
        
        $this->assertEquals($this->newClass->addNumbers(1, 2), 5);
    }
}


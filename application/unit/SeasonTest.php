<?php
require_once 'application/models/Season.php';

/**
 * Season test case.
 */
class SeasonTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Season
     */
    private $season;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated SeasonTest::setUp()
        
        $this->season = new Season();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated SeasonTest::tearDown()
        $this->season = null;
        
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
     * Tests Season->getSeasonID()
     */
    public function testGetSeasonID()
    {
        $inputSeasonID = 1;
        $expectedOutput = 1;
        
        $this->season->setSeasonID($inputSeasonID);
        $actualOutput = $this->season->getSeasonID();
        
        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * Tests Season->getSeasonYear()
     */
    public function testGetSeasonYear()
    {
        // TODO Auto-generated SeasonTest->testGetSeasonYear()
        $this->markTestIncomplete("getSeasonYear test not implemented");
        
        $this->season->getSeasonYear(/* parameters */);
    }

    
}


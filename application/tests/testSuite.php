<?php
require 'vendor/autoload.php'


require_once 'TestCase.php';
require_once 'Dummy_test.php';
//require_once 'Report_instance_test.php';
//require_once 'Report_controller_test.php';


/**
 * Static test suite.
 */
class testSuite extends PHPUnit_Framework_TestSuite
{

    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('testSuite');
        //$this->addTestSuite('Report_instance_test');
        //$this->addTestSuite('Report_controller_test');
        $this->addTestSuite('Dummy_test.php');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}


<?php
require_once 'application/tests/User_report_test.php';
require_once 'application/tests/Report_controller_test.php';

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
        $this->addTestSuite('User_report_test');
        $this->addTestSuite('Report_controller_test');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}


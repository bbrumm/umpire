<?php
class Report_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }
/*
    public function test_Report1() {
        $postArray = array(
            'reportName'=>'1',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkAgeGroup'=>array('Seniors'),
            'chkUmpireDiscipline'=>array('Field'),
            'chkLeague'=>array('GFL')
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>01 - Umpires and Clubs (2018)</h1>";
        $this->assertContains($expected, $output);
    }
*/
    public function test_dummy() {
        $this->assertEquals(1, 1);
    }

}

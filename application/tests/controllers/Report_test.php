<?php
class Report_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }

    public function test_Report1() {
        $postArray = array(
            'reportName'=>'1',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>01 - Umpires and Clubs (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report2() {
        $postArray = array(
            'reportName'=>'2',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>02 - Umpire Names by League (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report3() {
        $postArray = array(
            'reportName'=>'3',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            //'chkLeague'=>array('Women'),
            'chkLeagueHidden'=>'Women'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>03 - Summary by Week (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report4() {
        $postArray = array(
            'reportName'=>'4',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            //'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>04 - Summary by Club (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report5() {
        $postArray = array(
            'reportName'=>'5',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            //'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>05 - Games with Zero Umpires For Each League (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report6() {
        $postArray = array(
            'reportName'=>'6',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            //'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>06 - Umpire Pairing (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report7() {
        $postArray = array(
            'reportName'=>'7',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>07 - Games with 2 or 3 Field Umpires (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report8() {
        $postArray = array(
            'reportName'=>'8',
            'season'=>2018,
            //'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            //'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>08 - Umpire Games Tally</h1>";
        $this->assertContains($expected, $output);
    }


}

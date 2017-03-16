<?php
class Report_controller_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->controller('Report');
        //$this->reportController = $this->CI->reportController;

    }
    
    
    public function testIndex() {
        
        $_POST['reportName'] = 1;
        $_POST['season'] = 2016;
        $_POST['region'] = 'Geelong';
        $_POST['chkAgeGroup'] = array('Seniors');
        $_POST['chkUmpireDiscipline'] = array('Field');
        $_POST['chkLeague'] = array('GFL');
        $_POST['rdRegion'] = 'Geelong';
        
        //$this->assertEquals('GFL', $_POST['chkLeague']);
        
        $output = $this->request('GET', ['Report', 'index']);
        echo $output;
        $this->assertContains("01 - Umpires and Clubs (2016)", $output);
        $this->assertContains("<span class='boldedText'>Last Game Date</span>:", $output);
        $this->assertContains("<span class='boldedText'>Umpire Discipline</span>: Field", $output);
        $this->assertContains("<span class='boldedText'>League</span>: GFL", $output);
        $this->assertContains("<span class='boldedText'>Age Group</span>: Seniors", $output);
        $this->assertContains("<table class='reportTable ", $output);

        
    }
}
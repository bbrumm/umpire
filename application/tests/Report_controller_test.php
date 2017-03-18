<?php
class Report_controller_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->controller('Report');
        //$this->reportController = $this->CI->reportController;

    }
    
    
    public function testIndexReport1() {
        
        $_POST['reportName'] = 1;
        $_POST['season'] = 2016;
        $_POST['region'] = 'Geelong';
        $_POST['chkAgeGroup'] = array('Seniors');
        $_POST['chkUmpireDiscipline'] = array('Field');
        $_POST['chkLeague'] = array('GFL');
        $_POST['rdRegion'] = 'Geelong';
        
        //$this->assertEquals('GFL', $_POST['chkLeague']);
        
        $output = $this->request('GET', ['Report', 'index']);
        //echo $output;
        $this->assertContains("01 - Umpires and Clubs (2016)", $output);
        $this->assertContains("<span class='boldedText'>Last Game Date</span>:", $output);
        $this->assertContains("<span class='boldedText'>Umpire Discipline</span>: Field", $output);
        $this->assertContains("<span class='boldedText'>League</span>: GFL", $output);
        $this->assertContains("<span class='boldedText'>Age Group</span>: Seniors", $output);
        $this->assertContains("<table class='reportTable ", $output);
    }
    
    public function testIndexReport2() {
    
        $_POST['reportName'] = 2;
        $_POST['season'] = 2016;
        $_POST['region'] = 'Colac';
        $_POST['chkAgeGroup'] = array('Seniors', 'Reserves');
        $_POST['chkUmpireDiscipline'] = array('Goal');
        $_POST['chkLeague'] = array('CDFNL');
        $_POST['rdRegion'] = 'Colac';
    
        //$this->assertEquals('GFL', $_POST['chkLeague']);
    
        $output = $this->request('GET', ['Report', 'index']);
        //echo $output;
        $this->assertContains("02 - Umpire Names by League (2016)", $output);
        $this->assertContains("<span class='boldedText'>Last Game Date</span>:", $output);
        $this->assertContains("<span class='boldedText'>Umpire Discipline</span>: Goal", $output);
        $this->assertContains("<span class='boldedText'>League</span>: CDFNL", $output);
        $this->assertContains("<span class='boldedText'>Age Group</span>: Seniors, Reserves", $output);
        $this->assertContains("<table class='reportTable ", $output);
    }
    
    public function testIndexReport3() {
    
        $_POST['reportName'] = 3;
        $_POST['season'] = 2016;
        $_POST['region'] = 'Geelong';
        $_POST['chkAgeGroup'] = array('Seniors', 'Reserves', 'Colts', 'Under 16', 'Under 14', 'Under 12', 'Youth Girls', 'Junior Girls');
        $_POST['chkUmpireDiscipline'] = array('Field', 'Boundary', 'Goal');
        $_POST['chkLeague'] = array('BFL', 'GFL', 'GDFL', 'GJFL');
        $_POST['rdRegion'] = 'Geelong';
    
        //$this->assertEquals('GFL', $_POST['chkLeague']);
    
        $output = $this->request('GET', ['Report', 'index']);
        //echo $output;
        $this->assertContains("03 - Summary by Week (Matches Where No Umpires Are Recorded) (2016)", $output);
        $this->assertContains("<span class='boldedText'>Last Game Date</span>:", $output);
        $this->assertContains("<span class='boldedText'>Umpire Discipline</span>: Field, Boundary, Goal", $output);
        $this->assertContains("<span class='boldedText'>League</span>: BFL, GFL, GDFL, GJFL", $output);
        $this->assertContains("<span class='boldedText'>Age Group</span>: Seniors, Reserves, Colts, Under 16, Under 14, Under 12, Youth Girls, Junior Girls", $output);
        $this->assertContains("<table class='reportTable ", $output);
    }
    
    public function testIndexReport4() {
    
        $_POST['reportName'] = 4;
        $_POST['season'] = 2016;
        $_POST['region'] = 'Geelong';
        $_POST['chkAgeGroup'] = array('Seniors', 'Reserves', 'Colts', 'Under 16', 'Under 14', 'Under 12', 'Youth Girls', 'Junior Girls');
        $_POST['chkUmpireDiscipline'] = array('Field', 'Boundary', 'Goal');
        $_POST['chkLeague'] = array('BFL', 'GFL', 'GDFL', 'GJFL');
        $_POST['rdRegion'] = 'Geelong';
    
        //$this->assertEquals('GFL', $_POST['chkLeague']);
    
        $output = $this->request('GET', ['Report', 'index']);
        //echo $output;
        $this->assertContains("04 - Summary by Club (Matches Where No Umpires Are Recorded) (2016)", $output);
        $this->assertContains("<span class='boldedText'>Last Game Date</span>:", $output);
        $this->assertContains("<span class='boldedText'>Umpire Discipline</span>: Field, Boundary, Goal", $output);
        $this->assertContains("<span class='boldedText'>League</span>: BFL, GFL, GDFL, GJFL", $output);
        $this->assertContains("<span class='boldedText'>Age Group</span>: Seniors, Reserves, Colts, Under 16, Under 14, Under 12, Youth Girls, Junior Girls", $output);
        $this->assertContains("<table class='reportTable ", $output);
    }
    
    public function testIndexReport5() {
    
        $_POST['reportName'] = 5;
        $_POST['season'] = 2016;
        $_POST['region'] = 'Geelong';
        $_POST['chkAgeGroup'] = array('Seniors', 'Reserves', 'Colts', 'Under 16', 'Under 14', 'Under 12', 'Youth Girls', 'Junior Girls');
        $_POST['chkUmpireDiscipline'] = array('Field', 'Boundary', 'Goal');
        $_POST['chkLeague'] = array('BFL', 'GFL', 'GDFL', 'GJFL');
        $_POST['rdRegion'] = 'Geelong';
    
        //$this->assertEquals('GFL', $_POST['chkLeague']);
    
        $output = $this->request('GET', ['Report', 'index']);
        //echo $output;
        $this->assertContains("05 - Games with Zero Umpires For Each League (2016)", $output);
        $this->assertContains("<span class='boldedText'>Last Game Date</span>:", $output);
        $this->assertContains("<span class='boldedText'>Umpire Discipline</span>: Field, Boundary, Goal", $output);
        $this->assertContains("<span class='boldedText'>League</span>: BFL, GFL, GDFL, GJFL", $output);
        $this->assertContains("<span class='boldedText'>Age Group</span>: Seniors, Reserves, Colts, Under 16, Under 14, Under 12, Youth Girls, Junior Girls", $output);
        $this->assertContains("<table class='reportTable ", $output);
    }
    
    public function testIndexReport6() {
    
        $_POST['reportName'] = 6;
        $_POST['season'] = 2016;
        $_POST['region'] = 'Geelong';
        $_POST['chkAgeGroup'] = array('Reserves');
        $_POST['chkUmpireDiscipline'] = array('Field', 'Boundary', 'Goal');
        $_POST['chkLeague'] = array('BFL', 'GFL', 'GDFL', 'GJFL');
        $_POST['rdRegion'] = 'Geelong';
    
        //$this->assertEquals('GFL', $_POST['chkLeague']);
    
        $output = $this->request('GET', ['Report', 'index']);
        //echo $output;
        $this->assertContains("06 - Umpire Pairing (2016)", $output);
        $this->assertContains("<span class='boldedText'>Last Game Date</span>:", $output);
        $this->assertContains("<span class='boldedText'>Umpire Discipline</span>: Field, Boundary, Goal", $output);
        $this->assertContains("<span class='boldedText'>League</span>: BFL, GFL, GDFL, GJFL", $output);
        $this->assertContains("<span class='boldedText'>Age Group</span>: Reserves", $output);
        $this->assertContains("<table class='reportTable ", $output);
    }
}
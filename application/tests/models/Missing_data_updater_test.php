<?php
class Missing_data_updater_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Missing_data_updater');
        $this->obj = $this->CI->Missing_data_updater;
    }
    
    
    public function test_GetPossibleLeaguesForComp() {
        $arrayStore = new Array_store();
        $leagesForComp = $this->obj->loadPossibleLeaguesForComp($arrayStore);
        $expectedSize = 10;
        $actualSize = count($leagesForComp);
        $expectedFirstValue = "AFL Barwon Blood Toyota Geelong FNL";
        $actualFirstValue = $leagesForComp[0][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }
    
    public function test_GetClubsForTeam() {
        $arrayStore = new Array_store();
        $clubs = $this->obj->loadPossibleClubsForTeam($arrayStore);
        $expectedSize = 4;
        $actualSize = count($clubs);
        $expectedFirstValue = "East";
        $actualFirstValue = $clubs[2][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }
    
    public function test_GetRegions() {
        $arrayStore = new Array_store();
        $regions = $this->obj->loadPossibleRegions($arrayStore);
        $expectedSize = 3;
        $actualSize = count($regions);
        $expectedFirstValue = "Samba";
        $actualFirstValue = $regions[1][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }
    
    public function test_GetAgeGroups() {
        $arrayStore = new Array_store();
        $ages = $this->obj->loadPossibleAgeGroups($arrayStore);
        $expectedSize = 5;
        $actualSize = count($ages);
        $expectedFirstValue = "Under 18";
        $actualFirstValue = $ages[3][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }
    
    public function test_GetShortLeagueNames() {
        $arrayStore = new Array_store();
        $leagues = $this->obj->loadPossibleShortLeagueNames($arrayStore);
        $expectedSize = 4;
        $actualSize = count($leagues);
        $expectedFirstValue = "SANFL";
        $actualFirstValue = $leagues[1][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }
    
    public function test_GetDivisions() {
        $arrayStore = new Array_store();
        $divisions = $this->obj->loadPossibleDivisions($arrayStore);
        $expectedSize = 3;
        $actualSize = count($divisions);
        $expectedFirstValue = "Div 2";
        $actualFirstValue = $divisions[1][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }
    
}
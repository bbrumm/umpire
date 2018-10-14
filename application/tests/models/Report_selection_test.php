<?php
class Report_selection_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Report_selection');
    }
    
    public function test_CreateReportSelection() {
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled, 
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getReportID());
    }
    
    public function test_CreateReportSelectionEmptyID() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = "";
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionNullID() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = null;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionTextID() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = "text value here";
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionZeroEnabled() {
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 0;
        $inputLeagueEnabled = 0;
        $inputAgeGroupEnabled = 0;
        $inputUmpireTypeEnabled = 0;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getReportID());
    }
    
    public function test_CreateReportSelectionRegionEnabledTwo() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 2;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionLeagueEnabledTwo() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 2;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionAgeGroupEnabledTwo() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 2;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionUmpireTypeEnabledTwo() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 2;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionRegionEnabledText() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = "some words";
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionLeagueEnabledText() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = "some words";
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionAgeGroupEnabledText() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = "some words";
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionUmpireTypeEnabledText() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = "some words";
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
    }
    
    public function test_CreateReportSelectionIDName() {
        $inputID = 1;
        $inputName = "Something";
        
        $this->obj = Report_selection::createNewReportSelectionIDName($inputID, $inputName);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getReportID());
    }
    
    public function test_CreateReportSelectionName() {
        $inputID = 1;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
        $expected = "Something";
        $this->assertEquals($expected, $this->obj->getReportName());
    }
    
    public function test_CreateReportSelectionRegion() {
        $inputID = 2;
        $inputName = "Something";
        $inputRegionEnabled = 1;
        $inputLeagueEnabled = 0;
        $inputAgeGroupEnabled = 0;
        $inputUmpireTypeEnabled = 0;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getRegionEnabled());
    }
    
    public function test_CreateReportSelectionLeague() {
        $inputID = 2;
        $inputName = "Something";
        $inputRegionEnabled = 0;
        $inputLeagueEnabled = 1;
        $inputAgeGroupEnabled = 0;
        $inputUmpireTypeEnabled = 0;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getLeagueEnabled());
    }
    
    public function test_CreateReportSelectionAgeGroup() {
        $inputID = 2;
        $inputName = "Something";
        $inputRegionEnabled = 0;
        $inputLeagueEnabled = 0;
        $inputAgeGroupEnabled = 1;
        $inputUmpireTypeEnabled = 0;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getAgeGroupEnabled());
    }
    
    public function test_CreateReportSelectionUmpireType() {
        $inputID = 2;
        $inputName = "Something";
        $inputRegionEnabled = 0;
        $inputLeagueEnabled = 0;
        $inputAgeGroupEnabled = 0;
        $inputUmpireTypeEnabled = 1;
        
        $this->obj = Report_selection::createNewReportSelection($inputID, $inputName, $inputRegionEnabled,
            $inputLeagueEnabled, $inputAgeGroupEnabled, $inputUmpireTypeEnabled);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getUmpireTypeEnabled());
    }
}
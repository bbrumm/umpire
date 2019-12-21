<?php
class Report6_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->model('separate_reports/Report1');
        $this->CI->load->model('Report_instance');
        $this->obj = $this->CI->Report_factory->createReport(6);
    }
    
    public function test_GetDataQuery() {
        $reportInstance = new Report_instance();
        $inputFilterUmpire = array("First", "Second", "Third");
        $inputFilterAge = array("Four");
        $inputFilterShortLeague = array("Five", "Six");
        $inputFilterRegion = array("Seven");
        $inputSeasonYear = 2018;
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterUmpireType->createFilterParameter($inputFilterUmpire, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterAgeGroup->createFilterParameter($inputFilterAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        $reportInstance->requestedReport->setSeason($inputSeasonYear);
        
        $expected = "SELECT umpire_type, age_group, region_name, first_umpire, second_umpire, match_count FROM dw_mv_report_06 ".
            "WHERE season_year IN (2018) AND age_group IN ('Four') AND region_name IN ('Seven') AND umpire_type IN ('First','Second','Third') ".
            "AND short_league_name IN ('Five','Six') ORDER BY first_umpire, second_umpire;";
        $actual = $this->obj->getReportDataQuery($reportInstance);
        $this->assertEquals($expected, $actual);
    }
    
    public function test_GetReportColumnQuery() {
        $reportInstance = new Report_instance();
        $inputFilterUmpire = array("First", "Second", "Third");
        $inputFilterAge = array("Four");
        $inputFilterShortLeague = array("Five", "Six");
        $inputFilterRegion = array("Seven");
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterUmpireType->createFilterParameter($inputFilterUmpire, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterAgeGroup->createFilterParameter($inputFilterAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        
        /*$expected = "SELECT DISTINCT second_umpire FROM dw_mv_report_06 WHERE season_year IN () AND age_group IN ('Four') ".
        "AND region_name IN ('Seven') AND umpire_type IN ('First','Second','Third') AND short_league_name IN ('Five','Six') ORDER BY second_umpire;";*/
        $actual = $this->obj->getReportColumnQuery($reportInstance);
        //$this->assertEquals($expected, $actual);
        $this->assertNotEmpty($actual);
    }
    
    public function test_GetDataQueryNullParam() {
        $this->expectException(InvalidArgumentException::class);
        $reportInstance = new Report_instance();
        $inputFilterUmpire = null;
        $inputFilterAge = null;
        $inputFilterShortLeague = null;
        $inputFilterRegion = null;
        $inputSeasonYear = null;
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterUmpireType->createFilterParameter($inputFilterUmpire, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterAgeGroup->createFilterParameter($inputFilterAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        $reportInstance->requestedReport->setSeason(null);
        
        $actual = $this->obj->getReportDataQuery($reportInstance);
    }
    
    public function test_GetDataQueryEmptyParam() {
        $this->expectException(InvalidArgumentException::class);
        $reportInstance = new Report_instance();
        $inputFilterUmpire = [];
        $inputFilterAge = [];
        $inputFilterShortLeague = [];
        $inputFilterRegion = [];
        $inputSeasonYear = "";
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterUmpireType->createFilterParameter($inputFilterUmpire, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterAgeGroup->createFilterParameter($inputFilterAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        $reportInstance->requestedReport->setSeason(null);
        
        $actual = $this->obj->getReportDataQuery($reportInstance);
    }
    
    public function test_GetReportColumnQueryNullParam() {
        $this->expectException(InvalidArgumentException::class);
        $reportInstance = new Report_instance();
        $inputFilterUmpire = null;
        $inputFilterAge = null;
        $inputFilterShortLeague = null;
        $inputFilterRegion = null;
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterUmpireType->createFilterParameter($inputFilterUmpire, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterAgeGroup->createFilterParameter($inputFilterAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        
        $actual = $this->obj->getReportColumnQuery($reportInstance);
        
    }
    
    public function test_GetReportColumnQueryEmptyParam() {
        $this->expectException(InvalidArgumentException::class);
        $reportInstance = new Report_instance();
        $inputFilterUmpire = [];
        $inputFilterAge = [];
        $inputFilterShortLeague = [];
        $inputFilterRegion = [];
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterUmpireType->createFilterParameter($inputFilterUmpire, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterAgeGroup->createFilterParameter($inputFilterAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        
        $actual = $this->obj->getReportColumnQuery($reportInstance);
        
    }
    
}
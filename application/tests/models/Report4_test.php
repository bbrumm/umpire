<?php
class Report4_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->model('separate_reports/Report1');
        $this->CI->load->model('Report_instance');
        $this->obj = $this->CI->Report_factory->createReport(4);
    }
    
    public function test_GetDataQuery() {
        $reportInstance = new Report_instance();
        $inputFilterRegion = array("Seven");
        $inputSeasonYear = 2018;
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        $reportInstance->requestedReport->setSeason($inputSeasonYear);
        
        $expected = "SELECT club_name, age_group, short_league_name, umpire_type, match_count FROM dw_mv_report_04 ".
        "WHERE region_name IN ('Seven') AND season_year = 2018 ORDER BY club_name, age_sort_order, league_sort_order;";
        $actual = $this->obj->getReportDataQuery($reportInstance);
        $this->assertEquals($expected, $actual);
    }
    
    public function test_GetReportColumnQuery() {
        $reportInstance = new Report_instance();
        $inputFilterShortLeague = array("Five", "Six");
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        
        $expected = "SELECT DISTINCT s.umpire_type, s.age_group, s.short_league_name FROM staging_all_ump_age_league s ".
        "WHERE s.short_league_name IN ('Five','Six') ORDER BY s.umpire_type, s.age_sort_order, s.league_sort_order;";
        $actual = $this->obj->getReportColumnQuery($reportInstance);
        $this->assertEquals($expected, $actual);
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
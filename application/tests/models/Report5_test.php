<?php
class Report5_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->model('separate_reports/Report1');
        $this->CI->load->model('Report_instance');
        $this->obj = $this->CI->Report_factory->createReport(5);
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
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        $reportInstance->requestedReport->setSeason($inputSeasonYear);
        
        $expected = "SELECT umpire_type, age_group, short_league_name, match_no_ump, total_match_count, match_pct ".
        "FROM dw_mv_report_05 WHERE short_league_name IN ('Five','Six') AND region_name IN ('Seven') AND season_year = 2018 ".
        "ORDER BY umpire_type, age_sort_order, league_sort_order;";
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
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        
        /*$expected = "SELECT DISTINCT l.short_league_name, sub.subtotal FROM dw_dim_league l CROSS JOIN ".
        "( SELECT 'Games' AS subtotal UNION SELECT 'Total' UNION SELECT 'Pct' ) AS sub ".
        "WHERE l.short_league_name IN ('Five','Six') UNION ALL SELECT 'All', 'Total';";*/
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
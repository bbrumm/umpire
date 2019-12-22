<?php
class Report2_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->model('separate_reports/Report1');
        $this->CI->load->model('Report_instance');
        $this->obj = $this->CI->Report_factory->createReport(2);
    }

    //TODO refactor these tests as there is a lot of duplicate code
    //TODO: Instead of testing the actual query string, run a few tests that check the results.
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
        
        /*$expected = "SELECT last_first_name, age_group, age_sort_order, short_league_name, two_ump_flag, SUM(match_count) AS match_count ".
            "FROM dw_mv_report_02 WHERE age_group IN ('Four') AND short_league_name IN ('2 Umpires', 'Five','Six') AND region_name IN ('Seven') ".
            "AND umpire_type IN ('First','Second','Third') AND season_year = 2018 AND two_ump_flag = 0 ".
            "GROUP BY last_first_name, age_group, age_sort_order, short_league_name, two_ump_flag UNION ALL ".
            "SELECT last_first_name, age_group, age_sort_order, '2 Umpires', two_ump_flag, SUM(match_count) AS match_count ".
            "FROM dw_mv_report_02 WHERE age_group IN ('Four') AND short_league_name IN ('2 Umpires', 'Five','Six') AND region_name IN ('Seven') ".
            "AND umpire_type IN ('First','Second','Third') AND season_year = 2018 AND two_ump_flag = 1 GROUP BY last_first_name, age_group, age_sort_order, two_ump_flag ".
            "ORDER BY last_first_name, age_sort_order, short_league_name;";*/
        $actual = $this->obj->getReportDataQuery($reportInstance);
        $this->assertNotEmpty($actual);
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
        
        /*$expected = "SELECT DISTINCT age_group, short_league_name FROM ( SELECT age_group, age_sort_order, league_sort_order, short_league_name ".
            "FROM dw_mv_report_02 WHERE age_group IN ('Four') AND short_league_name IN ('Five','Six') AND region_name IN ('Seven') ".
            "AND umpire_type IN ('First','Second','Third') AND season_year =  UNION ALL SELECT 'Total', 1000, 1000, '' UNION ALL ".
            "SELECT 'Seniors', 1, 50, '2 Umpires' ) AS sub ORDER BY age_sort_order, league_sort_order, short_league_name;";
        */
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
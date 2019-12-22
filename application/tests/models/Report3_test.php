<?php
class Report3_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->model('separate_reports/Report1');
        $this->CI->load->model('Report_instance');
        $this->obj = $this->CI->Report_factory->createReport(3);
    }
    
    public function test_GetDataQuery() {
        $reportInstance = new Report_instance();
        $inputFilterShortLeague = array("Five", "Six");
        $inputSeasonYear = 2018;
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->requestedReport->setSeason($inputSeasonYear);
        
        /*$expected = "SELECT weekend_date, CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group, short_league_name, ".
        "GROUP_CONCAT(team_names) AS team_list, (SELECT COUNT(DISTINCT match_id) FROM staging_no_umpires s2 ".
        "WHERE s2.age_group = s.age_group AND s2.umpire_type = s.umpire_type AND s2.weekend_date = s.weekend_date AND short_league_name IN ('Five','Six') ) AS match_count ".
        "FROM staging_no_umpires s WHERE short_league_name IN ('Five','Six') AND season_year = 2018 ".
        "AND CONCAT(age_group, ' ', umpire_type) IN ( 'Seniors Boundary', 'Seniors Goal', 'Reserve Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field' ) ".
        "GROUP BY weekend_date, age_group, umpire_type, short_league_name ORDER BY weekend_date, age_group, umpire_type, short_league_name;";*/
        $actual = $this->obj->getReportDataQuery($reportInstance);
        $this->assertNotEmpty($actual);
    }
    
    public function test_GetReportColumnQuery() {
        $reportInstance = new Report_instance();
        $inputFilterAge = array("Four");
        $inputFilterShortLeague = array("Five", "Six");
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterAgeGroup->createFilterParameter($inputFilterAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        
        /*$expected = "SELECT DISTINCT CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group, short_league_name FROM ".
        "( SELECT s.age_group, s.umpire_type, s.short_league_name, s.region_name, s.age_sort_order FROM staging_all_ump_age_league s ".
        "UNION ALL SELECT s.age_group, s.umpire_type, 'Total', 'Total', s.age_sort_order FROM staging_all_ump_age_league s ) sub ".
        "WHERE CONCAT(age_group, ' ', umpire_type) IN ('Seniors Boundary' , 'Seniors Goal', 'Reserves Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field') ".
        "AND age_group IN ('Four') AND region_name IN ('Total', ) ORDER BY age_sort_order, umpire_type, short_league_name;";**/
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
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
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
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
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
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        
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
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        
        $actual = $this->obj->getReportColumnQuery($reportInstance);
        
    }
    
}
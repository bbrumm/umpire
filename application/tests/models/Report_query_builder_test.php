<?php
class Report_query_builder_test extends TestCase {

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('separate_reports/Report_query_builder');
        $this->CI->load->model('Report_instance');
        $this->obj = $this->CI->Report_query_builder;
    }

    const BIND_UMPIRE_TYPE = ":pUmpireType";
    const BIND_AGE_GROUP = ":pAgeGroup";
    const BIND_SEASON_YEAR = ":pSeasonYear";
    const BIND_LEAGUE = ":pLeague";
    const BIND_REGION = ":pRegion";

    const REPORT1_SQL_DATA = "report1_data.sql";
    const REPORT1_SQL_COLUMNS = "report1_columns.sql";

    //Report 1 Data
    public function test_ConstructReportQuery_Report1Data_UmpireType() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_DATA, $reportInstance);
        $this->assertNotContains(self::BIND_UMPIRE_TYPE, $actualSQLQuery);
    }

    public function test_ConstructReportQuery_Report1Data_AgeGroup() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_DATA, $reportInstance);
        $this->assertNotContains(self::BIND_AGE_GROUP, $actualSQLQuery);
    }

    public function test_ConstructReportQuery_Report1Data_SeasonYear() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_DATA, $reportInstance);
        $this->assertNotContains(self::BIND_SEASON_YEAR, $actualSQLQuery);
    }

    public function test_ConstructReportQuery_Report1Data_League() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_DATA, $reportInstance);
        $this->assertNotContains(self::BIND_LEAGUE, $actualSQLQuery);
    }

    public function test_ConstructReportQuery_Report1Data_Region() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_DATA, $reportInstance);
        $this->assertNotContains(self::BIND_REGION, $actualSQLQuery);
    }

    //Report 1 Columns
    public function test_ConstructReportQuery_Report1Columns_UmpireType() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_COLUMNS, $reportInstance);
        $this->assertNotContains(self::BIND_UMPIRE_TYPE, $actualSQLQuery);
    }

    public function test_ConstructReportQuery_Report1Columns_AgeGroup() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_COLUMNS, $reportInstance);
        $this->assertNotContains(self::BIND_AGE_GROUP, $actualSQLQuery);
    }

    public function test_ConstructReportQuery_Report1Columns_SeasonYear() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_COLUMNS, $reportInstance);
        $this->assertNotContains(self::BIND_SEASON_YEAR, $actualSQLQuery);
    }

    public function test_ConstructReportQuery_Report1Columns_League() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_COLUMNS, $reportInstance);
        $this->assertNotContains(self::BIND_LEAGUE, $actualSQLQuery);
    }

    public function test_ConstructReportQuery_Report1Columns_Region() {
        $reportInstance = new Report_instance();
        $actualSQLQuery = $this->obj->constructReportQuery(self::REPORT1_SQL_COLUMNS, $reportInstance);
        $this->assertNotContains(self::BIND_REGION, $actualSQLQuery);
    }




}
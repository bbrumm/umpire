<?php

class ReportColumnQuery_test extends TestCase {

    //TODO: Add these to some kind of global const file
    const UMPIRE_FIELD = "Field";
    const UMPIRE_BOUNDARY = "Boundary";
    const UMPIRE_GOAL = "Goal";

    const UMPIRE_AGE_SENIORS = "Seniors";
    const UMPIRE_AGE_RESERVES = "Reserves";
    const UMPIRE_AGE_COLTS = "Colts";
    const UMPIRE_AGE_U15 = "Under 15";

    const UMPIRE_LEAGUE_GFL = "GFL";
    const UMPIRE_LEAGUE_GDFL = "GDFL";
    const UMPIRE_LEAGUE_GJFL = "GJFL";
    const UMPIRE_LEAGUE_BFL = "BFL";
    const UMPIRE_LEAGUE_CDFNL = "CDFNL";


    const UMPIRE_REGION_GEELONG = "Geelong";
    const UMPIRE_REGION_COLAC = "Colac";

    const UMPIRE_SEASON_2018 = 2018;

    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);

        $this->importDWData();
    }


    //TODO Move these to a common class
    private function importDWData() {
        if ($this->dataAlreadyImported() == false) {
            //Import data so that these report tests run on real data
            $this->import2018Data();
        }
    }

    private function dataAlreadyImported() {
        $queryString = "SELECT COUNT(*) AS rec_count FROM dw_mv_report_01 WHERE season_year = 2018;";
        $query = $this->dbLocal->query($queryString);
        $resultArrayReal = $query->result();

        $queryString = "SELECT COUNT(*) AS rec_count FROM backup_report_01_2018;";
        $query = $this->dbLocal->query($queryString);
        $resultArrayBackup = $query->result();

        $recordsInRealTable = $resultArrayReal[0]->rec_count;
        $recordsInBackupTable = $resultArrayBackup[0]->rec_count;
        return ($recordsInRealTable == $recordsInBackupTable);
    }

    private function import2018Data() {
        //Import data from each of the backup tables into the real table, using a range of "insert into selects"
        //Report 1
        $queryString = "DELETE FROM dw_mv_report_01 WHERE season_year = 2018;";
        $this->dbLocal->query($queryString);

        $queryString = "INSERT INTO dw_mv_report_01 (last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, match_count, season_year)
SELECT last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, match_count, season_year
FROM backup_report_01_2018;";
        $this->dbLocal->query($queryString);

        //Report 2
        $queryString = "DELETE FROM dw_mv_report_02 WHERE season_year = 2018;";
        $this->dbLocal->query($queryString);

        $queryString = "INSERT INTO dw_mv_report_02 (last_first_name, short_league_name, age_group, age_sort_order, league_sort_order, two_ump_flag, region_name, umpire_type, match_count, season_year)
SELECT last_first_name, short_league_name, age_group, age_sort_order, league_sort_order, two_ump_flag, region_name, umpire_type, match_count, season_year
FROM backup_report_02_2018;";
        $this->dbLocal->query($queryString);

        //Report 3
        $queryString = "DELETE FROM staging_no_umpires WHERE season_year = 2018;";
        $this->dbLocal->query($queryString);

        $queryString = "INSERT INTO staging_no_umpires (weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
SELECT weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year
FROM backup_report_03_2018;";
        $this->dbLocal->query($queryString);

        //Report 4
        $queryString = "DELETE FROM dw_mv_report_04 WHERE season_year = 2018;";
        $this->dbLocal->query($queryString);

        $queryString = "INSERT INTO dw_mv_report_04 (club_name, age_group, short_league_name, region_name, umpire_type, age_sort_order, league_sort_order, match_count, season_year)
SELECT club_name, age_group, short_league_name, region_name, umpire_type, age_sort_order, league_sort_order, match_count, season_year
FROM backup_report_04_2018;";
        $this->dbLocal->query($queryString);

        //Report 5
        $queryString = "DELETE FROM dw_mv_report_05 WHERE season_year = 2018;";
        $this->dbLocal->query($queryString);

        $queryString = "INSERT INTO dw_mv_report_05 (umpire_type, age_group, age_sort_order, short_league_name, league_sort_order, region_name, match_no_ump, total_match_count, match_pct, season_year)
SELECT umpire_type, age_group, age_sort_order, short_league_name, league_sort_order, region_name, match_no_ump, total_match_count, match_pct, season_year
FROM backup_report_05_2018;";
        $this->dbLocal->query($queryString);

        //Report 6
        $queryString = "DELETE FROM dw_mv_report_06 WHERE season_year = 2018;";
        $this->dbLocal->query($queryString);

        $queryString = "INSERT INTO dw_mv_report_06 (umpire_type, age_group, region_name, first_umpire, second_umpire, season_year, match_count, short_league_name)
SELECT umpire_type, age_group, region_name, first_umpire, second_umpire, season_year, match_count, short_league_name
FROM backup_report_06_2018;";
        $this->dbLocal->query($queryString);

        //Report 7
        $queryString = "DELETE FROM dw_mv_report_07 WHERE season_year = 2018;";
        $this->dbLocal->query($queryString);

        $queryString = "INSERT INTO dw_mv_report_07 (umpire_type, age_group, region_name, short_league_name, season_year, age_sort_order, league_sort_order, umpire_count, match_count)
SELECT umpire_type, age_group, region_name, short_league_name, season_year, age_sort_order, league_sort_order, umpire_count, match_count
FROM backup_report_07_2018;";
        $this->dbLocal->query($queryString);

        $queryString = "COMMIT;";
        $this->dbLocal->query($queryString);

    }


    //TODO: Move these to a separate file for testing report column queries
    private function createReportInstance($pArrayUmpireType, $pArrayAge, $pArrayShortLeague, $pArrayRegion) {
        $reportInstance = new Report_instance();
        $inputPDFMode = false;
        $reportInstance->filterParameterUmpireType->createFilterParameter($pArrayUmpireType, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterAgeGroup->createFilterParameter($pArrayAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($pArrayShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($pArrayRegion, $inputPDFMode, $inputRegion);
        $reportInstance->requestedReport->setSeason(self::UMPIRE_SEASON_2018);
        return $reportInstance;
    }

    public function test_Report2ColumnQuery_SingleParams() {
        $reportInstance = $this->createReportInstance(
            array(self::UMPIRE_FIELD), array(self::UMPIRE_AGE_SENIORS), array(self::UMPIRE_LEAGUE_GFL),
            array(self::UMPIRE_REGION_GEELONG));

        $currentReport = $this->CI->Report_factory->createReport(2);

        $queryString = $currentReport->getReportColumnQuery($reportInstance);
        $query = $this->dbLocal->query($queryString);

        $resultArrayActual = $query->result_array();
        $resultArrayExpected = array(
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>self::UMPIRE_LEAGUE_GFL),
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>'2 Umpires'),
            array('age_group'=>'Total', 'short_league_name'=>'')
        );

        $this->assertEquals($resultArrayExpected, $resultArrayActual);
    }

    public function test_Report2ColumnQuery_SingleParams_V2() {
        $reportInstance = $this->createReportInstance(
            array(self::UMPIRE_BOUNDARY), array(self::UMPIRE_AGE_SENIORS), array(self::UMPIRE_LEAGUE_GFL),
            array(self::UMPIRE_REGION_GEELONG));

        $currentReport = $this->CI->Report_factory->createReport(2);

        $queryString = $currentReport->getReportColumnQuery($reportInstance);
        $query = $this->dbLocal->query($queryString);

        $resultArrayActual = $query->result_array();
        $resultArrayExpected = array(
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>self::UMPIRE_LEAGUE_GFL),
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>'2 Umpires'),
            array('age_group'=>'Total', 'short_league_name'=>'')
        );

        $this->assertEquals($resultArrayExpected, $resultArrayActual);
    }

    public function test_Report2ColumnQuery_SingleParams_V3() {
        $reportInstance = $this->createReportInstance(
            array(self::UMPIRE_BOUNDARY), array(self::UMPIRE_AGE_RESERVES), array(self::UMPIRE_LEAGUE_BFL),
            array(self::UMPIRE_REGION_GEELONG));

        $currentReport = $this->CI->Report_factory->createReport(2);

        $queryString = $currentReport->getReportColumnQuery($reportInstance);
        $query = $this->dbLocal->query($queryString);

        $resultArrayActual = $query->result_array();
        $resultArrayExpected = array(
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>'2 Umpires'),
            array('age_group'=>self::UMPIRE_AGE_RESERVES, 'short_league_name'=>self::UMPIRE_LEAGUE_BFL),
            array('age_group'=>'Total', 'short_league_name'=>'')
        );
        //TODO: A known bug means the 2 Umpires row is shown in all reports, regardless of if Seniors is selected.
        //Fix the bug in the query in Report2.getReportColumnQuery.

        $this->assertEquals($resultArrayExpected, $resultArrayActual);
    }

    public function test_Report2ColumnQuery_SingleParams_V4() {
        $reportInstance = $this->createReportInstance(
            array(self::UMPIRE_FIELD), array(self::UMPIRE_AGE_U15), array(self::UMPIRE_LEAGUE_GJFL),
            array(self::UMPIRE_REGION_GEELONG));

        $currentReport = $this->CI->Report_factory->createReport(2);

        $queryString = $currentReport->getReportColumnQuery($reportInstance);
        $query = $this->dbLocal->query($queryString);

        $resultArrayActual = $query->result_array();
        $resultArrayExpected = array(
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>'2 Umpires'),
            array('age_group'=>self::UMPIRE_AGE_U15, 'short_league_name'=>self::UMPIRE_LEAGUE_GJFL),
            array('age_group'=>'Total', 'short_league_name'=>'')
        );
        //TODO: A known bug means the 2 Umpires row is shown in all reports, regardless of if Seniors is selected.
        //Fix the bug in the query in Report2.getReportColumnQuery.

        $this->assertEquals($resultArrayExpected, $resultArrayActual);
    }

    public function test_Report2ColumnQuery_MultipleParams() {
        $reportInstance = $this->createReportInstance(
            array(self::UMPIRE_FIELD, self::UMPIRE_BOUNDARY),
            array(self::UMPIRE_AGE_SENIORS, self::UMPIRE_AGE_RESERVES),
            array(self::UMPIRE_LEAGUE_GFL),
            array(self::UMPIRE_REGION_GEELONG));

        $currentReport = $this->CI->Report_factory->createReport(2);

        $queryString = $currentReport->getReportColumnQuery($reportInstance);
        $query = $this->dbLocal->query($queryString);

        $resultArrayActual = $query->result_array();
        $resultArrayExpected = array(
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>self::UMPIRE_LEAGUE_GFL),
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>'2 Umpires'),
            array('age_group'=>self::UMPIRE_AGE_RESERVES, 'short_league_name'=>self::UMPIRE_LEAGUE_GFL),
            array('age_group'=>'Total', 'short_league_name'=>'')
        );
        //TODO: A known bug means the 2 Umpires row is shown in all reports, regardless of if Seniors is selected.
        //Fix the bug in the query in Report2.getReportColumnQuery.

        $this->assertEquals($resultArrayExpected, $resultArrayActual);
    }

    public function test_Report2ColumnQuery_MultipleParams_V2() {
        $reportInstance = $this->createReportInstance(
            array(self::UMPIRE_FIELD, self::UMPIRE_BOUNDARY, self::UMPIRE_GOAL),
            array(self::UMPIRE_AGE_SENIORS, self::UMPIRE_AGE_RESERVES),
            array(self::UMPIRE_LEAGUE_GFL, self::UMPIRE_LEAGUE_BFL, self::UMPIRE_LEAGUE_GDFL),
            array(self::UMPIRE_REGION_GEELONG));

        $currentReport = $this->CI->Report_factory->createReport(2);

        $queryString = $currentReport->getReportColumnQuery($reportInstance);
        $query = $this->dbLocal->query($queryString);

        $resultArrayActual = $query->result_array();
        $resultArrayExpected = array(
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>self::UMPIRE_LEAGUE_BFL),
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>self::UMPIRE_LEAGUE_GFL),
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>self::UMPIRE_LEAGUE_GDFL),
            array('age_group'=>self::UMPIRE_AGE_SENIORS, 'short_league_name'=>'2 Umpires'),
            array('age_group'=>self::UMPIRE_AGE_RESERVES, 'short_league_name'=>self::UMPIRE_LEAGUE_BFL),
            array('age_group'=>self::UMPIRE_AGE_RESERVES, 'short_league_name'=>self::UMPIRE_LEAGUE_GFL),
            array('age_group'=>self::UMPIRE_AGE_RESERVES, 'short_league_name'=>self::UMPIRE_LEAGUE_GDFL),
            array('age_group'=>'Total', 'short_league_name'=>'')
        );
        //TODO: A known bug means the 2 Umpires row is shown in all reports, regardless of if Seniors is selected.
        //Fix the bug in the query in Report2.getReportColumnQuery.

        $this->assertEquals($resultArrayExpected, $resultArrayActual);
    }
}
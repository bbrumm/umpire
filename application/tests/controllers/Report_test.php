<?php
class Report_test extends TestCase {

    //TODO: Add these to some kind of global const file
    const UMPIRE_FIELD = "Field";
    const UMPIRE_BOUNDARY = "Boundary";
    const UMPIRE_GOAL = "Goal";

    const UMPIRE_AGE_SENIORS = "Seniors";

    const UMPIRE_LEAGUE_GFL = "GFL";

    const UMPIRE_REGION_GEELONG = "Geelong";

    const UMPIRE_SEASON_2018 = 2018;


    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);

        $this->importDWData();
    }

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

    public function test_Report1() {
        /*$queryString = "INSERT INTO dw_mv_report_01
(last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, match_count, season_year)
VALUES ('Smith, John', 'GFL', 'Clubname', 'Seniors', 'Geelong', 'Field', 3, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $postArray = array(
            'reportName'=>'1',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>01 - Umpires and Clubs (2018)</h1>";
        $this->assertContains($expected, $output);
        //Check that Create PDF is showing on header
        $linkName = "Create PDF";
        $expectedHeader = "<div class='menuBarLink'>". $linkName ."</div>";
        $this->assertContains($expectedHeader, $output);
    }

    public function test_Report2() {
        /*$queryString = "INSERT INTO dw_mv_report_02
(last_first_name, short_league_name, age_group, age_sort_order, league_sort_order, two_ump_flag, region_name, umpire_type, match_count, season_year)
VALUES ('Smith, John', 'GFL', 'Seniors', 1, 1, 0, 'Geelong', 'Field', 3, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $postArray = array(
            'reportName'=>'2',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>02 - Umpire Names by League (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report3() {
        /*$queryString = "INSERT INTO staging_no_umpires
(weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
VALUES ('2018-04-07 00:00:00', 'Seniors', 'Goal', 'Women', 'A vs B', 40001, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $queryString = "INSERT INTO staging_all_ump_age_league VALUES ('Seniors','Goal','Women','Geelong',1,1);";
        $query = $this->dbLocal->query($queryString);

        $postArray = array(
            'reportName'=>'3',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Goal'),
            'chkUmpireDisciplineHidden'=>'Goal',
            //'chkLeague'=>array('Women'),
            'chkLeagueHidden'=>'Women'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>03 - Summary by Week (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }
    
    public function test_Report3_TwoUmpireTypes() {
        /*$queryString = "INSERT INTO staging_no_umpires
(weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
VALUES ('2018-04-07 00:00:00', 'Seniors', 'Goal', 'Women', 'A vs B', 40001, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $queryString = "INSERT INTO staging_all_ump_age_league VALUES
        ('Seniors','Goal','Women','Geelong',1,1),
        ('Seniors','Field','Women','Geelong',1,1);";
        $query = $this->dbLocal->query($queryString);

        $postArray = array(
            'reportName'=>'3',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Goal'),
            'chkUmpireDisciplineHidden'=>'Goal,Field',
            //'chkLeague'=>array('Women'),
            'chkLeagueHidden'=>'Women'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>03 - Summary by Week (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }
    
    public function test_Report3_TwoUmpireTypesAndAges() {
        /*$queryString = "INSERT INTO staging_no_umpires
(weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
VALUES ('2018-04-07 00:00:00', 'Seniors', 'Goal', 'Women', 'A vs B', 40001, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $queryString = "INSERT INTO staging_all_ump_age_league VALUES
        ('Seniors','Goal','Women','Geelong',1,1),
        ('Seniors','Field','Women','Geelong',1,1),
        ('Reserves','Field','Women','Geelong',1,1);";
        $query = $this->dbLocal->query($queryString);

        $postArray = array(
            'reportName'=>'3',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors,Reserves',
            //'chkUmpireDiscipline'=>array('Goal'),
            'chkUmpireDisciplineHidden'=>'Goal,Field',
            //'chkLeague'=>array('Women'),
            'chkLeagueHidden'=>'Women'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>03 - Summary by Week (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }
    
     public function test_Report3_TwoUmpireTypesAndAgesAndLeagues() {
        /*$queryString = "INSERT INTO staging_no_umpires
(weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
VALUES ('2018-04-07 00:00:00', 'Seniors', 'Goal', 'Women', 'A vs B', 40001, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $queryString = "INSERT INTO staging_all_ump_age_league VALUES
        ('Seniors','Goal','Women','Geelong',1,1),
        ('Seniors','Field','Women','Geelong',1,1),
        ('Seniors','Field','Women,GFL','Geelong',1,1),
        ('Reserves','Field','Women','Geelong',1,1);";
        $query = $this->dbLocal->query($queryString);

        $postArray = array(
            'reportName'=>'3',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors,Reserves',
            //'chkUmpireDiscipline'=>array('Goal'),
            'chkUmpireDisciplineHidden'=>'Goal,Field',
            //'chkLeague'=>array('Women'),
            'chkLeagueHidden'=>'Women,GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>03 - Summary by Week (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }
    
    public function test_Report3_ThreeOfSeveralParameters() {
        /*$queryString = "INSERT INTO staging_no_umpires
(weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
VALUES ('2018-04-07 00:00:00', 'Seniors', 'Goal', 'Women', 'A vs B', 40001, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $queryString = "INSERT INTO staging_all_ump_age_league VALUES
        ('Seniors','Goal','Women','Geelong',1,1),
        ('Seniors','Field','Women','Geelong',1,1),
        ('Seniors','Field','GFL','Geelong',1,1),
        ('Seniors','Field','GDFL','Geelong',1,1),
        ('Seniors','Field','BFL','Geelong',1,1),
        ('Seniors','Boundary','BFL','Geelong',1,1),
        ('Under 16','Field','BFL','Geelong',1,1),
        ('Seniors','Field','Women,GFL','Geelong',1,1),
        ('Reserves','Field','Women','Geelong',1,1);";
        $query = $this->dbLocal->query($queryString);

        $postArray = array(
            'reportName'=>'3',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors,Reserves,Under 16',
            //'chkUmpireDiscipline'=>array('Goal'),
            'chkUmpireDisciplineHidden'=>'Goal,Field,Boundary',
            //'chkLeague'=>array('Women'),
            'chkLeagueHidden'=>'Women,GFL,BFL,GDFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>03 - Summary by Week (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report4() {
        /*$queryString = "INSERT INTO dw_mv_report_04
(club_name, age_group, short_league_name, region_name, umpire_type, age_sort_order, league_sort_order, match_count, season_year)
VALUES ('Club name', 'Seniors', 'GFL', 'Geelong', 'Field', 1, 1, 5, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $postArray = array(
            'reportName'=>'4',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field,Goal,Boundary',
            //'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'BFL,GFL,GDFL,GJFL,CDFNL,Women'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>04 - Summary by Club (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report5() {
        /*$queryString = "INSERT INTO dw_mv_report_05
(umpire_type, age_group, age_sort_order, short_league_name, league_sort_order, region_name, match_no_ump, total_match_count, match_pct, season_year)
VALUES ('Field', 'Seniors', 1, 'GFL', 2, 'Geelong', 4, 8, 50, 2018);";
        $query = $this->dbLocal->query($queryString);
        */
        $postArray = array(
            'reportName'=>'5',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors, Reserves, Colts, Under 19, Under 17.5, Under 17, Under 16, Under 15, Under 14.5, Under 14, Under 13, Under 12, Under 19 Girls, Under 18 Girls, Under 15 Girls, Under 12 Girls, Youth Girls, Junior Girls',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field, Boundary, Goal',
            //'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'BFL,GFL,GDFL,GJFL,CDFNL,Women'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>05 - Games with Zero Umpires For Each League (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report6() {
        /*$queryString = "INSERT INTO dw_mv_report_06
(umpire_type, age_group, region_name, first_umpire, second_umpire, season_year, match_count, short_league_name)
VALUES ('Field', 'Seniors', 'Geelong', 'Smith, John', 'Jones, Susan', 2018, 6, 'GFL');";
        $query = $this->dbLocal->query($queryString);
        */
        $postArray = array(
            'reportName'=>'6',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            //'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>06 - Umpire Pairing (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report7() {
        /*$queryString = "INSERT INTO dw_mv_report_07
(umpire_type, age_group, region_name, short_league_name, season_year, age_sort_order, league_sort_order, umpire_count, match_count)
VALUES ('Field', 'Seniors', 'Geelong', 'GFL', 2018, 1, 1, 2, 6);";
        $query = $this->dbLocal->query($queryString);
        */
        $postArray = array(
            'reportName'=>'7',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>07 - Games with 2 or 3 Field Umpires (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_Report8() {
        $postArray = array(
            'reportName'=>'8',
            'season'=>2018,
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors',
            'chkUmpireDisciplineHidden'=>'Field',
            'chkLeagueHidden'=>'GFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>08 - Umpire Games Tally</h1>";
        $this->assertContains($expected, $output);
    }
    
    public function test_Report8_MultiParam() {
        $postArray = array(
            'reportName'=>'8',
            'season'=>2018,
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves',
            'chkUmpireDisciplineHidden'=>'Field,Goal,Boundary',
            'chkLeagueHidden'=>'GFL,BFL,GDFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>08 - Umpire Games Tally</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_ReportNumberNotFound() {
        $this->expectException(Exception::class);
        $postArray = array(
            'reportName'=>'12',
            'season'=>2018,
            //'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            //'chkAgeGroup'=>array('Seniors'),
            'chkAgeGroupHidden'=>'Seniors',
            //'chkUmpireDiscipline'=>array('Field'),
            'chkUmpireDisciplineHidden'=>'Field',
            //'chkLeague'=>array('GFL'),
            'chkLeagueHidden'=>'GFL'
        );

        //Load page
        //This is done to prevent this error message:
        //"Test code or tested code did not (only) close its own output buffers"
        try {
            $output = $this->request('POST', ['Report', 'index'], $postArray);
        } finally {
            $output = ob_get_clean();
        }
    }

    //TODO: Move these to a separate file for testing report column queries
    private function createReportInstance($pUmpireType, $pAge, $pShortLeague, $pRegion, $pSeasonYear) {
        $reportInstance = new Report_instance();
        $inputFilterUmpire = array($pUmpireType);
        $inputFilterAge = array($pAge);
        $inputFilterShortLeague = array($pShortLeague);
        $inputFilterRegion = array($pRegion);
        $inputSeasonYear = $pSeasonYear;
        $inputPDFMode = false;
        $inputRegion = false;
        $reportInstance->filterParameterUmpireType->createFilterParameter($inputFilterUmpire, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterAgeGroup->createFilterParameter($inputFilterAge, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterLeague->createFilterParameter($inputFilterShortLeague, $inputPDFMode, $inputRegion);
        $reportInstance->filterParameterRegion->createFilterParameter($inputFilterRegion, $inputPDFMode, $inputRegion);
        $reportInstance->requestedReport->setSeason($inputSeasonYear);
        return $reportInstance;
    }

    public function test_Report2ColumnQuery_SingleParams() {
        $reportInstance = $this->createReportInstance(
            self::UMPIRE_FIELD, self::UMPIRE_AGE_SENIORS, self::UMPIRE_LEAGUE_GFL,
            self::UMPIRE_REGION_GEELONG, self::UMPIRE_SEASON_2018);

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

}

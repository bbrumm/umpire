<?php
/*
 * 2018_03_11
 * This code was split out from the RunETLProcess stored procedure in MySQL due to performance issues.
 * There are some issues with populating the dw_rpt06_stg2 table using a stored procedure,
 * where it takes 60 seconds inside a stored proc, compared to 0.2 seconds as a standard query.
 * This breaks the 60 second timeout on the server, as well as a poor user experience
 * So this model object will run the commands to refresh these tables as individual SQL queries.
 */

/*
* @property Object db
*/
class Refresh_mv_tables extends CI_Model {

    private $etlHelper;

    function __construct() {
        parent::__construct();
        $this->load->model('Season');
        $this->load->model('Etl_procedure_steps');
        //$this->load->model('Etl_helper');
        $this->load->model('Simple_report_table_refresher');
        //TODO: create a factory class that creates these reports
        $this->load->model('Report1_refresher');
        $this->etlHelper = new Report_table_refresher();
    }
    
    //TODO: A lot of this code is duplicated in model/Etl_procedure_steps.
    //Not the report tables, but the code to run queries and delete data
    public function refreshMVTables(IData_store_matches $pDataStore, $season, $importedFileID) {
        if (is_a($pDataStore, 'Array_store_matches')) {
            //TODO remove this once I have refactored this code
        } else {
            $seasonYear = $this->getSeasonYear($season);
            $this->refreshMVTable1($seasonYear, $importedFileID);
            $this->refreshMVTable2($seasonYear, $importedFileID);
            $this->refreshMVTable4($seasonYear, $importedFileID);
            $this->refreshMVTable5($seasonYear, $importedFileID);
            $this->refreshMVTable6($seasonYear, $importedFileID);
            $this->refreshMVTable7($seasonYear, $importedFileID);
            $this->refreshMVTable8($seasonYear, $importedFileID);
        }
        
    }
    
    /*
    * @property array $db
    */
    private function getSeasonYear($pSeason) {
        $queryString = "SELECT MAX(season_year) AS season_year
            FROM season
            WHERE id = " . $pSeason->getSeasonID() . ";";
            $query = $this->db->query($queryString);
        
        $queryResultArray = $query->result_array();
        return $queryResultArray[0]['season_year'];
    }

    //TODO: Replace all of these table names with variables
    private function refreshMVTable1($pSeasonYear, $importedFileID) {
        $reportTableRefresher = new Report1_refresher()
        $reportTableRefresher->initialiseData("dw_mv_report_01", $pSeasonYear, $importedFileID);
        $reportTableRefresher->refreshMVTable();
        
        //$reportTableRefresher = Simple_report_table_refresher::createRefresher("dw_mv_report_01", $importedFileID, $pSeasonYear);
        //$reportTableRefresher->setDataRefreshQuery($this->getUpdateMV1Query($pSeasonYear));
        //$reportTableRefresher->refreshMVTable();
    }
    
    private function refreshMVTable2($pSeasonYear, $importedFileID) {
        $reportTableRefresher = Simple_report_table_refresher::createRefresher("dw_mv_report_02", $importedFileID, $pSeasonYear);
        //$reportTableRefresher->setTableName("dw_mv_report_02");
        $reportTableRefresher->setDataRefreshQuery($this->getUpdateMV2Query($pSeasonYear));
        $reportTableRefresher->refreshMVTable();
    }
    
    private function refreshMVTable4($pSeasonYear, $importedFileID) {
        $reportTableRefresher = Simple_report_table_refresher::createRefresher("dw_mv_report_04", $importedFileID, $pSeasonYear);
        //$reportTableRefresher->setTableName("dw_mv_report_04");
        $reportTableRefresher->setDataRefreshQuery($this->getUpdateMV4Query($pSeasonYear));
        $reportTableRefresher->refreshMVTable();
    }
    
    private function refreshMVTable5($pSeasonYear, $importedFileID) {
        $reportTableRefresher = Simple_report_table_refresher::createRefresher("dw_mv_report_05", $importedFileID, $pSeasonYear);
        //$reportTableRefresher->setTableName("dw_mv_report_05");
        $reportTableRefresher->setDataRefreshQuery($this->getUpdateMV5Query($pSeasonYear));
        $reportTableRefresher->refreshMVTable();
    }
    
    private function refreshMVTable6($pSeasonYear, $importedFileID) {
        $this->etlHelper->disableKeys("dw_rpt06_staging");
        $this->updateTableMV6Staging($pSeasonYear);
        $this->etlHelper->logTableInsertOperation("dw_rpt06_staging", $importedFileID);
        $this->etlHelper->enableKeys("dw_rpt06_staging");
        
        //TODO: Move all of these function calls into a single function, and call the new function. Lots of repetition here.
        //TODO: Change this to call separate stored procedures. Even though the large stored proc took a long time to run,
        //having a short stored proc would make it easier to rerun the steps if the import times out on the production server
        //I should store the stored proc names and their run order in a database table, and loop through the list.
        
        //Run each of the umpire types into the staging 2 table
        //TODO: Remove the repeated calls to enable and disable keys
        $this->etlHelper->disableKeys("dw_rpt06_stg2");
        $this->updateTableMV6StagingPart2($pSeasonYear, "Field");
        $this->etlHelper->logTableInsertOperation("dw_rpt06_stg2", $importedFileID);

        $this->updateTableMV6StagingPart2($pSeasonYear, "Goal");
        $this->etlHelper->logTableInsertOperation("dw_rpt06_stg2", $importedFileID);

        $this->updateTableMV6StagingPart2($pSeasonYear, "Boundary");
        $this->etlHelper->logTableInsertOperation("dw_rpt06_stg2", $importedFileID);

        
        //Now, insert into the staging table the opposite combination

        $this->updateTableMV6StagingPart2Opposite();
        $this->etlHelper->logTableInsertOperation("dw_rpt06_stg2", $importedFileID);
        $this->etlHelper->enableKeys("dw_rpt06_stg2");


        $this->etlHelper->disableKeys("dw_mv_report_06");
        $this->deleteFromDWTableForYear("dw_mv_report_06", $pSeasonYear);
        $this->etlHelper->logTableDeleteOperation("dw_mv_report_06", $importedFileID);
        $this->updateTableMV6($pSeasonYear);
        $this->etlHelper->logTableInsertOperation("dw_mv_report_06", $importedFileID);
        $this->etlHelper->enableKeys("dw_mv_report_06");
    }

    private function refreshMVTable7($pSeasonYear, $importedFileID) {
        $this->updateTableMV7Staging($pSeasonYear);
        $this->etlHelper->logTableInsertOperation("mv_report_07_stg1", $importedFileID);

        $this->etlHelper->disableKeys("dw_mv_report_07");
        $this->deleteFromDWTableForYear("dw_mv_report_07", $pSeasonYear);
        $this->etlHelper->logTableDeleteOperation("dw_mv_report_07", $importedFileID);
        $this->updateTableMV7($pSeasonYear);
        $this->etlHelper->logTableInsertOperation("dw_mv_report_07", $importedFileID);
        $this->etlHelper->enableKeys("dw_mv_report_07");
    }

    private function refreshMVTable8($pSeasonYear, $importedFileID) {
        $reportTableRefresher = Simple_report_table_refresher::createRefresher("dw_mv_report_08", $importedFileID, $pSeasonYear);
        $reportTableRefresher->setDataRefreshQuery($this->getUpdateMV5Query($pSeasonYear));
        
        $this->deleteFromDW8Table($pSeasonYear);
        
        $reportTableRefresher->refreshMVTable();
        
        /*
        $this->etlHelper->disableKeys("dw_mv_report_08");
        
        $this->deleteFromDWTableForYear("dw_mv_report_08", $pSeasonYear);
        $this->etlHelper->logTableDeleteOperation("dw_mv_report_08", $importedFileID);
        $this->updateTableMV8($pSeasonYear);
        $this->etlHelper->logTableInsertOperation("dw_mv_report_08", $importedFileID);
        */

        $this->updateTableMV8Totals();
        $this->etlHelper->logTableUpdateOperation("dw_mv_report_08", $importedFileID);
        $this->etlHelper->enableKeys("dw_mv_report_08");
    }

    //TODO refactor this process. This comes from another object so I should get the query from that object
    private function deleteFromDW8Table($pSeasonYear) {
        $queryString = "DELETE rec FROM dw_mv_report_08 rec 
WHERE rec.season_year IN(CONVERT(". $pSeasonYear .", CHAR), 'Games Other Leagues', 'Games Prior', 'Other Years');";
        $this->runQuery($queryString);
    }
    
    /*
    * @property array $this->db
    */
    private function runQuery($queryString) {
        return $this->db->query($queryString);
    }

    private function deleteFromDWTableForYear($pTableName, $pSeasonYear) {
        $queryString = "DELETE FROM " . $pTableName . " WHERE season_year = " . $pSeasonYear;
        $this->runQuery($queryString);
    }

    //TODO: Move these to Etl_query_builder object

    /*
    * @property array $this->db
    */
    private function getUpdateMV1Query($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_01 (last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, season_year, match_count)
            SELECT
            u.last_first_name,
            l.short_league_name,
            te.club_name,
            a.age_group,
            l.region_name,
            u.umpire_type,
            ti.date_year,
            COUNT(DISTINCT m.match_id) AS match_count
            FROM dw_fact_match m
            INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
            WHERE ti.date_year = $pSeasonYear
            GROUP BY u.last_first_name, l.short_league_name, te.club_name, a.age_group, l.region_name, u.umpire_type, ti.date_year;";
        return $queryString;
    }
    
    
    private function getUpdateMV2Query($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_02 (last_first_name, short_league_name, age_group, age_sort_order, league_sort_order, two_ump_flag, region_name, umpire_type, season_year, match_count)
            SELECT
            u.last_first_name,
            l.short_league_name,
            a.age_group,
            a.sort_order,
            l.league_sort_order,
            0 AS two_ump_flag,
            l.region_name,
            u.umpire_type,
            ti.date_year,
            COUNT(DISTINCT m.match_id) AS match_count
            FROM dw_fact_match m
            INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
            WHERE ti.date_year = $pSeasonYear
            GROUP BY u.last_first_name, l.short_league_name, a.age_group, a.sort_order, l.region_name, u.umpire_type, ti.date_year
            UNION ALL
            SELECT
            u.last_first_name,
            l.short_league_name,
            a.age_group,
            a.sort_order,
            l.league_sort_order,
            1 AS two_ump_flag,
            l.region_name,
            u.umpire_type,
            ti.date_year,
            COUNT(DISTINCT m.match_id) AS match_count
            FROM dw_fact_match m
            INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
            INNER JOIN (
            	SELECT
            	m2.match_id,
            	COUNT(DISTINCT u2.umpire_key) AS umpire_count
            	FROM dw_fact_match m2
            	INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
            	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
            	WHERE u2.umpire_type = 'Field'
            	AND a2.age_group = 'Seniors'
            	GROUP BY m2.match_id
            	HAVING COUNT(DISTINCT u2.umpire_key) = 2
            	) AS qryMatchesWithTwoUmpires ON m.match_id = qryMatchesWithTwoUmpires.match_id
            WHERE u.umpire_type = 'Field'
            AND a.age_group = 'Seniors'
            AND ti.date_year = $pSeasonYear
            GROUP BY u.last_first_name, l.short_league_name, a.age_group, a.sort_order, l.region_name, u.umpire_type, ti.date_year;";
        return $queryString;
    }
    
    private function getUpdateMV4Query($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_04 (club_name, age_group, short_league_name, umpire_type, age_sort_order, league_sort_order, match_count, season_year, region_name)
        SELECT
        te.club_name,
        a.age_group,
        l.short_league_name,
        'Field' AS umpire_type,
        a.sort_order,
        l.league_sort_order,
        COUNT(DISTINCT match_id) AS match_count,
        ti.date_year,
        l.region_name
        FROM dw_fact_match m
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        WHERE m.match_id IN (
        	SELECT match_id
        	FROM staging_no_umpires s
        	WHERE s.umpire_type = 'Field'
        	AND s.short_league_name = l.short_league_name
        	AND s.age_group = a.age_group
        )
        AND ti.date_year = $pSeasonYear
        GROUP BY te.club_name, a.age_group, l.short_league_name
        UNION ALL
        SELECT
        te.club_name,
        a.age_group,
        l.short_league_name,
        'Goal' AS umpire_type,
        a.sort_order,
        l.league_sort_order,
        COUNT(DISTINCT match_id) AS match_count,
        ti.date_year,
        l.region_name
        FROM dw_fact_match m
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        WHERE m.match_id IN (
        	SELECT match_id
        	FROM staging_no_umpires s
        	WHERE s.umpire_type = 'Goal'
        	AND s.short_league_name = l.short_league_name
        	AND s.age_group = a.age_group
        )
        AND ti.date_year = $pSeasonYear
        GROUP BY te.club_name, a.age_group, l.short_league_name
        UNION ALL
        SELECT
        te.club_name,
        a.age_group,
        l.short_league_name,
        'Boundary' AS umpire_type,
        a.sort_order,
        l.league_sort_order,
        COUNT(DISTINCT match_id) AS match_count,
        ti.date_year,
        l.region_name
        FROM dw_fact_match m
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        WHERE m.match_id IN (
        	SELECT match_id
        	FROM staging_no_umpires s
        	WHERE s.umpire_type = 'Boundary'
        	AND s.short_league_name = l.short_league_name
        	AND s.age_group = a.age_group
        )
        AND ti.date_year = $pSeasonYear
        GROUP BY te.club_name, a.age_group, l.short_league_name;";
        
        return $queryString;
    }
    
    private function getUpdateMV5Query($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_05 (umpire_type, age_group, age_sort_order, short_league_name, league_sort_order, region_name, match_no_ump, total_match_count, match_pct, season_year) 
        SELECT 
        ua.umpire_type,
        ua.age_group,
        ua.age_sort_order,
        ua.short_league_name,
        ua.league_sort_order,
        ua.region_name,
        IFNULL(sub_match_count.match_count, 0) AS match_no_ump,
        IFNULL(sub_total_matches.total_match_count, 0) AS total_match_count,
        IFNULL(FLOOR(sub_match_count.match_count / sub_total_matches.total_match_count * 100),0) AS match_pct,
        sub_total_matches.date_year
        FROM (
            SELECT 
        	umpire_type,
        	age_group,
        	short_league_name,
        	age_sort_order,
        	league_sort_order,
        	region_name
            FROM staging_all_ump_age_league
        ) AS ua
        LEFT JOIN (
        	SELECT 
        	a.age_group,
        	l.short_league_name,
        	a.sort_order,
        	l.league_sort_order,
        	ti.date_year,
        	COUNT(DISTINCT match_id) AS total_match_count
        	FROM dw_fact_match m
        	INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        	INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        	INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        	GROUP BY a.age_group , l.short_league_name , a.sort_order , l.league_sort_order, ti.date_year
        ) AS sub_total_matches
        ON ua.age_group = sub_total_matches.age_group
        AND ua.short_league_name = sub_total_matches.short_league_name
        LEFT JOIN (
        	SELECT 
        	umpire_type,
        	age_group,
        	short_league_name,
        	COUNT(s.match_id) AS Match_Count
        	FROM staging_no_umpires s
            WHERE s.season_year = $pSeasonYear
        	GROUP BY umpire_type , age_group , short_league_name
        ) AS sub_match_count
        ON ua.umpire_type = sub_match_count.umpire_type
        AND ua.age_group = sub_match_count.age_group
        AND ua.short_league_name = sub_match_count.short_league_name
        WHERE sub_total_matches.date_year = $pSeasonYear;";
        return $queryString;
    }
    
    private function updateTableMV6Staging($pSeasonYear) {
        $queryString = "INSERT INTO dw_rpt06_staging (umpire_key, umpire_type, last_first_name, match_id, date_year, league_key, age_group, region_name, short_league_name)
            SELECT DISTINCT
            u.umpire_key,
            u.umpire_type,
            u.last_first_name,
            m.match_id,
            dti.date_year,
            m.league_key,
            a.age_group,
            l.region_name,
            l.short_league_name
            FROM dw_fact_match m
            INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
            INNER JOIN dw_dim_time dti ON m.time_key = dti.time_key
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            WHERE dti.date_year = $pSeasonYear;";
        $this->runQuery($queryString);
    }

    private function updateTableMV6StagingPart2($pSeasonYear, $pUmpireType) {
        $queryString = "INSERT INTO dw_rpt06_stg2 (umpire_type, age_group, region_name, short_league_name, first_umpire, second_umpire, date_year, match_id)
            SELECT DISTINCT
            s.umpire_type,
            s.age_group,
            s.region_name,
            s.short_league_name,
            s.last_first_name,
            s2.last_first_name,
            s.date_year,
            s.match_id
            FROM dw_rpt06_staging s
            INNER JOIN dw_rpt06_staging s2 ON s.match_id = s2.match_id
                AND s.umpire_type = s2.umpire_type
                AND s.umpire_key <> s2.umpire_key
                AND s.league_key = s2.league_key
            WHERE s.umpire_type = '". $pUmpireType ."'
            AND s.last_first_name > s2.last_first_name;";
        $this->runQuery($queryString);
    }
    
    private function updateTableMV6StagingPart2Opposite() {
        //Note: The first and second umpire fields are deliberately swapped.
        //This is because the original query only inserts a single combination, and this query inserts the second combination.
        $queryString = "INSERT INTO dw_rpt06_stg2 (umpire_type, age_group, region_name, short_league_name, first_umpire, second_umpire, date_year, match_id)
            SELECT 
            s.umpire_type,
            s.age_group,
            s.region_name,
            s.short_league_name,
            s.second_umpire,
            s.first_umpire,
            s.date_year,
            s.match_id
            FROM dw_rpt06_stg2 s";
        $this->runQuery($queryString);
    }

    private function updateTableMV6($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_06 (umpire_type, age_group, region_name, short_league_name, first_umpire, second_umpire, season_year, match_count)
        SELECT
        s.umpire_type,
        s.age_group,
        s.region_name,
        s.short_league_name,
        s.first_umpire,
        s.second_umpire,
        s.date_year,
        COUNT(DISTINCT s.match_id) AS match_count
        FROM dw_rpt06_stg2 s
        GROUP BY s.umpire_type, s.age_group, s.region_name, s.short_league_name, s.first_umpire, s.second_umpire, s.date_year;";
        $this->runQuery($queryString);
    }

    private function updateTableMV7Staging($pSeasonYear) {
        $queryString = "TRUNCATE TABLE mv_report_07_stg1;";
        $this->runQuery($queryString);

        $queryString = "INSERT INTO mv_report_07_stg1(match_id, umpire_type, age_group, short_league_name, umpire_key, region_name, sort_order, league_sort_order)
SELECT
m2.match_id,
u2.umpire_type,
a2.age_group,
l2.short_league_name,
u2.umpire_key,
l2.region_name,
a2.sort_order,
l2.league_sort_order
FROM dw_fact_match m2
INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
INNER JOIN dw_dim_time ti2 ON m2.time_key = ti2.time_key
WHERE ti2.date_year = $pSeasonYear;";

        $this->runQuery($queryString);
    }

    private function updateTableMV7($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_07 (umpire_type, age_group, region_name, short_league_name, season_year, age_sort_order, league_sort_order, umpire_count, match_count)
            SELECT
            m.umpire_type,
            m.age_group,
            m.region_name,
            m.short_league_name,
            $pSeasonYear AS season_year,
            m.sort_order AS age_sort_order,
            m.league_sort_order,
            CONCAT(sub.umpire_count, ' Umpires') AS umpire_count,
            COUNT(DISTINCT sub.match_id) AS match_count
            FROM mv_report_07_stg1 m
            INNER JOIN (
                SELECT
                s.match_id,
                s.umpire_type,
                s.age_group,
                s.short_league_name,
                COUNT(DISTINCT s.umpire_key) AS umpire_count
                FROM mv_report_07_stg1 s
                GROUP BY s.match_id,  s.umpire_type, s.age_group, s.short_league_name
                HAVING COUNT(DISTINCT s.umpire_key) IN (2, 3)
                
            ) AS sub
            ON m.match_id = sub.match_id
            AND m.umpire_type = sub.umpire_type
            AND m.age_group = sub.age_group
            GROUP BY m.short_league_name, m.age_group, m.region_name, m.umpire_type, m.sort_order, sub.umpire_count, m.league_sort_order;";
        $this->runQuery($queryString);
    }

    private function getUpdateMV8Query($pSeasonYear) {
        //Use the baseline data if the imported year is not 2018
        //This is because annual report/baseline data is more correct than the master spreadsheets
        if ($pSeasonYear <= 2017) {
            $queryString = "INSERT INTO dw_mv_report_08 (season_year, full_name, match_count, last_name, first_name)
                SELECT
                '$pSeasonYear',
                CONCAT(b.last_name, ', ', b.first_name),
                b.games_$pSeasonYear,
                b.last_name,
                b.first_name
                FROM umpire_match_baseline b
                UNION ALL
                SELECT DISTINCT
                'Games Prior',
                u.last_first_name,
                u.games_prior,
                u.last_name,
                u.first_name
                FROM dw_dim_umpire u
                UNION ALL
                SELECT DISTINCT
                'Games Other Leagues',
                u.last_first_name,
                u.games_other_leagues,
                u.last_name,
                u.first_name
                FROM dw_dim_umpire u;";
        } else {
            $queryString = "INSERT INTO dw_mv_report_08 (season_year, full_name, match_count, last_name, first_name)
                SELECT
                ti.date_year,
                u.last_first_name,
                COUNT(DISTINCT m.match_id) AS match_count,
                u.last_name,
                u.first_name
                FROM dw_fact_match m
                INNER JOIN dw_dim_league l ON m.league_key = l.league_key
                INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
                INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
                WHERE ti.date_year = $pSeasonYear
                GROUP BY ti.date_year, u.first_name, u.last_name
                UNION ALL
                SELECT DISTINCT
                'Games Prior',
                u.last_first_name,
                u.games_prior,
                u.last_name,
                u.first_name
                FROM dw_dim_umpire u
                UNION ALL
                SELECT DISTINCT
                'Games Other Leagues',
                u.last_first_name,
                u.games_other_leagues,
                u.last_name,
                u.first_name
                FROM dw_dim_umpire u;";
        }
        return $queryString;
    }

    private function updateTableMV8Totals() {
        $queryString = "UPDATE dw_mv_report_08 d
            INNER JOIN (
                SELECT s.full_name,
                SUM(s.match_count) AS total_match_count
                FROM dw_mv_report_08 s
                GROUP BY s.full_name
            ) AS grp ON d.full_name = grp.full_name
            SET d.total_match_count = grp.total_match_count;";
        $this->runQuery($queryString);
    }
}
    

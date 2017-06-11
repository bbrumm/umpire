<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Datatestmodel extends CI_Model
{
    /* Code .. */
    
    var $debugMode;

    public function __construct()
    {
        //parent::__construct();
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->library('Array_library');
        
        
        $this->load->model("UmpireMatchRecord");
    }
    
    public function runAllTests() {
        /*Structure
         * Get a list of unique umpires, from the match_import table, because that table is the raw data and it could have been lost along the way.
         * Also get a unique list of teams
         * Then, run tests for report 1:
         * For each umpire in the list, count the number of matches for the team and the competition_name. Count them by umpire type.
         * Translate the competition name into the league and age group.
         * The output from this should be: umpire name, age group, umpire type, league, team name, count.
         * Then, run the same test on the MV_report_01 table. Input the umpire name, age group, umpire type, league, team name.
         * Get the count that is returned (which could be 0 or no results found)
         * 
         * Output the umpire data and the count for each check.
         * Repeat these for all umpires.
         * This is the end of the test for report 1.
         *
         */
         
        $this->debugMode = $this->config->item('debug_mode');
        $outputArray['tableOperations'] = $this->checkImportedTableOperations();
        $outputArray['report01'] = $this->runTestsForReport01();
        
        return $outputArray;
         
    }
    
    private function runTestsForReport01() {
        $this->refreshTempTables();
        
        $umpireMatchCountArray = $this->getUmpireMatchCountForReport01();
        return $umpireMatchCountArray;
    }
    
    private function refreshTempTables() {
        $queryResultArray = array();
        
        $queryString = "TRUNCATE TABLE test_mi_all;";
        
        $query = $this->db->query($queryString);
        //$query->free_result();
        
        $queryString = "INSERT INTO test_mi_all (match_import_id, season, round, date, competition_name, ground, time, team, umpire_name, umpire_type)
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.field_umpire_1, 'Field'
FROM match_import m
WHERE m.field_umpire_1 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.field_umpire_1, 'Field'
FROM match_import m
WHERE m.field_umpire_1 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.field_umpire_2, 'Field'
FROM match_import m
WHERE m.field_umpire_2 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.field_umpire_2, 'Field'
FROM match_import m
WHERE m.field_umpire_2 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.field_umpire_3, 'Field'
FROM match_import m
WHERE m.field_umpire_3 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.field_umpire_3, 'Field'
FROM match_import m
WHERE m.field_umpire_3 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.boundary_umpire_1, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_1 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.boundary_umpire_1, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_1 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.boundary_umpire_2, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_2 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.boundary_umpire_2, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_2 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.boundary_umpire_3, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_3 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.boundary_umpire_3, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_3 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.boundary_umpire_4, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_4 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.boundary_umpire_4, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_4 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.boundary_umpire_5, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_5 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.boundary_umpire_5, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_5 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.boundary_umpire_6, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_6 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.boundary_umpire_6, 'Boundary'
FROM match_import m
WHERE m.boundary_umpire_6 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.goal_umpire_1, 'Goal'
FROM match_import m
WHERE m.goal_umpire_1 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.goal_umpire_1, 'Goal'
FROM match_import m
WHERE m.goal_umpire_1 IS NOT NULL

UNION ALL

SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.home_team, m.goal_umpire_2, 'Goal'
FROM match_import m
WHERE m.goal_umpire_2 IS NOT NULL
UNION ALL
SELECT m.id, m.season, m.round, m.date, m.competition_name, m.ground, m.time, m.away_team, m.goal_umpire_2, 'Goal'
FROM match_import m
WHERE m.goal_umpire_2 IS NOT NULL;";
        
        $query = $this->db->query($queryString);
        //$query->free_result();
        
        
    }
    
    
    private function getUmpireMatchCountForReport01() {
        $queryResultArray = array();
        
        $queryString = "SELECT umpire_name, club_name, region_name,
mi_sum_field, mi_sum_boundary, mi_sum_goal, mv_sum_field, mv_sum_boundary, mv_sum_goal, q_check
FROM (
SELECT
mi_data.umpire_name,
mi_data.club_name,
mi_data.region_name,
mi_data.sum_field AS mi_sum_field,
mi_data.sum_boundary AS mi_sum_boundary,
mi_data.sum_goal AS mi_sum_goal,
mv.sum_field AS mv_sum_field,
mv.sum_boundary AS mv_sum_boundary,
mv.sum_goal AS mv_sum_goal,
CASE WHEN (
	mi_data.sum_field = mv.sum_field AND
	mi_data.sum_boundary = mv.sum_boundary AND
	mi_data.sum_goal = mv.sum_goal
) THEN 'PASS' ELSE 'FAIL' END AS q_check


FROM (

	SELECT
	mi.umpire_name,
	c.club_name,
	CASE WHEN INSTR(mi.competition_name, 'CDFNL') > 0 THEN 'Colac' ELSE 'Geelong' END AS region_name,
	SUM(
		CASE WHEN umpire_type = 'Field' THEN 1 ELSE 0 END
	) AS sum_field,
	SUM(
		CASE WHEN umpire_type = 'Boundary' THEN 1 ELSE 0 END
	) AS sum_boundary,
	SUM(
		CASE WHEN umpire_type = 'Goal' THEN 1 ELSE 0 END
	) AS sum_goal
	FROM test_mi_all mi
    LEFT JOIN team t ON t.team_name = mi.team
    LEFT JOIN club c ON t.club_id = c.id
	GROUP BY mi.umpire_name, c.club_name, CASE WHEN INSTR(mi.competition_name, 'CDFNL') > 0 THEN 'Colac' ELSE 'Geelong' END
	) AS mi_data
    

LEFT JOIN (

	SELECT club_name, region_name, first_name, last_name,
	SUM(sum_field) AS sum_field,
	SUM(sum_boundary) AS sum_boundary,
	SUM(sum_goal) AS sum_goal
	FROM (

		SELECT r.club_name,
		r.region_name,
		TRIM(RIGHT(r.last_first_name,Length(r.last_first_name)-InStr(r.last_first_name,','))) AS first_name,
		TRIM(LEFT(r.last_first_name,InStr(r.last_first_name,',')-1)) AS last_name, 
		SUM(CASE r.umpire_type WHEN 'Field' THEN r.match_count ELSE 0 END) AS sum_field,
		SUM(CASE r.umpire_type WHEN 'Boundary' THEN r.match_count ELSE 0 END) AS sum_boundary,
		SUM(CASE r.umpire_type WHEN 'Goal' THEN r.match_count ELSE 0 END) AS sum_goal
		FROM dw_mv_report_01 r
		WHERE season_year = 2017
		GROUP BY r.club_name, r.region_name, r.last_first_name, r.umpire_type
    ) AS rg
	GROUP BY rg.club_name, rg.region_name, rg.first_name, rg.last_name
) AS mv
ON mi_data.club_name = mv.club_name
AND mi_data.umpire_name = CONCAT(mv.first_name,' ', mv.last_name)
AND mi_data.region_name = mv.region_name

) as alldata
WHERE q_check = 'FAIL'
ORDER BY umpire_name, club_name";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        
        //$queryResultArray['umpire_name'] = "TEST NAME";
        
        return $queryResultArray;
        
    }
    
    private function checkImportedTableOperations() {
        $queryResultArray = array();
        
        $queryString = "SELECT t.operation_datetime, o.operation_name, p.table_name, t.rowcount " .
            "FROM table_operations t " .
            "INNER JOIN operation_ref o ON t.operation_id = o.id " .
            "INNER JOIN processed_table p ON t.processed_table_id = p.id " .
            "INNER JOIN imported_files f ON t.imported_file_id = f.imported_file_id " .
            "WHERE f.imported_file_id = ( " .
                "SELECT MAX(imported_file_id) " .
                "FROM imported_files " .
            ") " .
            "ORDER BY t.id;";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        /*
         if ($this->debugMode) {
         echo "getUmpireMatchCountForReport01:<BR/>";
         echo "<pre>";
         print_r($queryResultArray);
         echo "</pre>";
         }
         */
        return $queryResultArray;
    }
    

}
?>
        
        
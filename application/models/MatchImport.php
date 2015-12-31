<?php
//define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/../system/libraries/MY_Model.php');
//require_once('/system/libraries/MY_Model.php');



class MatchImport extends MY_Model 
{   
  /* Code .. */   
  
  function __construct()
    {
          parent::__construct();
    }
  
  public function fileImport($data) {
    date_default_timezone_set("Australia/Melbourne");
	//Remove data from previous load first
    $this->deleteFromSingleTable('match_import');
	
	//$dataFile = "application/import/2015-appointments-summary.xls";
	//print_r($data);
	$importedFilename = $data['upload_data']['file_name'];
	$dataFile = "application/import/". $importedFilename;
	$objPHPExcel = PHPExcel_IOFactory::load($dataFile);
	$sheet = $objPHPExcel->getActiveSheet();
	$lastRow = $sheet->getHighestRow();
	//echo "Last row: $lastRow<BR/>";
	$data = $sheet->rangeToArray('A2:Q'.$lastRow);
	//echo "Rows available: " . count($data) . "\n";
	/*foreach ($data as $row) {
		print_r($row);
	}*/
	/*
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	*/
	$columns = array('season', 'round', 'date', 'competition_name', 'ground', 'time', 'home_team', 'away_team', 'field_umpire_1', 'field_umpire_2', 'field_umpire_3', 'boundary_umpire_1', 'boundary_umpire_2', 'boundary_umpire_3', 'boundary_umpire_4', 'goal_umpire_1', 'goal_umpire_2');
	$rows = $data;
	/*echo "<pre>";
	print_r($data);
	echo "</pre>";*/
	$queryStatus = $this->insert_rows('match_import', $columns, $rows);
	if ($queryStatus) {
	   //echo "File imported!";
	   //Now the data is imported, extract it into the normalised tables.
	   $this->prepareNormalisedTables();
	   $this->logImportedFile($importedFilename);
	   
	   
	} else {
	    $error = $this->db->error();
	    print_r($error);
	    echo "File import error: " . $error['code'] . " - " . $error['message'];
	}
	
  }
  
  private function prepareNormalisedTables() {
      $this->deleteFromTables();
      
  }
  
  private function deleteFromTables() {
      
      $this->deleteFromSingleTable('umpire_name_type_match');
      $this->deleteFromSingleTable('match_played');
      $this->deleteFromSingleTable('umpire_name_type');
      $this->deleteFromSingleTable('umpire');
      $this->deleteFromSingleTable('round');
      $this->deleteFromSingleTable('match_staging');
      
      //$this->recreateMatchImportIndexes();
      
      $this->reloadRoundTable();
      $this->reloadUmpireTable();
      $this->reloadUmpireNameTypeTable();
      $this->reloadMatchStagingTable();
      $this->deleteDuplicateMatches();
      $this->reloadMatchTable();
      $this->reloadUmpireNameTypeMatchTable();
      
      //$this->recreateNormalisedTablendexes();
      //$this->recreateMVIndexes();

      //Reload tables for the reports
      $this->reloadMVReport01Table();
      $this->reloadMVReport02Table();
      $this->reloadMVSummaryStaging();
      $this->reloadMVReport03Table();
      $this->reloadMVReport04Table();
      $this->reloadMVReport05Table();
      
  }
  
  private function deleteFromSingleTable($tableName) {
      $queryString = "DELETE FROM ". $tableName;
      $this->db->query($queryString);
      //echo "Table deleted: " . $tableName . ", " . $this->db->affected_rows() . " rows.<BR />";
  }
  
  private function reloadRoundTable() {
      $queryString = "INSERT INTO round ( round_number, round_date, season_id, league_id ) " .
        "SELECT DISTINCT match_import.round, STR_TO_DATE(match_import.date, '%d/%m/%Y'), season.ID AS season_id, league.ID AS league_id " .
        "FROM match_import  " .
        "INNER JOIN season ON match_import.season = season.season_year " .
        "INNER JOIN competition_lookup ON (season.ID = competition_lookup.season_id) AND (match_import.competition_name = competition_lookup.competition_name) " .
        "INNER JOIN league ON league.ID = competition_lookup.league_id " .
        "ORDER BY match_import.Round, match_import.Date";
      $this->db->query($queryString);
      //echo "Query run: reloadRoundTable, " . $this->db->affected_rows() . " rows.<BR />";
  }
  
  private function reloadUmpireTable() {
      $queryString = "INSERT INTO umpire (first_name, last_name) " .
        "SELECT LEFT(UMPIRE_FULLNAME,InStr(UMPIRE_FULLNAME,' ')-1) AS FIRST_NAME, " .
        "RIGHT(UMPIRE_FULLNAME,Length(UMPIRE_FULLNAME)-InStr(UMPIRE_FULLNAME,' ')) AS LAST_NAME " .
        "FROM (SELECT match_import.field_umpire_1 AS UMPIRE_FULLNAME " .
        	"FROM match_import " .
        	"UNION " .
        	"SELECT match_import.field_umpire_2 " .
        	"FROM match_import " .
        	"UNION " .
        	"SELECT match_import.field_umpire_3 " .
        	"FROM match_import " .
        	"UNION " .
        	"SELECT match_import.boundary_umpire_1 " .
        	"FROM match_import " .
        	"UNION " .
        	"SELECT match_import.boundary_umpire_2 " .
        	"FROM match_import " .
        	"UNION " .
        	"SELECT match_import.boundary_umpire_3 " .
        	"FROM match_import " .
        	"UNION " .
        	"SELECT match_import.boundary_umpire_4 " .
        	"FROM match_import " .
        	"UNION " .
        	"SELECT match_import.goal_umpire_1 " .
        	"FROM match_import " .
        	"UNION " .
        	"SELECT match_import.goal_umpire_2 " .
        	"FROM match_import " .
        ") AS com " . 
        "WHERE UMPIRE_FULLNAME IS NOT NULL";
      $this->db->query($queryString);
      //echo "Query run: reloadUmpireTable, " . $this->db->affected_rows() . " rows.<BR />";
  }
  
  private function reloadUmpireNameTypeTable() {
      $queryString = "INSERT INTO umpire_name_type ( umpire_id, umpire_type_id ) " .
          "SELECT umpire.ID, umpire_type.ID " .
          "FROM ( " .
        	"SELECT " .
        	"LEFT(UMPIRE_FULLNAME,INSTR(UMPIRE_FULLNAME,' ')-1) AS FIRST_NAME, " .
        	"RIGHT(UMPIRE_FULLNAME,LENGTH(UMPIRE_FULLNAME)-INSTR(UMPIRE_FULLNAME,' ')) AS LAST_NAME, " .
        	"com1.UMPIRE_TYPE " .
        	"FROM ( " .
        		"SELECT match_import.field_umpire_1 AS UMPIRE_FULLNAME, 'Field' as UMPIRE_TYPE " .
        		"FROM match_import " .
        		"UNION " .
        		"SELECT match_import.field_umpire_2, 'Field' as UMPIRE_TYPE " .
        		"FROM match_import " .
        		"UNION " .
        		"SELECT match_import.field_umpire_3, 'Field' as UMPIRE_TYPE " .
        		"FROM match_import " .
        		"UNION " .
        		"SELECT match_import.boundary_umpire_1, 'Boundary' as UMPIRE_TYPE " .
        		"FROM match_import " .
        		"UNION " .
        		"SELECT match_import.boundary_umpire_2, 'Boundary' as UMPIRE_TYPE " .
        		"FROM match_import " .
        		"UNION " .
        		"SELECT match_import.boundary_umpire_3, 'Boundary' as UMPIRE_TYPE " .
        		"FROM match_import " .
        		"UNION " .
        		"SELECT match_import.boundary_umpire_4, 'Boundary' as UMPIRE_TYPE " .
        		"FROM match_import " .
        		"UNION " .
        		"SELECT match_import.goal_umpire_1, 'Goal' as UMPIRE_TYPE " .
        		"FROM match_import " .
        		"UNION " .
        		"SELECT match_import.goal_umpire_2, 'Goal' as UMPIRE_TYPE " .
        		"FROM match_import " .
        	") com1 " .
        	"WHERE com1.UMPIRE_FULLNAME IS NOT NULL " .
        ")  AS com2 " .
        "INNER JOIN umpire ON com2.first_name = umpire.first_name AND com2.last_name = umpire.last_name " .
        "INNER JOIN umpire_type ON com2.umpire_type = umpire_type.umpire_type_name";
      //echo "reloadUmpireNameTypeTable SQL:<BR />" . $queryString . "<BR />";
      $this->db->query($queryString);
      //echo "Query run: reloadUmpireNameTypeTable, " . $this->db->affected_rows() . " rows.<BR />";
  }
  
  private function reloadMatchStagingTable() {
      $queryString = "INSERT INTO match_staging " .
        "(appointments_id, appointments_season, appointments_round, appointments_date, appointments_compname, appointments_ground, appointments_time, " .
        "appointments_hometeam, appointments_awayteam, appointments_field1_first, appointments_field1_last, appointments_field2_first, appointments_field2_last, " .
        "appointments_field3_first, appointments_field3_last, appointments_boundary1_first, appointments_boundary1_last, appointments_boundary2_first, appointments_boundary2_last, " .
        "appointments_boundary3_first, appointments_boundary3_last, appointments_boundary4_first, appointments_boundary4_last, appointments_goal1_first, appointments_goal1_last, " .
        "appointments_goal2_first, appointments_goal2_last, season_id, round_ID, round_date, round_leagueid, league_leaguename, league_sponsored_league_name, agd_agegroupid, " .
        "ag_agegroup, agd_divisionid, division_divisionname, ground_id, ground_mainname, home_team_id, away_team_id) ";
      $queryString .= "SELECT match_import.ID, " . 
        "match_import.Season, " .  
        "match_import.Round, " .  
        "STR_TO_DATE(match_import.date, '%d/%m/%Y'), " .  
        "match_import.competition_name, " .  
        "match_import.ground, " .  
        "STR_TO_DATE(match_import.time, '%h:%i %p'), " .  
        "match_import.home_team, " .  
        "match_import.away_team, ";
      $queryString .= "LEFT(match_import.field_umpire_1,InStr(match_import.field_umpire_1,' ')-1) AS match_import_field1_first, " .  
        "RIGHT(match_import.field_umpire_1,LENGTH(match_import.field_umpire_1)-InStr(match_import.field_umpire_1,' ')) AS match_import_field1_last,  " .  
        "LEFT(match_import.field_umpire_2,InStr(match_import.field_umpire_2,' ')-1) AS match_import_field2_first,  " .  
        "RIGHT(match_import.field_umpire_2,LENGTH(match_import.field_umpire_2)-InStr(match_import.field_umpire_2,' ')) AS match_import_field2_last,  " .  
        "LEFT(match_import.field_umpire_3,InStr(match_import.field_umpire_3,' ')-1) AS match_import_field3_first,  " .  
        "RIGHT(match_import.field_umpire_3,LENGTH(match_import.field_umpire_3)-InStr(match_import.field_umpire_3,' ')) AS match_import_field3_last,  " .  
        "LEFT(match_import.boundary_umpire_1,InStr(match_import.boundary_umpire_1,' ')-1) AS match_import_boundary1_first, " .   
        "RIGHT(match_import.boundary_umpire_1,LENGTH(match_import.boundary_umpire_1)-InStr(match_import.boundary_umpire_1,' ')) AS match_import_boundary1_last, " .   
        "LEFT(match_import.boundary_umpire_2,InStr(match_import.boundary_umpire_2,' ')-1) AS match_import_boundary2_first, " .   
        "RIGHT(match_import.boundary_umpire_2,LENGTH(match_import.boundary_umpire_2)-InStr(match_import.boundary_umpire_2,' ')) AS match_import_boundary2_last, " .   
        "LEFT(match_import.boundary_umpire_3,InStr(match_import.boundary_umpire_3,' ')-1) AS match_import_boundary3_first, " .
        "RIGHT(match_import.boundary_umpire_3,LENGTH(match_import.boundary_umpire_3)-InStr(match_import.boundary_umpire_3,' ')) AS match_import_boundary3_last, " .   
        "LEFT(match_import.boundary_umpire_4,InStr(match_import.boundary_umpire_4,' ')-1) AS match_import_boundary4_first, " .   
        "RIGHT(match_import.boundary_umpire_4,LENGTH(match_import.boundary_umpire_4)-InStr(match_import.boundary_umpire_4,' ')) AS match_import_boundary4_last, " .   
        "LEFT(match_import.goal_umpire_1,InStr(match_import.goal_umpire_1,' ')-1) AS match_import_goal1_first, " .   
        "RIGHT(match_import.goal_umpire_1,LENGTH(match_import.goal_umpire_1)-InStr(match_import.goal_umpire_1,' ')) AS match_import_goal1_last, " .   
        "LEFT(match_import.goal_umpire_2,InStr(match_import.goal_umpire_2,' ')-1) AS match_import_goal2_first, " .   
        "RIGHT(match_import.goal_umpire_2,LENGTH(match_import.goal_umpire_2)-InStr(match_import.goal_umpire_2,' ')) AS match_import_goal2_last, " .   
        "season.ID AS season_id, " .   
        "round.ID AS round_ID, " .   
        "round.round_date AS round_date, " .   
        "round.league_id AS round_leagueid, " .   
        "league.league_name AS league_leaguename,  " .  
        "league.sponsored_league_name AS league_sponsored_league_name, " .   
        "age_group_division.age_group_id AS agd_agegroupid, " .   
        "age_group.age_group AS ag_agegroup, " .   
        "age_group_division.division_id AS agd_divisionid, " .   
        "division.division_name AS division_divisionname, " .   
        "ground.id AS ground_id, " .   
        "ground.main_name AS ground_mainname, " .   
        "team.ID AS home_team_id, " .   
        "team_1.ID AS away_team_id ";
      $queryString .= "FROM match_import " .
        "INNER JOIN round ON (STR_TO_DATE(match_import.date, '%d/%m/%Y') = round.round_date) AND (match_import.round = round.round_number) " .
        "INNER JOIN competition_lookup ON match_import.competition_name = competition_lookup.competition_name " .
        "INNER JOIN season ON (match_import.season = season.season_year) AND (season.ID = competition_lookup.season_id) AND (season.ID = round.season_id) " .
        "INNER JOIN ground ON match_import.Ground = ground.alternative_name " .
        "INNER JOIN team ON match_import.home_team = team.team_name " .
        "INNER JOIN team AS team_1 ON match_import.away_team = team_1.team_name " .
        "INNER JOIN league ON (league.ID = competition_lookup.league_id) AND (league.ID = round.league_id) " .
        "INNER JOIN age_group_division ON league.age_group_division_id = age_group_division.ID " .
        "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id " .
        "INNER JOIN division ON division.ID = age_group_division.division_id";
      
      $this->db->query($queryString);
      //echo "Query run: reloadMatchStagingTable, " . $this->db->affected_rows() . " rows.<BR />";
      
  }
  
  private function deleteDuplicateMatches() {
        $queryString = "DELETE m1 " .
            "FROM match_staging m1, match_staging m2 " .
            "WHERE m1.appointments_id > m2.appointments_id " .
            "AND m1.ground_id = m2.ground_id " .
            "AND m1.round_id = m2.round_id " .
            "AND m1.appointments_time = m2.appointments_time " .
            "AND m1.home_team_id = m2.home_team_id " .
            "AND m1.away_team_id = m2.away_team_id";
      $this->db->query($queryString);
      //echo "Query run: deleteDuplicateMatches<BR />";
      
  }
  
  private function reloadMatchTable() {
      $queryString = "INSERT INTO match_played (round_ID, ground_id, home_team_id, away_team_id, match_time) " .
        "SELECT match_staging.round_ID, match_staging.ground_id, match_staging.home_team_id, match_staging.away_team_id, match_staging.appointments_time " .
        "FROM match_staging";
      $this->db->query($queryString);
      //echo "Query run: reloadMatchTable, " . $this->db->affected_rows() . " rows.<BR />";
  }
  
  private function reloadUmpireNameTypeMatchTable() {
      $queryString = "INSERT INTO umpire_name_type_match ( umpire_name_type_id, match_id ) " .
        "SELECT umpire_name_type_id, match_id " .
        "FROM (";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Field' " .
	"UNION ALL ";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field2_first) AND (umpire.last_name = match_staging.appointments_field2_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Field' " .
	"UNION ALL ";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field3_first) AND (umpire.last_name = match_staging.appointments_field3_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Field' " .
	"UNION ALL ";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary1_first) AND (umpire.last_name = match_staging.appointments_boundary1_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Boundary' " .
	"UNION ALL ";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary2_first) AND (umpire.last_name = match_staging.appointments_boundary2_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Boundary' " .
	"UNION ALL ";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary3_first) AND (umpire.last_name = match_staging.appointments_boundary3_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Boundary' " .
	"UNION ALL ";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary4_first) AND (umpire.last_name = match_staging.appointments_boundary4_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Boundary' " .
	"UNION ALL ";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal1_first) AND (umpire.last_name = match_staging.appointments_goal1_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Goal' " .
	"UNION ALL ";
      $queryString .= "SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id " .
	"FROM match_played " .
	"INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID) " .
	"INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal2_first) AND (umpire.last_name = match_staging.appointments_goal2_last) " .
	"INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
	"INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
	"WHERE umpire_type.umpire_type_name = 'Goal') AS ump";
      $this->db->query($queryString);
      //echo "--reloadUmpireNameTypeMatchTable SQL:<BR />" . $queryString . "<BR />";
      //echo "Query run: reloadUmpireNameTypeMatchTable, " . $this->db->affected_rows() . " rows.<BR />";
  }
  
  private function reloadMVReport01Table() {
      //First, delete the data from the table
      //$reportTableName = $this->lookupReportTableName('1');
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('1');
      $this->deleteFromSingleTable($reportTableName);
      
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_01 (full_name, club_name, short_league_name, age_group, umpire_type_name, 
        `GDFL|Anakie`, `GDFL|Bannockburn`, `None|Corio`, `GDFL|East_Geelong`, `GDFL|North_Geelong`, `None|Portarlington`, `GDFL|Werribee_Centrals`,
        `GDFL|Winchelsea`, `GFL|Bell_Park`, `GDFL|Bell_Post_Hill`, `GDFL|Belmont_Lions`, `GFL|Colac`, `BFL|Geelong_Amateur`, `GDFL|Geelong_West`,
        `GFL|Grovedale`, `GFL|Gwsp`, `GDFL|Inverleigh`, `GFL|Lara`, `GFL|Leopold`, `GFL|Newtown_&_Chilwell`, `GFL|North_Shore`,
        `GFL|South_Barwon`, `GFL|St_Joseph's`, `GFL|St_Mary's`, `BFL|Torquay`, `None|Barwon_Heads`, `None|Drysdale`, `None|East_Geelong`,
        `None|Geelong_West_St_Peters`, `None|Grovedale`, `None|Inverleigh`, `None|Leopold`, `None|Newcomb`, `None|Newtown_&_Chilwell`, `None|Ocean_Grove`,
        `None|South_Barwon`, `None|St_Albans`, `None|St_Joseph's`, `None|St_Mary's`, `None|Torquay`, `BFL|Anglesea`, `BFL|Barwon_Heads`,
        `GDFL|Corio`, `GDFL|Thomson`, `None|Anglesea`, `None|Bell_Park`, `None|North_Shore`, `None|Belmont_Lions`, `None|Colac`,
        `None|North_Geelong`, `None|Ogcc`, `None|Torquay_Jones`, `None|Torquay_Papworth`, `None|Winchelsea_/_Grovedale`, `BFL|Modewarre`, `BFL|Newcomb_Power`,
        `BFL|Queenscliff`, `GFL|St_Albans`, `None|Drysdale_Byrne`, `None|Drysdale_Hall`, `None|Drysdale_Hector`, `None|Lara`, `None|Queenscliff`,
        `None|St_Albans_Reid`, `None|Torquay_Bumpstead`, `None|Torquay_Pyers`, `None|Modewarre`, `BFL|Ocean_Grove`, `BFL|Drysdale`, `BFL|Portarlington`,
        `None|St_Joseph's_Podbury`, `None|Geelong_Amateur`, `None|Winchelsea`, `None|Anakie`, `None|Bannockburn`, `None|South_Barwon_/_Geelong_Amateur`,
        `None|St_Joseph's_Hill`, `None|Torquay_Dunstan`, `None|Werribee_Centrals`, `None|Drysdale_Eddy`, `None|Belmont_Lions_/_Newcomb`, `None|Torquay_Coles`,
        `None|Gwsp_/_Bannockburn`, `None|St_Albans_Allthorpe`, `None|Drysdale_Bennett`, `None|Torquay_Scott`, `None|Torquay_Nairn`, `None|Tigers_Gold`)";  
        $queryString .= "SELECT CONCAT(last_name,', ',first_name) AS full_name, club_name, short_league_name, age_group, umpire_type_name,  " .
        "(CASE WHEN club_name = 'Anakie' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Anakie', " .
        "(CASE WHEN club_name = 'Bannockburn' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Bannockburn', " .
        "(CASE WHEN club_name = 'Corio' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Corio', " .
        "(CASE WHEN club_name = 'East Geelong' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|East_Geelong', " .
        "(CASE WHEN club_name = 'North Geelong' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|North_Geelong', " .
        "(CASE WHEN club_name = 'Portarlington' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Portarlington', " .
        "(CASE WHEN club_name = 'Werribee Centrals' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Werribee_Centrals', " .
        "(CASE WHEN club_name = 'Winchelsea' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Winchelsea', " .
        "(CASE WHEN club_name = 'Bell Park' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Bell_Park', " .
        "(CASE WHEN club_name = 'Bell Post Hill' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Bell_Post_Hill', " .
        "(CASE WHEN club_name = 'Belmont Lions' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Belmont_Lions', " .
        "(CASE WHEN club_name = 'Colac' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Colac', " .
        "(CASE WHEN club_name = 'Geelong Amateur' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Geelong_Amateur', " .
        "(CASE WHEN club_name = 'Geelong West' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Geelong_West', " .
        "(CASE WHEN club_name = 'Grovedale' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Grovedale', " .
        "(CASE WHEN club_name = 'Gwsp' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Gwsp', " .
        "(CASE WHEN club_name = 'Inverleigh' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Inverleigh', " .
        "(CASE WHEN club_name = 'Lara' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Lara', " .
        "(CASE WHEN club_name = 'Leopold' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Leopold', " .
        "(CASE WHEN club_name = 'Newtown & Chilwell' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Newtown_&_Chilwell', " .
        "(CASE WHEN club_name = 'North Shore' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|North_Shore', " .
        "(CASE WHEN club_name = 'South Barwon' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|South_Barwon', " .
        "(CASE WHEN club_name = 'St Joseph''s' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|St_Joseph\'s', " .
        "(CASE WHEN club_name = 'St Mary''s' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|St_Mary\'s', " .
        "(CASE WHEN club_name = 'Torquay' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Torquay', ";
      $queryString .= "(CASE WHEN club_name = 'Barwon Heads' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Barwon_Heads', " .
        "(CASE WHEN club_name = 'Drysdale' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Drysdale', " .
        "(CASE WHEN club_name = 'East Geelong' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|East_Geelong', " .
        "(CASE WHEN club_name = 'Geelong West St Peters' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Geelong_West_St_Peters', " .
        "(CASE WHEN club_name = 'Grovedale' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Grovedale', " .
        "(CASE WHEN club_name = 'Inverleigh' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Inverleigh', " .
        "(CASE WHEN club_name = 'Leopold' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Leopold', " .
        "(CASE WHEN club_name = 'Newcomb' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Newcomb', " .
        "(CASE WHEN club_name = 'Newtown & Chilwell' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Newtown_&_Chilwell', " .
        "(CASE WHEN club_name = 'Ocean Grove' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Ocean_Grove', " .
        "(CASE WHEN club_name = 'South Barwon' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|South_Barwon', " .
        "(CASE WHEN club_name = 'St Albans' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|St_Albans', " .
        "(CASE WHEN club_name = 'St Joseph''s' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|St_Joseph\'s', " .
        "(CASE WHEN club_name = 'St Mary''s' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|St_Mary\'s', " .
        "(CASE WHEN club_name = 'Torquay' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay', " .
        "(CASE WHEN club_name = 'Anglesea' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Anglesea', " .
        "(CASE WHEN club_name = 'Barwon Heads' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Barwon_Heads', " .
        "(CASE WHEN club_name = 'Corio' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Corio', " .
        "(CASE WHEN club_name = 'Thomson' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Thomson', " .
        "(CASE WHEN club_name = 'Anglesea' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Anglesea', " .
        "(CASE WHEN club_name = 'Bell Park' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Bell_Park', " .
        "(CASE WHEN club_name = 'North Shore' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|North_Shore', " .
        "(CASE WHEN club_name = 'Belmont Lions' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Belmont_Lions',";
      $queryString .= "(CASE WHEN club_name = 'Colac' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Colac', " .
        "(CASE WHEN club_name = 'North Geelong' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|North_Geelong', " .
        "(CASE WHEN club_name = 'Ogcc' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Ogcc', " .
        "(CASE WHEN club_name = 'Torquay Jones' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay_Jones', " .
        "(CASE WHEN club_name = 'Torquay Papworth' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay_Papworth', " .
        "(CASE WHEN club_name = 'Winchelsea / Grovedale' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Winchelsea_/_Grovedale', " .
        "(CASE WHEN club_name = 'Modewarre' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Modewarre', " .
        "(CASE WHEN club_name = 'Newcomb Power' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Newcomb_Power', " .
        "(CASE WHEN club_name = 'Queenscliff' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Queenscliff', " .
        "(CASE WHEN club_name = 'St Albans' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|St_Albans', " .
        "(CASE WHEN club_name = 'Drysdale Byrne' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Drysdale_Byrne', " .
        "(CASE WHEN club_name = 'Drysdale Hall' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Drysdale_Hall', " .
        "(CASE WHEN club_name = 'Drysdale Hector' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Drysdale_Hector', " .
        "(CASE WHEN club_name = 'Lara' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Lara', " .
        "(CASE WHEN club_name = 'Queenscliff' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Queenscliff', " .
        "(CASE WHEN club_name = 'St Albans Reid' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|St_Albans_Reid', " .
        "(CASE WHEN club_name = 'Torquay Bumpstead' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay_Bumpstead', " .
        "(CASE WHEN club_name = 'Torquay Pyers' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay_Pyers', " .
        "(CASE WHEN club_name = 'Modewarre' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Modewarre', " .
        "(CASE WHEN club_name = 'Ocean Grove' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Ocean_Grove', " .
        "(CASE WHEN club_name = 'Drysdale' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Drysdale', " .
        "(CASE WHEN club_name = 'Portarlington' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Portarlington', " .
        "(CASE WHEN club_name = 'St Joseph''s Podbury' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|St_Joseph\'s_Podbury', " .
        "(CASE WHEN club_name = 'Geelong Amateur' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Geelong_Amateur', ";
      $queryString .= "(CASE WHEN club_name = 'Winchelsea' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Winchelsea', " .
        "(CASE WHEN club_name = 'Anakie' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Anakie', " .
        "(CASE WHEN club_name = 'Bannockburn' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Bannockburn', " .
        "(CASE WHEN club_name = 'South Barwon / Geelong Amateur' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|South_Barwon_/_Geelong_Amateur', " .
        "(CASE WHEN club_name = 'St Joseph''s Hill' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|St_Joseph\'s_Hill', " .
        "(CASE WHEN club_name = 'Torquay Dunstan' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay_Dunstan', " .
        "(CASE WHEN club_name = 'Werribee Centrals' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Werribee_Centrals', " .
        "(CASE WHEN club_name = 'Drysdale Eddy' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Drysdale_Eddy', " .
        "(CASE WHEN club_name = 'Belmont Lions / Newcomb' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Belmont_Lions_/_Newcomb', " .
        "(CASE WHEN club_name = 'Torquay Coles' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay_Coles', " .
        "(CASE WHEN club_name = 'Gwsp / Bannockburn' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Gwsp_/_Bannockburn', " .
        "(CASE WHEN club_name = 'St Albans Allthorpe' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|St_Albans_Allthorpe', " .
        "(CASE WHEN club_name = 'Drysdale Bennett' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Drysdale_Bennett', " .
        "(CASE WHEN club_name = 'Torquay Scott' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay_Scott', " .
        "(CASE WHEN club_name = 'Torquay Nairn' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Torquay_Nairn', " .
        "(CASE WHEN club_name = 'Tigers Gold' AND short_league_name = 'None' THEN COUNT(*) ELSE 0 END) as 'None|Tigers_Gold' ";
      $queryString .= "FROM (SELECT umpire.first_name, umpire.last_name, match_played.ID, team.team_name, club.club_name, league.short_league_name, age_group.age_group, umpire_type.umpire_type_name " .
        "FROM match_played " .
        "INNER JOIN round ON round.ID = match_played.round_id " .
        "INNER JOIN league ON league.ID = round.league_id " .
        "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id " .
        "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id " .
        "INNER JOIN team ON team.ID = match_played.home_team_id " .
        "INNER JOIN club ON club.ID = team.club_id " .
        "INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id " .
        "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id " .
        "INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
        "INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id " .
        "UNION ALL " .
        "SELECT umpire.first_name, umpire.last_name, match_played.ID, team.team_name, club.club_name, league.short_league_name, age_group.age_group, umpire_type.umpire_type_name " .
        "FROM match_played " .
        "INNER JOIN round ON round.ID = match_played.round_id " .
        "INNER JOIN league ON league.ID = round.league_id " .
        "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id " .
        "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id " .
        "INNER JOIN team ON team.ID = match_played.away_team_id " .
        "INNER JOIN club ON club.ID = team.club_id " .
        "INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id " .
        "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id " .
        "INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
        "INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id " .
        ")  AS a " .
        "GROUP BY first_name, last_name, club_name, short_league_name, age_group, umpire_type_name " .
        "ORDER BY first_name, last_name, club_name, short_league_name, age_group, umpire_type_name";

      $this->db->query($queryString);
      //echo "--reloadUmpireNameTypeMatchTable SQL:<BR />" . $queryString . "<BR />";
      //echo "Query run: reloadMVReport01Table, " . $this->db->affected_rows() . " rows.<BR />";
  
  }
  
  private function reloadMVReport02Table() {
      //First, delete the data from the table
      //$reportTableName = $this->lookupReportTableName('1');
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('2');
      $this->deleteFromSingleTable($reportTableName);
  
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_02 (full_name, umpire_type_name, short_league_name, age_group, " . 
            "`Seniors|BFL`, `Seniors|GDFL`, `Seniors|GFL`, `Reserves|BFL`, `Reserves|GDFL`, `Reserves|GFL`, `Colts|None`, " . 
            "`Under 16|None`, `Under 14|None`, `Youth Girls|None`, `Junior Girls|None`, `Seniors|2 Umpires`) ";
      $queryString .= "SELECT " . 
            "full_name, " . 
            "umpire_type_name, " .
            "short_league_name, " .
            "age_group, " .
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = '2 Umpires' THEN match_count ELSE 0 END) " . 
            "FROM ( " . 
            "SELECT  " . 
            "umpire_type.umpire_type_name, " . 
            "age_group.ID, " . 
            "age_group.age_group, " . 
            "league.short_league_name, " . 
            "CONCAT(last_name,', ',first_name) AS full_name, " . 
            "COUNT(match_played.ID) AS match_count " . 
            "FROM match_played ";
      $queryString .= "INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id " . 
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id " . 
            "INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " . 
            "INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id " . 
            "INNER JOIN round ON round.ID = match_played.round_id " . 
            "INNER JOIN league ON league.ID = round.league_id " . 
            "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id " . 
            "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id " . 
            "INNER JOIN division ON division.ID = age_group_division.division_id " . 
            "GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , league.short_league_name , CONCAT(last_name,', ',first_name) " . 
            "UNION ALL " . 
            "SELECT  " . 
            "umpire_type.umpire_type_name, " . 
            "age_group.ID, " . 
            "age_group.age_group, " . 
            "'2 Umpires', " . 
            "CONCAT(last_name,', ',first_name), " . 
            "COUNT(match_played.ID) " . 
            "FROM match_played " . 
            "INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id " . 
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id " . 
            "INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " . 
            "INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id " . 
            "INNER JOIN round ON round.ID = match_played.round_id " . 
            "INNER JOIN league ON league.ID = round.league_id " . 
            "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id " . 
            "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id " . 
            "INNER JOIN division ON division.ID = age_group_division.division_id " . 
            "INNER JOIN ( ";
      $queryString .= "SELECT  " . 
            "match_played.ID AS match_id, " . 
            "COUNT(umpire.ID) AS umpire_count " . 
            "FROM match_played " . 
            "INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id " . 
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id " . 
            "INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " . 
            "INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id " . 
            "INNER JOIN round ON round.ID = match_played.round_id " . 
            "INNER JOIN league ON league.ID = round.league_id " . 
            "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id " . 
            "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id " . 
            "INNER JOIN division ON division.ID = age_group_division.division_id " . 
            "WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors' " . 
            "GROUP BY match_played.ID, umpire_type.umpire_type_name, age_group.age_group " . 
            "HAVING COUNT(umpire.ID) = 2 " . 
            ") AS qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id " . 
            "WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors' " . 
            "GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , CONCAT(last_name,', ',first_name) " . 
            ") AS mainquery " . 
            "ORDER BY full_name";

      
      $this->db->query($queryString);
      //echo "--reloadUmpireNameTypeMatchTable SQL:<BR />" . $queryString . "<BR />";
      //echo "Query run: reloadMVReport01Table, " . $this->db->affected_rows() . " rows.<BR />";
      
  }
  
  private function reloadMVSummaryStaging() {
      //First, delete the data from the table
      $this->deleteFromSingleTable("mv_summary_staging");
      
      //Then, insert into table
      $queryString = "INSERT INTO mv_summary_staging (umpire_type_id, umpire_type, age_group, short_league_name, " .
          "round_date, match_id, home, away, home_club, away_club, age_group_ID, weekdate) ";
      $queryString .= "SELECT umpire_type_id, umpire_type, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID,  ".
            "ADDDATE(round_date, (5-Weekday(round_date))) as WeekDate ".
            "FROM ( ";
      $queryString .= "SELECT 1 as umpire_type_id, 'Field' AS umpire_type, age_group.age_group, league.short_league_name,  ".
            "round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club,  ".
            "age_group_division.age_group_ID ".
            "FROM match_played ".
            "INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID ".
            "INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id ".
            "INNER JOIN team ON team.ID = match_played.away_team_id ".
            "INNER JOIN club ON club.ID = team.club_id ".
            "INNER JOIN round ON round.ID = match_played.round_id ".
            "INNER JOIN league ON league.ID = ROUND.league_id ".
            "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id ".
            "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id ".
            "INNER JOIN division ON division.ID = age_group_division.division_id ".
            "WHERE match_played.id NOT IN ( ".
            "SELECT umpire_name_type_match.match_id ".
            "FROM umpire_name_type_match ".
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id ".
            "INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id ".
            "WHERE ut_sub.umpire_type_name='Field' ".
            ") ".
            "UNION ".
            "SELECT 3 as umpire_type_id, 'Goal' AS umpire_type, age_group.age_group, league.short_league_name,  ".
            "round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club,  ".
            "age_group_division.age_group_ID ".
            "FROM match_played ".
            "INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID ".
            "INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id ".
            "INNER JOIN team ON team.ID = match_played.away_team_id ".
            "INNER JOIN club ON club.ID = team.club_id ".
            "INNER JOIN round ON round.ID = match_played.round_id ".
            "INNER JOIN league ON league.ID = ROUND.league_id ".
            "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id ".
            "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id ".
            "INNER JOIN division ON division.ID = age_group_division.division_id ".
            "WHERE match_played.id NOT IN ( ".
            "SELECT umpire_name_type_match.match_id ".
            "FROM umpire_name_type_match ".
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id ".
            "INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id ".
            "WHERE ut_sub.umpire_type_name='Goal' ".
            ") ";
      $queryString .= "UNION ".
            "SELECT 2 as umpire_type_id, 'Boundary' AS umpire_type, age_group.age_group, league.short_league_name,  ".
            "round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club,  ".
            "age_group_division.age_group_ID ".
            "FROM match_played ".
            "INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID ".
            "INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id ".
            "INNER JOIN team ON team.ID = match_played.away_team_id ".
            "INNER JOIN club ON club.ID = team.club_id ".
            "INNER JOIN round ON round.ID = match_played.round_id ".
            "INNER JOIN league ON league.ID = round.league_id ".
            "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id ".
            "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id ".
            "INNER JOIN division ON division.ID = age_group_division.division_id ".
            "WHERE match_played.id NOT IN ( ".
            "SELECT umpire_name_type_match.match_id ".
            "FROM umpire_name_type_match ".
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id ".
            "INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id ".
            "WHERE ut_sub.umpire_type_name='Boundary' ".
            ") ".
            ") AS outer1";
      
      
      $this->db->query($queryString);
      //echo "--reloadUmpireNameTypeMatchTable SQL:<BR />" . $queryString . "<BR />";
      //echo "Query run: reloadMVReport03Table, " . $this->db->affected_rows() . " rows.<BR />";
      
  }
  
  private function reloadMVReport03Table() {
      //First, delete the data from the table
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('3');
      $this->deleteFromSingleTable($reportTableName);
  
      //Then, insert into table
      $queryString = "INSERT INTO `umpire`.`mv_report_03` (weekdate, " .
            "`No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, " .
            "`No Senior Goal|BFL`, `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, " .
            "`No Reserve Goal|BFL`, `No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, " .
            "`No Colts Field|Clubs`, `No Colts Field|No.`, `No U16 Field|Clubs`, `No U16 Field|No.`, " .
            "`No U14 Field|Clubs`, `No U14 Field|No.`) " .
            "SELECT weekdate, " .
            "MAX(`No Senior Boundary|BFL`), MAX(`No Senior Boundary|GDFL`), MAX(`No Senior Boundary|GFL`), SUM(`No Senior Boundary|No.`), MAX(`No Senior Goal|BFL`), " .
            "MAX(`No Senior Goal|GDFL`), MAX(`No Senior Goal|GFL`), SUM(`No Senior Goal|No.`), MAX(`No Reserve Goal|BFL`), MAX(`No Reserve Goal|GDFL`), " .
            "MAX(`No Reserve Goal|GFL`), SUM(`No Reserve Goal|No.`), MAX(`No Colts Field|Clubs`),SUM(`No Colts Field|No.`), MAX(`No U16 Field|Clubs`), " .
            "SUM(`No U16 Field|No.`), MAX(`No U14 Field|Clubs`), SUM(`No U14 Field|No.`) " . 
            "FROM (";
      $queryString .= "SELECT weekdate, ".
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END) as `No Senior Boundary|BFL`, ".
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END) as `No Senior Boundary|GDFL`, ".
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END) as `No Senior Boundary|GFL`, ".
            "(CASE WHEN age_group = 'Seniors' AND umpire_type = 'Boundary' THEN match_count ELSE 0 END) as `No Senior Boundary|No.`, ".
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Senior Goal|BFL`, ".
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Senior Goal|GDFL`, ".
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Senior Goal|GFL`, ".
            "(CASE WHEN age_group = 'Seniors' AND umpire_type = 'Goal' THEN match_count ELSE 0 END) as `No Senior Goal|No.`, ".
            "(CASE WHEN age_group = 'Reserve' AND short_league_name = 'BFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Reserve Goal|BFL`, ".
            "(CASE WHEN age_group = 'Reserve' AND short_league_name = 'GDFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Reserve Goal|GDFL`, ".
            "(CASE WHEN age_group = 'Reserve' AND short_league_name = 'GFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Reserve Goal|GFL`, ".
            "(CASE WHEN age_group = 'Reserve' AND umpire_type = 'Goal' THEN match_count ELSE 0 END) as `No Reserve Goal|No.`, ".
            "(CASE WHEN age_group = 'Colts' AND umpire_type = 'Field' THEN team_list ELSE NULL END) as `No Colts Field|Clubs`, ".
            "(CASE WHEN age_group = 'Colts' AND umpire_type = 'Field' THEN match_count ELSE 0 END) as `No Colts Field|No.`, ".
            "(CASE WHEN age_group = 'Under 16' AND umpire_type = 'Field' THEN team_list ELSE NULL END) as `No U16 Field|Clubs`, ".
            "(CASE WHEN age_group = 'Under 16' AND umpire_type = 'Field' THEN match_count ELSE 0 END) as `No U16 Field|No.`, ".
            "(CASE WHEN age_group = 'Under 14' AND umpire_type = 'Field' THEN team_list ELSE NULL END) as `No U14 Field|Clubs`, ".
            "(CASE WHEN age_group = 'Under 14' AND umpire_type = 'Field' THEN match_count ELSE 0 END)  as `No U14 Field|No.`".
            "FROM (";
            
      $queryString .= "SELECT umpire_type, age_group, short_league_name, weekdate, ".
            "GROUP_CONCAT(home, ' vs ', away) AS team_list, ".
            "COUNT(home) AS match_count  ".
            "FROM ( ".
            "SELECT umpire_type_id, umpire_type, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID,  ".
            "weekdate ".
            "FROM mv_summary_staging " .
            ") AS outer2 ".
            "GROUP BY umpire_type, age_group, short_league_name, weekdate ";
      $queryString .= ") AS outer3 " .
            "GROUP BY weekdate,  " .
        		"`No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, `No Senior Goal|BFL`, " .
        	 "`No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, `No Reserve Goal|BFL`, `No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, `No Colts Field|Clubs`, " .
        	 "`No Colts Field|No.`, `No U16 Field|Clubs`, `No U16 Field|No.`, `No U14 Field|Clubs`, `No U14 Field|No.` " .
        	") as maintable " .
        "GROUP BY  weekdate, `No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, " . 
        "`No Senior Goal|BFL`, `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, `No Reserve Goal|BFL`,  " .
        "`No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, `No Colts Field|Clubs`,`No Colts Field|No.` " .
        "ORDER BY weekdate";
  
      $this->db->query($queryString);
      //echo "--reloadUmpireNameTypeMatchTable SQL:<BR />" . $queryString . "<BR />";
      //echo "Query run: reloadMVReport03Table, " . $this->db->affected_rows() . " rows.<BR />";
  
  }
  
  private function reloadMVReport04Table() {
      //First, delete the data from the table
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('4');
      $this->deleteFromSingleTable($reportTableName);
  
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_04 (club_name, `Boundary|Seniors|BFL`, ".
        "`Boundary|Seniors|GDFL`, `Boundary|Seniors|GFL`, `Boundary|Reserves|BFL`, `Boundary|Reserves|GDFL`, ".
        "`Boundary|Reserves|GFL`, `Boundary|Colts|None`, `Boundary|Under 16|None`, `Boundary|Under 14|None`, ".
        "`Boundary|Youth Girls|None`, `Boundary|Junior Girls|None`, `Field|Seniors|BFL`, `Field|Seniors|GDFL`, ".
        "`Field|Seniors|GFL`, `Field|Reserves|BFL`, `Field|Reserves|GDFL`, `Field|Reserves|GFL`, ".
        "`Field|Colts|None`, `Field|Under 16|None`, `Field|Under 14|None`, `Field|Youth Girls|None`, ".
        "`Field|Junior Girls|None`, `Goal|Seniors|BFL`, `Goal|Seniors|GDFL`, `Goal|Seniors|GFL`, ".
        "`Goal|Reserves|BFL`, `Goal|Reserves|GDFL`, `Goal|Reserves|GFL`, `Goal|Colts|None`, ".
        "`Goal|Under 16|None`, `Goal|Under 14|None`, `Goal|Youth Girls|None`, `Goal|Junior Girls|None`) ";
      
      $queryString .= "SELECT club, SUM(`Boundary|Seniors|BFL`), SUM(`Boundary|Seniors|GDFL`), SUM(`Boundary|Seniors|GFL`), ".
        "SUM(`Boundary|Reserves|BFL`), SUM(`Boundary|Reserves|GDFL`), SUM(`Boundary|Reserves|GFL`), SUM(`Boundary|Colts|None`), ".
        "SUM(`Boundary|Under 16|None`), SUM(`Boundary|Under 14|None`), SUM(`Boundary|Youth Girls|None`), SUM(`Boundary|Junior Girls|None`), ".
        "SUM(`Field|Seniors|BFL`), SUM(`Field|Seniors|GDFL`), SUM(`Field|Seniors|GFL`), SUM(`Field|Reserves|BFL`), ".
        "SUM(`Field|Reserves|GDFL`), SUM(`Field|Reserves|GFL`), SUM(`Field|Colts|None`), SUM(`Field|Under 16|None`), ".
        "SUM(`Field|Under 14|None`), SUM(`Field|Youth Girls|None`), SUM(`Field|Junior Girls|None`), SUM(`Goal|Seniors|BFL`), ".
        "SUM(`Goal|Seniors|GDFL`), SUM(`Goal|Seniors|GFL`), SUM(`Goal|Reserves|BFL`), SUM(`Goal|Reserves|GDFL`), ".
        "SUM(`Goal|Reserves|GFL`), SUM(`Goal|Colts|None`), SUM(`Goal|Under 16|None`), SUM(`Goal|Under 14|None`), ".
        "SUM(`Goal|Youth Girls|None`), SUM(`Goal|Junior Girls|None`) ".
        "FROM ( ";
      
      $queryString .= "SELECT club, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|BFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|GDFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|GFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|BFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|GDFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|GFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Colts|None`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Under 16|None`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Under 14|None`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Youth Girls|None`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Junior Girls|None`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Field|Seniors|BFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Field|Seniors|GDFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Field|Seniors|GFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Field|Reserves|BFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Field|Reserves|GDFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Field|Reserves|GFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Colts|None`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Under 16|None`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Under 14|None`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Youth Girls|None`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Junior Girls|None`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Goal|Seniors|BFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Goal|Seniors|GDFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Goal|Seniors|GFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Goal|Reserves|BFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Goal|Reserves|GDFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Goal|Reserves|GFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Colts|None`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Under 16|None`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Under 14|None`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Youth Girls|None`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Junior Girls|None` ".
        "FROM ( ";
      
      $queryString .= "SELECT age_group, umpire_type, Club, short_league_name, SUM(Match_Count) AS match_count ".
            "FROM ( ".
            "SELECT 'Home' as Club_Type, s.age_group, s.umpire_type, s.home_club as Club, s.short_league_name,  ".
            "COUNT(s.age_group_ID) AS Match_Count, age_group_ID ".
            "FROM mv_summary_staging s ".
            "GROUP BY s.age_group, s.umpire_type, s.home_club, s.age_group_ID ".
            "UNION ALL ".
            "SELECT 'Away' as Club_Type,  s.age_group, s.umpire_type, s.away_club,  s.short_league_name,  ".
            "COUNT(s.age_group_ID), age_group_ID ".
            "FROM mv_summary_staging s ".
            "GROUP BY s.age_group, s.umpire_type, s.away_club, s.age_group_ID, s.short_league_name ".
            ")  AS outer1 ".
            "GROUP BY age_group, umpire_type, Club, short_league_name ".
            ") AS outer2 ".
            ") AS outer3 ".
            "GROUP BY club ".
            "ORDER BY club; ";
      
  
      $this->db->query($queryString);
      //echo "--reloadUmpireNameTypeMatchTable SQL:<BR />" . $queryString . "<BR />";
      //echo "Query run: reloadMVReport03Table, " . $this->db->affected_rows() . " rows.<BR />";
  
  }
  
  private function reloadMVReport05Table() {
      //First, delete the data from the table
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('5');
      $this->deleteFromSingleTable($reportTableName);
  
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_05 (umpire_type, age_group, BFL, GDFL, GFL, `None`, `Total`) ";
      $queryString .= "SELECT ua.umpire_type_name, ua.age_group, " .
            "IFNULL(SUM(`BFL`),0), " .
            "IFNULL(SUM(`GDFL`),0), " .
            "IFNULL(SUM(`GFL`),0), " .
            "IFNULL(SUM(`None`),0), " .
            "IFNULL(SUM(`BFL`+`GDFL`+`GFL`+`None`),0) " .
            "FROM ( " .
            "SELECT ut.id AS umpire_type_id, ut.umpire_type_name, " .
            "ag.id AS age_group_id, ag.age_group " .
            "FROM umpire_type ut, age_group ag " .
            ") AS ua LEFT JOIN (";
  
      $queryString .= "SELECT umpire_type, age_group, age_group_ID,  " .
        	"(CASE WHEN short_league_name = 'BFL' THEN match_count ELSE 0 END) as `BFL`, " .
        	"(CASE WHEN short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `GDFL`, " .
        	"(CASE WHEN short_league_name = 'GFL' THEN match_count ELSE 0 END) as `GFL`, " .
        	"(CASE WHEN short_league_name = 'None' THEN match_count ELSE 0 END) as `None` " .
        	"FROM ( " .
			"SELECT s.umpire_type, s.age_group, s.short_league_name, s.age_group_ID, " .
			"COUNT(s.match_id) AS Match_Count " .
			"FROM mv_summary_staging s " .
			"GROUP BY s.age_group, s.umpire_type, s.short_league_name, s.age_group_ID " .
    		") AS outer1 " .
            ") AS outer2 ON ua.umpire_type_name = outer2.umpire_type " .
            "AND ua.age_group = outer2.age_group " .
            "GROUP BY ua.umpire_type_id, ua.age_group_id " .
            "ORDER BY ua.umpire_type_id, ua.age_group_id";

      $this->db->query($queryString);
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadUmpireNameTypeMatchTable SQL:<BR />" . $queryString . "<BR />";
          echo "Query run: reloadMVReport03Table, " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  private function logImportedFile($filename) {
      $session_data = $this->session->userdata('logged_in');
      $username = $session_data['username'];
      
      $data = array(
          'filename' => $filename,
          'imported_datetime' => date('Y-m-d H:i:s', time()),
          'imported_user_id' => $username
      );

      $queryStatus = $this->db->insert('imported_files', $data);
      //echo "Query run: logImportedFile, " . $this->db->affected_rows() . " rows.<BR />";
      
  }
  /*
  private function recreateMatchImportIndexes() {
      $this->db->query("CREATE INDEX idx_matchimport_date ON umpire.match_import(date);");
      $this->db->query("CREATE INDEX idx_matchimport_round ON umpire.match_import(round);");
      $this->db->query("CREATE INDEX idx_matchimport_competition_name ON umpire.match_import(competition_name);");
      $this->db->query("CREATE INDEX idx_matchimport_season ON umpire.match_import(season);");
      $this->db->query("CREATE INDEX idx_matchimport_ground ON umpire.match_import(ground);");
      $this->db->query("CREATE INDEX idx_matchimport_home_team ON umpire.match_import(home_team);");
      $this->db->query("CREATE INDEX idx_matchimport_away_team ON umpire.match_import(away_team);");
      
  }
  
  private function recreateNormalisedTablendexes() {
      $this->db->query("CREATE INDEX idx_team_team_name ON umpire.team(team_name);");
      $this->db->query("CREATE INDEX idx_ground_alternative_name ON umpire.ground(alternative_name);");
  }
  
  private function recreateMVIndexes() {
      $this->db->query("CREATE INDEX idx_mv01_short_league_name ON umpire.mv_report_01(short_league_name);");

      
  }*/
  
  
  
}
?>


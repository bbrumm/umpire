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
      //TODO: Convert this process to use a season object instead of two variables
      $seasonToUpdate = $this->findSeasonToUpdate();
      $seasonYearToUpdate = $this->findSeasonYearFromID($seasonToUpdate);
      
      $this->deleteFromTables($seasonToUpdate);
      $this->reloadTables($seasonYearToUpdate);
  }
  
  private function deleteFromTables($seasonToUpdate) {
      //Before deleting from tables, find out which season to delete data from
      //This is because the file imported will relate to one season, and we don't want to delete data for all seasons.
      
      
      //Delete from tables for the season that is being reloaded
      $this->deleteFromUmpire($seasonToUpdate);
      $this->deleteFromUmpireNameType($seasonToUpdate);
      $this->deleteFromUmpireNameTypeMatch($seasonToUpdate);
      $this->deleteFromMatchPlayed($seasonToUpdate);
      $this->deleteFromRound($seasonToUpdate);
      $this->deleteFromSingleTable('match_staging');
      
      
  }
  
  private function reloadTables($seasonYearToUpdate) {
      //Reload tables from the imported data
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
      $this->reloadMVReport01Table($seasonYearToUpdate);
      $this->reloadMVReport02Table($seasonYearToUpdate);
      $this->reloadMVSummaryStaging($seasonYearToUpdate);
      $this->reloadMVReport03Table($seasonYearToUpdate);
      $this->reloadMVReport04Table($seasonYearToUpdate);
      $this->reloadMVReport05Table($seasonYearToUpdate);
      $this->reloadMVReport06Table($seasonYearToUpdate);
  }
  
  private function findSeasonToUpdate() {
      $queryString = "SELECT MAX(season.ID) AS season_id " .
        "FROM season " .
        "INNER JOIN match_import ON season.season_year = match_import.season;";
      $query = $this->db->query($queryString);
      $resultArray = $query->result_array();
      //echo "findSeasonToUpdate: ". $resultArray[0]['season_id'] . "<BR />";
      return $resultArray[0]['season_id'];
  }
  
  private function findSeasonYearFromID($seasonID) {
      $queryString = "SELECT season_year " .
          "FROM season " .
          "WHERE id = $seasonID;";
      $query = $this->db->query($queryString);
      $resultArray = $query->result_array();
      //echo "findSeasonYearFromID: ". $resultArray[0]['season_year'] . "<BR />";
      return $resultArray[0]['season_year'];
      
  }
  
  private function deleteFromSingleTable($tableName) {
      $queryString = "DELETE FROM ". $tableName;
      $this->db->query($queryString);
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--deleteFromSingleTable SQL:<BR />" . $queryString . "<BR />";
          echo "Table deleted: " . $tableName . ", " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  private function deleteFromSingleTableForSeason($tableName, $seasonYearToDelete) {
      $queryString = "DELETE FROM ". $tableName . " WHERE season_year = '$seasonYearToDelete';";
      $this->db->query($queryString);
  
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--deleteFromSingleTableForSeason SQL:<BR />" . $queryString . "<BR />";
          echo "Table deleted: " . $tableName . ", " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  private function deleteFromUmpireNameTypeMatch($seasonID) {
      $queryString = "DELETE umpire_name_type_match " .
        "FROM umpire_name_type_match " . 
        "INNER JOIN match_played ON umpire_name_type_match.match_ID = match_played.ID " . 
        "INNER JOIN round ON match_played.round_id = round.ID " .
        "WHERE round.season_id = " . $seasonID;
      $this->db->query($queryString);
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--deleteFromUmpireNameTypeMatch SQL:<BR />" . $queryString . "<BR />";
          echo "Table deleted: umpire_name_type_match, " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  private function deleteFromMatchPlayed($seasonID) {
      $queryString = "DELETE match_played " .
          "FROM match_played " .
          "INNER JOIN round ON match_played.round_id = round.ID " .
          "WHERE round.season_id = " . $seasonID;
      $this->db->query($queryString);
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--deleteFromMatchPlayed SQL:<BR />" . $queryString . "<BR />";
          echo "Table deleted: deleteFromMatchPlayed, " . $this->db->affected_rows() . " rows.<BR />";
      } 
  }
  
  private function deleteFromUmpireNameType($seasonID) {
      $queryString = "DELETE umpire_name_type " .
          "FROM umpire_name_type " .
          "INNER JOIN umpire_name_type_match ON umpire_name_type.id = umpire_name_type_match.umpire_name_type_id " .
          "INNER JOIN match_played ON umpire_name_type_match.match_ID = match_played.ID " .
          "INNER JOIN round ON match_played.round_id = round.ID " .
          "WHERE round.season_id = " . $seasonID;
      $this->db->query($queryString);
  
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--deleteFromUmpireNameType SQL:<BR />" . $queryString . "<BR />";
          echo "Table deleted: deleteFromUmpireNameType, " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  private function deleteFromUmpire($seasonID) {
      $queryString = "DELETE umpire " .
          "FROM umpire " .
          "INNER JOIN umpire_name_type ON umpire_name_type.umpire_id = umpire.ID " .
          "INNER JOIN umpire_name_type_match ON umpire_name_type.id = umpire_name_type_match.umpire_name_type_id " .
          "INNER JOIN match_played ON umpire_name_type_match.match_ID = match_played.ID " .
          "INNER JOIN round ON match_played.round_id = round.ID " .
          "WHERE round.season_id = " . $seasonID;
      $this->db->query($queryString);
  
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--deleteFromUmpire SQL:<BR />" . $queryString . "<BR />";
          echo "Table deleted: deleteFromUmpire, " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  private function deleteFromRound($seasonID) {
      $queryString = "DELETE round " .
          "FROM round " .
          "WHERE round.season_id = " . $seasonID;
      $this->db->query($queryString);
  
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--deleteFromRound SQL:<BR />" . $queryString . "<BR />";
          echo "Table deleted: deleteFromRound, " . $this->db->affected_rows() . " rows.<BR />";
      }
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
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadRoundTable SQL:<BR />" . $queryString . "<BR />";
          echo "Query run: reloadRoundTable, " . $this->db->affected_rows() . " rows.<BR />";
      }
      
      
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
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadUmpireTable SQL:<BR />" . $queryString . "<BR />";
          echo "Query run: reloadUmpireTable, " . $this->db->affected_rows() . " rows.<BR />";
      }
      
      
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
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadUmpireNameTypeTable SQL:<BR />" . $queryString . "<BR />";
      }
      
      
      $this->db->query($queryString);
      

      if ($debugMode) {
          
          echo "Query run: reloadUmpireNameTypeTable, " . $this->db->affected_rows() . " rows.<BR />";
      }
      
      
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
        "STR_TO_DATE(CONCAT(match_import.date, ' ', match_import.time), '%d/%m/%Y %h:%i %p'), " .  
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
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadMatchStagingTable SQL:<BR />" . $queryString . "<BR />";
      }
      
      $this->db->query($queryString);
      
      if ($debugMode) {
          echo "--reloadMatchStagingTable SQL:<BR />" . $queryString . "<BR />";
      }

      
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
        
        $debugMode = $this->config->item('debug_mode');
        if ($debugMode) {
            echo "--deleteDuplicateMatches SQL:<BR />" . $queryString . "<BR />";
        }
        
        $this->db->query($queryString);
      
      
      if ($debugMode) {
          echo "Query run: deleteDuplicateMatches<BR />";
      }
      
      
      
  }
  
  private function reloadMatchTable() {
      $queryString = "INSERT INTO match_played (round_ID, ground_id, home_team_id, away_team_id, match_time) " .
        "SELECT match_staging.round_ID, match_staging.ground_id, match_staging.home_team_id, match_staging.away_team_id, match_staging.appointments_time " .
        "FROM match_staging";
      $this->db->query($queryString);
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadMatchTable SQL:<BR />" . $queryString . "<BR />";
          echo "Query run: reloadMatchTable, " . $this->db->affected_rows() . " rows.<BR />";
      }
      
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
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadUmpireNameTypeMatchTable SQL:<BR />" . $queryString . "<BR />";
          echo "Query run: reloadUmpireNameTypeMatchTable, " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  private function reloadMVReport01Table($seasonToUpdate) {
      //First, delete the data from the table
      //$reportTableName = $this->lookupReportTableName('1');
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('1');
      $this->deleteFromSingleTableForSeason($reportTableName, $seasonToUpdate);
      
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_01 (season_year, full_name, club_name, short_league_name, age_group, umpire_type_name,  " .
        "`GDFL|Anakie`, `GDFL|Bannockburn`, `GJFL|Corio`, `GDFL|East_Geelong`, `GDFL|North_Geelong`, `GJFL|Portarlington`, `GDFL|Werribee_Centrals`, " .
        "`GDFL|Winchelsea`, `GFL|Bell_Park`, `GDFL|Bell_Post_Hill`, `GDFL|Belmont_Lions`, `GFL|Colac`, `BFL|Geelong_Amateur`, `GDFL|Geelong_West`, " .
        "`GFL|Grovedale`, `GFL|Gwsp`, `GDFL|Inverleigh`, `GFL|Lara`, `GFL|Leopold`, `GFL|Newtown_&_Chilwell`, `GFL|North_Shore`, " .
        "`GFL|South_Barwon`, `GFL|St_Joseph's`, `GFL|St_Mary's`, `BFL|Torquay`, `GJFL|Barwon_Heads`, `GJFL|Drysdale`, `GJFL|East_Geelong`, " .
        "`GJFL|Geelong_West_St_Peters`, `GJFL|Grovedale`, `GJFL|Inverleigh`, `GJFL|Leopold`, `GJFL|Newcomb`, `GJFL|Newtown_&_Chilwell`, `GJFL|Ocean_Grove`, " .
        "`GJFL|South_Barwon`, `GJFL|St_Albans`, `GJFL|St_Joseph's`, `GJFL|St_Mary's`, `GJFL|Torquay`, `BFL|Anglesea`, `BFL|Barwon_Heads`, " .
        "`GDFL|Corio`, `GDFL|Thomson`, `GJFL|Anglesea`, `GJFL|Bell_Park`, `GJFL|North_Shore`, `GJFL|Belmont_Lions`, `GJFL|Colac`, " .
        "`GJFL|North_Geelong`, `GJFL|Ogcc`, `GJFL|Torquay_Jones`, `GJFL|Torquay_Papworth`, `GJFL|Winchelsea_/_Grovedale`, `BFL|Modewarre`, `BFL|Newcomb_Power`, " .
        "`BFL|Queenscliff`, `GFL|St_Albans`, `GJFL|Drysdale_Byrne`, `GJFL|Drysdale_Hall`, `GJFL|Drysdale_Hector`, `GJFL|Lara`, `GJFL|Queenscliff`, " .
        "`GJFL|St_Albans_Reid`, `GJFL|Torquay_Bumpstead`, `GJFL|Torquay_Pyers`, `GJFL|Modewarre`, `BFL|Ocean_Grove`, `BFL|Drysdale`, `BFL|Portarlington`, " .
        "`GJFL|St_Joseph's_Podbury`, `GJFL|Geelong_Amateur`, `GJFL|Winchelsea`, `GJFL|Anakie`, `GJFL|Bannockburn`, `GJFL|South_Barwon_/_Geelong_Amateur`, " .
        "`GJFL|St_Joseph's_Hill`, `GJFL|Torquay_Dunstan`, `GJFL|Werribee_Centrals`, `GJFL|Drysdale_Eddy`, `GJFL|Belmont_Lions_/_Newcomb`, `GJFL|Torquay_Coles`, " .
        "`GJFL|Gwsp_/_Bannockburn`, `GJFL|St_Albans_Allthorpe`, `GJFL|Drysdale_Bennett`, `GJFL|Torquay_Scott`, `GJFL|Torquay_Nairn`, `GJFL|Tigers_Gold`, " .
        "`CDFNL|Birregurra`, `CDFNL|Lorne`, `CDFNL|Colac Imperials`, `CDFNL|Irrewarra-beeac`, `CDFNL|Otway Districts`, `CDFNL|Simpson`, `CDFNL|South Colac`, `CDFNL|Western Eagles`) ";
        $queryString .= "SELECT season_year, CONCAT(last_name,', ',first_name) AS full_name, club_name, short_league_name, age_group, umpire_type_name,  " .
        "(CASE WHEN club_name = 'Anakie' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Anakie', " .
        "(CASE WHEN club_name = 'Bannockburn' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Bannockburn', " .
        "(CASE WHEN club_name = 'Corio' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Corio', " .
        "(CASE WHEN club_name = 'East Geelong' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|East_Geelong', " .
        "(CASE WHEN club_name = 'North Geelong' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|North_Geelong', " .
        "(CASE WHEN club_name = 'Portarlington' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Portarlington', " .
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
      $queryString .= "(CASE WHEN club_name = 'Barwon Heads' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Barwon_Heads', " .
        "(CASE WHEN club_name = 'Drysdale' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale', " .
        "(CASE WHEN club_name = 'East Geelong' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|East_Geelong', " .
        "(CASE WHEN club_name = 'Geelong West St Peters' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Geelong_West_St_Peters', " .
        "(CASE WHEN club_name = 'Grovedale' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Grovedale', " .
        "(CASE WHEN club_name = 'Inverleigh' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Inverleigh', " .
        "(CASE WHEN club_name = 'Leopold' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Leopold', " .
        "(CASE WHEN club_name = 'Newcomb' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Newcomb', " .
        "(CASE WHEN club_name = 'Newtown & Chilwell' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Newtown_&_Chilwell', " .
        "(CASE WHEN club_name = 'Ocean Grove' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ocean_Grove', " .
        "(CASE WHEN club_name = 'South Barwon' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|South_Barwon', " .
        "(CASE WHEN club_name = 'St Albans' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Albans', " .
        "(CASE WHEN club_name = 'St Joseph''s' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Joseph\'s', " .
        "(CASE WHEN club_name = 'St Mary''s' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Mary\'s', " .
        "(CASE WHEN club_name = 'Torquay' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay', " .
        "(CASE WHEN club_name = 'Anglesea' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Anglesea', " .
        "(CASE WHEN club_name = 'Barwon Heads' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Barwon_Heads', " .
        "(CASE WHEN club_name = 'Corio' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Corio', " .
        "(CASE WHEN club_name = 'Thomson' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Thomson', " .
        "(CASE WHEN club_name = 'Anglesea' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Anglesea', " .
        "(CASE WHEN club_name = 'Bell Park' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Bell_Park', " .
        "(CASE WHEN club_name = 'North Shore' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|North_Shore', " .
        "(CASE WHEN club_name = 'Belmont Lions' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Belmont_Lions',";
      $queryString .= "(CASE WHEN club_name = 'Colac' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Colac', " .
        "(CASE WHEN club_name = 'North Geelong' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|North_Geelong', " .
        "(CASE WHEN club_name = 'Ogcc' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ogcc', " .
        "(CASE WHEN club_name = 'Torquay Jones' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Jones', " .
        "(CASE WHEN club_name = 'Torquay Papworth' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Papworth', " .
        "(CASE WHEN club_name = 'Winchelsea / Grovedale' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Winchelsea_/_Grovedale', " .
        "(CASE WHEN club_name = 'Modewarre' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Modewarre', " .
        "(CASE WHEN club_name = 'Newcomb Power' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Newcomb_Power', " .
        "(CASE WHEN club_name = 'Queenscliff' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Queenscliff', " .
        "(CASE WHEN club_name = 'St Albans' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|St_Albans', " .
        "(CASE WHEN club_name = 'Drysdale Byrne' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Byrne', " .
        "(CASE WHEN club_name = 'Drysdale Hall' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Hall', " .
        "(CASE WHEN club_name = 'Drysdale Hector' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Hector', " .
        "(CASE WHEN club_name = 'Lara' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Lara', " .
        "(CASE WHEN club_name = 'Queenscliff' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Queenscliff', " .
        "(CASE WHEN club_name = 'St Albans Reid' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Albans_Reid', " .
        "(CASE WHEN club_name = 'Torquay Bumpstead' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Bumpstead', " .
        "(CASE WHEN club_name = 'Torquay Pyers' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Pyers', " .
        "(CASE WHEN club_name = 'Modewarre' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Modewarre', " .
        "(CASE WHEN club_name = 'Ocean Grove' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Ocean_Grove', " .
        "(CASE WHEN club_name = 'Drysdale' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Drysdale', " .
        "(CASE WHEN club_name = 'Portarlington' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Portarlington', " .
        "(CASE WHEN club_name = 'St Joseph''s Podbury' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Joseph\'s_Podbury', " .
        "(CASE WHEN club_name = 'Geelong Amateur' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Geelong_Amateur', ";
      $queryString .= "(CASE WHEN club_name = 'Winchelsea' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Winchelsea', " .
        "(CASE WHEN club_name = 'Anakie' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Anakie', " .
        "(CASE WHEN club_name = 'Bannockburn' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Bannockburn', " .
        "(CASE WHEN club_name = 'South Barwon / Geelong Amateur' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|South_Barwon_/_Geelong_Amateur', " .
        "(CASE WHEN club_name = 'St Joseph''s Hill' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Joseph\'s_Hill', " .
        "(CASE WHEN club_name = 'Torquay Dunstan' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Dunstan', " .
        "(CASE WHEN club_name = 'Werribee Centrals' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Werribee_Centrals', " .
        "(CASE WHEN club_name = 'Drysdale Eddy' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Eddy', " .
        "(CASE WHEN club_name = 'Belmont Lions / Newcomb' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Belmont_Lions_/_Newcomb', " .
        "(CASE WHEN club_name = 'Torquay Coles' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Coles', " .
        "(CASE WHEN club_name = 'Gwsp / Bannockburn' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Gwsp_/_Bannockburn', " .
        "(CASE WHEN club_name = 'St Albans Allthorpe' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Albans_Allthorpe', " .
        "(CASE WHEN club_name = 'Drysdale Bennett' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Bennett', " .
        "(CASE WHEN club_name = 'Torquay Scott' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Scott', " .
        "(CASE WHEN club_name = 'Torquay Nairn' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Nairn', " .
        "(CASE WHEN club_name = 'Tigers Gold' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Tigers_Gold', " .
        "(CASE WHEN club_name = 'Birregurra' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Birregurra', " .
        "(CASE WHEN club_name = 'Lorne' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Lorne', " .
        "(CASE WHEN club_name = 'Colac Imperials' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Colac Imperials', " .
        "(CASE WHEN club_name = 'Irrewarra-beeac' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Irrewarra-beeac', " .
        "(CASE WHEN club_name = 'Otway Districts' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Otway Districts', " .
        "(CASE WHEN club_name = 'Simpson' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Simpson', " .
        "(CASE WHEN club_name = 'South Colac' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|South Colac', " .
        "(CASE WHEN club_name = 'Western Eagles' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Western Eagles' ";

      $queryString .= "FROM (SELECT DISTINCT season_year, umpire.first_name, umpire.last_name, match_played.ID, team.team_name, club.club_name, league.short_league_name, age_group.age_group, umpire_type.umpire_type_name " .
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
        "INNER JOIN season ON season.id = round.season_id " .
        "WHERE season.season_year = '$seasonToUpdate' " .
        "UNION ALL " .
        "SELECT DISTINCT season_year, umpire.first_name, umpire.last_name, match_played.ID, team.team_name, club.club_name, league.short_league_name, age_group.age_group, umpire_type.umpire_type_name " .
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
        "INNER JOIN season ON season.id = round.season_id " .
        "WHERE season.season_year = '$seasonToUpdate' " .
        ")  AS a " .
        "GROUP BY season_year, first_name, last_name, club_name, short_league_name, age_group, umpire_type_name " .
        "ORDER BY season_year, first_name, last_name, club_name, short_league_name, age_group, umpire_type_name";

      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadMVReport01Table SQL:<BR />" . $queryString . "<BR />";
      
      }
      
      $this->db->query($queryString);

      if ($debugMode) {
         echo "Query run: reloadMVReport01Table, " . $this->db->affected_rows() . " rows.<BR />";
      }
  
  }
  
  private function reloadMVReport02Table($seasonToUpdate) {
      //First, delete the data from the table
      //$reportTableName = $this->lookupReportTableName('1');
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('2');
      $this->deleteFromSingleTableForSeason($reportTableName, $seasonToUpdate);
  
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_02 (season_year, full_name, umpire_type_name, short_league_name, age_group, " . 
            "`Seniors|BFL`, `Seniors|GDFL`, `Seniors|GFL`, `Reserves|BFL`, `Reserves|GDFL`, `Reserves|GFL`, `Colts|GJFL`, " . 
            "`Under 16|GJFL`, `Under 14|GJFL`, `Youth Girls|GJFL`, `Junior Girls|GJFL`, `Seniors|2 Umpires`, " .
            "`Seniors|CDFNL`, `Reserves|CDFNL`, `Under 17.5|CDFNL`, `Under 14.5|CDFNL`) ";
      $queryString .= "SELECT season_year, " . 
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
            "(CASE WHEN age_group = 'Colts' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Under 16' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Under 14' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Youth Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Junior Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END), " . 
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = '2 Umpires' THEN match_count ELSE 0 END), " .
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END), " .
            "(CASE WHEN age_group = 'Reserves' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END), " .
            "(CASE WHEN age_group = 'Under 17.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END), " .
            "(CASE WHEN age_group = 'Under 14.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) " .
            "FROM ( " . 
            "SELECT  " . 
            "season_year, " .
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
            "INNER JOIN season ON season.id = round.season_id " .
            "WHERE season.season_year = '$seasonToUpdate' " .
            "GROUP BY season_year, umpire_type.umpire_type_name , age_group.ID , age_group.age_group , league.short_league_name , CONCAT(last_name,', ',first_name) " . 
            "UNION ALL " . 
            "SELECT  " . 
            "season.season_year, " .
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
            "INNER JOIN season ON season.id = round.season_id " .
            "INNER JOIN ( ";
      $queryString .= "SELECT  " . 
            "season.season_year, " .
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
            "INNER JOIN season ON season.id = round.season_id " .
            "WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors' " . 
            "AND season.season_year = '$seasonToUpdate' " .
            "GROUP BY season.season_year, match_played.ID, umpire_type.umpire_type_name, age_group.age_group " . 
            "HAVING COUNT(umpire.ID) = 2 " . 
            ") AS qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id " . 
            "WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors' " . 
            "AND season.season_year = '$seasonToUpdate' " .
            "GROUP BY season.season_year, umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , CONCAT(last_name,', ',first_name) " . 
            ") AS mainquery " . 
            "ORDER BY season_year, full_name";

      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadMVReport02Table SQL:<BR />" . $queryString . "<BR />";
      
      }
      $this->db->query($queryString);
      
      if ($debugMode) {
          
          echo "Query run: reloadMVReport02Table, " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  private function reloadMVSummaryStaging($seasonToUpdate) {
      //First, delete the data from the table
      $this->deleteFromSingleTable("mv_summary_staging");
      
      //Then, insert into table
      $queryString = "INSERT INTO mv_summary_staging (season_year, region, umpire_type_id, umpire_type, age_group, short_league_name, " .
          "round_date, match_id, home, away, home_club, away_club, age_group_ID, weekdate) ";
      $queryString .= "SELECT season_year, region_name, umpire_type_id, umpire_type, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID,  ".
            "ADDDATE(round_date, (5-Weekday(round_date))) as WeekDate ".
            "FROM ( ";
      $queryString .= "SELECT season_year, region.region_name, 1 as umpire_type_id, 'Field' AS umpire_type, age_group.age_group, league.short_league_name,  ".
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
            "INNER JOIN season ON season.ID = round.season_id ".
            "INNER JOIN region ON league.region_id = region.id " .
            "WHERE match_played.id NOT IN ( ".
            "SELECT umpire_name_type_match.match_id ".
            "FROM umpire_name_type_match ".
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id ".
            "INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id ".
            "WHERE ut_sub.umpire_type_name='Field' ".
            ") ".
            "AND season.season_year = '$seasonToUpdate' " .
            "UNION ".
            "SELECT season_year, region.region_name, 3 as umpire_type_id, 'Goal' AS umpire_type, age_group.age_group, league.short_league_name,  ".
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
            "INNER JOIN season ON season.ID = round.season_id ".
            "INNER JOIN region ON league.region_id = region.id " .
            "WHERE match_played.id NOT IN ( ".
            "SELECT umpire_name_type_match.match_id ".
            "FROM umpire_name_type_match ".
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id ".
            "INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id ".
            "WHERE ut_sub.umpire_type_name='Goal' ".
            ") " .
            "AND season.season_year = '$seasonToUpdate' ";
      $queryString .= "UNION ".
            "SELECT season_year, region.region_name, 2 as umpire_type_id, 'Boundary' AS umpire_type, age_group.age_group, league.short_league_name,  ".
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
            "INNER JOIN season ON season.ID = round.season_id ".
            "INNER JOIN region ON league.region_id = region.id " .
            "WHERE match_played.id NOT IN ( ".
            "SELECT umpire_name_type_match.match_id ".
            "FROM umpire_name_type_match ".
            "INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id ".
            "INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id ".
            "WHERE ut_sub.umpire_type_name='Boundary' ".
            ") ".
            "AND season.season_year = '$seasonToUpdate' " .
            ") AS outer1";
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadMVSummaryStaging SQL:<BR />" . $queryString . "<BR />";
      
      }
      
      $this->db->query($queryString);
      
      
      if ($debugMode) {
          
          echo "Query run: reloadMVSummaryStaging, " . $this->db->affected_rows() . " rows.<BR />";
      }
      
  }
  
  private function reloadMVReport03Table($seasonToUpdate) {
      //First, delete the data from the table
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('3');
      $this->deleteFromSingleTableForSeason($reportTableName, $seasonToUpdate);
  
      //Then, insert into table
      $queryString = "INSERT INTO `mv_report_03` (season_year, region, weekdate, " .
            "`No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, " .
            "`No Senior Goal|BFL`, `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, " .
            "`No Reserve Goal|BFL`, `No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, " .
            "`No Colts Field|Clubs`, `No Colts Field|No.`, `No U16 Field|Clubs`, `No U16 Field|No.`, " .
            "`No U14 Field|Clubs`, `No U14 Field|No.`, " .
            "`No Senior Boundary|CDFNL`, " .
            "`No Senior Goal|CDFNL`, " .
            "`No Reserve Goal|CDFNL`) " .
            "SELECT season_year, region, weekdate, " .
            "MAX(`No Senior Boundary|BFL`), MAX(`No Senior Boundary|GDFL`), MAX(`No Senior Boundary|GFL`), SUM(`No Senior Boundary|No.`), MAX(`No Senior Goal|BFL`), " .
            "MAX(`No Senior Goal|GDFL`), MAX(`No Senior Goal|GFL`), SUM(`No Senior Goal|No.`), MAX(`No Reserve Goal|BFL`), MAX(`No Reserve Goal|GDFL`), " .
            "MAX(`No Reserve Goal|GFL`), SUM(`No Reserve Goal|No.`), MAX(`No Colts Field|Clubs`),SUM(`No Colts Field|No.`), MAX(`No U16 Field|Clubs`), " .
            "SUM(`No U16 Field|No.`), MAX(`No U14 Field|Clubs`), SUM(`No U14 Field|No.`), " .
            "MAX(`No Senior Boundary|CDFNL`), MAX(`No Senior Goal|CDFNL`), MAX(`No Reserve Goal|CDFNL`) " . 
            "FROM (";
      $queryString .= "SELECT season_year, region, weekdate, ".
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
            "(CASE WHEN age_group = 'Under 14' AND umpire_type = 'Field' THEN match_count ELSE 0 END)  as `No U14 Field|No.`, ".
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'CDFNL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END) as `No Senior Boundary|CDFNL`, ".
            "(CASE WHEN age_group = 'Seniors' AND short_league_name = 'CDFNL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Senior Goal|CDFNL`, ".
            "(CASE WHEN age_group = 'Reserve' AND short_league_name = 'CDFNL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Reserve Goal|CDFNL` ".
            "FROM (";
            
      $queryString .= "SELECT season_year, region, umpire_type, age_group, short_league_name, weekdate, ".
            "GROUP_CONCAT(home, ' vs ', away) AS team_list, ".
            "COUNT(home) AS match_count  ".
            "FROM ( ".
            "SELECT season_year, region, umpire_type_id, umpire_type, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID,  ".
            "weekdate ".
            "FROM mv_summary_staging " .
            "WHERE season_year = '$seasonToUpdate' " .
            ") AS outer2 ".
            "GROUP BY season_year, region, umpire_type, age_group, short_league_name, weekdate ";
      $queryString .= ") AS outer3 " .
            "GROUP BY season_year, region, weekdate,  " .
        		"`No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, `No Senior Goal|BFL`, " .
        	 "`No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, `No Reserve Goal|BFL`, `No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, `No Colts Field|Clubs`, " .
        	 "`No Colts Field|No.`, `No U16 Field|Clubs`, `No U16 Field|No.`, `No U14 Field|Clubs`, `No U14 Field|No.`, " .
        	 "`No Senior Boundary|CDFNL`, `No Senior Goal|CDFNL`, `No Reserve Goal|CDFNL` " .
        	") as maintable " .
        "GROUP BY  season_year, region, weekdate, `No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, " . 
        "`No Senior Goal|BFL`, `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, `No Reserve Goal|BFL`,  " .
        "`No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, `No Colts Field|Clubs`,`No Colts Field|No.`,  " .
        "`No U16 Field|Clubs`, `No U16 Field|No.`, `No U14 Field|Clubs`, `No U14 Field|No.`, " .
        "`No Senior Boundary|CDFNL`, `No Senior Goal|CDFNL`, `No Reserve Goal|CDFNL` " .
        "ORDER BY season_year, region, weekdate";
  
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadMVReport03Table SQL:<BR />" . $queryString . "<BR />";
      }
      
      
      $this->db->query($queryString);
      

      if ($debugMode) {
          
          echo "Query run: reloadMVReport03Table, " . $this->db->affected_rows() . " rows.<BR />";
      }
  
  }
  
  private function reloadMVReport04Table($seasonToUpdate) {
      //First, delete the data from the table
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('4');
      $this->deleteFromSingleTableForSeason($reportTableName, $seasonToUpdate);
  
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_04 (season_year, region, club_name, `Boundary|Seniors|BFL`, ".
        "`Boundary|Seniors|GDFL`, `Boundary|Seniors|GFL`, `Boundary|Reserves|BFL`, `Boundary|Reserves|GDFL`, ".
        "`Boundary|Reserves|GFL`, `Boundary|Colts|GJFL`, `Boundary|Under 16|GJFL`, `Boundary|Under 14|GJFL`, ".
        "`Boundary|Youth Girls|GJFL`, `Boundary|Junior Girls|GJFL`, `Field|Seniors|BFL`, `Field|Seniors|GDFL`, ".
        "`Field|Seniors|GFL`, `Field|Reserves|BFL`, `Field|Reserves|GDFL`, `Field|Reserves|GFL`, ".
        "`Field|Colts|GJFL`, `Field|Under 16|GJFL`, `Field|Under 14|GJFL`, `Field|Youth Girls|GJFL`, ".
        "`Field|Junior Girls|GJFL`, `Goal|Seniors|BFL`, `Goal|Seniors|GDFL`, `Goal|Seniors|GFL`, ".
        "`Goal|Reserves|BFL`, `Goal|Reserves|GDFL`, `Goal|Reserves|GFL`, `Goal|Colts|GJFL`, ".
        "`Goal|Under 16|GJFL`, `Goal|Under 14|GJFL`, `Goal|Youth Girls|GJFL`, `Goal|Junior Girls|GJFL`, " .
        "`Boundary|Seniors|CDFNL`, `Boundary|Reserves|CDFNL`, `Boundary|Under 17.5|CDFNL`, `Boundary|Under 14.5|CDFNL`, " .
        "`Field|Seniors|CDFNL`, `Field|Reserves|CDFNL`, `Field|Under 17.5|CDFNL`, `Field|Under 14.5|CDFNL`, " .
        "`Goal|Seniors|CDFNL`, `Goal|Reserves|CDFNL`, `Goal|Under 17.5|CDFNL`, `Goal|Under 14.5|CDFNL`) ";
      $queryString .= "SELECT season_year, region, club, SUM(`Boundary|Seniors|BFL`), SUM(`Boundary|Seniors|GDFL`), SUM(`Boundary|Seniors|GFL`), ".
        "SUM(`Boundary|Reserves|BFL`), SUM(`Boundary|Reserves|GDFL`), SUM(`Boundary|Reserves|GFL`), SUM(`Boundary|Colts|GJFL`), ".
        "SUM(`Boundary|Under 16|GJFL`), SUM(`Boundary|Under 14|GJFL`), SUM(`Boundary|Youth Girls|GJFL`), SUM(`Boundary|Junior Girls|GJFL`), ".
        "SUM(`Field|Seniors|BFL`), SUM(`Field|Seniors|GDFL`), SUM(`Field|Seniors|GFL`), SUM(`Field|Reserves|BFL`), ".
        "SUM(`Field|Reserves|GDFL`), SUM(`Field|Reserves|GFL`), SUM(`Field|Colts|GJFL`), SUM(`Field|Under 16|GJFL`), ".
        "SUM(`Field|Under 14|GJFL`), SUM(`Field|Youth Girls|GJFL`), SUM(`Field|Junior Girls|GJFL`), SUM(`Goal|Seniors|BFL`), ".
        "SUM(`Goal|Seniors|GDFL`), SUM(`Goal|Seniors|GFL`), SUM(`Goal|Reserves|BFL`), SUM(`Goal|Reserves|GDFL`), ".
        "SUM(`Goal|Reserves|GFL`), SUM(`Goal|Colts|GJFL`), SUM(`Goal|Under 16|GJFL`), SUM(`Goal|Under 14|GJFL`), ".
        "SUM(`Goal|Youth Girls|GJFL`), SUM(`Goal|Junior Girls|GJFL`), ".
        "SUM(`Boundary|Seniors|CDFNL`), SUM(`Boundary|Reserves|CDFNL`), SUM(`Boundary|Under 17.5|CDFNL`), SUM(`Boundary|Under 14.5|CDFNL`), ".
        "SUM(`Field|Seniors|CDFNL`), SUM(`Field|Reserves|CDFNL`), SUM(`Field|Under 17.5|CDFNL`), SUM(`Field|Under 14.5|CDFNL`), ".
        "SUM(`Goal|Seniors|CDFNL`), SUM(`Goal|Reserves|CDFNL`), SUM(`Goal|Under 17.5|CDFNL`), SUM(`Goal|Under 14.5|CDFNL`) ".
        "FROM ( ";
      
      $queryString .= "SELECT season_year, region, club, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|BFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|GDFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|GFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|BFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|GDFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|GFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Colts' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Colts|GJFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 16' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Under 16|GJFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 14' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Under 14|GJFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Youth Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Youth Girls|GJFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Junior Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Junior Girls|GJFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Field|Seniors|BFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Field|Seniors|GDFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Field|Seniors|GFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Field|Reserves|BFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Field|Reserves|GDFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Field|Reserves|GFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Colts' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Colts|GJFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Under 16' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Under 16|GJFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Under 14' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Under 14|GJFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Youth Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Youth Girls|GJFL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Junior Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Junior Girls|GJFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Goal|Seniors|BFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Goal|Seniors|GDFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Goal|Seniors|GFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Goal|Reserves|BFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Goal|Reserves|GDFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Goal|Reserves|GFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Colts' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Colts|GJFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 16' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Under 16|GJFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 14' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Under 14|GJFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Youth Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Youth Girls|GJFL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Junior Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Junior Girls|GJFL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Boundary|Seniors|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Boundary|Reserves|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 17.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Boundary|Under 17.5|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 14.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Boundary|Under 14.5|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Field|Seniors|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Field|Reserves|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Under 17.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Field|Under 17.5|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Field' AND age_group = 'Under 14.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Field|Under 14.5|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Goal|Seniors|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Goal|Reserves|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 17.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Goal|Under 17.5|CDFNL`, ".
        "(CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 14.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Goal|Under 14.5|CDFNL` ".
        "FROM ( ";
      $queryString .= "SELECT season_year, region, age_group, umpire_type, Club, short_league_name, SUM(Match_Count) AS match_count ".
            "FROM ( ".
            "SELECT season_year, region, 'Home' as Club_Type, s.age_group, s.umpire_type, s.home_club as Club, s.short_league_name,  ".
            "COUNT(s.age_group_ID) AS Match_Count, age_group_ID ".
            "FROM mv_summary_staging s ".
            "WHERE season_year = '$seasonToUpdate' " .
            "GROUP BY s.age_group, s.region, s.umpire_type, s.home_club, s.age_group_ID ".
            "UNION ALL ".
            "SELECT season_year, region, 'Away' as Club_Type,  s.age_group, s.umpire_type, s.away_club,  s.short_league_name,  ".
            "COUNT(s.age_group_ID), age_group_ID ".
            "FROM mv_summary_staging s ".
            "WHERE season_year = '$seasonToUpdate' " .
            "GROUP BY season_year, s.region, s.age_group, s.umpire_type, s.away_club, s.age_group_ID, s.short_league_name ".
            ")  AS outer1 ".
            "GROUP BY season_year, region, age_group, umpire_type, Club, short_league_name ".
            ") AS outer2 ".
            ") AS outer3 ".
            "GROUP BY season_year, region, club ".
            "ORDER BY season_year, region, club; ";
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadMVReport04Table SQL:<BR />" . $queryString . "<BR />";
      }
      
      $this->db->query($queryString);
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          
          echo "Query run: reloadMVReport04Table, " . $this->db->affected_rows() . " rows.<BR />";
      }
  
  }
  
  private function reloadMVReport05Table($seasonToUpdate) {
      //First, delete the data from the table
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('5');
      $this->deleteFromSingleTableForSeason($reportTableName, $seasonToUpdate);
  
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_05 (season_year, region, umpire_type, age_group, BFL, GDFL, GFL, GJFL, CDFNL, `Total`) ";
      $queryString .= "SELECT season_year, region, ua.umpire_type_name, ua.age_group, " .
            "IFNULL(SUM(`BFL`),0), " .
            "IFNULL(SUM(`GDFL`),0), " .
            "IFNULL(SUM(`GFL`),0), " .
            "IFNULL(SUM(`GJFL`),0), " .
            "IFNULL(SUM(`CDFNL`),0), " .
            "CASE " .
            "WHEN region = 'Geelong' THEN IFNULL(SUM(`BFL`+`GDFL`+`GFL`+`GJFL`),0) " .
            "WHEN region = 'Colac' THEN IFNULL(SUM(`CDFNL`),0) " .
            "ELSE 0 END as Total " .
            "FROM ( " .
            "SELECT ut.id AS umpire_type_id, ut.umpire_type_name, " .
            "ag.id AS age_group_id, ag.age_group " .
            "FROM umpire_type ut, age_group ag " .
            ") AS ua LEFT JOIN (";
  
      $queryString .= "SELECT season_year, region, umpire_type, age_group, age_group_ID,  " .
        	"(CASE WHEN short_league_name = 'BFL' THEN match_count ELSE 0 END) as `BFL`, " .
        	"(CASE WHEN short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `GDFL`, " .
        	"(CASE WHEN short_league_name = 'GFL' THEN match_count ELSE 0 END) as `GFL`, " .
        	"(CASE WHEN short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `GJFL`, " .
        	"(CASE WHEN short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `CDFNL` " .
        	"FROM ( " .
			"SELECT s.season_year, region, s.umpire_type, s.age_group, s.short_league_name, s.age_group_ID, " .
			"COUNT(s.match_id) AS Match_Count " .
			"FROM mv_summary_staging s " .
			"WHERE s.season_year = '$seasonToUpdate' " .
			"GROUP BY s.season_year, region, s.age_group, s.umpire_type, s.short_league_name, s.age_group_ID " .
    		") AS outer1 " .
            ") AS outer2 ON ua.umpire_type_name = outer2.umpire_type " .
            "AND ua.age_group = outer2.age_group " .
            "GROUP BY season_year, region, ua.umpire_type_id, ua.age_group_id " .
            "ORDER BY season_year, region, ua.umpire_type_id, ua.age_group_id";

      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--reloadMVReport05Table SQL:<BR />" . $queryString . "<BR />";
      }
      $this->db->query($queryString);
      
      if ($debugMode) {
          
          echo "Query run: reloadMVReport05Table, " . $this->db->affected_rows() . " rows.<BR />";
      }
  }
  
  
  private function reloadMVReport06Table($seasonToUpdate) {
      $debugMode = $this->config->item('debug_mode');
      //First, delete the data from the table
      $reportModel = new report_model();
      $reportTableName = $reportModel->lookupReportTableName('6');
      $this->deleteFromSingleTableForSeason($reportTableName, $seasonToUpdate);
      
      //Also delete from staging tables
      //$this->deleteFromSingleTableForSeason('mv_report_06_staging', $seasonToUpdate);
      //$this->deleteFromSingleTableForSeason('mv_umpire_list', $seasonToUpdate);
      
      $this->deleteFromSingleTable("mv_report_06_staging");
      $this->deleteFromSingleTable("mv_umpire_list");
      
      //Insert into umpire MV
      $queryString = "INSERT INTO mv_umpire_list (season_year, umpire_type_name, age_group, umpire_name) " .
        "SELECT DISTINCT " .
        "season_year, umpire_type.umpire_type_name, " . 
        "age_group.age_group, " . 
        "CONCAT(umpire.last_name, ', ', umpire.first_name) AS umpire_name " .
        "FROM umpire " .
        "INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id " .
        "INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id " .
        "INNER JOIN umpire_name_type_match ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id " .
        "INNER JOIN match_played ON match_played.ID = umpire_name_type_match.match_id " .
        "INNER JOIN round ON round.ID = match_played.round_id " .
        "INNER JOIN league ON league.ID = round.league_id " .
        "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id " .
        "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id " .
        "INNER JOIN season ON season.id = round.season_id " .
        "WHERE season.season_year = '$seasonToUpdate';";

      if ($debugMode) {
          echo "--reloadMVReport06Table UmpireList SQL:<BR />" . $queryString . "<BR />";
      }
      
      $this->db->query($queryString);
      
      if ($debugMode) {
          
          echo "Query run: reloadMVReport06Table, " . $this->db->affected_rows() . " rows.<BR />";
      }
      
      //Insert into MV 06 staging
      $queryString = "INSERT INTO mv_report_06_staging (season_year, region, umpire_type_name, age_group, first_umpire, second_umpire, match_ID)  " .
        "SELECT " . 
        "season.season_year, region.region_name, umpire_type1.umpire_type_name, " . 
        "age_group.age_group, " . 
        "CONCAT(umpire1.last_name, ', ', umpire1.first_name) AS first_umpire, " . 
        "CONCAT(umpire2.last_name, ', ', umpire2.first_name) AS second_umpire, " .
        "match_played1.ID " .
        "FROM umpire AS umpire1 " .
        "INNER JOIN umpire_name_type AS umpire_name_type1 ON umpire1.ID = umpire_name_type1.umpire_id " .
        "INNER JOIN umpire_type AS umpire_type1 ON umpire_type1.ID = umpire_name_type1.umpire_type_id " .
        "INNER JOIN umpire_name_type_match AS umpire_name_type_match1 ON umpire_name_type1.ID = umpire_name_type_match1.umpire_name_type_id " .
        "INNER JOIN match_played AS match_played1 ON match_played1.ID = umpire_name_type_match1.match_id " .
        "INNER JOIN match_played AS match_played2 ON match_played1.ID = match_played2.ID " .
        "INNER JOIN umpire_name_type_match AS umpire_name_type_match2 ON umpire_name_type_match2.match_id = match_played2.ID " .
        "INNER JOIN umpire_name_type AS umpire_name_type2 ON umpire_name_type_match2.umpire_name_type_id = umpire_name_type2.ID " .
        "INNER JOIN umpire AS umpire2 ON umpire_name_type2.umpire_id = umpire2.ID " .
        "INNER JOIN umpire_type AS umpire_type2 ON umpire_type2.ID = umpire_name_type2.umpire_type_id " .
        "INNER JOIN round ON round.ID = match_played1.round_id AND round.ID = match_played2.round_id " .
        "INNER JOIN league ON league.ID = round.league_id " .
        "INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id " .
        "INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id " .
        "INNER JOIN season ON season.id = round.season_id " .
        "INNER JOIN region ON league.region_id = region.id " .
        "WHERE umpire1.first_name <> umpire2.first_name " .
        "AND umpire1.last_name <> umpire2.last_name " .
        "AND umpire_type1.ID = umpire_name_type2.umpire_type_id " .
        "AND season.season_year = '$seasonToUpdate';";

      if ($debugMode) {
          echo "--reloadMVReport06Table 06 Staging SQL:<BR />" . $queryString . "<BR />";
      }
      
      $this->db->query($queryString);
      
      if ($debugMode) {
          
          echo "Query run: reloadMVReport06Table, " . $this->db->affected_rows() . " rows.<BR />";
      }
  
      //Then, insert into table
      $queryString = "INSERT INTO mv_report_06 (season_year, region, umpire_type_name, age_group, first_umpire, second_umpire, match_count) " . 
        "SELECT u1.season_year, s.region, u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name, COUNT(s.match_id) " .
        "FROM mv_umpire_list u1 " .
        "INNER JOIN mv_umpire_list u2 ON u1.umpire_type_name = u2.umpire_type_name AND u1.age_group = u2.age_group " .
        "LEFT OUTER JOIN mv_report_06_staging s ON u1.umpire_name = s.first_umpire AND u2.umpire_name = s.second_umpire " .
        "WHERE u1.season_year = '$seasonToUpdate' " .
        "AND u2.season_year = '$seasonToUpdate' " .
        "AND s.season_year = '$seasonToUpdate' " .
        "GROUP BY u1.season_year, s.region, u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name " . 
        "ORDER BY u1.season_year, s.region, u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name;";
  
      
      if ($debugMode) {
          echo "--reloadMVReport06Table SQL:<BR />" . $queryString . "<BR />";
      }
      
      $this->db->query($queryString);
  
      
      if ($debugMode) {
          
          echo "Query run: reloadMVReport06Table, " . $this->db->affected_rows() . " rows.<BR />";
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
      $this->db->query("CREATE INDEX idx_mv01_short_league_name ON umpire.mv_report_01(short_league_name);");
      $this->db->query("CREATE INDEX idx_mv01_short_league_name ON umpire.mv_report_01(short_league_name);");

      
  }*/
  
  
  
}
?>



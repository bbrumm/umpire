<?php
//define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/../system/libraries/MY_Model.php');
//require_once('/system/libraries/MY_Model.php');



class Match_import extends MY_Model 
{   
  /* Code .. */   
    
  
  function __construct()
    {
          parent::__construct();
          $this->load->model("Table_operation");
          $this->load->model('Season');
    }
  
  public function fileImport($data) {
    date_default_timezone_set("Australia/Melbourne");
	//Remove data from previous load first
    $this->deleteFromSingleTable('match_import', NULL, FALSE);
    
	//$dataFile = "application/import/2015-appointments-summary.xls";
	//print_r($data);
	$importedFilename = $data['upload_data']['file_name'];
	$dataFile = "application/import/". $importedFilename;
	$objPHPExcel = PHPExcel_IOFactory::load($dataFile);
	$sheet = $objPHPExcel->getActiveSheet();
	$lastRow = $sheet->getHighestRow();
	//echo "Last row: $lastRow<BR/>";
	$data = $sheet->rangeToArray('A2:S'.$lastRow);
	//echo "Rows available: " . count($data) . "\n"
	/*
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	*/
	$columns = array('season', 'round', 'date', 'competition_name', 'ground', 'time', 
	    'home_team', 'away_team', 'field_umpire_1', 'field_umpire_2', 'field_umpire_3', 
	    'boundary_umpire_1', 'boundary_umpire_2', 'boundary_umpire_3', 'boundary_umpire_4', 
	    'boundary_umpire_5', 'boundary_umpire_6', 'goal_umpire_1', 'goal_umpire_2');
	$rows = $data;
	/*echo "<pre>";
	print_r($data);
	echo "</pre>";*/
	$queryStatus = $this->insert_rows('match_import', $columns, $rows);
	if ($queryStatus) {
	   //echo "File imported!";
	   //Now the data is imported, extract it into the normalised tables.
	   $importedFileID = $this->logImportedFile($importedFilename);
	    
	   $this->prepareNormalisedTables($importedFileID);

	} else {
	    $error = $this->db->error();
	    //print_r($error);
	    echo "File import error: " . $error['code'] . " - " . $error['message'];
	}
	
  }
  
  private function prepareNormalisedTables($importedFileID) {
      $season = new Season();
      $season->setSeasonID($this->findSeasonToUpdate());
      $this->Run_etl_stored_proc->runETLProcedure($season, $importedFileID);
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
  
  private function deleteFromSingleTable($tableName, $importedFileID, $logDeletedRow = TRUE) {
      $queryString = "DELETE FROM ". $tableName;
      $this->db->query($queryString);
      
      $debugMode = $this->config->item('debug_mode');
      if ($debugMode) {
          echo "--deleteFromSingleTable SQL:<BR />" . $queryString . "<BR />";
          echo "Table deleted: " . $tableName . ", " . $this->db->affected_rows() . " rows.<BR />";
      }
      
      if ($logDeletedRow) {
          $this->logTableOperation('DELETE', $tableName, $importedFileID, $this->db->affected_rows());
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
      
      return $this->db->insert_id();
      
  }
  
  private function logTableOperation($operationName, $tableName, $importedFileID, $rowCount) {
      
      $operationID =  $this->findTableIDFromName('operation_ref', 'operation_name', $operationName);
      $processedTableID =  $this->findTableIDFromName('processed_table', 'table_name', $tableName);
      
      $data = array(
          'imported_file_id' => $importedFileID,
          'processed_table_id' => $processedTableID,
          'operation_id' => $operationID,
          'operation_datetime' => date('Y-m-d H:i:s', time()),
          'rowcount' => $rowCount
      );
      
      $queryStatus = $this->db->insert('table_operations', $data);
      
  }
  
  private function findTableIDFromName($tableName, $tableField, $operationName) {
      $this->db->where($tableField, $operationName);
      
      $query = $this->db->get($tableName);
      
      if($query->num_rows() == 1)
      {
          // If there is a user, then create session data
          $row = $query->row();
          return $row->id;
      }
      // If the previous process did not validate
      // then return false.
      return null;
  }  
 
}
?>



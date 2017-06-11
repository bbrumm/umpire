<?php

class Match_import extends CI_Model 
{   

    function __construct() {
          parent::__construct();
          $this->load->model('Table_operation');
          $this->load->model('Season');
          $this->load->library('Debug_library');
    }
  
    public function fileImport($data) {
        date_default_timezone_set("Australia/Melbourne");
    	//Remove data from previous load first
        $this->deleteFromSingleTable('match_import', NULL, FALSE);
    
    	$importedFilename = $data['upload_data']['file_name'];
    	$dataFile = "application/import/". $importedFilename;
    	$objPHPExcel = PHPExcel_IOFactory::load($dataFile);
    	$sheet = $objPHPExcel->getActiveSheet();
    	$lastRow = $sheet->getHighestRow();
    	/*TODO: Put in a permanent fix to dynamically map the column headings to DB tables.
    	Column headings can vary depending on data in the source system (e.g. sometimes there are 4 boundary umpires, or 5, or 6)
    	
    	*/
    	/*$columns = array('season', 'round', 'date', 'competition_name', 'ground', 'time', 
    	    'home_team', 'away_team', 'field_umpire_1', 'field_umpire_2', 'field_umpire_3', 
    	    'boundary_umpire_1', 'boundary_umpire_2', 'boundary_umpire_3', 'boundary_umpire_4', 
    	    'boundary_umpire_5', 'boundary_umpire_6', 'goal_umpire_1', 'goal_umpire_2');
    	   $data = $sheet->rangeToArray('A2:S'.$lastRow, $columns);
    	    */
    	$columns = array('season', 'round', 'date', 'competition_name', 'ground', 'time',
    	    'home_team', 'away_team', 'field_umpire_1', 'field_umpire_2', 'field_umpire_3',
    	    'boundary_umpire_1', 'boundary_umpire_2', 'boundary_umpire_3', 'boundary_umpire_4',
    	    'boundary_umpire_5', 'goal_umpire_1', 'goal_umpire_2');
    	
    	$data = $sheet->rangeToArray('A2:R'.$lastRow, $columns);
    	
    	$rows = $data;
    	$queryStatus = $this->db->insert_batch('match_import', $data);
    	if ($queryStatus) {
    	   //Now the data is imported, extract it into the normalised tables.
    	   $importedFileID = $this->logImportedFile($importedFilename);
    	   $this->prepareNormalisedTables($importedFileID);
    	} else {
    	    $error = $this->db->error();
    	}
	
    }
  
    private function prepareNormalisedTables($importedFileID) {
        $season = new Season();
        $season->setSeasonID($this->findSeasonToUpdate());
        echo "Run ETL file " . $season->getSeasonID() . ", " . $importedFileID . "<BR />";
        $this->Run_etl_stored_proc->runETLProcedure($season, $importedFileID);
    }
  
  
  
    public function findSeasonToUpdate() {
        $queryString = "SELECT MAX(season.ID) AS season_id " .
          "FROM season " .
          "INNER JOIN match_import ON season.season_year = match_import.season;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['season_id'];
    }
    
    public function findLatestImportedFile() {
        $queryString = "SELECT MAX(imported_file_id) AS imported_file_id
            FROM table_operations";
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['imported_file_id'];
    }
    
    private function deleteFromSingleTable($tableName, $importedFileID, $logDeletedRow = TRUE) {
        $queryString = "DELETE FROM ". $tableName;
        $this->db->query($queryString);
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
      
        if($query->num_rows() == 1) {
            // If there is a user, then create session data
            $row = $query->row();
            return $row->id;
        }
        // If the previous process did not validate, then return false.
        return null;
    }  
    
    public function findMissingDataOnImport() {
        $queryString = "CALL `FindMissingData`()";
        $query = $this->db->query($queryString);
    
        $queryString = "SELECT DISTINCT record_type, source_id, source_value
            FROM incomplete_records
            ORDER BY record_type, source_id;";
        $query = $this->db->query($queryString);
      
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
    
        $resultArray = $query->result_array();
        $query->free_result();
      
        $resultArray = $this->splitArrayBasedOnType($resultArray);
      
        $this->debug_library->debugOutput("resultArray (in findMissingDataOnImport):", $resultArray);
      
        return $resultArray;
    }
    
    private function splitArrayBasedOnType(array $pResultArray) {
        //$resultArray = "";
        $resultArray = array(); 
      
        //Split the results into separate arrays, based on the record type
        $this->debug_library->debugOutput("pResultArray (in splitArrayBasedOnType):", $pResultArray);
      
        foreach ($pResultArray as $currentRowItem) {
            $resultArray[$currentRowItem['record_type']][] = $currentRowItem;
        }
        return $resultArray;
    }
 
}
?>
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
    	$lastColumn = $sheet->getHighestColumn();
    	
    	$this->debug_library->debugOutput("last column:", $lastColumn);
    	$this->debug_library->debugOutput("last row:", $lastRow);

    	$columns = $this->findColumnsFromSpreadshet($sheet, $lastColumn);
    	
    	$this->debug_library->debugOutput("columns:", $columns);
    	
    	$data = $sheet->rangeToArray('A2:'. $lastColumn .$lastRow, $columns);
    	
    	
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
    
    private function findColumnsFromSpreadshet($pSheet, $pLastColumn) {
        $sheetColumnHeaderArray = $pSheet->rangeToArray("A1:". $pLastColumn ."1");
        $columnHeaderToTableMatchArray = array(
            'Season'=>'season',
            'Round'=>'round',
            'Date'=>'date',
            'Competition Name'=>'competition_name',
            'Ground'=>'ground',
            'Time'=>'time',
            'Home Team'=>'home_team',
            'Away Team'=>'away_team',
            'Field Umpire 1'=>'field_umpire_1',
            'Field Umpire 2'=>'field_umpire_2',
            'Field Umpire 3'=>'field_umpire_3',
            'Boundary Umpire 1'=>'boundary_umpire_1',
            'Boundary Umpire 2'=>'boundary_umpire_2',
            'Boundary Umpire 3'=>'boundary_umpire_3',
            'Boundary Umpire 4'=>'boundary_umpire_4',
            'Boundary Umpire 5'=>'boundary_umpire_5',
            'Goal Umpire 1'=>'goal_umpire_1',
            'Goal Umpire 2'=>'goal_umpire_2'
        );
        
        $columns = array();
        
        foreach ($sheetColumnHeaderArray[0] as $columnHeader) {
            /*
            This looks up the table's column name from the columnHeaderTableMatchArray above,
            and if it finds a match, adds the column name into the columns array
            */
            if (array_key_exists($columnHeader, $columnHeaderToTableMatchArray)) {
                $columns[] = $columnHeaderToTableMatchArray[$columnHeader];
            }
        }
        
        return $columns;
        
    }
  
    private function prepareNormalisedTables($importedFileID) {
        $season = new Season();
        $season->setSeasonID($this->findSeasonToUpdate());
        $this->Run_etl_stored_proc->runETLProcedure($season, $importedFileID);
    }
    
    public function logErrorMessage($pMessage) {
        $queryString = "INSERT INTO test_error_log (logged_date, message) VALUES (NOW(), '". $pMessage ."');";
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
    }
    
    public function updateProgressValueInDB($progressPct) {
        $queryString = "UPDATE test_progress SET progress_value = ". $progressPct .";";
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
    }
    
    public function getProgressValueInDB() {
        $queryString = "SELECT progress_value FROM test_progress;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['progress_value'];
    }
  
    public function findSeasonToUpdate(IData_store $pDataStore) {
        return $pDataStore->findSeasonToUpdate();
    }
    
    public function findLatestImportedFile() {
        return $pDataStore->findLatestImportedFile();
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
            'imported_user_id' => $username,
            'etl_status' => 2 //2 = not yet started
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
      
        return $resultArray;
    }
    
    private function splitArrayBasedOnType(array $pResultArray) {
        $resultArray = array(); 
        foreach ($pResultArray as $currentRowItem) {
            $resultArray[$currentRowItem['record_type']][] = $currentRowItem;
        }
        return $resultArray;
    }
 
}
?>
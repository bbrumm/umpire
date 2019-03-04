<?php
/*
* @property Object db
*/
class Match_import extends CI_Model
{
    
    const COLUMN_TO_TABLE_MATCH = array(
            'Season' => array('column_name'=>'season', 'required'=>true),
            'Round' => array('column_name'=>'round', 'required'=>true),
            'Date' => array('column_name'=>'date', 'required'=>true),
            'Competition Name' => array('column_name'=>'competition_name', 'required'=>true),
            'Ground' => array('column_name'=>'ground', 'required'=>true),
            'Time' => array('column_name'=>'time', 'required'=>true),
            'Home Team' => array('column_name'=>'home_team', 'required'=>true),
            'Away Team' => array('column_name'=>'away_team', 'required'=>true),
            'Field Umpire 1' => array('column_name'=>'field_umpire_1', 'required'=>true),
            'Field Umpire 2' => array('column_name'=>'field_umpire_2', 'required'=>true),
            'Field Umpire 3' => array('column_name'=>'field_umpire_3', 'required'=>true),
            'Boundary Umpire 1' => array('column_name'=>'boundary_umpire_1', 'required'=>true),
            'Boundary Umpire 2' => array('column_name'=>'boundary_umpire_2', 'required'=>true),
            'Boundary Umpire 3' => array('column_name'=>'boundary_umpire_3', 'required'=>true),
            'Boundary Umpire 4' => array('column_name'=>'boundary_umpire_4', 'required'=>false),
            'Boundary Umpire 5' => array('column_name'=>'boundary_umpire_5', 'required'=>false),
            'Goal Umpire 1' => array('column_name'=>'goal_umpire_1', 'required'=>true),
            'Goal Umpire 2' => array('column_name'=>'goal_umpire_2', 'required'=>true)
        );

    public function __construct() {
        parent::__construct();
        $this->load->model('Table_operation');
        $this->load->model('Season');
        $this->load->library('Debug_library');
        $this->load->model('Database_store_matches');
        $this->load->model('Run_etl_stored_proc');
        $this->load->model('Import_file');
    }

    public function fileImport($pFileLoader, $pDataStore, $data) {
        date_default_timezone_set("Australia/Melbourne");
        //Remove data from previous load first
        $pFileLoader->clearMatchImportTable();
        
        $importedFile = new Import_file();
        
        $importedFile->setFilename($pFileLoader->getImportedFilename($data));
        //$importedFilename = $pFileLoader->getImportedFilename($data);
        
        $dataFile = $pFileLoader->getImportedFilePathAndName($data);

        //TODO: Refactor this into an ImportFile object that has each of these attributes
        $objPHPExcel = PHPExcel_IOFactory::load($dataFile);
        
        $importedFile->setDataSheet($objPHPExcel->getActiveSheet());
        
        
        $sheet = $objPHPExcel->getActiveSheet();
        //TODO: Move these into the Import_file function setDataSheet
        //$lastRow = $sheet->getHighestRow();
        //$lastColumn = $sheet->getHighestColumn();

        $columns = $this->findColumnsFromSpreadsheet($importedFile);
        $sheetData = $sheet->rangeToArray('A2:' . $importedFile->getLastColumn() . $importedFile->getLastRow());

        //Update array keys in sheetData to use the column headings.
        //Without this, the keys will just be numbers
        $sheetData = $this->updateKeysToUseColumnNames($sheetData, $columns);

        $queryStatus = $pFileLoader->insertMatchImportTable($sheetData, $columns);

        if ($queryStatus) {
            //Now the data is imported, extract it into the normalised tables.
            //echo "test table ";
            $this->prepareNormalisedTables($pFileLoader, $pDataStore, $importedFile->getFilename());
            return true;
        } else {
            $errorArray = $this->db->error();
            throw new Exception("Error inserting data into match_import table. Code: " . $errorArray["code"] . ", Message: ". $errorArray["message"]);
        }
    }

    private function sheetHasNoData($sheet) {
        if ($sheet->getHighestRow() == 1) {
            return true;
        } else {
            return false;
        }
    }

    private function updateKeysToUseColumnNames($sheetData, $pColumns) {
        $newSheetData = [];
        //foreach($sheet as $row) {
        $rowCount = count($sheetData);
        for($j=0; $j < $rowCount; $j++) {
            $colCount = count($sheetData[$j]);
            for($i=0; $i < $colCount; $i++) {
                if($pColumns[$i]['found'] == true) {
                    $newSheetData[$j][$pColumns[$i]['column_name']] = $sheetData[$j][$i];
                }
            }
        }

        return $newSheetData;
    }

    private function findColumnsFromSpreadsheet($pImportedFile) {
        $sheetColumnHeaderArray = $pImportedFile->getDataSheet()->rangeToArray("A1:" . $pImportedFile->getLastColumn() . "1");
        if (!$this->isColumnHeaderExist($sheetColumnHeaderArray)) {
            throw new Exception("Column headers are missing from the imported file.");
        }

        $columns = $this->populateColumnsArray($sheetColumnHeaderArray);

        //Check if all required columns are in the imported spreadsheet
        $this->checkAllRequiredColumnsFound($sheetColumnHeaderArray[0]);

        return $columns;
    }

    
    private function populateColumnsArray($sheetColumnHeaderArray) {
        $columns = array();
        foreach ($sheetColumnHeaderArray[0] as $columnHeader) {
            if ($this->isColumnHeaderInMappingDefinition($columnHeader)) {
                $columns[] = $this->addMatchingColumnHeader($columnHeader);
            } else {
                $columns[] = $this->addNewColumnHeader($columnHeader);
            }
        }
         return $columns;
    }
    
    /*
    This looks up the table's column name from the columnHeaderTableMatchArray above,
    and if it finds a match, adds the column name into the columns array
    */
    private function isColumnHeaderInMappingDefinition($pColumnHeader) {
        return array_key_exists($pColumnHeader, self::COLUMN_TO_TABLE_MATCH);
    }
    
    private function addMatchingColumnHeader($columnHeader) {
        return array('column_name'=>self::COLUMN_TO_TABLE_MATCH[$columnHeader]['column_name'],
                    'found'=>true);
    }
    
    private function addNewColumnHeader($columnHeader) {
        return array('column_name'=>$columnHeader,
                    'found'=>false);
    }
    
    
    private function isColumnHeaderExist($pSheetColumnHeaderArray) {
        return ($pSheetColumnHeaderArray[0][0] == "Season");
    }

    private function checkAllRequiredColumnsFound($pSheetColumnHeaderArray) {
        $missingColumnCount = 0;
        $missingColumnNames = "";

        foreach (self::COLUMN_TO_TABLE_MATCH as $expectedColumnHeaderLabel=>$expectedColumnHeaderProperties) {
            if ($expectedColumnHeaderProperties['required']) {
                if (!in_array($expectedColumnHeaderLabel, $pSheetColumnHeaderArray)) {
                    $missingColumnCount++;
                    $missingColumnNames .= $expectedColumnHeaderLabel . ", ";
                }
            }
        }

        if ($missingColumnCount > 0) {
            throw new Exception("A required column is missing from the imported file: " . $missingColumnNames);
        }
    }

    private function prepareNormalisedTables($pFileLoader, $pDataStore, $importedFilename) {
        $importedFileID = $pFileLoader->logImportedFile($importedFilename);

        $season = Season::createSeasonFromID($this->findSeasonToUpdate($pDataStore));
        $pFileLoader->runETLProcedure($pDataStore, $season, $importedFileID);
    }

    public function findSeasonToUpdate(IData_store_matches $pDataStore) {
        return $pDataStore->findSeasonToUpdate();
    }

    public function findLatestImportedFile($pDataStore) {
        return $pDataStore->findLatestImportedFile();
    }

    public function findMissingDataOnImport() {
        $queryString = "CALL `FindMissingData`()";
        $this->db->query($queryString);

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

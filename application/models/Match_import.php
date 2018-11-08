<?php

class Match_import extends CI_Model
{

    public function __construct() {
        parent::__construct();
        $this->load->model('Table_operation');
        $this->load->model('Season');
        $this->load->library('Debug_library');
        $this->load->model('Database_store_matches');
        $this->load->model('Run_etl_stored_proc');
    }

    public function fileImport($pFileLoader, $pDataStore, $data) {
        date_default_timezone_set("Australia/Melbourne");
        //Remove data from previous load first
        $pFileLoader->clearMatchImportTable();

        $importedFilename = $pFileLoader->getImportedFilename($data);
        $dataFile = $pFileLoader->getImportedFilePathAndName($data);

        //TODO: Refactor this into an ImportFile object that has each of these attributes
        $objPHPExcel = PHPExcel_IOFactory::load($dataFile);
        $sheet = $objPHPExcel->getActiveSheet();
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        $columns = $this->findColumnsFromSpreadshet($sheet, $lastColumn);
        $sheetData = $sheet->rangeToArray('A2:' . $lastColumn . $lastRow, $columns);

        $queryStatus = $pFileLoader->insertMatchImportTable($sheetData);

        if ($queryStatus) {
            //Now the data is imported, extract it into the normalised tables.
            $this->prepareNormalisedTables($pFileLoader, $pDataStore, $importedFilename);
            return true;
        } else {
            $errorArray = $this->db->error();
            throw new Exception("Error inserting data into match_import table. Code: " . $errorArray["code"] . ", Message: ". $errorArray["message"]);
        }
    }

    private function findColumnsFromSpreadshet($pSheet, $pLastColumn) {
        $sheetColumnHeaderArray = $pSheet->rangeToArray("A1:" . $pLastColumn . "1");
        if (!$this->isColumnHeaderExist($sheetColumnHeaderArray)) {
            throw new Exception("Column headers are missing from the imported file.");
        }

        $columnHeaderToTableMatchArray = array(
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

        $columns = array();
        foreach ($sheetColumnHeaderArray[0] as $columnHeader) {
            /*
            This looks up the table's column name from the columnHeaderTableMatchArray above,
            and if it finds a match, adds the column name into the columns array
            */
            if (array_key_exists($columnHeader, $columnHeaderToTableMatchArray)) {
                $columns[] = $columnHeaderToTableMatchArray[$columnHeader]['column_name'];
            }
        }

        //Check if all required columns are in the imported spreadsheet
        $this->checkAllRequiredColumnsFound($sheetColumnHeaderArray[0], $columnHeaderToTableMatchArray);

        return $columns;
    }

    private function isColumnHeaderExist($pSheetColumnHeaderArray) {
        return ($pSheetColumnHeaderArray[0][0] == "Season");
    }

    private function checkAllRequiredColumnsFound($pSheetColumnHeaderArray, $pColumnHeaderToTableMatchArray) {
        $missingColumnCount = 0;
        $missingColumnNames = "";

        foreach ($pColumnHeaderToTableMatchArray as $expectedColumnHeaderLabel=>$expectedColumnHeaderProperties) {
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
        $pFileLoader->runETLProcedure($season, $importedFileID);
    }

    public function findSeasonToUpdate(IData_store_matches $pDataStore) {
        return $pDataStore->findSeasonToUpdate();
    }

    public function findLatestImportedFile($pDataStore) {
        return $pDataStore->findLatestImportedFile();
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
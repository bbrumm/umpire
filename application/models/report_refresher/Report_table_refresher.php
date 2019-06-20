<?php
class Report_table_refresher extends CI_Model {

    const OPERATION_INSERT = 1;
    const OPERATION_UPDATE = 2;
    const OPERATION_DELETE = 3;
    
    private $importFileID;
    private $seasonYear;
    private $tableName;
    private $dataRefreshQuery;

    function __construct() {
        parent::__construct();
    }
    
    public function refreshMVTable() {
        $this->disableKeys();
        $this->deleteFromDWTableForYear();
        $this->logTableDeleteOperation();
        $this->updateMVTable();
        $this->enableKeys();
    }

    public function setSeasonYear($pSeason) {
        $queryString = "SELECT MAX(season_year) AS season_year
            FROM season
            WHERE id = " . $pSeason->getSeasonID() . ";";
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        $this->seasonYear = $queryResultArray[0]['season_year'];
    }
    
    public function setDataRefreshQuery($pQuery) {
      $this->dataRefreshQuery = $pQuery;
    }
    
    public function setTableName($pTableName) {
      $this->tableName = $pTableName;
    }

    public function setImportFileID($pImportFileID) {
        $this->importFileID = $pImportFileID;
    }

    public function getSeasonYear() {
        return $this->seasonYear;
    }
    
    private function updateMVTable() {
      $this->runQuery($this->dataRefreshQuery);
      $this->logTableInsertOperation();
    }
    
    public function runQuery($pQueryString) {
        return $this->db->query($pQueryString);
    }

    public function disableKeys() {
        if(isset($this->tableName)) {
            $this->disableKeysForTable($this->tableName);
        } else {
            throw new Exception("Table name for object cannot be empty.");
        }
    }
    
    public function disableKeysForSpecificTable($pTableName) {
        if(isset($pTableName)) {
            $this->disableKeysForTable($pTableName);
        } else {
            throw new Exception("Table name specified cannot be empty.");
        }
    }
    
    private function disableKeysForTable($pTableName) {
        $queryString = "ALTER TABLE ". $pTableName ." DISABLE KEYS;";
        $this->runQuery($queryString);
    }

    public function enableKeys() {
        if(isset($this->tableName)) {
          $this->enableKeysForTable($this->tableName);
        } else {
            throw new Exception("Table name for object cannot be empty.");
        }
    }
    
    public function enableKeysForSpecificTable($pTableName) {
        if(isset($pTableName)) {
            $this->enableKeysForTable($pTableName);
        } else {
            throw new Exception("Table name specified cannot be empty.");
        }
    }
    
    private function enableKeysForTable($pTableName) {
        $queryString = "ALTER TABLE ". $pTableName ." ENABLE KEYS;";
        $this->runQuery($queryString);
    }

    public function logTableInsertOperation() {
        $this->logSpecificTableInsertOperation($this->tableName);
    }

    public function logTableDeleteOperation() {
        $this->logSpecificTableDeleteOperation($this->tableName);
    }

    public function logTableUpdateOperation() {
        $this->logSpecificTableUpdateOperation($this->tableName);
    }
    
    public function logSpecificTableInsertOperation($pTableName) {
        if(isset($pTableName)) {
            $this->logTableOperation($pTableName, self::OPERATION_INSERT);
        } else {
            throw new Exception("Table name specified cannot be empty.");
        }
    }
    
    public function logSpecificTableDeleteOperation($pTableName) {
        if(isset($pTableName)) {
            $this->logTableOperation($pTableName, self::OPERATION_DELETE);
        } else {
            throw new Exception("Table name specified cannot be empty.");
        }
    }

    public function logSpecificTableUpdateOperation($pTableName) {
        if(isset($pTableName)) {
            $this->logTableOperation($pTableName, self::OPERATION_UPDATE);
        } else {
            throw new Exception("Table name specified cannot be empty.");
        }
    }

    private function logTableOperation($pTableName, $pOperationType) {
        $queryString = "INSERT INTO table_operations (imported_file_id, processed_table_id, operation_id, operation_datetime, rowcount)
VALUES (". $this->importFileID .", (SELECT id FROM processed_table WHERE table_name = '". $pTableName ."'), ". $pOperationType .",  NOW(), ROW_COUNT());";
        $this->runQuery($queryString);
    }

    public function setupScript() {
        $queryString = "SET group_concat_max_len = 15000;";
        $this->runQuery($queryString);
    }

    public function commitTransaction() {
        $queryString = "COMMIT;";
        $this->runQuery($queryString);
    }

    public function deleteFromDWTableForYear() {
        $queryString = "DELETE FROM " . $this->tableName . " WHERE season_year = " . $this->seasonYear;
        $this->runQuery($queryString);
    }

    public function runInsertETLStep($pTableName, $pQueryString) {
        $this->disableKeysForSpecificTable($pTableName);
        $this->runQuery($pQueryString);
        $this->enableKeysForSpecificTable($pTableName);
        //Run this after the keys to get a true time taken
        $this->logSpecificTableInsertOperation($pTableName);
    }

    public function runInsertETLStepWithoutKeys($pTableName, $pQueryString) {
        $this->runQuery($pQueryString);
        $this->logSpecificTableInsertOperation($pTableName);
    }

    public function runDeleteETLStep($pTableName, $pQueryString) {
        $this->runQuery($pQueryString);
        $this->logSpecificTableDeleteOperation($pTableName);
    }

    public function truncateTable($pTableName) {
        $queryString = "TRUNCATE ". $pTableName .";";
        $this->runQuery($queryString);
    }

}

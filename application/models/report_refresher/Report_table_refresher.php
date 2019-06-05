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

    public function getTableName() {
        return $this->tableName;
    }

    public function setImportFileID($pImportFileID) {
        $this->importFileID = $pImportFileID;
    }

    //TODO: remove this when the log operation functions use the object value instead of a parameter
    public function getImportFileID() {
        return $this->importFileID;
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
            $queryString = "ALTER TABLE ". $this->tableName ." DISABLE KEYS;";
            $this->runQuery($queryString);
        } else {
            throw new Exception("Table name cannot be empty.");
        }
    }

    public function enableKeys() {
        if(isset($this->tableName)) {
          $queryString = "ALTER TABLE ". $this->tableName ." ENABLE KEYS;";
          $this->runQuery($queryString);  
        } else {
            throw new Exception("Table name cannot be empty.");
        }
    }

    public function logTableInsertOperation() {
        $this->logTableOperation(self::OPERATION_INSERT);
    }

    public function logTableDeleteOperation() {
        $this->logTableOperation(self::OPERATION_DELETE);
    }

    public function logTableUpdateOperation() {
        $this->logTableOperation(self::OPERATION_UPDATE);
    }

    private function logTableOperation($pOperationType) {
        $queryString = "INSERT INTO table_operations (imported_file_id, processed_table_id, operation_id, operation_datetime, rowcount)
VALUES (". $this->importFileID .", (SELECT id FROM processed_table WHERE table_name = '". $this->tableName ."'), ". $pOperationType .",  NOW(), ROW_COUNT());";
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

}

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
        $this->disableKeys($this->tableName);
        $this->deleteFromDWTableForYear($this->tableName, $this->seasonYear);
        $this->logTableDeleteOperation($this->tableName, $this->importFileID);
        $this->updateMVTable();
        $this->enableKeys($this->tableName);
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
      $this->logTableInsertOperation($this->tableName, $this->importFileID);
    }
    
    public function runQuery($pQueryString) {
        return $this->db->query($pQueryString);
    }

    public function disableKeys($pTableName) {
        if(isset($pTableName)) {
            $queryString = "ALTER TABLE ". $pTableName ." DISABLE KEYS;";
            $this->runQuery($queryString);
        } else {
            throw new Exception("Table name cannot be empty.");
        }
    }

    public function enableKeys($pTableName) {
        if(isset($pTableName)) {
          $queryString = "ALTER TABLE ". $pTableName ." ENABLE KEYS;";
          $this->runQuery($queryString);  
        } else {
            throw new Exception("Table name cannot be empty.");
        }
    }

    public function logTableInsertOperation($pTableName, $pImportFileID) {
        $this->logTableOperation($pTableName, self::OPERATION_INSERT, $pImportFileID);
    }

    public function logTableDeleteOperation($pTableName, $pImportFileID) {
        $this->logTableOperation($pTableName, self::OPERATION_DELETE, $pImportFileID);
    }

    public function logTableUpdateOperation($pTableName, $pImportFileID) {
        $this->logTableOperation($pTableName, self::OPERATION_UPDATE, $pImportFileID);
    }

    private function logTableOperation($pTableName, $pOperationType, $pImportFileID) {
        $queryString = "INSERT INTO table_operations (imported_file_id, processed_table_id, operation_id, operation_datetime, rowcount)
VALUES (". $pImportFileID .", (SELECT id FROM processed_table WHERE table_name = '". $pTableName ."'), ". $pOperationType .",  NOW(), ROW_COUNT());";
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

    public function deleteFromDWTableForYear($pTableName, $pSeasonYear) {
        $queryString = "DELETE FROM " . $pTableName . " WHERE season_year = " . $pSeasonYear;
        $this->runQuery($queryString);
    }

}

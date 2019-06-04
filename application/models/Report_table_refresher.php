<?php
class Report_table_refresher extends CI_Model {

    const OPERATION_INSERT = 1;
    const OPERATION_UPDATE = 2;
    const OPERATION_DELETE = 3;
    
    private $importFileID;
    private $seasonYear;
    private $tableName;

    function __construct() {
        parent::__construct();
    }
    
    public static function createRefresher($pImportFileID, $pSeasonYear) {
      $reportTableRefresher = new Report_table_refresher();
      $reportTableRefresher->importFileID = $pImportFileID;
      $reportTableRefresher->seasonYear = $pSeasonYear;
  
      return $reportTableRefresher;
    }
    
    public function refreshMVTable() {
      $this->disableKeys($this->tableName);
      $this->deleteFromDWTableForYear($this->tableName, $this->seasonYear);
      $this->logTableDeleteOperation($this->tableName, $this->importFileID);
      $this->updateMVTable();
      $this->enableKeys($this->tableName);
    }
    
    public function setDataRefreshQuery($pQuery) {
      $this->dataRefreshQuery = $pQuery;
    }
    
    public function setTableName($pTableName) {
      $this->tableName = $pTableName;
    }
    
    private function updateMVTable() {
      $this->runQuery($this->dataRefreshQuery);
      $this->logTableInsertOperation($this->tableName, $this->importFileID);
    }
    
    public function runQuery($pQueryString) {
        return $this->db->query($pQueryString);
    }

    public function disableKeys($pTableName) {
        $queryString = "ALTER TABLE ". $pTableName ." DISABLE KEYS;";
        $this->runQuery($queryString);
    }

    public function enableKeys($pTableName) {
        $queryString = "ALTER TABLE ". $pTableName ." ENABLE KEYS;";
        $this->runQuery($queryString);
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

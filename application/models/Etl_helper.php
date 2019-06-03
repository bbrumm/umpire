<?php
class Etl_helper extends CI_Model {

    const OPERATION_INSERT = 1;
    const OPERATION_UPDATE = 2;
    const OPERATION_DELETE = 3;

    function __construct() {
        parent::__construct();
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

}
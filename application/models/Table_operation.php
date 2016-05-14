<?php
class Table_operation extends CI_Model
{
    /* Code .. */

    function __construct()
    {
        parent::__construct();
        //$this->load->config('constants');
    }

    private $importedFileID;
    private $processedTableID;
    private $operationID;
    private $operationDateTime;
    private $rowCount;


    //Get Functions
    public function getImportedFileID() {
        return $this->importedFileID;
    }

    public function getProcessedTableID() {
        return $this->processedTableID;
    }

    public function getOperationID() {
        return $this->operationID;
    }

    public function getOperationDateTime() {
        return $this->operationDateTime;
    }

    public function getRowCount() {
        return $this->rowCount;
    }


    //Set Functions
    public function setImportedFileID($pValue) {
        $this->importedFileID = $pValue;
    }
    
    public function setProcessedTableID($pValue) {
        $this->processedTableID = $pValue;
    }
    
    public function setOperationID($pValue) {
        $this->operationID = $pValue;
    }
    
    public function setOperationDateTime($pValue) {
        $this->operationDateTime = $pValue;
    }
    
    public function setRowCount($pValue) {
        $this->rowCount = $pValue;
    }
    
}
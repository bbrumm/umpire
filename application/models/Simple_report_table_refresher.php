<?php
class Simple_report_table_refresher extends CI_Model implements IReport_table_refresher {

private $etlHelper;
private $dataRefreshQuery;
private $tableName;
private $importFileID;
private $seasonYear;

  function __construct() {
    parent::__construct();
    $this->load->model('Report_table_refresher');
    $this->etlHelper = new Report_table_refresher();
  }
  
  public static function createRefresher($pTableName, $pImportFileID, $pSeasonYear) {
    $reportTableRefresher = new Simple_report_table_refresher();
    $reportTableRefresher->tableName = $pTableName;
    $reportTableRefresher->importFileID = $pImportFileID;
    $reportTableRefresher->seasonYear = $pSeasonYear;
  
    return $reportTableRefresher;
  }


  public function refreshMVTable() {
    $this->etlHelper->disableKeys($this->tableName);
    $this->etlHelper->deleteFromDWTableForYear($this->tableName, $this->seasonYear);
    //TODO: Include this in the above function (in etlHelper)
    $this->etlHelper->logTableDeleteOperation($this->tableName, $this->importFileID);

    $this->updateMVTable();
    $this->etlHelper->enableKeys($this->tableName);
  }

  public function setDataRefreshQuery($pQuery) {
    $this->dataRefreshQuery = $pQuery;
  }

  private function updateMVTable() {
    $this->etlHelper->runQuery($this->dataRefreshQuery);
    $this->etlHelper->logTableInsertOperation($this->tableName, $this->importFileID);
  }

}

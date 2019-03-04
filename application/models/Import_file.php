<?php
class Import_file extends CI_Model
{
  public function __construct() {
  
  }
  
  private $dataSheet;
  private $lastRow;
  private $lastColumn;
  private $filename;
  
  public function setDataSheet($pValue) {
      $this->dataSheet = $pValue;
      $this->lastRow = $this->dataSheet->getHighestRow();
      $this->lastColumn = $this->dataSheet->getHighestColumn();
      if ($this->sheetHasNoData()) {
          throw new Exception("The imported file contains no rows on the selected sheet.");
      }
  }
  
  public function getDataSheet() {
      return $this->dataSheet;
  }
  
  public function getLastRow() {
      return $this->lastRow;
  }
  
  public function getLastColumn() {
      return $this->lastColumn;
  }
  
  private function sheetHasNoData() {
      return ($this->lastRow == 1);
  }
  
  public function setFilename($pValue) {
      $this->filename = $pValue;
  }
  
  public function getFilename() {
      return $this->filename;
  }

}

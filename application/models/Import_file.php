<?php
class Import_file extends CI_Model
{
  public function __construct() {
  
  }
  
  private $dataSheet;
  private $lastRow;
  private $lastColumn;
  
  public function setDataSheet($pValue) {
      $this->dataSheet = $pValue;
  }
  
  public function setLastRow($pValue) {
      $this->lastRow = $pValue;
  }
  
  public function setLastColumn($pValue) {
      $this->lastColumn = $pValue;
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

}

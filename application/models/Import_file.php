<?php
class Import_file extends CI_Model
{
  public function __construct() {
  
  }
  
  private $dataSheet;
  private $lastRow;
  private $lastColumn;
  private $filename;
  private $sheetData;
  
  public function setDataSheet($pValue) {
      $this->dataSheet = $pValue;
      $this->lastRow = $this->dataSheet->getHighestRow();
      $this->lastColumn = $this->dataSheet->getHighestColumn();
      if ($this->sheetHasNoData()) {
          throw new Exception("The imported file contains no rows on the selected sheet.");
      }
   
  }
  
  public function setSheetData($pColumns) {
    $this->sheetData = $this->dataSheet->rangeToArray('A2:' . $this->lastColumn . $this->lastRow);
    $this->updateKeysToUseColumnNames($pColumns);
  }
  
   private function updateKeysToUseColumnNames($pColumns) {
        $newSheetData = [];
        //foreach($sheet as $row) {
        $rowCount = count($this->sheetData);
        for($j=0; $j < $rowCount; $j++) {
            $colCount = count($this->sheetData[$j]);
            for($i=0; $i < $colCount; $i++) {
                if($pColumns[$i]['found'] == true) {
                    $newSheetData[$j][$pColumns[$i]['column_name']] = $this->sheetData[$j][$i];
                }
            }
        }

        $this->sheetData = $newSheetData;
    }
  
  public function getSheetData() {
    return $this->sheetData;
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

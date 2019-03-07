<?php
class Report_result extends CI_Model {

    private $reportResultCells;
    private $resultArray;
    private $columnLabelResultArray;
    //private $resultOutputArray;
    
    public function __construct() {
        $this->load->model('separate_reports/Report_cell');
    }
    
    public function loadDataForReport($pDataStore, $pSeparateReport) {
        $this->resultArray = $pDataStore->loadReportData($separateReport, $this);
        $this->convertResultArrayToCollection();
    }
    
     public function convertResultArrayToCollection() {
        $countRows = count($this->resultArray);
        $countColumns = count($this->columnLabelResultArray);
        for($rowCounter = 0; $rowCounter < $countRows; $rowCounter++) {
            for($columnCounter = 0; $columnCounter < $countColumns; $columnCounter++) {
                //Create new Report_cell and add it to the array
                $currentCell = new Report_cell();
                //TODO Add this into a constructor
                $currentCell->setColumnNumber($columnCounter);
                $currentCell->setRowNumber($rowCounter);
                $currentCell->setCellValue($this->checkAndReturnValueFromOutputArray($rowCounter, $columnCounter));
                $this->reportResultCells[] = $currentCell;
            }
        }
        //echo "test";
    }
    
    private function checkAndReturnValueFromOutputArray($rowCounter, $columnCounter) {
        //if (array_key_exists($columnCounter, $this->resultOutputArray[$rowCounter])) {
        //    return $this->resultOutputArray[$rowCounter][$columnCounter];
        /*
        TODO: Check that this works. The old code uses reportOutputArray, which may be a transformed version of the report array
        Ideally there should only be one array, so perhaps I need to transform it first,
        or update this logic.
        */
        if (array_key_exists($columnCounter, $this->resultArray[$rowCounter])) {
            return $this->resultArray[$rowCounter][$columnCounter];
        } else {
            return "";
        }
    }

}

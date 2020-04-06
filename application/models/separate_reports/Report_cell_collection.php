<?php
//A collection of report cell objects.
//The collection represents the table that is written to the screen and displayed.
class Report_cell_collection extends CI_Model {

    public function __construct() {

    }

    private $reportCellArray;

    public function setReportCellArray($pValue) {
        $this->reportCellArray = $pValue;
    }

    public function getReportCellArray() {
        return $this->reportCellArray;
    }

}
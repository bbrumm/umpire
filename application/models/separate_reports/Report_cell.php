<?php
/*
This class represents a single cell in a report output. There will be many of these for a report.
*/
class Report_cell extends CI_Model {
    
    public function __construct() {
        $this->load->model('Cell_formatting_helper');
    }
    
    private $cellValue;
    private $formatClass;
    private $rowNumber;
    private $columnNumber;
    
    public function setCellValue($pValue) {
        $this->cellValue = $pValue;
    }
    
    public function setFormatClass($pValue) {
        $this->formatClass = $pValue;
    }
    
    public function setRowNumber($pValue) {
        $this->rowNumber = $pValue;
    }
    
    public function setColumnNumber($pValue) {
        $this->columnNumber = $pValue;
    }
    
    public function getCellValue() {
        return $this->cellValue;
    }
    
    public function getFormatClass() {
        return $this->formatClass;
    }
    
    public function getRowNumber() {
        return $this->rowNumber;
    }
    
    public function getColumnNumber() {
        return $this->columnNumber;
    }


}

<?php
/*
This class represents a single cell in a report output. There will be many of these for a report.
A report cell can be a heading or a regular table cell.
*/
class Report_cell extends CI_Model {
    
    public function __construct() {
        $this->load->model('Cell_formatting_helper');
    }
    
    private $cellValue;
    private $formatClass;
    private $rowNumber;
    private $columnNumber;
    private $isHeader;
    private $sourceResultRow; //A temp variable to hold the resultRow array entry that the cell is sourced from. Helps to identify matches to other columns during debugging.
    private $columnHeaderValueFirst;
    private $columnHeaderValueSecond;//We will need more than one column header for some reports
    private $columnHeaderValueThird;

    public static function createNewReportCellWithHeaders($pCellValue, $pColumnHeaderValueFirst, $pColumnHeaderValueSecond) {
        $obj = new Report_cell();

        $obj->setCellValue($pCellValue);
        $obj->setColumnHeaderValueFirst($pColumnHeaderValueFirst);
        $obj->setColumnHeaderValueSecond($pColumnHeaderValueSecond);
        return $obj;
    }

    //TODO: Add unit tests for this object
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

    public function setIsHeader($pValue) {
        $this->isHeader = $pValue;
    }

    public function setSourceResultRow($pValue) {
        $this->sourceResultRow = $pValue;
    }

    public function setColumnHeaderValueFirst($pValue) {
        $this->columnHeaderValueFirst = $pValue;
    }

    public function setColumnHeaderValueSecond($pValue) {
        $this->columnHeaderValueSecond = $pValue;
    }

    public function setColumnHeaderValueThird($pValue) {
        $this->columnHeaderValueThird = $pValue;
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

    public function getIsHeader() {
        return $this->isHeader;
    }

    public function getSourceResultRow() {
        return $this->sourceResultRow;
    }

    public function getColumnHeaderValueFirst() {
        return $this->columnHeaderValueFirst;
    }

    public function getColumnHeaderValueSecond() {
        return $this->columnHeaderValueSecond;
    }

    public function getColumnHeaderValueThird() {
        return $this->columnHeaderValueThird;
    }


}

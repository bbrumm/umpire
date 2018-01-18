<?php
/*
ReportParameter
This class represents a parameter for a particular report.
It contains information such as the report title, the value to display, and the PDF resolution.
*/

class Report_parameter 
{
    function __construct()
    {
        //parent::__construct()
        //$this->load->model('report_param/ReportGroupingStructure');
    }

    private $reportID;
    private $reportTitle;
    private $valueFieldID;
    private $noValueDisplay;
    private $firstColumnFormat;
    private $colourCells;
    private $pdfOrientation;
    private $pdfPaperSize;
    private $pdfResolution;
    

    //Get Functions
    public function getReportID() {
        return $this->reportID;
    }
    
    public function getReportTitle() {
        return $this->reportTitle;
    }
    

    public function getValueFieldID() {
        return $this->valueFieldID;
    }

    public function getNoValueDisplay() {
        return $this->noValueDisplay;
    }
    
    public function getFirstColumnFormat() {
        return $this->firstColumnFormat;
    }
    
    public function getColourCells() {
        return $this->colourCells;
    }
    
    public function getPDFOrientation() {
        return $this->pdfOrientation;
    }
    
    public function getPDFPaperSize() {
        return $this->pdfPaperSize;
    }
    
    public function getPDFResolution() {
        return $this->pdfResolution;
    }

    //Set Functions
    public function setReportTitle($pValue) {
        $this->reportTitle = $pValue;
    }
    
    public function setValueFieldID($pValue) {
        $this->valueFieldID = $pValue;
    }
    
    public function setNoValueDisplay($pValue) {
        $this->noValueDisplay = $pValue;
    }

    public function setFirstColumnFormat($pValue) {
        $this->firstColumnFormat= $pValue;
    }

    public function setColourCells($pValue) {
        $this->colourCells = $pValue;
    }
    
    public function setPDFOrientation($pValue) {
        $this->pdfOrientation = $pValue;
    }
    
    public function setPDFPaperSize($pValue) {
        $this->pdfPaperSize = $pValue;
    }
    
    public function setPDFResolution($pValue) {
        $this->pdfResolution = $pValue;
    }
}

?>
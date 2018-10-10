<?php
/*
ReportParameter
This class represents a parameter for a particular report.
It contains information such as the report title, the value to display, and the PDF resolution.
*/

class Report_parameter 
{
    public function __construct() {
    }

    public static function createNewReportParameter($pReportTitle, $pValueFieldID, $pNoValueDisplay, 
        $pFirstColumnFormat, $pColourCells, $pPDFOrientation, 
        $pPDFPaperSize, $pPDFResolution) {
        $obj = new Report_parameter();

        $obj->setReportTitle($pReportTitle);
        $obj->setValueFieldID($pValueFieldID);
        $obj->setNoValueDisplay($pNoValueDisplay);
        $obj->setFirstColumnFormat($pFirstColumnFormat);
        $obj->setColourCells($pColourCells);
        $obj->setPDFOrientation($pPDFOrientation);
        $obj->setPDFPaperSize($pPDFPaperSize);
        $obj->setPDFResolution($pPDFResolution);

        return $obj;
    }

    private $reportTitle;
    private $valueFieldID;
    private $noValueDisplay;
    private $firstColumnFormat;
    private $colourCells;
    private $pdfOrientation;
    private $pdfPaperSize;
    private $pdfResolution;
    

    //Get Functions
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
    private function setReportTitle($pValue) {
        $this->reportTitle = $pValue;
    }
    
    private function setValueFieldID($pValue) {
        $this->valueFieldID = $pValue;
    }
    
    private function setNoValueDisplay($pValue) {
        $this->noValueDisplay = $pValue;
    }

    private function setFirstColumnFormat($pValue) {
        $this->firstColumnFormat= $pValue;
    }

    private function setColourCells($pValue) {
        $this->colourCells = $pValue;
    }
    
    private function setPDFOrientation($pValue) {
        $this->pdfOrientation = $pValue;
    }
    
    private function setPDFPaperSize($pValue) {
        $this->pdfPaperSize = $pValue;
    }
    
    private function setPDFResolution($pValue) {
        $this->pdfResolution = $pValue;
    }
}

?>
<?php

class Report_display_options extends CI_Model {
	
	private $columnGroup;
	private $rowGroup;
	private $fieldToDisplay;
	private $noDataValue;
	private $mergeColumnGroup;
	private $colourCells;
	private $columnHeadingLabel;
	private $firstColumnFormat;
	private $columnHeadingSizeText; //An attribute, invisible on the page, but used to force the width of some columns.
	private $pdfResolution;
	private $pdfPaperSize;
	private $pdfOrientation;
	private $lastGameDate; //The last date that a game has been played from the data in the system
	
	public function __construct() {
	    parent::__construct();
	    $this->load->model('report_param/Report_parameter');
	}
	
	public static function createReportDisplayOptions(Report_instance $pReportInstance) {
	    
	    //TODO: Refactor this object or this function as it seems to be duplicating the Report_Parameter object
	    $instance = new Report_display_options();
	    $reportParameter = $pReportInstance->reportParamLoader->getReportParameter();
	    $instance->setNoDataValue($reportParameter->getNoValueDisplay());
	    $instance->setFirstColumnFormat($reportParameter->getFirstColumnFormat());
	    $instance->setColourCells($reportParameter->getColourCells());
	    $instance->setPDFResolution($reportParameter->getPDFResolution());
	    $instance->setPDFPaperSize($reportParameter->getPDFPaperSize());
	    $instance->setPDFOrientation($reportParameter->getPDFOrientation());
	    
	    return $instance;
	}
/*
    public static function createReportDisplayOptionsNew(Report_resultTempinprogress $pReportResult) {

        //TODO: Refactor this object or this function as it seems to be duplicating the Report_Parameter object
        $instance = new Report_display_options();
        $reportParameter = $pReportResult->reportParamLoader->getReportParameter();
        $instance->setNoDataValue($reportParameter->getNoValueDisplay());
        $instance->setFirstColumnFormat($reportParameter->getFirstColumnFormat());
        $instance->setColourCells($reportParameter->getColourCells());
        $instance->setPDFResolution($reportParameter->getPDFResolution());
        $instance->setPDFPaperSize($reportParameter->getPDFPaperSize());
        $instance->setPDFOrientation($reportParameter->getPDFOrientation());

        return $instance;
    }
	*/
	
	
	//GET functions
	public function getColumnGroup() {
		return $this->columnGroup;
	}
	
	public function getRowGroup() {
		return $this->rowGroup;
	}
	
	public function getFieldToDisplay() {
		return $this->fieldToDisplay;
	}
	
	public function getNoDataValue() {
		return $this->noDataValue;
	}
	
	public function getMergeColumnGroup() {
		return $this->mergeColumnGroup;
	}
	
	public function getColourCells() {
	    return $this->colourCells;
	}
	
	public function getColumnHeadingLabel() {
	    return $this->columnHeadingLabel;
	}
	
	public function getFirstColumnFormat() {
	    return $this->firstColumnFormat;
	}
	
	public function getColumnHeadingSizeText() {
	    return $this->columnHeadingSizeText;
	}
	
    public function getPDFResolution() {
	    return $this->pdfResolution;
	}
	
	public function getPDFPaperSize() {
	    return $this->pdfPaperSize;
	}
	public function getPDFOrientation() {
	    return $this->pdfOrientation;
	}
	
	public function getLastGameDate() {
	    return $this->lastGameDate;
	}
	
	
	
	//SET functions
	public function setColumnGroup($pColumnGroupArray) {
		$this->columnGroup = $pColumnGroupArray;
	}
	
	public function setRowGroup($pRowGroupArray) {
		$this->rowGroup = $pRowGroupArray;
	}
	
	public function setFieldToDisplay($pFieldToDisplay) {
		$this->fieldToDisplay = $pFieldToDisplay;
	}
	
	public function setNoDataValue($pNoDataValue) {
		$this->noDataValue = $pNoDataValue;
	}
	
	public function setMergeColumnGroup($pMergeColumnValue) {
		$this->mergeColumnGroup = $pMergeColumnValue;
	}
	
	public function setColourCells($pColourCellsValue) {
	    $this->colourCells = $pColourCellsValue;
	}
	
	public function setColumnHeadingLabel($pValue) {
	    $this->columnHeadingLabel = $pValue;
	}
	
	public function setFirstColumnFormat($pValue) {
	    $this->firstColumnFormat = $pValue;
	}
	
	public function setColumnHeadingSizeText($pValue) {
	    $this->columnHeadingSizeText = $pValue;
	}
	
	public function setPDFResolution($pValue) {
	    $this->pdfResolution = $pValue;
	}
	
	public function setPDFPaperSize($pValue) {
	    $this->pdfPaperSize = $pValue;
	}
	
	public function setPDFOrientation($pValue) {
	    $this->pdfOrientation = $pValue;
	}
	
	public function setLastGameDate($pValue) {
	    $this->lastGameDate = $pValue;
	}
	
}


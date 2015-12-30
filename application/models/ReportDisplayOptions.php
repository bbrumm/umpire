<?php

class ReportDisplayOptions {
	
	private $columnGroup;
	private $rowGroup;
	private $fieldToDisplay;
	private $noDataValue;
	private $mergeColumnGroup;
	private $colourCells;
	private $firstColumnHeadingLabel;
	private $firstColumnFormat;
	
	public function __construct() {
		
	}
	
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
	
	public function getFirstColumnHeadingLabel() {
	    return $this->firstColumnHeadingLabel;
	}
	
	public function getFirstColumnFormat() {
	    return $this->firstColumnFormat;
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
	
	public function setFirstColumnHeadingLabel($pValue) {
	    $this->firstColumnHeadingLabel = $pValue;
	}
	
	public function setFirstColumnFormat($pValue) {
	    $this->firstColumnFormat = $pValue;
	}
	
}

?>
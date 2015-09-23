<?php

class ReportDisplayOptions {
	
	private $columnGroup;
	private $rowGroup;
	private $fieldToDisplay;
	private $noDataValue;
	
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
	
	
}

?>
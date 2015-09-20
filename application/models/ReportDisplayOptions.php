<?php

class ReportDisplayOptions {
	
	private $columnGroup;
	private $rowGroup;
	
	public function __construct() {
		
	}
	
	public function getColumnGroup() {
		return $this->columnGroup;
	}
	
	public function getRowGroup() {
		return $this->rowGroup;
	}
	
	public function setColumnGroup($pColumnGroupArray) {
		$this->columnGroup = $pColumnGroupArray;
	}
	
	public function setRowGroup($pRowGroupArray) {
		$this->rowGroup = $pRowGroupArray;
	}
	
	
}

?>
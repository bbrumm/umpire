<?php
require_once('ReportDisplayOptions.php');
class UserReport extends CI_Model {
	
	private $columnGroupForReport05 = array(
				'short_league_name',
				'club_name'
			);
	private $rowGroupForReport05 = array(
				'full_name'
			);
			
	private $fieldForReport05 = 'match_count';
	
	private $reportQuery;
	private $resultArray;
	private $columnGroupingArray;
	private $rowGroupingArray;
	private $reportTitle;
	private $reportTableName;
	private $reportColumnLabelQuery;
	private $reportRowLabelQuery;
	private $columnLabelResultArray;
	private $rowLabelResultArray;

	public $reportDisplayOptions;
	
	public function __construct() {
		$this->reportDisplayOptions = new ReportDisplayOptions();
		$this->load->database();
	}
	
	public function setReportType($reportParameters) {
		/*echo "<pre>";
		print_r($reportParameters);
		echo "</pre>";*/
		if ($reportParameters['reportName'] == 5) {
			$this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport05);
			$this->reportDisplayOptions->setRowGroup($this->rowGroupForReport05);
			$this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport05);
			$this->reportDisplayOptions->setNoDataValue("");
			$this->reportDisplayOptions->setMergeColumnGroup(array(TRUE, FALSE));
			$this->reportTitle = "05 - Umpires and Clubs - ". $reportParameters['age']." - ".$reportParameters['umpireType'];

		}
		
	}
	
	
	
	public function getReportQuery() {
		return $this->reportQuery;
	}
	
	public function setResultArray($pResultArray) {
		$this->resultArray = $pResultArray;
	}
	
	public function getResultArray() {
		return $this->resultArray;
	}
	
	public function setColumnGroupingArray($pColumnGroupingArray) {
		$this->columnGroupingArray = $pColumnGroupingArray;
	}
	
	public function getColumnGroupingArray() {
		return $this->columnGroupingArray;
	}
	
	public function setRowGroupingArray($pRowGroupingArray) {
		$this->rowGroupingArray = $pRowGroupingArray;
	}
	
	public function getRowGroupingArray() {
		return $this->rowGroupingArray;
	}
	
	public function getDisplayOptions() {
		return $this->reportDisplayOptions;
	}
	
	public function getReportTitle() {
		return $this->reportTitle;
		
	}
	
	public function getReportColumnLabelQuery() {
		return $this->reportColumnLabelQuery;
	}
	
	public function getReportRowLabelQuery() {
		return $this->reportRowLabelQuery;
	}
	
	public function setColumnLabelResultArray($pResultArray) {
		$this->columnLabelResultArray = $pResultArray;
	}
	
	public function getColumnLabelResultArray() {
		return $this->columnLabelResultArray;
	}
	
	public function setRowLabelResultArray($pResultArray) {
		$this->rowLabelR = $pResultArray;
	}
	
	public function getRowLabelResultArray() {
		return $this->rowLabelResultArray;
	}
	
	
	
	
	
}



?>
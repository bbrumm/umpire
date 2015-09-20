<?php
require_once('ReportDisplayOptions.php');
class UserReport {
	
	private $reportQuery;
	private $queryForReport05 = "SELECT full_name, club_name, short_league_name, SUM(match_count) as match_count FROM mv_report_05 WHERE age_group = 'Seniors' and umpire_type_name = 'Field' GROUP BY full_name, club_name, short_league_name";
	private $columnGroupForReport05 = array(
				'short_league_name',
				'club_name'
			);
	private $rowGroupForReport05 = array(
				'full_name'
			);
	
	private $resultArray;
	private $columnGroupingArray;
	private $rowGroupingArray;

	public $reportDisplayOptions;
	
	public function __construct() {
		$this->reportDisplayOptions = new ReportDisplayOptions();
		
	}
	
	public function setReportType($pReportTypeID) {
		if ($pReportTypeID == 5) {
			$this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport05);
			$this->reportDisplayOptions->setRowGroup($this->rowGroupForReport05);
			
			$this->reportQuery = $this->queryForReport05;
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
	
	

}



?>
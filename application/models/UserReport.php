<?php
require_once('ReportDisplayOptions.php');
class UserReport {
	
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

	public $reportDisplayOptions;
	
	public function __construct() {
		$this->reportDisplayOptions = new ReportDisplayOptions();
	}
	
	public function setReportType($pReportTypeID, $pAgeGroup, $pUmpireType) {
		if ($pReportTypeID == 5) {
			$this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport05);
			$this->reportDisplayOptions->setRowGroup($this->rowGroupForReport05);
			$this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport05);
			$this->reportDisplayOptions->setNoDataValue("");
			
			$whereClause = "WHERE age_group = '$pAgeGroup' AND umpire_type_name = '$pUmpireType' ";
			$queryForReport = "SELECT full_name, club_name, short_league_name, SUM(match_count) as match_count FROM mv_report_05 $whereClause GROUP BY full_name, club_name, short_league_name";
			
			$this->reportTitle = "05 - Umpires and Clubs - $pAgeGroup - $pUmpireType";
			
			echo $queryForReport;
			$this->reportQuery = $queryForReport;
			
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
	
	

}



?>
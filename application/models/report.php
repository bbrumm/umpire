<?php
require_once('ReportDisplayOptions');
class UserReport {
	
	private $reportQuery;
	
	public $reportDisplayOptions;
	
	public function __construct() {
		$reportDisplayOptions = new ReportDisplayOptions();
		
	}
	
	public function setReportType($pReportTypeID) {
		switch ($pReportTypeID) {
			case 5:
				//if ($pReportTypeID == 5) {
			    $this->reportDisplayOptions->setColumnGroup(array(
					'short_league_name',
					'club_name'
				));
				$this->reportDisplayOptions->setRowGroup(array(
					'full_name'
				));
				
				//$reportQuery = $queryForReport05;
				break;
			case 6:
				$this->reportDisplayOptions->setColumnGroup(array(
					'short_league_name',
					'club_name'
				));
				$this->reportDisplayOptions->setRowGroup(array(
					'full_name'
				));
				
				//$reportQuery = $queryForReport05;
				break;
			}
	}
}

?>
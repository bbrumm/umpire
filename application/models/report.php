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
				$reportDisplayOptions->setColumnGroup(array(
					'short_league_name',
					'club_name'
				));
				$reportDisplayOptions->setRowGroup(array(
					'full_name'
				));
				
				//$reportQuery = $queryForReport05;
				break;
			case 6:
				$reportDisplayOptions->setColumnGroup(array(
					'short_league_name',
					'club_name'
				));
				$reportDisplayOptions->setRowGroup(array(
					'full_name'
				));
				
				//$reportQuery = $queryForReport05;
				break;
			
			
				break;
		
			}
			
		
	}

}



?>
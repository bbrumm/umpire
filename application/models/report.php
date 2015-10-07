<?php
require_once('ReportDisplayOptions');
class UserReport {
	
	private $reportQuery;
	private $queryForReport05 = "SELECT full_name, club_name, short_league_name, SUM(match_count) as match_count FROM mv_report_05 WHERE age_group = 'Seniors' and umpire_type_name = 'Field' GROUP BY full_name, club_name, short_league_name";

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
				
				$reportQuery = $queryForReport05;
				break;
			case 6:
				$reportDisplayOptions->setColumnGroup(array(
					'short_league_name',
					'club_name'
				));
				$reportDisplayOptions->setRowGroup(array(
					'full_name'
				));
				
				$reportQuery = $queryForReport05;
				break;
			
			
				break;
		
			}
			
		
	}

}



?>
<?php
class report_query extends CI_Model {
	
	//private $queryForReport05 = "SELECT full_name, club_name, short_league_name, SUM(match_count) as match_count FROM mv_report_05 WHERE age_group = 'Seniors' and umpire_type_name = 'Field' GROUP BY full_name, club_name, short_league_name";
	
	public function __construct() {
		$this->load->database();
	}
	
	public function initialiseQuery() {
		//Test this first using the 05 report
		$this->queryString = $this->queryForReport05;
		$this->columnGroup = array(
			'short_league_name',
			'club_name'
		);
		$this->rowGroup = array(
			'full_name'
		);
		
		return $this->queryString;
	
	}
	
}
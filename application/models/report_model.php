<?php
class report_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}
	
	public function get_report() {
		//Only gets the test report at the moment.
		require_once('UserReport.php');
		
		$reportToDisplay = new UserReport();
		$reportToDisplay->setReportType(5);
		
		//print_r($reportToDisplay);
		$query = $this->db->query($reportToDisplay->getReportQuery());
		
		$reportToDisplay->setResultArray($query->result_array());
		
		$uniqueColumnGroup = $this->getDistinctListForGrouping(array('short_league_name', 'club_name'), $reportToDisplay->getResultArray());
		//$uniqueColumnGroup2 = $this->getDistinctListForGrouping('club_name', $reportToDisplay->getResultArray());
		$uniqueRowGroup = $this->getDistinctListForGrouping('full_name', $reportToDisplay->getResultArray());
		
		$reportToDisplay->setColumnGroupingArray($uniqueColumnGroup);
		$reportToDisplay->setRowGroupingArray($uniqueRowGroup);
		
		return $reportToDisplay;
		
	}
	
	//Create distinct arrays for grouping purposes
	private function getDistinctListForGrouping($pArrayFieldName, $pResultArray) {
		$fieldList = array();
		//print_r($pResultArray);
		for ($i=0, $numItems = count($pResultArray); $i < $numItems; $i++) {
	
			$fieldList[0][$i] = $pResultArray[$i]['short_league_name'];
			$fieldList[1][$i] = $pResultArray[$i]['club_name'];
		}
		
		print_r($fieldList);
		$uniqueFieldList = array_unique($fieldList, SORT_REGULAR );
		return $uniqueFieldList;

	}
	
}

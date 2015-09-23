<?php
	require_once('UserReport.php');
	require_once('/../libraries/array_library.php');

class report_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}
	
	public function get_report() {
		$reportToDisplay = new UserReport();
		$reportToDisplay->setReportType($_POST['reportName'], $_POST['age'], $_POST['umpireType']);
		$query = $this->db->query($reportToDisplay->getReportQuery());
		
		$reportToDisplay->setResultArray($query->result_array());
		
		$uniqueColumnGroup = $this->getDistinctListForGrouping(array('short_league_name', 'club_name'), $reportToDisplay->getResultArray());
		$uniqueRowGroup = $this->getDistinctListForGrouping(array('full_name'), $reportToDisplay->getResultArray());
		$reportToDisplay->setColumnGroupingArray($uniqueColumnGroup);
		$reportToDisplay->setRowGroupingArray($uniqueRowGroup);
		
		return $reportToDisplay;
	}
	
	//Create distinct arrays for grouping purposes
	private function getDistinctListForGrouping($pArrayFieldName, $pResultArray) {
		$fieldList = array();
		
		echo "<pre>";
		//print_r($pResultArray);
		echo "</pre>";
		
		for ($i=0, $numItems = count($pResultArray); $i < $numItems; $i++) {
			if (array_key_exists(0, $pArrayFieldName)) {
				$fieldList[$i][0] = $pResultArray[$i][$pArrayFieldName[0]];
			}
			if (array_key_exists(1, $pArrayFieldName)) {
				$fieldList[$i][1] = $pResultArray[$i][$pArrayFieldName[1]];
			}
		}
		echo "<pre>";
		//print_r($fieldList);
		echo "</pre>";
		$uniqueFieldList = array_unique($fieldList, SORT_REGULAR );

		usort($uniqueFieldList, 'sortArray');
		
		//echo "<pre>";
		//print_r($uniqueFieldList);
		//echo "</pre>";
		return $uniqueFieldList;
	}
}

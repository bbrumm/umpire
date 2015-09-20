<?php
	require_once('UserReport.php');
	require_once('/../libraries/array_library.php');

class report_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}
	
	public function get_report() {
		//Only gets the test report at the moment.

		
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
		
		echo "<pre>";
		//print_r($pResultArray);
		echo "</pre>";
		
		for ($i=0, $numItems = count($pResultArray); $i < $numItems; $i++) {
			//if (!in_array_r($pResultArray, $fieldList)) {
				$fieldList[$i][0] = $pResultArray[$i]['short_league_name'];
				$fieldList[$i][1] = $pResultArray[$i]['club_name'];
			//}
		}
		echo "<pre>";
		//print_r($fieldList);
		echo "</pre>";
		$uniqueFieldList = array_unique($fieldList, SORT_REGULAR );
		
		//array_multisort($uniqueFieldList[1], SORT_ASC, SORT_STRING);
		
		usort($uniqueFieldList, 'sortByOrder');
		
		echo "<pre>";
		print_r($uniqueFieldList);
		echo "</pre>";

		
		return $uniqueFieldList;
		
		
	}
	
}

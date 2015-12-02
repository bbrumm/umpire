<?php
	require_once('UserReport.php');
	define('__ROOT__', dirname(dirname(__FILE__))); 
	require_once(__ROOT__.'/libraries/array_library.php');
	

class report_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}
	
	public function get_report($reportParameters) {
		try {
			//$startTime = time();
			
			$reportToDisplay = new UserReport();
			$reportToDisplay->setReportType($reportParameters);
			$query = $this->db->query($reportToDisplay->getReportQuery());
			
			$reportToDisplay->setResultArray($query->result_array());
			//$uniqueColumnGroup = $this->getDistinctListForGrouping(array('short_league_name', 'club_name'), $reportToDisplay->getResultArray());
			//$uniqueRowGroup = $this->getDistinctListForGrouping(array('full_name'), $reportToDisplay->getResultArray());
			
			//Load the results from the queries for the column labels and row labels from the database and store them in an array
			$columnLabelQuery = $this->db->query($reportToDisplay->getReportColumnLabelQuery());
			$reportToDisplay->setColumnLabelResultArray($columnLabelQuery->result_array());
			
			$rowLabelQuery = $this->db->query($reportToDisplay->getReportRowLabelQuery());
			$reportToDisplay->setRowLabelResultArray($rowLabelQuery->result_array());
			
			/*
			echo "Row Label Array<pre>";
			print_r($rowLabelQuery->result_array());
			echo "</pre>";
			*/
			
			//$reportToDisplay->setColumnGroupingArray($uniqueColumnGroup);
			//$reportToDisplay->setRowGroupingArray($uniqueRowGroup);
			
			//temp comment
			$reportToDisplay->setColumnGroupingArray($columnLabelQuery->result_array());
			$reportToDisplay->setRowGroupingArray($rowLabelQuery->result_array());
			
			/*
			echo "<BR />setColumnGroupingArray<pre>";
			print_r($reportToDisplay->getColumnGroupingArray());
			echo "</pre>";
			*/
			//$endTime = time();
			//echo "<BR />getReport Time Taken (s) (" . ($endTime - $startTime) . ")<BR/>";
			
			return $reportToDisplay;
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			
		}
	}
	
	//Create distinct arrays for grouping purposes
	
	private function getDistinctListForGrouping($pArrayFieldName, $pResultArray) {
		$fieldList = array();
		
		/*
		echo "<pre>";
		print_r($pResultArray);
		echo "</pre>";
		*/
		for ($i=0, $numItems = count($pResultArray); $i < $numItems; $i++) {
			if (array_key_exists(0, $pArrayFieldName)) {
				$fieldList[$i][0] = $pResultArray[$i][$pArrayFieldName[0]];
			}
			if (array_key_exists(1, $pArrayFieldName)) {
				$fieldList[$i][1] = $pResultArray[$i][$pArrayFieldName[1]];
			}
		}
		/*
		echo "<pre>";
		print_r($fieldList);
		echo "</pre>";
		*/
		$uniqueFieldList = array_unique($fieldList, SORT_REGULAR );

		usort($uniqueFieldList, 'sortArray');
		
		//echo "<pre>";
		//print_r($uniqueFieldList);
		//echo "</pre>";
		return $uniqueFieldList;
	}
}

<?php
class Report_populator_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->model('Report_instance');
		$this->load->library('Array_library');
		$this->load->model('Requested_report_model');
	}
	
	public function get_report(Requested_report_model $requestedReport) {
	    $requestedReport->setPDFMode(isset($_POST['PDFSubmitted']));

	    $debugMode = $this->config->item('debug_mode');
	    
		$reportToDisplay = new Report_instance();
		$reportToDisplay->setReportType($requestedReport);
		
		//TODO: Convert this to a Collection-type object once it is built
		//$columnLabelCollection = new Object;
		
		//Find table name to select data from
		
		//Convert array of checkbox values into string ready for SQL
		//TODO: Remove the parameter of this as it is already an embedded object.
		$reportToDisplay->convertParametersToSQLReadyValues($requestedReport);
		$reportToDisplay->convertParametersToDisplayValues($requestedReport);
		
		//TODO: Write this function
		//$columnLabelCollection = $this->loadColumnLabels($requestedReport);
		
		/* To get the column labels, currently I get the query data for the report and then translate that.
		 * Is that the best way to go? Or should I use a different method of getting the column labels?
		 * If I keep doing it the same way, then I'll need to convert the report results to a collection
		 * of objects first, then convert the label arrays to objects.
		 * The function to get the report data should pass in the report parameters, and receive a collection
		 * of objects.
		 *  
		 */
		//TODO: Write this function
		$reportToDisplay->loadReportResults();
		
		return $reportToDisplay;
		
		/*
		if ($requestedReport->getReportNumber() == 6) {
		    //Build SELECT query for report data
		    $queryForReport = $this->buildSelectQueryForReport6(
		        $reportToDisplay, $reportTableName, $requestedReport);
		    
		    //Run query and store result in array
		    $query = $this->db->query($queryForReport);			    
		    
		    //Transform array to pivot
		    $queryResultArray = $query->result_array();
		    
		    //TODO: Convert these arrays to objects and collections
		    $rowLabelArray = $this->getDistinctListOfValues('first_umpire', $queryResultArray);
		    $columnLabelArray = $this->getDistinctListOfValues('second_umpire', $queryResultArray);
		    
		    //Params: queryResultArray, field for row, field for columns
		    //TODO: Convert this array to objects and collections
		    $pivotedResultArray = $this->pivotQueryArray($queryResultArray, 'first_umpire', 'second_umpire');
            
		    //Convert column labels into array for the output page
		    //TODO: Convert this array to objects and collections
		    $columnLabelArray = $this->convertSimpleArrayToColumnLabelArray($columnLabelArray);
		    
		    //Set values for result array and column label array
		    $reportToDisplay->setResultArray($pivotedResultArray);
		    $reportToDisplay->setColumnLabelResultArray($columnLabelArray);
		    
		    return $reportToDisplay;
		    
		} else {
			//Build SELECT query for report
			$queryForReport = $this->buildSelectQueryForReport(
			    $reportToDisplay, $reportTableName, $requestedReport);

			$query = $this->db->query($queryForReport);
				
			$reportToDisplay->setResultArray($query->result_array());
			
			//Look up the column labels for this report
			//TODO: Update this function
			$columnLabelQuery = $this->buildColumnLabelQuery(
			    $reportTableName, $reportToDisplay, $requestedReport);
				
			$query = $this->db->query($columnLabelQuery);
			
			$columnLabelResultArray = $query->result_array();
			
			//Add an extra entry if it is report 2, for the Total column
			if ($requestedReport->getReportNumber() == 2) {
    			$columnLabelResultArray[] = array(
    			    'column_name' => 'Total',
    			    'report_column_id' => '0',
    			    'age_group' => 'Total',
    			    'short_league_name' => ''
    			);
			}
			$reportToDisplay->setColumnLabelResultArray($columnLabelResultArray);
			return $reportToDisplay;
		}
		*/
		
	}
	
	
	
	private function convertSimpleArrayToColumnLabelArray($pArray) {
	    /*
	     *** Input format: ***
Array
(
    [0] => Abbott, Trevor
    [1] => Abrehart, Jack
    [2] => Abrehart, Tom
    [3] => Arnott, Tim

	     *** Output format:**
Array
(
    [0] => Array
        (
            [column_name] => BFL|Anglesea
            [report_column_id] => 1
            [short_league_name] => BFL
            [club_name] => Anglesea
        )

    [1] => Array
        (
            [column_name] => BFL|Barwon_Heads
            [report_column_id] => 2
            [short_league_name] => BFL
            [club_name] => Barwon Heads
        )
	     */
	    
	    $outputArray = array();
	    
	    foreach($pArray as $key => $value) {
	        $outputArray[$key]['column_name'] = $value;
	        $outputArray[$key]['umpire_name'] = $value;
	        $outputArray[$key]['report_column_id'] = $key;   
	    }
	    return $outputArray;
	}
	
	//Turn a query result set into a pivot table result set
	private function pivotQueryArray($pResultArray, $pFieldForRowLabel, $pFieldForColumnLabel) {
	    //Create new array to hold values for output
	    $pivotResultArray = array();
	    
	    $pivotedArray = array();
	    $first_umpire_names = array();

	    foreach ($pResultArray as $resultRow) {
	        $second_umpire_names[] = $resultRow['second_umpire'];
	        $pivotedArray[$resultRow['first_umpire']]['umpire_name'] = $resultRow['first_umpire'];
	        $pivotedArray[$resultRow['first_umpire']]['umpire_type_name'] = $resultRow['umpire_type_name'];
	        $pivotedArray[$resultRow['first_umpire']][$resultRow['second_umpire']] = $resultRow['match_count'];
	    }

	    return $pivotedArray;
	}
	
	
	
	private function getDistinctListOfValues($pFieldNameToCheck, $pResultArray) {
		$fieldList = array();
		
		for ($i=0, $numItems = count($pResultArray); $i < $numItems; $i++) {
		    if (array_key_exists(0, $pResultArray)) {
				$fieldList[$i] = $pResultArray[$i][$pFieldNameToCheck];
			}
			
		}

		$uniqueFieldList = array_unique($fieldList, SORT_REGULAR );
		usort($uniqueFieldList, 'compareStringValues');
		return $uniqueFieldList;
	}
	
	//Create distinct arrays for grouping purposes
	
	private function getDistinctListForGrouping($pArrayFieldName, $pResultArray) {
		$fieldList = array();

		for ($i=0, $numItems = count($pResultArray); $i < $numItems; $i++) {
			if (array_key_exists(0, $pArrayFieldName)) {
				$fieldList[$i][0] = $pResultArray[$i][$pArrayFieldName[0]];
			}
			if (array_key_exists(1, $pArrayFieldName)) {
				$fieldList[$i][1] = $pResultArray[$i][$pArrayFieldName[1]];
			}
		}

		$uniqueFieldList = array_unique($fieldList, SORT_REGULAR );

		return $uniqueFieldList;
	}
	
   
	
	

	
	
	
	
	
	
}

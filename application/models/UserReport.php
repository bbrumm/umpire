<?php
require_once('Reportdisplayoptions.php');
class UserReport extends CI_Model {
	
	private $columnGroupForReport01 = array(
				'short_league_name',
				'club_name'
			);
	private $rowGroupForReport01 = array(
				'full_name'
			);
	
	private $columnGroupForReport02 = array(
	    'age_group',
	    'short_league_name'
	);
	private $rowGroupForReport02 = array(
	    'full_name'
	);
	
	private $columnGroupForReport03 = array(
	    'umpire_type_age_group',
	    'short_league_name'
	);
	private $rowGroupForReport03 = array(
	    'weekdate'
	);
	
	private $columnGroupForReport04 = array(
	    'umpire_type',
	    'age_group',
	    'short_league_name'
	);
	private $rowGroupForReport04 = array(
	    'club_name'
	);
	
	private $columnGroupForReport05 = array(
	    'short_league_name'
	);
	
	private $rowGroupForReport05 = array(
	    'umpire_type',
	    'age_group'
	);
	
	private $columnGroupForReport06 = array(
	    'umpire_name'
	);
	private $rowGroupForReport06 = array(
	    'umpire_name'
	);
    
		
	private $fieldForReport01 = 'match_count';
	private $fieldForReport02 = 'match_count';
	private $fieldForReport03 = 'match_count';
	private $fieldForReport04 = 'match_count';
	private $fieldForReport05 = 'match_count';
	private $fieldForReport06 = 'match_count';
	
	private $reportQuery;
	private $resultArray;
	private $columnGroupingArray;
	private $rowGroupingArray;
	private $reportTitle;
	private $reportTableName;
	private $reportColumnLabelQuery;
	private $reportRowLabelQuery;
	private $columnLabelResultArray;
	private $rowLabelResultArray;
	
	private $umpireTypeSQLValues;
	private $leagueSQLValues;
	private $ageGroupSQLValues;
	private $regionSQLValues;
	
	private $umpireTypeDisplayValues;
	private $leagueDisplayValues;
	private $ageGroupDisplayValues;
	private $regionDisplayValues;
	
	
	private $reportID;

	public $reportDisplayOptions;
	
	public function getUmpireTypeSQLValues() {
	    return $this->umpireTypeSQLValues;
	}
	
	public function getLeagueSQLValues() {
	    return $this->leagueSQLValues;
	}
	
	public function getAgeGroupSQLValues() {
	    return $this->ageGroupSQLValues;
	}
	
	public function getRegionSQLValues() {
	    return $this->regionSQLValues;
	}
	
	public function getUmpireTypeDisplayValues() {
	    return $this->umpireTypeDisplayValues;
	}
	
	public function getLeagueDisplayValues() {
	    return $this->leagueDisplayValues;
	}
	
	public function getAgeGroupDisplayValues() {
	    return $this->ageGroupDisplayValues;
	}
	
	
	
	
	public function __construct() {
		$this->reportDisplayOptions = new ReportDisplayOptions();
		$this->load->database();
		$this->load->model('report_param/ReportParamLoader');
		$this->load->model('report_param/ReportParameter');
	}
	
	public function setReportType($reportParameters) {
	    $debugMode = $this->config->item('debug_mode');
	     
	    if ($debugMode) {
    		echo "reportParameters in setReportType<pre>";
    		print_r($reportParameters);
    		echo "</pre><BR />";
    		
    		echo "POST in setReportType<pre>";
    		print_r($_POST);
    		echo "</pre>";
	    }
	    //ReportParameters are set in controllers/report.php->index();
	    
	    
	    
	    if ($reportParameters['PDFMode'] == true) {
	        $ageGroupValue = rtrim($reportParameters['age'], ',');
	        $umpireDisciplineValue = rtrim($reportParameters['umpireType'], ',');
	        if ($debugMode) {
	           echo "Umpire Discipline in setReportType: " . $umpireDisciplineValue . "<BR/>";    
	        
	        }
	        $seasonValue = $reportParameters['season'];
	    } else {
	    
    	    $ageGroupValue = implode(',', $reportParameters['age']);
    	    //$ageGroupValue = $reportParameters['age'];
    	    $leagueValue = "";
    	    $umpireDisciplineValue = implode(',', $reportParameters['umpireType']);
    	    //$umpireDisciplineValue = ($reportParameters['umpireType'],",");
    	    $seasonValue = $reportParameters['season'];
    	    
	    }
	    
	    $reportParamLoader = new ReportParamLoader();
	    $reportParamLoader->loadAllReportParametersForReport((int)$reportParameters['reportName']);
	    $reportParameterArray = $reportParamLoader->getReportParameterArray();
	    
	    $reportParamLoader->loadAllGroupingStructuresForReport((int)$reportParameters['reportName']);
	    $reportGroupingStructureArray = $reportParamLoader->getReportGroupingStructureArray();
	    
	    //echo "ReportName(". (int)$reportParameters['reportName'] .")";
	    
	    //$this->reportDisplayOptions->setFieldToDisplay($this->lookupParameterValue($reportParameterArray, 'Value Field'));
	    $this->reportDisplayOptions->setNoDataValue($this->lookupParameterValue($reportParameterArray, 'No Value To Display'));
	    $this->reportDisplayOptions->setFirstColumnFormat($this->lookupParameterValue($reportParameterArray, 'First Column Format'));
	    $this->reportDisplayOptions->setColourCells($this->lookupParameterValue($reportParameterArray, 'Colour Cells'));
	    $this->reportDisplayOptions->setPDFResolution($this->lookupParameterValue($reportParameterArray, 'PDF Resolution'));
	    $this->reportDisplayOptions->setPDFPaperSize($this->lookupParameterValue($reportParameterArray, 'PDF Paper Size'));
	    $this->reportDisplayOptions->setPDFOrientation($this->lookupParameterValue($reportParameterArray, 'PDF Orientation'));
	    $this->reportTitle = str_replace("%seasonYear", $seasonValue, $this->lookupParameterValue($reportParameterArray, 'Display Title'));
	    $this->reportID = $reportParameters['reportName'];
	    
	    /*
	    echo "<pre>reportParameterArray:";
	    print_r($reportParameterArray);
	    echo "</pre>";
	    
	    echo "<pre>reportDisplayOptions:";
	    print_r($this->reportDisplayOptions);
	    echo "</pre>";
	    */
	    
	    //Extract the ReportGroupingStructure into separate arrays for columns and rows
	    $columnGroupForReport = $this->extractGroupFromGroupingStructure($reportGroupingStructureArray, 'Column');
	    $rowGroupForReport = $this->extractGroupFromGroupingStructure($reportGroupingStructureArray, 'Row');	    
	    $this->reportDisplayOptions->setColumnGroup($columnGroupForReport);
	    $this->reportDisplayOptions->setRowGroup($rowGroupForReport);

		$this->reportDisplayOptions->setLastGameDate($this->findLastGameDateForSelectedSeason($reportParameters['season']));
	}
	
	private function extractGroupFromGroupingStructure($pReportGroupingStructureArray, $pGroupingType) {
	    $reportGroupingStructure = new ReportGroupingStructure();
	    
	    for($i=0; $i<count($pReportGroupingStructureArray); $i++) {
	        if ($pReportGroupingStructureArray[$i]->getGroupingType() == $pGroupingType) {
	           $outputReportGroupingStructure[] = $pReportGroupingStructureArray[$i];
	        }
	    }
	    return $outputReportGroupingStructure;
	}
	
	
	private function lookupParameterValue($reportParameterArray, $parameterName) {
	    $parameterValue = "";
	    
	    for($i=0; $i<count($reportParameterArray); $i++) {
	        $reportParameter = new ReportParameter();
	        $reportParameter = $reportParameterArray[$i];
	        
	        if($reportParameter->getParameterName() == $parameterName) {
	            $parameterValue = $reportParameter->getParameterValue();
	            break;
	        }
	    }
	
	    return $parameterValue;
	
	}
	
	
	private function findLastGameDateForSelectedSeason($selectedSeason) {
	    $queryString = "SELECT DATE_FORMAT(MAX(match_time), '%a %d %b %Y, %h:%i %p') AS last_date " .
            "FROM match_played " .
            "INNER JOIN round ON round.id = match_played.round_id " .
            "INNER JOIN season ON season.id = round.season_id " .
            "WHERE season.season_year = '$selectedSeason';";
	     
	    $query = $this->db->query($queryString);
	    $resultArray = $query->result_array();
	    //echo "LGD: " . $resultArray[0]['last_date'];
	    return $resultArray[0]['last_date'];
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
	
	public function getReportColumnLabelQuery() {
		return $this->reportColumnLabelQuery;
	}
	
	public function getReportRowLabelQuery() {
		return $this->reportRowLabelQuery;
	}
	
	public function setColumnLabelResultArray($pResultArray) {
		$this->columnLabelResultArray = $pResultArray;
	}
	
	public function getColumnLabelResultArray() {
		return $this->columnLabelResultArray;
	}
	
	public function setRowLabelResultArray($pResultArray) {
		$this->rowLabelR = $pResultArray;
	}
	
	public function getRowLabelResultArray() {
		return $this->rowLabelResultArray;
	}
	
	public function setReportID($pValue) {
	    $this->reportID = $pValue;
	}
	
	public function getReportID() {
	    return $this->reportID;
	    
	}

	public function getColumnCountForHeadingCells() {
	    /* This function finds the number of columns for each column value, so that the report can merge the correct number of cells.
	     * It uses the column labels to show (e.g. BFL, GFL), and loops through the records from the database. 
	     * Inside the loop, it looks for records that match each of the column labels, and increments the counter if one is found.
	     * E.g. if a report needs to show columns for BFL, GFL, and GDFL, and the full list of columns includes BFL, BFL, GDFL, GFL, BFL, GFL...
	     * Then the result will be BFL=3, GFL=1, GDFL=1.
	     */
	    $debugMode = $this->config->item('debug_mode');
	    
	    $columnLabelResults = $this->columnLabelResultArray;
	    if ($debugMode) {
    	    echo "<pre>CLR: ";
    	    print_r($columnLabelResults);
    	    echo "</pre>";
	    }
	    
	    $columnLabels = $this->getDisplayOptions()->getColumnGroup(); //Used to be array('field_name1', 'field_name2')
	    $columnCountLabels = [];
        if ($debugMode) {
    	    echo "<pre>CL: ";
    	    print_r($columnLabels);
    	    echo "</pre>";
        }
	    
	    //Loop through the possible labels
	    for ($i=0; $i < count($columnLabels); $i++) {
	        if ($i == 0) {
	            $columnCountLabels[0] = [];
	        }
	        if ($i == 1) {
	            $columnCountLabels[1] = [];
	        }
	        if ($i == 2) {
	            $columnCountLabels[2] = [];
	        }
	        
	        $arrayKeyNumber = 0;
	        
	        //Loop through columnLabelResults
	        for ($j=0; $j < count($columnLabelResults); $j++) {
	           if ($i == 0) {
	               //if (in_array_r($columnLabelResults[$j][$columnLabels[$i]], $columnCountLabels[$i]) == TRUE) {
	               if (in_array_r($columnLabelResults[$j][$columnLabels[$i]->getFieldName()], $columnCountLabels[$i]) == TRUE) {
	                   //Value found in array. Increment counter value
	                   //echo "- Value found: " . $columnLabelResults[$j][$columnLabels[$i]] . "<BR />";
	                   //Find the array that stores this value
	                   $currentArrayKey = $this->findKeyFromValue(
	                           $columnCountLabels[$i], $columnLabelResults[$j][$columnLabels[$i]->getFieldName()], "unique label");
	                   $columnCountLabels[$i][$currentArrayKey]["count"]++;
	                   
	                   //$columnCountLabels[0][$columnLabelResults[$j][$columnLabels[$i]]] = $columnCountLabels[0][$columnLabelResults[$j][$columnLabels[$i]]] + 1;
	               } else {
	                   //Value not found. Add to array.
	                   //$columnCountLabels[0][$columnLabelResults[$j][$columnLabels[$i]]] = 1;
	                   $columnCountLabels[$i][$arrayKeyNumber]["label"] = $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	                   $columnCountLabels[$i][$arrayKeyNumber]["unique label"] = $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	                   $columnCountLabels[$i][$arrayKeyNumber]["count"] = 1;
	                   $arrayKeyNumber++;
	               }
	           }
	           if ($i == 1) {
	               //echo "<BR />Find (" . $columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]] . ")<BR/>";
	               if (in_array_r($columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()] . "|" . 
	                   $columnLabelResults[$j][$columnLabels[$i]->getFieldName()], $columnCountLabels[$i]) == TRUE) {
	                   //Value found in array. Increment counter value
	                   //echo "- Value found: " . $columnLabelResults[$j][$columnLabels[$i]] . ". Check if previous fields match.<BR />";
	                   //Check if the value on the first row matches
	                   //echo "- Look for match (". $columnLabelResults[$j-1][$columnLabels[$i-1]] .") and (". $columnLabelResults[$j][$columnLabels[$i-1]] .")<BR />";
	                   if ($columnLabelResults[$j-1][$columnLabels[$i-1]->getFieldName()] == $columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()]) {
	                       //echo "-- Match";
	                       
                           $currentArrayKey = $this->findKeyFromValue($columnCountLabels[$i], 
                               $columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()] . "|" . 
                               $columnLabelResults[$j][$columnLabels[$i]->getFieldName()], "unique label");
                           //echo "-- Increment array key [". $i ."][". $currentArrayKey ."]<BR/>";
                           $columnCountLabels[$i][$currentArrayKey]["count"]++;
	                   } else {
	                       $columnCountLabels[$i][$arrayKeyNumber]["label"] = 
	                           $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	                       $columnCountLabels[$i][$arrayKeyNumber]["unique label"] = 
	                           $columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()] . "|" . 
	                           $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	                       $columnCountLabels[$i][$arrayKeyNumber]["count"] = 1;
	                       $arrayKeyNumber++;
	                   }
	               } else {
	                   //Value not found. Add to array.
	                   //echo "- Value not found: " . $columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]] . ". Added to array.<BR />";
	                   $columnCountLabels[$i][$arrayKeyNumber]["label"] = 
	                       $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	                   $columnCountLabels[$i][$arrayKeyNumber]["unique label"] = 
	                       $columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()] . "|" . 
	                       $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	                   $columnCountLabels[$i][$arrayKeyNumber]["count"] = 1;
	                   $arrayKeyNumber++;
	               }
	           }
	           if ($i == 2) {
	               //Set all count values to 1 for this level, as it is not likely that the third row will need to be merged/have a higher than 1 colspan.
	               $columnCountLabels[$i][$j]["label"] = 
	                   $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	               $columnCountLabels[$i][$j]["unique label"] = 
	   	               $columnLabelResults[$j][$columnLabels[$i-2]->getFieldName()] . "|" . 
	                   $columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()] . "|" . 
	                   $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	               $columnCountLabels[$i][$j]["count"] = 1;
	           }
	        }
	    }
	    
	    //echo "<BR />getColumnCountForHeadingCells:<BR /><pre>";
	    //print_r($columnCountLabels);
	    //echo "</pre><BR />";
	    
	    return $columnCountLabels;
	    
	}
	
	function in_array_r($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }
	
	    return false;
	}
	
	function findKeyFromValue($pArray, $pValueToFind, $pKeyToLookAt) {
	   $arrayKeyFound = 0;
	   for ($i=0; $i < count($pArray); $i++) {
	       if ($pArray[$i][$pKeyToLookAt] == $pValueToFind) {
	           $arrayKeyFound = $i;
	       }
	   }
	   return $arrayKeyFound;
	    
	}
	
	public function convertParametersToSQLReadyValues($reportParameters) {
	   //Converts several of the reportParameters arrays into comma separate values that are ready for SQL queries
	    //Add a value of "All" and "None" to the League list, so that reports that users select for ages with no league (e.g. Colts) are still able to be loaded
	    //$reportParameters['league'][] = 'All';
	    //$reportParameters['league'][] = 'None';
	    //echo "reportParameters UmpireType: " . $reportParameters['umpireType'] . "<BR/>";
	    //
	    if ($reportParameters['PDFMode']) {
	        $this->umpireTypeSQLValues = str_replace(",", "','", "'" . rtrim($reportParameters['umpireType'], ',')) . "'";
	        $this->leagueSQLValues = str_replace(",", "','", "'" . rtrim($reportParameters['league'], ',')) . "'";
	        $this->ageGroupSQLValues = str_replace(",", "','", "'" . rtrim($reportParameters['age'], ',')) . "'";
	        $this->regionSQLValues = str_replace(",", "','", "'" . rtrim($reportParameters['region'], ',')) . "'";
	    } else {
    	    $this->umpireTypeSQLValues = "'".implode("','",$reportParameters['umpireType'])."'";
    	    $this->leagueSQLValues = "'".implode("','",$reportParameters['league'])."'";
    	    $this->ageGroupSQLValues = "'".implode("','",$reportParameters['age'])."'";
    	    //$this->regionSQLValues = "'".implode("','",$reportParameters['region'])."'";
    	    $this->regionSQLValues = str_replace(",", "','", "'" . rtrim($reportParameters['region'], ',')) . "'";
	    }
	}
	
	public function convertParametersToDisplayValues($reportParameters) {
	    
	    if ($reportParameters['PDFMode']) {
	        $this->umpireTypeDisplayValues = str_replace(",", ", ", rtrim($reportParameters['umpireType'], ',')) . "'";
	        $this->leagueDisplayValues = str_replace(",", ", ", rtrim($reportParameters['league'], ',')) . "'";
	        $this->ageGroupDisplayValues = str_replace(",", ", ", rtrim($reportParameters['age'], ',')) . "'";
	    } else {
    	    $this->umpireTypeDisplayValues = implode(", ", $reportParameters['umpireType']);
    	    $this->leagueDisplayValues = implode(", ", $reportParameters['league']);
    	    $this->ageGroupDisplayValues = implode(", ", $reportParameters['age']);
	    }
	}	
}
?>
<?php
class Report_instance extends CI_Model {
	
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
	
	public $requestedReport;

	public $reportDisplayOptions;

	public function __construct() {
	    $this->load->model('ReportDisplayOptions');
	    $this->reportDisplayOptions = new ReportDisplayOptions();
	    $this->load->database();
	    $this->load->library('Debug_library');
	    $this->load->model('report_param/ReportParamLoader');
	    $this->load->model('report_param/ReportParameter');
	    $this->load->model('Requested_report_model');
	    $this->requestedReport = new Requested_report_model();
	}
	
	
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
	
	

	public function setReportType(Requested_report_model $pRequestedReport) {
	    $this->debug_library->debugOutput("reportParameters in setReportType", $pRequestedReport);
	    $this->debug_library->debugOutput("POST in setReportType", $_POST);
	    
	    //RequestedReport values are set in controllers/report.php->index();
	    if ($pRequestedReport->getPDFMode() == true) {
	        $ageGroupValue = rtrim($pRequestedReport->getAgeGroup(), ',');
	        $umpireDisciplineValue = rtrim($pRequestedReport->getUmpireType(), ',');
	        
	        $this->debug_library->debugOutput("Umpire Discipline in setReportType:", $umpireDisciplineValue);
	    } else {
    	    $ageGroupValue = implode(',', $pRequestedReport->getAgeGroup());
    	    $leagueValue = "";
    	    $umpireDisciplineValue = implode(',', $pRequestedReport->getUmpireType());
	    }
	    
	    $reportParamLoader = new ReportParamLoader();
	    $reportParamLoader->loadAllReportParametersForReport($pRequestedReport);
	    $reportParameterArray = $reportParamLoader->getReportParameterArray();
	    $reportParamLoader->loadAllGroupingStructuresForReport($pRequestedReport);
	    $reportGroupingStructureArray = $reportParamLoader->getReportGroupingStructureArray();
	    
	    $this->reportDisplayOptions->setNoDataValue($this->lookupParameterValue($reportParameterArray, 'No Value To Display'));
	    $this->reportDisplayOptions->setFirstColumnFormat($this->lookupParameterValue($reportParameterArray, 'First Column Format'));
	    $this->reportDisplayOptions->setColourCells($this->lookupParameterValue($reportParameterArray, 'Colour Cells'));
	    $this->reportDisplayOptions->setPDFResolution($this->lookupParameterValue($reportParameterArray, 'PDF Resolution'));
	    $this->reportDisplayOptions->setPDFPaperSize($this->lookupParameterValue($reportParameterArray, 'PDF Paper Size'));
	    $this->reportDisplayOptions->setPDFOrientation($this->lookupParameterValue($reportParameterArray, 'PDF Orientation'));
	    $this->reportTitle = str_replace("%seasonYear", $pRequestedReport->getSeason(), $this->lookupParameterValue($reportParameterArray, 'Display Title'));
	    //TODO: Remove this variable and line as it is in the sub-object
	    $this->reportID = $pRequestedReport->getReportNumber();
	    
	    //Extract the ReportGroupingStructure into separate arrays for columns and rows
	    $columnGroupForReport = $this->extractGroupFromGroupingStructure($reportGroupingStructureArray, 'Column');
	    $rowGroupForReport = $this->extractGroupFromGroupingStructure($reportGroupingStructureArray, 'Row');	    
	    $this->reportDisplayOptions->setColumnGroup($columnGroupForReport);
	    $this->reportDisplayOptions->setRowGroup($rowGroupForReport);

		$this->reportDisplayOptions->setLastGameDate(
		      $this->findLastGameDateForSelectedSeason($pRequestedReport));
		
		$this->requestedReport = $pRequestedReport;
		
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
	
	
	private function findLastGameDateForSelectedSeason(Requested_report_model $pRequestedReport) {
	    $queryString = "SELECT DATE_FORMAT(MAX(match_time), '%a %d %b %Y, %h:%i %p') AS last_date " .
            "FROM match_played " .
            "INNER JOIN round ON round.id = match_played.round_id " .
            "INNER JOIN season ON season.id = round.season_id " .
            "WHERE season.season_year = ". $pRequestedReport->getSeason() .";";
	     
	    $resultArray = $query->result_array();
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
	    
	    $columnLabelResults = $this->columnLabelResultArray;
	    
	    $this->debug_library->debugOutput("CLR:", $columnLabelResults);
	    
	    $columnLabels = $this->getDisplayOptions()->getColumnGroup(); //Used to be array('field_name1', 'field_name2')
	    $columnCountLabels = [];
	    
	    $this->debug_library->debugOutput("CL:", $columnLabels);
	    
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
	               if ($this->in_array_r($columnLabelResults[$j][$columnLabels[$i]->getFieldName()], $columnCountLabels[$i]) == TRUE) {
	                   //Value found in array. Increment counter value
	                   //Find the array that stores this value
	                   $currentArrayKey = $this->findKeyFromValue(
	                           $columnCountLabels[$i], $columnLabelResults[$j][$columnLabels[$i]->getFieldName()], "unique label");
	                   $columnCountLabels[$i][$currentArrayKey]["count"]++;
	                   
	               } else {
	                   //Value not found. Add to array.
	                   $columnCountLabels[$i][$arrayKeyNumber]["label"] = $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	                   $columnCountLabels[$i][$arrayKeyNumber]["unique label"] = $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	                   $columnCountLabels[$i][$arrayKeyNumber]["count"] = 1;
	                   $arrayKeyNumber++;
	               }
	           }
	           if ($i == 1) {
	               if ($this->in_array_r($columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()] . "|" . 
	                   $columnLabelResults[$j][$columnLabels[$i]->getFieldName()], $columnCountLabels[$i]) == TRUE) {
	                   //Value found in array. Increment counter value
	                   //$this->debug_library->debugOutput("- Value found:", $columnLabelResults[$j][$columnLabels[$i]]);
	                   //Check if the value on the first row matches
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
	    return $columnCountLabels;
	    
	}
	
	private function in_array_r($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }
	
	    return false;
	}
	
	private function findKeyFromValue($pArray, $pValueToFind, $pKeyToLookAt) {
	   $arrayKeyFound = 0;
	   for ($i=0; $i < count($pArray); $i++) {
	       if ($pArray[$i][$pKeyToLookAt] == $pValueToFind) {
	           $arrayKeyFound = $i;
	       }
	   }
	   return $arrayKeyFound;
	    
	}
	
	public function convertParametersToSQLReadyValues(Requested_report_model $requestedReport) {
	    //Converts several of the reportParameters arrays into comma separate values that are ready for SQL queries
	    //Add a value of "All" and "None" to the League list, so that reports that users select for ages with no league (e.g. Colts) are still able to be loaded
	    //$reportParameters['league'][] = 'All';
	    //$reportParameters['league'][] = 'None';
	    //echo "reportParameters UmpireType: " . $reportParameters['umpireType'] . "<BR/>";
	    if ($requestedReport->getPDFMode()) {
	        $this->umpireTypeSQLValues = str_replace(",", "','", "'" . rtrim($requestedReport->getUmpireType(), ',')) . "'";
	        $this->leagueSQLValues = str_replace(",", "','", "'" . rtrim($requestedReport->getLeague(), ',')) . "'";
	        $this->ageGroupSQLValues = str_replace(",", "','", "'" . rtrim($requestedReport->getAgeGroup(), ',')) . "'";
	        $this->regionSQLValues = str_replace(",", "','", "'" . rtrim($requestedReport->getRegion(), ',')) . "'";
	    } else {
    	    $this->umpireTypeSQLValues = "'".implode("','", $requestedReport->getUmpireType())."'";
    	    $this->leagueSQLValues = "'".implode("','", $requestedReport->getLeague())."'";
    	    $this->ageGroupSQLValues = "'".implode("','", $requestedReport->getAgeGroup())."'";
    	    $this->regionSQLValues = str_replace(",", "','", "'" . rtrim($requestedReport->getRegion(), ',')) . "'";
	    }
	}
	
	public function convertParametersToDisplayValues(Requested_report_model $requestedReport) {
	    
	    if ($requestedReport->getPDFMode()) {
	        $this->umpireTypeDisplayValues = str_replace(",", ", ", rtrim($requestedReport->getUmpireType(), ',')) . "'";
	        $this->leagueDisplayValues = str_replace(",", ", ", rtrim($requestedReport->getLeague(), ',')) . "'";
	        $this->ageGroupDisplayValues = str_replace(",", ", ", rtrim($requestedReport->getAgeGroup(), ',')) . "'";
	    } else {
    	    $this->umpireTypeDisplayValues = implode(", ", $requestedReport->getUmpireType());
    	    $this->leagueDisplayValues = implode(", ", $requestedReport->getLeague());
    	    $this->ageGroupDisplayValues = implode(", ", $requestedReport->getAgeGroup());
	    }
	}	
}
?>
<?php
class Report_instance extends CI_Model {
	
	private $reportQuery;
	private $resultArray;
	private $reportTitle;
	private $columnLabelResultArray;
	private $rowLabelResultArray;
	
	private $resultOutputArray; //New variable to store the array so it can be directly output to the screen
	
	public $requestedReport;
	public $reportDisplayOptions;
	
	public $reportParamLoader;
	public $reportParameter;
	
	public $filterParameterUmpireType;
	public $filterParameterAgeGroup;
	public $filterParameterLeague;
	public $filterParameterRegion;

	private $separateReport;
	private $formattedOutputArray;

	public function __construct() {
	    $this->load->model('Report_display_options');
	    $this->reportDisplayOptions = new Report_display_options();
	    $this->load->database();
	    $this->load->library('Debug_library');
	    $this->load->library('Array_library');
	    $this->load->model('report_param/Report_param_loader');
	    $this->load->model('report_param/Report_parameter');
	    $this->load->model('Requested_report_model');
	    $this->load->model('Report_filter_parameter');
	    $this->requestedReport = new Requested_report_model();
	    $this->filterParameterUmpireType = new Report_filter_parameter();
	    $this->filterParameterAgeGroup = new Report_filter_parameter();
	    $this->filterParameterLeague = new Report_filter_parameter();
	    $this->filterParameterRegion = new Report_filter_parameter();
	    $this->reportParamLoader = new Report_param_loader();
	    $this->load->model('separate_reports/Report_factory');

	}
	
	public function getResultArray() {
	    return $this->resultArray;
	}
	
	public function getDisplayOptions() {
	    return $this->reportDisplayOptions;
	}
	
	public function getReportTitle() {
	    return $this->reportTitle;
	
	}
	
	public function getReportColumnFields() {
	    return $this->reportColumnFields;
	}
	

	public function getResultOutputArray() {
	    return $this->resultOutputArray;
	}
	
	public function getColumnLabelResultArray() {
	    return $this->columnLabelResultArray;
	}
	

	

	

	
	private function translateRptGrStructureToSimpleArray($pReportGroupingStructureArray) {
	    $simpleColumnFieldArray =[];
	    $countReportGroupingStructureArray = count($pReportGroupingStructureArray);
	    for ($i=0; $i < $countReportGroupingStructureArray; $i++) {
	        if ($pReportGroupingStructureArray[$i]->getGroupingType() == 'Column') {
	            $simpleColumnFieldArray[] = $pReportGroupingStructureArray[$i]->getFieldName();
	        }
	    }
	    return $simpleColumnFieldArray;
	}


	public function setReportType(Requested_report_model $pRequestedReport) {
	    //RequestedReport values are set in controllers/report.php->index();
	    if ($pRequestedReport->getPDFMode() == true) {
	        $ageGroupValue = rtrim($pRequestedReport->getAgeGroup(), ',');
	        $umpireDisciplineValue = rtrim($pRequestedReport->getUmpireType(), ',');
	    } else {
    	    $ageGroupValue = implode(',', $pRequestedReport->getAgeGroup());
    	    $leagueValue = "";
    	    $umpireDisciplineValue = implode(',', $pRequestedReport->getUmpireType());
	    }
	    
	    $pDatabaseStore = new Database_store();
	    
	    $this->reportParamLoader->loadAllReportParametersForReport($pRequestedReport, $pDatabaseStore);
	    $this->reportParameter = $this->reportParamLoader->getReportParameter();
	    $this->reportParamLoader->loadAllGroupingStructuresForReport($pRequestedReport, $pDatabaseStore);
	    
	    $reportGroupingStructureArray = $this->reportParamLoader->getReportGroupingStructureArray();

	    $this->reportDisplayOptions = Report_display_options::createReportDisplayOptions($this);
	    
	    //ReportGroupingStructureArray comes from the database tables
	    $this->reportColumnFields = $this->translateRptGrStructureToSimpleArray($reportGroupingStructureArray);
	    $this->reportTitle = $this->setReportTitle($pRequestedReport->getSeason());
	    
	    $this->requestedReport = $pRequestedReport;

	    //Extract the ReportGroupingStructure into separate arrays for columns and rows
	    $columnGroupForReport = $this->extractGroupFromGroupingStructure($reportGroupingStructureArray, 'Column');
	    $rowGroupForReport = $this->extractGroupFromGroupingStructure($reportGroupingStructureArray, 'Row');
	    $this->reportDisplayOptions->setColumnGroup($columnGroupForReport);
	    $this->reportDisplayOptions->setRowGroup($rowGroupForReport);
	    
	    $this->filterParameterUmpireType->createFilterParameter($this->requestedReport->getUmpireType(), $this->requestedReport->getPDFMode());
	    $this->filterParameterAgeGroup->createFilterParameter($this->requestedReport->getAgeGroup(), $this->requestedReport->getPDFMode());
	    $this->filterParameterLeague->createFilterParameter($this->requestedReport->getLeague(), $this->requestedReport->getPDFMode());
	    $this->filterParameterRegion->createFilterParameter($this->requestedReport->getRegion(), $this->requestedReport->getPDFMode(), true);
		$this->reportDisplayOptions->setLastGameDate($this->findLastGameDateForSelectedSeason());
	}
	
	private function setReportTitle($pSeasonYear) {
	    return str_replace("%seasonYear", $pSeasonYear, $this->reportParameter->getReportTitle());
	}
	
	
	public function loadReportResults() {
        $separateReport = Report_factory::createReport($this->requestedReport->getReportNumber());
        $queryForReport = $separateReport->getReportDataQuery($this);
        
        $this->debug_library->debugOutput("queryForReport:",  $queryForReport);

        //Run query and store result in array
        $query = $this->db->query($queryForReport);
        
        //Transform array to pivot
        $queryResultArray = $query->result_array();
        
        if (!isset($queryResultArray[0])) {
            throw new Exception("Result Array is empty. This is probably due to the SQL query not returning any results for report "
                . $this->requestedReport->getReportNumber() .".<BR />Query:<BR />" . $queryForReport);
        }
        
        //Set result array (function includes logic for different reports
        /*
         *  Expected Output:
            Array
            (
                [Abbott, Trevor] => Array
                    (
                        [0] => Array (each instance or col/row combination to output)
                            (
                                [short_league_name] => BFL (one of the column groups)
                                [match_count] => 1 (value to output)
                                [club_name] => Modewarre (another column group)
                            )

                        [1] => Array (another instance to output)
                            (
                                [short_league_name] => BFL
                                [match_count] => 1
                                [club_name] => Queenscliff
                            )

                    )
         *
         */
        $this->setResultArray($separateReport, $queryResultArray);

        //Pivot the array so it can be displayed
        $this->setColumnLabelResultArray($separateReport, $queryResultArray);
        
        //TODO: This function is causing the output values to be misaligned.
        $this->setResultOutputArray($separateReport);

        $this->separateReport = $separateReport;
	}

	private function extractGroupFromGroupingStructure($pReportGroupingStructureArray, $pGroupingType) {
	    $reportGroupingStructure = new Report_grouping_structure();
	    
	    for($i=0; $i<count($pReportGroupingStructureArray); $i++) {
	        if ($pReportGroupingStructureArray[$i]->getGroupingType() == $pGroupingType) {
	           $outputReportGroupingStructure[] = $pReportGroupingStructureArray[$i];
	        }
	    }
	    return $outputReportGroupingStructure;
	}
	
	
	private function findLastGameDateForSelectedSeason() {
	    $queryString = "SELECT DATE_FORMAT(MAX(match_time), '%a %d %b %Y, %h:%i %p') AS last_date 
            FROM match_played 
            INNER JOIN round ON round.id = match_played.round_id 
            INNER JOIN season ON season.id = round.season_id 
            WHERE season.season_year = ". $this->requestedReport->getSeason() .";";
	     
	    $query = $this->db->query($queryString);
	    $queryResultArray = $query->result_array();
	    return $queryResultArray[0]['last_date'];
	}
	
	public function setResultArray(IReport $pSeparateReport, $pResultArray) {
        foreach ($this->reportDisplayOptions->getColumnGroup() as $columnGroupItem) {
            $columnLabelArray[] = $columnGroupItem->getFieldName();
        }
        foreach ($this->reportDisplayOptions->getRowGroup() as $rowGroupItem) {
            $rowLabelField[] = $rowGroupItem->getFieldName();
        }
        
        $this->resultArray = $pSeparateReport->pivotQueryArray($pResultArray, $rowLabelField, $columnLabelArray);
        //$this->debug_library->debugOutput("pResultArray:", $pResultArray);
	}

    private function setColumnLabelResultArray(IReport $separateReport, $pColumnLabelArray) {
        //Find a distinct list of values to use as column headings
        //$separateReport = Report_factory::createReport($this->requestedReport->getReportNumber());
        $columnLabelQuery = $separateReport->getReportColumnQuery($this);

        $query = $this->db->query($columnLabelQuery);
        $this->columnLabelResultArray = $query->result_array();
        $this->debug_library->debugOutput("columnLabelResultArray in setColumnLabelResultArray:", $this->getColumnLabelResultArray());
    }

    private function setResultOutputArray(IReport $separateReport) {
        $columnLabelResultArray = $this->getColumnLabelResultArray();
        $resultArray = $this->getResultArray();

        $this->resultOutputArray = $separateReport->transformQueryResultsIntoOutputArray(
            $resultArray, $columnLabelResultArray, $this->getReportColumnFields());


    }

    public function getFormattedResultsForOutput() {
	    $this->formattedOutputArray = $this->separateReport->formatOutputArrayForView($this->getResultOutputArray(),
            $this->getColumnLabelResultArray(), $this->getDisplayOptions(), $this->getColumnCountForHeadingCells());
        //$this->debug_library->debugOutput("formattedOutputArray:", $this->formattedOutputArray);
	    return $this->formattedOutputArray;
    }

    public function getRowCount() {
        //$this->debug_library->debugOutput("getRowCount:", $this->formattedOutputArray);
	    return count($this->formattedOutputArray);
    }

	
	public function getColumnCountForHeadingCells() {
	    /* This function finds the number of columns for each column value, so that the report can merge the correct number of cells.
	     * It uses the column labels to show (e.g. BFL, GFL), and loops through the records from the database.
	     * Inside the loop, it looks for records that match each of the column labels, and increments the counter if one is found.
	     * E.g. if a report needs to show columns for BFL, GFL, and GDFL, and the full list of columns includes BFL, BFL, GDFL, GFL, BFL, GFL...
	     * Then the result will be BFL=3, GFL=1, GDFL=1.
	     */
	     
	    $columnLabelResults = $this->columnLabelResultArray;
	    $columnLabels = $this->getDisplayOptions()->getColumnGroup();
	    $columnCountLabels = [];
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
	                if ($this->isFirstColumnLabelInArray($columnLabels, $columnLabelResults, $columnCountLabels, $i, $j)) {
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
                    if ($this->isFirstAndSecondColumnLabelInArray($columnLabels, $columnLabelResults, $columnCountLabels, $i, $j)) {
                        //Value found in array. Increment counter value
                        //Check if the value on the first row matches
                        if ($this->isFirstRowMatching($columnLabels, $columnLabelResults, $i, $j)) {
                            $currentArrayKey = $this->findKeyFromValue($columnCountLabels[$i],
                                $columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()] . "|" .
                                $columnLabelResults[$j][$columnLabels[$i]->getFieldName()], "unique label");
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
	    $this->debug_library->debugOutput("ColumnCountLabels:", $columnCountLabels);
	    return $columnCountLabels;
	     
	}
	
	private function isFirstColumnLabelInArray($pColumnLabels, $pColumnLabelResults, $pColumnCountLabels, $firstLoopCounter, $secondLoopCounter) {
	    return $this->in_array_r(
	        $pColumnLabelResults[$secondLoopCounter][$pColumnLabels[$firstLoopCounter]->getFieldName()], $pColumnCountLabels[$firstLoopCounter]
	    );
	}
	
	private function isFirstAndSecondColumnLabelInArray($pColumnLabels, $pColumnLabelResults, $pColumnCountLabels, $firstLoopCounter, $secondLoopCounter) {
	    return $this->in_array_r(
	        $pColumnLabelResults[$secondLoopCounter][$pColumnLabels[$firstLoopCounter-1]->getFieldName()] . "|" .
            $pColumnLabelResults[$secondLoopCounter][$pColumnLabels[$firstLoopCounter]->getFieldName()], $pColumnCountLabels[$firstLoopCounter]
	    );
	}
	
	private function isFirstRowMatching($pColumnLabels, $pColumnLabelResults, $firstLoopCounter, $secondLoopCounter) {
	    return $pColumnLabelResults[$secondLoopCounter-1][$pColumnLabels[$firstLoopCounter-1]->getFieldName()] == 
	       $pColumnLabelResults[$secondLoopCounter][$pColumnLabels[$firstLoopCounter-1]->getFieldName()];
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
	
}
?>
<?php
class Report_instance extends CI_Model {

	private $resultArray;
	private $reportTitle;
	private $columnLabelResultArray;
	//private $rowLabelResultArray;
    private $reportColumnFields;
	
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

	private $columnCountLabels;

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


	public function setReportType(IData_store_matches $pDataStore, Requested_report_model $pRequestedReport) {
	    //RequestedReport values are set in controllers/report.php->index();
	    //$pDataStore = new Database_store_matches();
	    
	    $this->reportParamLoader->loadAllReportParametersForReport($pRequestedReport, $pDataStore);
	    $this->reportParameter = $this->reportParamLoader->getReportParameter();
	    $this->reportParamLoader->loadAllGroupingStructuresForReport($pRequestedReport, $pDataStore);
	    
	    $reportGroupingStructureArray = $this->reportParamLoader->getReportGroupingStructureArray();

	    $this->reportDisplayOptions = Report_display_options::createReportDisplayOptions($this);
	    
	    //ReportGroupingStructureArray comes from the database tables
	    $this->reportColumnFields = $this->translateRptGrStructureToSimpleArray($reportGroupingStructureArray);
	    $this->reportTitle = $this->setReportTitle($pRequestedReport->getSeason());
	    
	    $this->requestedReport = $pRequestedReport;

	    //Extract the ReportGroupingStructure into separate arrays for columns and rows
	    $this->setRowAndColumnGroups($reportGroupingStructureArray);
	    $this->filterParameters();
		$this->reportDisplayOptions->setLastGameDate($this->findLastGameDateForSelectedSeason($pDataStore));
	}
	
	private function setReportTitle($pSeasonYear) {
	    return str_replace("%seasonYear", $pSeasonYear, $this->reportParameter->getReportTitle());
	}

	private function filterParameters() {
        $this->filterParameterUmpireType->createFilterParameter($this->requestedReport->getUmpireType(), $this->requestedReport->getPDFMode());
        $this->filterParameterAgeGroup->createFilterParameter($this->requestedReport->getAgeGroup(), $this->requestedReport->getPDFMode());
        $this->filterParameterLeague->createFilterParameter($this->requestedReport->getLeague(), $this->requestedReport->getPDFMode());
        $this->filterParameterRegion->createFilterParameter($this->requestedReport->getRegion(), $this->requestedReport->getPDFMode(), true);
    }

    private function setRowAndColumnGroups($pReportGroupingStructureArray) {
        $columnGroupForReport = $this->extractGroupFromGroupingStructure($pReportGroupingStructureArray, 'Column');
        $rowGroupForReport = $this->extractGroupFromGroupingStructure($pReportGroupingStructureArray, 'Row');
        $this->reportDisplayOptions->setColumnGroup($columnGroupForReport);
        $this->reportDisplayOptions->setRowGroup($rowGroupForReport);
    }
	
	
	public function loadReportResults($pDataStore) {
        $separateReport = Report_factory::createReport($this->requestedReport->getReportNumber());
        $queryResultArray = $pDataStore->loadReportData($separateReport, $this);
        /*
        echo "loadReportResults: " . count($queryResultArray);
        echo "<pre>". print_r($queryResultArray) ."</pre>";
        */
        $this->setResultArray($separateReport, $queryResultArray);

        //echo "getResultArray: " . count($this->getResultArray());
        //Pivot the array so it can be displayed
        $this->setColumnLabelResultArray($pDataStore, $separateReport);
        
        //TODO: This function is causing the output values to be misaligned.
        $this->setResultOutputArray($separateReport);

        $this->separateReport = $separateReport;
	}


	private function extractGroupFromGroupingStructure($pReportGroupingStructureArray, $pGroupingType) {
	    //$reportGroupingStructure = new Report_grouping_structure();
            $outputReportGroupingStructure = [];
	    $rowCount = count($pReportGroupingStructureArray);
	    for($i=0; $i<$rowCount; $i++) {
	        if ($pReportGroupingStructureArray[$i]->getGroupingType() == $pGroupingType) {
	           $outputReportGroupingStructure[] = $pReportGroupingStructureArray[$i];
	        }
	    }
	    return $outputReportGroupingStructure;
	}
	
	
	private function findLastGameDateForSelectedSeason($pDataStore) {
	    return $pDataStore->findLastGameDateForSelectedSeason($this->requestedReport);
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
    public function setResultArray(IReport $pSeparateReport, $pResultArray) {
	$columnLabelArray = array();
	$rowLabelField = array();
        foreach ($this->reportDisplayOptions->getColumnGroup() as $columnGroupItem) {
            $columnLabelArray[] = $columnGroupItem->getFieldName();
        }
        foreach ($this->reportDisplayOptions->getRowGroup() as $rowGroupItem) {
            $rowLabelField[] = $rowGroupItem->getFieldName();
        }
        
        $this->resultArray = $pSeparateReport->pivotQueryArray($pResultArray, $rowLabelField, $columnLabelArray);
        //$this->debug_library->debugOutput("pResultArray:", $pResultArray);
	}

    private function setColumnLabelResultArray(IData_store_matches $pDataStore, IReport $separateReport) {
        //Find a distinct list of values to use as column headings
        //$separateReport = Report_factory::createReport($this->requestedReport->getReportNumber());
        $this->columnLabelResultArray = $pDataStore->findDistinctColumnHeadings($separateReport, $this);
        //$this->debug_library->debugOutput("columnLabelResultArray in setColumnLabelResultArray:", $this->getColumnLabelResultArray());
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

	    $arrayLibrary = new Array_library();
	    $columnLabelResults = $this->columnLabelResultArray;
	    $columnLabels = $this->getDisplayOptions()->getColumnGroup();

	    //Loop through the possible labels
	    $columnCount = count($columnLabels);
	    for ($i=0; $i < $columnCount; $i++) {
	        if ($i == 0) {
	            $this->columnCountLabels[0] = [];
	        }
	        if ($i == 1) {
                $this->columnCountLabels[1] = [];
	        }
	        if ($i == 2) {
                $this->columnCountLabels[2] = [];
	        }
	         
	        $arrayKeyNumber = 0;
	         
	        //Loop through columnLabelResults
		$columnLabelResultCount = count($columnLabelResults);
	        for ($j=0; $j < $columnLabelResultCount; $j++) {
                $currentIterationReportGroupFieldName = $columnLabelResults[$j][$columnLabels[$i]->getFieldName()];
	            if ($i == 0) {
	                if ($this->isFirstColumnLabelInArray($columnLabels, $columnLabelResults, $i, $j)) {
	                    //Value found in array. Increment counter value
	                    //Find the array that stores this value
	                    $currentArrayKey = $arrayLibrary->findKeyFromValue(
                            $this->columnCountLabels[$i], $currentIterationReportGroupFieldName, "unique label");
                        $this->incrementArrayColumnCount($i, $currentArrayKey);
	               } else {
	                    //Value not found. Add to array.
                        $this->setArrayColumnLabel($i, $arrayKeyNumber, $currentIterationReportGroupFieldName);
                        $this->setArrayColumnUniqueLabel($i, $arrayKeyNumber, $currentIterationReportGroupFieldName);
                        $this->setArrayColumnCount($i, $arrayKeyNumber, 1);
	                    $arrayKeyNumber++;
	                }
	            }
	            if ($i == 1) {
                    $previousIterationReportGroupFieldName = $columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()];
                    //if ($this->isFirstAndSecondColumnLabelInArray($columnLabels, $this->columnCountLabels, $i, $j)) {
                    //TODO text that this new line is working
                    if ($this->isFirstAndSecondColumnLabelInArray($columnLabels, $columnLabelResults, $i, $j)) {
                        //Value found in array. Increment counter value
                        //Check if the value on the first row matches
                        if ($this->isFirstRowMatching($columnLabels, $columnLabelResults, $i, $j)) {
                            $currentArrayKey = $arrayLibrary->findKeyFromValue($this->columnCountLabels[$i],
                                $previousIterationReportGroupFieldName . "|" .
                                $currentIterationReportGroupFieldName, "unique label");
                            $this->incrementArrayColumnCount($i, $currentArrayKey);
                        } else {
                            $this->setArrayColumnLabel($i, $arrayKeyNumber, $currentIterationReportGroupFieldName);
                            $this->setArrayColumnUniqueLabel($i, $arrayKeyNumber, $previousIterationReportGroupFieldName . "|" .
                                $currentIterationReportGroupFieldName);
                            $this->setArrayColumnCount($i, $arrayKeyNumber, 1);
                            $arrayKeyNumber++;
                        }
                    } else {
                        //Value not found. Add to array.
                        $this->setArrayColumnLabel($i, $arrayKeyNumber, $currentIterationReportGroupFieldName);
                        $this->setArrayColumnUniqueLabel($i, $arrayKeyNumber, $previousIterationReportGroupFieldName . "|" .
                            $currentIterationReportGroupFieldName);
                        $this->setArrayColumnCount($i, $arrayKeyNumber, 1);
                        $arrayKeyNumber++;
                    }
	            }
	            if ($i == 2) {
                    $previousIterationReportGroupFieldName = $columnLabelResults[$j][$columnLabels[$i-1]->getFieldName()];
                    $previousTwoIterationReportGroupFieldName = $columnLabelResults[$j][$columnLabels[$i-2]->getFieldName()];
	                //Set all count values to 1 for this level, as it is not likely that the third row
                    //will need to be merged/have a higher than 1 colspan.

                    $this->setArrayColumnLabel($i, $arrayKeyNumber, $currentIterationReportGroupFieldName);
                    $this->setArrayColumnUniqueLabel($i, $arrayKeyNumber, $previousTwoIterationReportGroupFieldName . "|" .
                        $previousIterationReportGroupFieldName . "|" .
                        $currentIterationReportGroupFieldName);
                    $this->setArrayColumnCount($i, $arrayKeyNumber, 1);
	            }
	        }
	    }
	    //$this->debug_library->debugOutput("ColumnCountLabels:", $columnCountLabels);
	    return $this->columnCountLabels;
	     
	}

    private function setArrayColumnLabel($pIteration, $pArrayKeyNumber, $pValue) {
        $this->columnCountLabels[$pIteration][$pArrayKeyNumber]["label"] = $pValue;
    }

    private function setArrayColumnUniqueLabel($pIteration, $pArrayKeyNumber, $pValue) {
        $this->columnCountLabels[$pIteration][$pArrayKeyNumber]["unique label"] = $pValue;
    }

    private function setArrayColumnCount($pIteration, $pArrayKeyNumber, $pValue) {
        $this->columnCountLabels[$pIteration][$pArrayKeyNumber]["count"] = $pValue;
    }

    private function incrementArrayColumnCount($pIteration, $pArrayKeyNumber) {
        $this->columnCountLabels[$pIteration][$pArrayKeyNumber]["count"]++;
    }
	
	private function isFirstColumnLabelInArray($pColumnLabels, $pColumnLabelResults, $firstLoopCounter, $secondLoopCounter) {
	    $arrayLibrary = new Array_library();
	    return $arrayLibrary->in_array_r(
	        $pColumnLabelResults[$secondLoopCounter][$pColumnLabels[$firstLoopCounter]->getFieldName()], $this->columnCountLabels[$firstLoopCounter]
	    );
	}
	
	private function isFirstAndSecondColumnLabelInArray($pColumnLabels, $pColumnLabelResults, $firstLoopCounter, $secondLoopCounter) {
        $arrayLibrary = new Array_library();
        $firstFieldName = $pColumnLabelResults[$secondLoopCounter][$pColumnLabels[$firstLoopCounter-1]->getFieldName()];
        $secondFieldName = $pColumnLabelResults[$secondLoopCounter][$pColumnLabels[$firstLoopCounter]->getFieldName()];

        return $arrayLibrary->in_array_r(
            $firstFieldName . "|" . $secondFieldName, $this->columnCountLabels[$firstLoopCounter]
	    );
	}
	
	private function isFirstRowMatching($pColumnLabels, $pColumnLabelResults, $firstLoopCounter, $secondLoopCounter) {
	    return $pColumnLabelResults[$secondLoopCounter-1][$pColumnLabels[$firstLoopCounter-1]->getFieldName()] == 
	       $pColumnLabelResults[$secondLoopCounter][$pColumnLabels[$firstLoopCounter-1]->getFieldName()];
	}

}

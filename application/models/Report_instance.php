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
        $this->setResultArray($separateReport, $queryResultArray);

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
        $this->columnLabelResultArray = $pDataStore->findDistinctColumnHeadings($separateReport, $this);
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
        //$this->debug_library->debugOutput("formattedOutputArray: ", $this->formattedOutputArray);
	    return $this->formattedOutputArray;
    }

    public function getRowCount() {
        //$this->debug_library->debugOutput("getRowCount:", $this->formattedOutputArray);
	    return count($this->formattedOutputArray);
    }

    private $columnLabels;

	 /* This function finds the number of columns for each column value, so that the report can merge the correct number of cells.
	     * It uses the column labels to show (e.g. BFL, GFL), and loops through the records from the database.
	     * Inside the loop, it looks for records that match each of the column labels, and increments the counter if one is found.
	     * E.g. if a report needs to show columns for BFL, GFL, and GDFL, and the full list of columns includes BFL, BFL, GDFL, GFL, BFL, GFL...
	     * Then the result will be BFL=3, GFL=1, GDFL=1.
	     */

	//columnLabelResultsArray: an array of all possible values for each of the column headings.
            //these will be the values shown on all column headings. It's a unique list.
            //E.g.: [0]short_league_name=GFL,club_name='Geelong'[1]...
	
	//columnLabels: an array of Report_grouping_structure objects, each of which is a record that has the columns
            //that a report is grouped by. The array could have 1, 2, or 3 objects.
    public function getColumnCountForHeadingCells() {
        //TODO: Remove this variable as it's just a replica of the other object
	$this->columnLabels = $this->getDisplayOptions()->getColumnGroup();

        $this->setValuesForFirstColumnGroup();
	$columnCount = count($this->columnLabels);
        $this->setValuesForFirstColumnGroup();
        if ($columnCount >= 2) {
            $this->setValuesForSecondColumnGroup();
        }

        if ($columnCount >= 3) {
            $this->setValuesForThirdColumnGroup();
        }

        return $this->columnCountLabels;   
    }

    private function setValuesForFirstColumnGroup() {
        $this->columnCountLabels[0] = [];
	$arrayKeyNumber = 0;
        $columnLabelResultCount = count($this->columnLabelResultArray);
        for ($j=0; $j < $columnLabelResultCount; $j++) {
            $currentIterationReportGroupFieldName = $this->columnLabelResultArray[$j][$this->columnLabels[0]->getFieldName()];
            if ($this->isFirstColumnLabelInArray($this->columnLabels, $this->columnLabelResultArray, 0, $j)) {
                //Value found in array. Increment counter value
                //Find the array that stores this value
                $currentArrayKey = $this->findColumnLabelArrayThatHasHeading($currentIterationReportGroupFieldName, 0);
                $this->incrementArrayColumnCount(0, $currentArrayKey);
            } else {
                //Value not found. Add to array.
                $this->setArrayColumnValues(0, $arrayKeyNumber, $currentIterationReportGroupFieldName, $currentIterationReportGroupFieldName);
                $arrayKeyNumber++;
            }
        }
    }

    private function setValuesForSecondColumnGroup() {
        $this->columnCountLabels[1] = [];
	$arrayKeyNumber = 0;
        $columnLabelResultCount = count($this->columnLabelResultArray);
        for ($j=0; $j < $columnLabelResultCount; $j++) {
            $currentIterationReportGroupFieldName = $this->columnLabelResultArray[$j][$this->columnLabels[1]->getFieldName()];
            $previousIterationReportGroupFieldName = $this->columnLabelResultArray[$j][$this->columnLabels[0]->getFieldName()];
            $uniqueLabel = $previousIterationReportGroupFieldName . "|" . $currentIterationReportGroupFieldName;
            if ($this->isFirstAndSecondColumnLabelInArray($this->columnLabels, $this->columnLabelResultArray, 1, $j)) {
                //Value found in array. Increment counter value
                //Check if the value on the first row matches
                if ($this->isFirstRowMatching($this->columnLabels, $this->columnLabelResultArray, 1, $j)) {
                    $currentArrayKey = $this->findColumnLabelArrayThatHasHeading($uniqueLabel, 1);
                    $this->incrementArrayColumnCount(1, $currentArrayKey);
                } else {
                    $this->setArrayColumnValues(1, $arrayKeyNumber, $currentIterationReportGroupFieldName, $uniqueLabel);
                    $arrayKeyNumber++;
                }
            } else {
                //Value not found. Add to array.
                $this->setArrayColumnValues(1, $arrayKeyNumber, $currentIterationReportGroupFieldName, $previousIterationReportGroupFieldName . "|" .
                    $currentIterationReportGroupFieldName);
                $arrayKeyNumber++;
            }
        }
    }

	//This function will set all count values to 1 for this level, as it is not likely that the third row
            //will need to be merged/have a higher than 1 colspan.
    private function setValuesForThirdColumnGroup() {
        $this->columnCountLabels[2] = [];
        $columnLabelResultCount = count($this->columnLabelResultArray);
        for ($j=0; $j < $columnLabelResultCount; $j++) {
            $this->setArrayColumnValues(2, 0, 
		$this->calculateCurrentIterationReportGroupFieldNameForThreeColumns($j), $uniqueLabel);
        }
    }

    private function calculateCurrentIterationReportGroupFieldNameForThreeColumns($pLoopCounter) {
	    $currentIterationReportGroupFieldName = $this->columnLabelResultArray[$pLoopCounter][$this->columnLabels[2]->getFieldName()];
            $previousIterationReportGroupFieldName = $this->columnLabelResultArray[$pLoopCounter][$this->columnLabels[1]->getFieldName()];
            $previousTwoIterationReportGroupFieldName = $this->columnLabelResultArray[$pLoopCounter][$this->columnLabels[0]->getFieldName()];
            return $previousTwoIterationReportGroupFieldName . "|" .
                $previousIterationReportGroupFieldName . "|" .
                $currentIterationReportGroupFieldName;
    }

    private function findColumnLabelArrayThatHasHeading($pHeadingValue, $i) {
        $arrayLibrary = new Array_library();
        return $arrayLibrary->findKeyFromValue(
            $this->columnCountLabels[$i], $pHeadingValue, "unique label");
    }

    private function setArrayColumnValues($i, $arrayKeyNumber, $columnLabel, $uniqueLabel) {
        $this->setArrayColumnLabel($i, $arrayKeyNumber, $columnLabel);
        $this->setArrayColumnUniqueLabel($i, $arrayKeyNumber, $uniqueLabel);
        $this->setArrayColumnCount($i, $arrayKeyNumber, 1);
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

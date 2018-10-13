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
	
	public function setColumnLabelResultArray($pColumnLabelArray) {
        //Find a distinct list of values to use as column headings
        $separateReport = Report_factory::createReport($this->requestedReport->getReportNumber());
        $columnLabelQuery = $separateReport->getReportColumnQuery($this);
        
        $query = $this->db->query($columnLabelQuery);
        $this->columnLabelResultArray = $query->result_array();
        $this->debug_library->debugOutput("columnLabelResultArray in setColumnLabelResultArray:", $this->getColumnLabelResultArray());
	}
	
	public function setResultOutputArray() {
	    $columnLabelResultArray = $this->getColumnLabelResultArray();
	    $resultArray = $this->getResultArray();
	    
	    $resultOutputArray = [];
	    
	    $countItemsInColumnHeadingSet = count($columnLabelResultArray[0]);
        $currentResultArrayRow = 0;
        
        //$this->debug_library->debugOutput("countItemsInColumnHeadingSet:", $countItemsInColumnHeadingSet);
        
	    foreach ($resultArray as $rowKey => $currentRowItem) { //Maps to a single row of output
	        $columnNumber = 0;
	        $totalGeelong = 0;
	        $totalForRow = 0;
	        $twoUmpGamesForRow = 0;
	        
	        if ($this->requestedReport->getReportNumber() == 5) {
	            $resultOutputArray[$currentResultArrayRow][0] = $currentRowItem[0]['umpire_type'];
	        } else {
	            $resultOutputArray[$currentResultArrayRow][0] = $rowKey;
	        }
	        
	        //$this->debug_library->debugOutput("columnLabelResultArray:", $columnLabelResultArray);
	        //$this->debug_library->debugOutput("resultOutputArray key:", $resultOutputArray[$currentResultArrayRow][0]);
	        
	        /*
	         * columnLabelResultArray example:
Array
(
    [0] => Array
        (
            [short_league_name] => GFL
            [umpire_count] => 2 Umpires
        )

    [1] => Array
        (
            [short_league_name] => GFL
            [umpire_count] => 3 Umpires
        )

    [2] => Array
        (
            [short_league_name] => BFL
            [umpire_count] => 2 Umpires
        )
	         * 
	         * 
	         * 
	         */
	        foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
	            $columnNumber++;
	            
	            //Loops through each value of $columnLabelResultArray.
	            //This comes from the results found in the separate_reports.ReportX.getReportColumnQuery() function.
	            //E.g. if Report 8's column query returns 4 rows, then this columnHeadingSet has 4 records in it
	            
	            //$this->debug_library->debugOutput("currentRowItem:", $currentRowItem);
	            
	            
	            foreach ($currentRowItem as $columnKey => $columnItem) { //Maps to a single match_count, not necessarily a column
    	            //Loop through each row and column intersection in the result array
    	        
	                if ($columnNumber == 1 && $this->requestedReport->getReportNumber() == 5) {
	                    //Add extra column for report 5
	                    $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['age_group'];
	                    $columnNumber++;
	                }
	                
	                if ($this->requestedReport->getReportNumber() == 8) {
	                    /*if ($columnNumber == 1) {
	                        //Add extra column for report 8. Column heading is called Other Games, the heading does not come from column data.
	                        //$this->debug_library->debugOutput("COLUMN NUMBER:", $columnNumber);
	                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Other Games';
	                        $columnNumber++;
	                    }
    	                */
    	                
    	                if ($columnNumber == 6) {
    	                    //Add extra column for report 8, after column 5 (array index 5 which is column 6).
    	                    //Column heading is called Total Geelong, the heading does not come from column data.
    	                    //$this->debug_library->debugOutput("COLUMN NUMBER:", $columnNumber);
    	                    $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Total Geelong';
    	                    //$columnNumber++;
    	                }
    	                if ($columnNumber == 8) {
    	                    //$this->debug_library->debugOutput("COLUMN NUMBER:", $columnNumber);
    	                    $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Total Overall';
    	                    //$columnNumber++;
    	                }
	                }
	                
    	            //Match the column headings to the values in the array
	                //$this->debug_library->debugOutput("isFieldMatchingColumnA:", $columnItem);
	                //$this->debug_library->debugOutput("isFieldMatchingColumnB:", $columnHeadingSet);
	                
	               if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet)) {
	                   if($this->requestedReport->getReportNumber() == 2) { 
	                       //Add up total values for report 2, only if the "short_league_name" equivalent value is not "2 Umpires"
	                       if ($columnHeadingSet['short_league_name'] != '2 Umpires') {
	                           $totalForRow = $totalForRow + $columnItem['match_count'];
	                       }
	                       //Set the "2 Umpires" match count to the total so far of rows marked as two_ump_flag=1
	                       if ($columnHeadingSet['short_league_name'] == '2 Umpires') {
	                           $twoUmpGamesForRow = $twoUmpGamesForRow + $columnItem['match_count'];
	                           $this->debug_library->debugOutput("twoUmpGamesForRow:", $twoUmpGamesForRow);
	                           $resultOutputArray[$currentResultArrayRow][$columnNumber] = $twoUmpGamesForRow;
	                       }
	                       
	                       $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
	                   } elseif($this->requestedReport->getReportNumber() == 3) {
	                        if ($columnHeadingSet['short_league_name'] == 'Total') {
	                            //Output the Total column values for report 3
	                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
	                        } else {
	                            //Output the team list value for non-total columns
	                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['team_list'];
	                        }
	                    } elseif ($this->requestedReport->getReportNumber() == 5) {
	                        
	                        //TODO: Clean this code up
	                        if ($columnHeadingSet['subtotal'] == 'Games') {
	                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_no_ump'];
	                            $totalForRow = $totalForRow + $columnItem['match_no_ump'];
	                        } elseif ($columnHeadingSet['subtotal'] == 'Total') {
	                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['total_match_count'];
                            } elseif ($columnHeadingSet['subtotal'] == 'Pct') {
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_pct'];
                            }
	                    } elseif ($this->requestedReport->getReportNumber() == 8) {
	                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
	                        
	                        //TODO: Update this logic to remove the specific year numbers, and the hardcoding of column 6 and 8
	                        if ($columnItem['season_year'] == 'Games Prior' ||
	                            $columnItem['season_year'] == '2015' ||
	                            $columnItem['season_year'] == '2016' ||
	                            $columnItem['season_year'] == '2017' ||
	                            $columnItem['season_year'] == '2018') {
	                                $totalGeelong = $totalGeelong + $columnItem['match_count'];
	                                $totalForRow = $totalForRow+ $columnItem['match_count'];
	                        }
	                        if ($columnItem['season_year'] == 'Games Other Leagues') {
	                            $totalForRow = $totalForRow+ $columnItem['match_count'];
	                        }
	                        //$resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['total_match_count'];
	                        //$totalForRow = $columnItem['total_match_count'];
	                        
	                    } else {
	                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
	                    }
	                        
	                } //end isFieldMatchingColumn
	            }
	        }
	        //Add on final column for report 2 and 5 and 8 for totals for the row
	        if ($this->requestedReport->getReportNumber() == 2 || 
	            $this->requestedReport->getReportNumber() == 5) {
	            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $totalForRow;
	        }
	        
	        if ($this->requestedReport->getReportNumber() == 8) {
	            //$this->debug_library->debugOutput("columnitem array:", $columnItem);
	            $resultOutputArray[$currentResultArrayRow][6] = $totalGeelong;
	            $resultOutputArray[$currentResultArrayRow][8] = $totalForRow;
	        }
	        $currentResultArrayRow++;
	    }
	    $this->resultOutputArray = $resultOutputArray;
	}
	
	private function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet) {
	    //$this->debug_library->debugOutput("getReportColumnFields count:", count($this->getReportColumnFields()));
	    /* Explanation:
	     * - pColumnItem: An array that contains values from the report query that could go into a column.
	     * Array
            (
                [season_year] => 2017
                [match_count] => 25
                [total_match_count] => 174
            )
	     * - $this->getReportColumnFields(): Returns an array that contains the fields from the results to use as columns:
	     * Array
            (
                [0] => season_year
                [1] => total_match_count
            )
	     * - pColumnHeadingSet: Array that contains... the column names and values that apply to this row??
	     * Array
            (
                [season_year] => 2015
            )
	     * 
	     * 
	     */
	    
	    
	    switch (count($this->getReportColumnFields())) {
	        
	        case 1:
	            //$this->debug_library->debugOutput("getReportColumnFields:", $this->getReportColumnFields());
	            //$this->debug_library->debugOutput("pColItem check:", $pColumnItem[$this->getReportColumnFields()[0]]);
	            //$this->debug_library->debugOutput("pColHeadingSet check:", $pColumnHeadingSet);
	            
	            if($this->getReportTitle() == 8) {
	                if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet['season_year']) {
	                    return true;
	                } else {
	                    return false;
	                }
	            } elseif ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]]) {
                    return true;	            
	            } else {
	                return false;
	            }
	            break;
	        case 2:
	            
	            //$this->debug_library->debugOutput("getReportColumnFields:", count($this->getReportColumnFields()));
	            /*
	            $this->debug_library->debugOutput("getReportColumnFields:", $this->getReportColumnFields());
	            $this->debug_library->debugOutput("pColumnItem:", $pColumnItem);
	            $this->debug_library->debugOutput("pColumnHeadingSet:", $pColumnHeadingSet);
	            */
	            if ($this->requestedReport->getReportNumber() == 5) {
	                if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]]) {
	                    return true;
	                } else {
	                    return false;
	                }
	            } elseif ($this->requestedReport->getReportNumber() == 8) {
	                if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]]) {
	                    return true;
	                } else {
	                    return false;
	                }
	                
	            } elseif ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]] && 
	            $pColumnItem[$this->getReportColumnFields()[1]] == $pColumnHeadingSet[$this->getReportColumnFields()[1]]) {
	                return true;
	            } elseif ($this->requestedReport->getReportNumber() == 3 &&
	                $pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]] && 
	                $pColumnHeadingSet[$this->getReportColumnFields()[1]] == 'Total') {
	                return true;
	            } else {
	                return false;
	            }
	            
	            
	            break;
	        case 3:
	            if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]] &&
	                $pColumnItem[$this->getReportColumnFields()[1]] == $pColumnHeadingSet[$this->getReportColumnFields()[1]] &&
	                $pColumnItem[$this->getReportColumnFields()[2]] == $pColumnHeadingSet[$this->getReportColumnFields()[2]]) {
	                    return true;
	                } else {
	                    return false;
	                }
	            break;
	    }
    
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
	    
	    $this->reportParamLoader->loadAllReportParametersForReport($pRequestedReport);
	    $this->reportParameter = $this->reportParamLoader->getReportParameter();
	    $this->reportParamLoader->loadAllGroupingStructuresForReport($pRequestedReport);
	    
	    $reportGroupingStructureArray = $this->reportParamLoader->getReportGroupingStructureArray();
	    
	    //$this->debug_library->debugOutput("reportGroupingStructureArray:", $reportGroupingStructureArray);
	    
	    //Replace this with a single function in reportDisplayOptions to create new RDO object.
	    $this->reportDisplayOptions->createReportDisplayOptions($this);
	    
	    
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
		
		//$this->reportTableName = $this->lookupReportTableName();
	}
	
	private function setReportTitle($pSeasonYear) {
	    return str_replace("%seasonYear", $pSeasonYear, $this->reportParameter->getReportTitle());
	}
	
	
	public function loadReportResults() {
        //$queryForReport = $this->buildSelectQueryForReportUsingDW();

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
        $this->setResultArray($queryResultArray);
        
        //$this->debug_library->debugOutput("loadReportResults.queryResultArray:",  $queryResultArray);
        
        //Pivot the array so it can be displayed
        $this->setColumnLabelResultArray($queryResultArray);
        
        //TODO: This function is causing the output values to be misaligned.
        $this->setResultOutputArray();
        
        //$this->debug_library->debugOutput("loadReportResults.getResultOutputArray:",  $this->getResultOutputArray());

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
	
	public function setResultArray($pResultArray) {
        foreach ($this->reportDisplayOptions->getColumnGroup() as $columnGroupItem) {
            $columnLabelArray[] = $columnGroupItem->getFieldName();
        }
        foreach ($this->reportDisplayOptions->getRowGroup() as $rowGroupItem) {
            $rowLabelField[] = $rowGroupItem->getFieldName();
        }
        
        $this->resultArray = $this->pivotQueryArrayNew($pResultArray, $rowLabelField, $columnLabelArray);
        //$this->debug_library->debugOutput("pResultArray:", $pResultArray);
	}
	
	
	
	private function pivotQueryArrayNew($pResultArray, array $pFieldForRowLabel, array $pFieldsForColumnLabel) {
	    //Create new array to hold values for output
	    /* Expected Output:
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
	    
	    
	    //$this->debug_library->debugOutput("pFieldForRowLabel:", $pFieldForRowLabel);
	    
	    $countRowGroups = count($pFieldForRowLabel);
	    
	    $pivotedArray = array();
	    $counterForRow = 0;
	    $previousRowLabel[0] = "";
	    foreach ($pResultArray as $resultRow) {
	        /*
	         *IMPORTANT: If the SQL query DOES NOT order by the row labels (e.g. the umpire name),
	         *then this loop structure will cause all values to be set to the last column,
	         *and show incorrect data in the report.
	         *If this happens, ensure the SELECT query inside the Report_data_query object for this report (e.g. Report8.php)
	         *orders by the correct column
	         *
	         */
	        if ($resultRow[$pFieldForRowLabel[0]] != $previousRowLabel[0]) {
	            //New row label, so reset counter
	            $counterForRow = 0;
	        } elseif (array_key_exists(1, $pFieldForRowLabel)) {
	            if ($resultRow[$pFieldForRowLabel[1]] != $previousRowLabel[1]) {
	                //New row label, so reset counter
	                $counterForRow = 0;
	            }
	        }
	            
	        
	        $previousRowLabel[0] = $resultRow[$pFieldForRowLabel[0]];
	        if (array_key_exists(1, $pFieldForRowLabel)) {
	            $previousRowLabel[1] = $resultRow[$pFieldForRowLabel[1]];
	        }
	        
	        foreach ($pFieldsForColumnLabel as $columnField) {
	            if ($this->requestedReport->getReportNumber() == 5) {
	                $rowArrayKey = $resultRow[$pFieldForRowLabel[0]] . " " . $resultRow[$pFieldForRowLabel[1]];
	                //$this->debug_library->debugOutput("pFieldForRowLabel:",  $pFieldForRowLabel);
	                $pivotedArray[$rowArrayKey][$counterForRow]['short_league_name'] = $resultRow['short_league_name'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['age_group'] = $resultRow['age_group'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['umpire_type'] = $resultRow['umpire_type'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['match_no_ump'] = $resultRow['match_no_ump'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['total_match_count'] = $resultRow['total_match_count'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['match_pct'] = $resultRow['match_pct'];
	            } elseif ($this->requestedReport->getReportNumber() == 8) {
	                //$this->debug_library->debugOutput("pFieldForRowLabel:",  $pFieldForRowLabel);
	                //$this->debug_library->debugOutput("columnField:",  $columnField);
	                //$this->debug_library->debugOutput("resultRow:",  $resultRow);
	                //$rowArrayKey = $resultRow[$pFieldForRowLabel[0]] . " " . $resultRow[$pFieldForRowLabel[0]];
	                
	                
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField] = $resultRow[$columnField];
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
	                //echo "pivotArray key (". $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField].") set to (". $resultRow[$columnField].")<BR />";
	              
	            } elseif ($this->requestedReport->getReportNumber() == 2) {
	                //$this->debug_library->debugOutput("result row 1:",  $resultRow);
	                
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField] = $resultRow[$columnField];
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
	                
	                if ($resultRow['two_ump_flag'] == 1) {
	                    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['short_league_name'] = '2 Umpires';
	                    
	                    
	                    //$pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
	                //} else {
	                   // $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['2 Umpires'] = $resultRow['match_count'];
	                    
	                    
	                //    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['2 Umpires'] = $resultRow[$columnField];
	                }
	                
	                
	                
	                
	                
	            } else {
	                
	                //$this->debug_library->debugOutput("pivot before:",  $pivotedArray[$resultRow[$pFieldForRowLabel[0]]]);
	                
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField] = $resultRow[$columnField];
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
	                
	                
	                //$this->debug_library->debugOutput("pivot value updated:", $resultRow[$columnField]);
	                //$this->debug_library->debugOutput("pivot after:",  $pivotedArray[$resultRow[$pFieldForRowLabel[0]]]);
	            }
	            
	            if ($this->requestedReport->getReportNumber() == 3) {
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['team_list'] = $resultRow['team_list'];
	            }
	            
	            
	        }
	        
	        
	        
	        $counterForRow++;
	    }
	    $this->debug_library->debugOutput("pivotedArray:", $pivotedArray);
	    return $pivotedArray;
	}
	
	public function getColumnCountForHeadingCells() {
	    /* This function finds the number of columns for each column value, so that the report can merge the correct number of cells.
	     * It uses the column labels to show (e.g. BFL, GFL), and loops through the records from the database.
	     * Inside the loop, it looks for records that match each of the column labels, and increments the counter if one is found.
	     * E.g. if a report needs to show columns for BFL, GFL, and GDFL, and the full list of columns includes BFL, BFL, GDFL, GFL, BFL, GFL...
	     * Then the result will be BFL=3, GFL=1, GDFL=1.
	     */
	     
	    $columnLabelResults = $this->columnLabelResultArray;
	     
	    //$this->debug_library->debugOutput("CLR:", $columnLabelResults);
	     
	    $columnLabels = $this->getDisplayOptions()->getColumnGroup();
	    $columnCountLabels = [];
	     
	    //$this->debug_library->debugOutput("CL:", $columnLabels);
	     
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
	                //$this->debug_library->debugOutput("getColumnCountForHeadingCells: i is ", "0");
	                //$this->debug_library->debugOutput("CL J:", $columnLabels);
	                //$this->debug_library->debugOutput("CL J Results:", $columnLabelResults);
	
	                //if ($this->in_array_r($columnLabelResults[$j][$columnLabels[$i]->getFieldName()], $columnCountLabels[$i]) == TRUE) {
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
                        //$this->debug_library->debugOutput("- Value found:", $columnLabelResults[$j][$columnLabels[$i]]);
                        //Check if the value on the first row matches
                        if ($this->isFirstRowMatching($columnLabels, $columnLabelResults, $i, $j)) {
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
	                //$this->debug_library->debugOutput("getColumnCountForHeadingCells: i is ", "2");
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
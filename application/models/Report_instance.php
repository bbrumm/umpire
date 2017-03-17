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
	
	public function getReportTableName() {
	    return $this->reportTableName;
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

	    $this->requestedReport = $pRequestedReport;
	    
		$this->reportDisplayOptions->setLastGameDate(
		      $this->findLastGameDateForSelectedSeason());
		
		$this->reportTableName = $this->lookupReportTableName();
		
		
		
	}
	
	
	//Find the table to select the data for the report. This tablename is stored in the database
	public function lookupReportTableName() {
	    $tableNameQuery = "SELECT table_name FROM report_table WHERE report_name = ". $this->requestedReport->getReportNumber() .";";
	    $query = $this->db->query($tableNameQuery);
	    $tableNameResultArray = $query->result_array();
	    return $tableNameResultArray[0]['table_name'];
	}
	
	
	
	public function loadReportResults() {
	    if ($this->requestedReport->getReportNumber() == 6) {
	        //Build SELECT query for report data
	        $queryForReport = $this->buildSelectQueryForReport6();
	    
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
	        $this->setResultArray($pivotedResultArray);
	        $this->setColumnLabelResultArray($columnLabelArray);
	    
	    } else {
	        //Build SELECT query for report
	        $queryForReport = $this->buildSelectQueryForReport();
	    
	        $query = $this->db->query($queryForReport);
	    
	        $this->setResultArray($query->result_array());
	        	
	        //Look up the column labels for this report
	        //TODO: Update this function
	        $columnLabelQuery = $this->buildColumnLabelQuery();
	    
	        $query = $this->db->query($columnLabelQuery);
	        	
	        $columnLabelResultArray = $query->result_array();
	        	
	        //Add an extra entry if it is report 2, for the Total column
	        if ($this->requestedReport->getReportNumber() == 2) {
	            $columnLabelResultArray[] = array(
	                'column_name' => 'Total',
	                'report_column_id' => '0',
	                'age_group' => 'Total',
	                'short_league_name' => ''
	            );
	        }
	        $this->setColumnLabelResultArray($columnLabelResultArray);
	    }
	    
	    
	    
	}
	
	
	private function buildSelectQueryForReport() {
	        //Increase maximum length for GROUP_CONCAT value
	        $debugMode = $this->config->item('debug_mode');
	        $query = $this->db->query("SET group_concat_max_len = 8000;");
	         
	        //$rowsToSelect = $reportToDisplay->getDisplayOptions()->getRowGroup();
	        $rowsToSelect = $this->convertReportGroupingStructureArrayToSelectClause(
	            $this->getDisplayOptions()->getRowGroup());
	        if ($debugMode) {
	            echo "Rows to select: " . $rowsToSelect[0] . "<BR/>";
	        }
	        $pAge = $this->getAgeGroupSQLValues();
	        $pUmpireType = $this->getUmpireTypeSQLValues();
	        $pLeague = $this->getLeagueSQLValues();
	        $pRegion = $this->getRegionSQLValues();
	         
	        if ($debugMode == true) {
	            echo "Report to Display:<pre>";
	            print_r($this);
	            echo "</pre>";
	             
	        }
	
	        //TODO: Merge this query with the buildColumnLabels query, as it is quite similar.
	         
	        //Find columns to select from
	        $columnQuery = "SELECT GROUP_CONCAT(gc.column_name SEPARATOR ', ') as COLS ".
	            "FROM (" .
	            "SELECT DISTINCT CASE " .
	            "WHEN rc.column_name = 'Seniors|2 Umpires' THEN CONCAT('mv2u.`', rc.column_name, '` as `', rc.column_name, '`') " .
	            "WHEN rc.column_function IS NULL THEN CONCAT('`', rc.column_name, '` as `', rc.column_name, '`') " .
	            "ELSE CONCAT(rc.column_function, '(`', rc.column_name, '`', ') as `', rc.column_name, '`') " .
	            "END AS column_name " .
	            "FROM report_column rc " .
	            "JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id " .
	            "JOIN report_table rt ON rcl.report_table_id = rt.report_table_id " .
	            "JOIN report_column_lookup_display rcld ON rcld.report_column_id = rc.report_column_id ";
	         
	        //TODO: Remove this hard-coding
	        if ($this->requestedReport->getReportNumber() == 1) {
	            $columnQuery .= "JOIN ". $this->getReportTableName() ." mv ON rcld.column_display_name = mv.short_league_name ";
	        }
	         
	        $columnQuery .= "WHERE rcl.filter_name = 'short_league_name' AND rcl.filter_value IN (". $pLeague .") " .
	            "AND rt.report_name = ". $this->requestedReport->getReportNumber() ." ";
	        if ($this->requestedReport->getReportNumber() == 2) {
	            $columnQuery .= "AND rc.report_column_id IN ( " .
	                "SELECT DISTINCT rcld2.report_column_id " .
	                "FROM report_column_lookup_display rcld2 " .
	                "INNER JOIN report_column rc2 ON rcld2.report_column_id = rc2.report_column_id " .
	                "INNER JOIN report_column_lookup rcl2 ON rc2.report_column_id = rcl2.report_column_id " .
	                "INNER JOIN report_table rt2 ON rcl2.report_table_id = rt2.report_table_id " .
	                "WHERE rcl2.filter_name = 'age_group' AND rcl2.filter_value IN (". $pAge .") " .
	                "AND rt2.report_name = ". $this->requestedReport->getReportNumber() ." ";
	            $columnQuery .= "AND rcld2.report_column_id IN ( " .
	                "SELECT DISTINCT rcld3.report_column_id " .
	                "FROM report_column_lookup_display rcld3 " .
	                "INNER JOIN report_column rc3 ON rcld3.report_column_id = rc3.report_column_id " .
	                "INNER JOIN report_column_lookup rcl3 ON rc3.report_column_id = rcl3.report_column_id " .
	                "INNER JOIN report_table rt3 ON rcl3.report_table_id = rt3.report_table_id " .
	                "WHERE (rcl3.filter_name = 'umpire_type' AND rcl3.filter_value IN (". $pUmpireType .") " .
	                "AND rt3.report_name = ". $this->requestedReport->getReportNumber() ."))) ";
	        }
	         
	        if ($this->requestedReport->getReportNumber() == 1 && $pAge != "All") {
	            $columnQuery .= "AND mv.age_group IN (". $pAge .") ";
	        }
	        $columnQuery .= "AND rcld.column_display_filter_name = 'short_league_name' " .
	            "ORDER BY rc.display_order, rc.column_name ASC" .
	            ") gc;";
	         
	         
	        if ($debugMode) {
	            echo "Column Query: $columnQuery <BR/>";
	        }
	
	        //Run the query to find what columns to select from the report table
	        $query = $this->db->query($columnQuery);
	        $queryResultArray = $query->result_array();
	        $columnsToSelect = $queryResultArray[0]["COLS"];
	         
	        //Add a Totals column for report 2.
	        //This is done as a separate query, then concatenated, to make it easier
	         
	        if ($this->requestedReport->getReportNumber() == 2) {
	            //TODO: Convert this to a stored proc or a better way of running SQL
	            $columnQuery = "SELECT GROUP_CONCAT(gc.column_name SEPARATOR ' + ') as COLS ".
	                "FROM (" .
	                "SELECT DISTINCT CASE " .
	                "WHEN rc.column_function IS NULL THEN CONCAT('`', rc.column_name, '`') " .
	                "ELSE CONCAT(rc.column_function, '(`', rc.column_name, '`', ')') " .
	                "END AS column_name " .
	                "FROM report_column rc " .
	                "JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id " .
	                "JOIN report_table rt ON rcl.report_table_id = rt.report_table_id " .
	                "JOIN report_column_lookup_display rcld ON rcld.report_column_id = rc.report_column_id ";
	
	            $columnQuery .= "WHERE rcl.filter_name = 'short_league_name' AND rcl.filter_value IN (". $pLeague .") " .
	                "AND rt.report_name = ". $this->requestedReport->getReportNumber() ." " .
	                "AND rc.report_column_id IN ( " .
	                "SELECT DISTINCT rcld2.report_column_id " .
	                "FROM report_column_lookup_display rcld2 " .
	                "INNER JOIN report_column rc2 ON rcld2.report_column_id = rc2.report_column_id " .
	                "INNER JOIN report_column_lookup rcl2 ON rc2.report_column_id = rcl2.report_column_id " .
	                "INNER JOIN report_table rt2 ON rcl2.report_table_id = rt2.report_table_id " .
	                "WHERE rcl2.filter_name = 'age_group' AND rcl2.filter_value IN (". $pAge .") " .
	                "AND rt2.report_name = ". $this->requestedReport->getReportNumber() .")";
	
	            $columnQuery .= "AND rcld.column_display_filter_name = 'short_league_name' " .
	                "AND rc.overall_total = 1 " .
	                "ORDER BY rc.display_order, rc.column_name ASC" .
	                ") gc;";
	             
	            $query = $this->db->query($columnQuery);
	            $queryResultArray = $query->result_array();
	             
	            //Concatenate the results of this query to the original query
	            $columnsToSelect .= ", " . $queryResultArray[0]["COLS"] . " as Total";
	             
	        }
	         
	         
	        if ($debugMode) {
	            echo "<BR />columnsToSelect (". $columnsToSelect .") <BR />";
	        }
	         
	        if ($columnsToSelect == '') {
	            throw new Exception("No columns found to select from. Check the Report_populator_model.buildSelectQueryForReport function (Query: ". $columnQuery .") <BR />");
	             
	        }
	         
	        //Build WHERE clause
	        $whereClause = $this->buildWhereClause();
	        //echo "whereClause(". $whereClause .")<BR/>";
	
	         
	        //Determine fields to select
	
	        //TODO: Replace the [0] with a string that concatenates all values in the array with a comma,
	        //to handle cases where more than one field is shown in the row
	        //Construct SQL query
	        if ($this->requestedReport->getReportNumber() == 5) {
	            $queryForReport = "SELECT ". $rowsToSelect[0] .", " . $rowsToSelect[1] .", " .
	                $columnsToSelect . " " .
	                "FROM " . $this->getReportTableName() . " " . $whereClause . " " .
	                "GROUP BY ". $rowsToSelect[0] . ", " . $rowsToSelect[1];
	        } elseif ($this->requestedReport->getReportNumber() == 2) {
	            $queryForReport = "SELECT ". $rowsToSelect[0] .", " .
	                $columnsToSelect . "  " .
	                "FROM " . $this->getReportTableName() . " " . $whereClause . " " .
	                "GROUP BY ". $rowsToSelect[0];
	                 
	        } else {
	            if ($debugMode) {
	                echo "ReportModel rows to select<pre>";
	                print_r($rowsToSelect[0]);
	                echo "</pre>";
	            }
	             
	            $queryForReport = "SELECT ". $rowsToSelect[0] .", " .
	                $columnsToSelect . " " .
	                "FROM " . $this->getReportTableName() . " " . $whereClause . " " .
	                "GROUP BY ". $rowsToSelect[0];
	        }
	
	        if ($debugMode) {
	            echo "<BR/>Query For Report ($queryForReport)<BR/>";
	        }
	        /*
	         echo "<BR/>Query For Report Length (" .strlen($queryForReport) .")<BR/>";
	         */
	        return $queryForReport;
	
	}
	
	private function convertReportGroupingStructureArrayToSelectClause($pReportGroupingStructureArray) {
	     
	    for ($i=0; $i < count($pReportGroupingStructureArray); $i++) {
	        $selectClause[] = $pReportGroupingStructureArray[$i]->getFieldName();
	        /*if ($i != count($pReportGroupingStructureArray)) {
	         $selectClause . ", ";
	         }*/
	    }
	     
	    return $selectClause;
	     
	}
	
	private function buildSelectQueryForReport6() {
	        //Increase maximum length for GROUP_CONCAT value
	        $debugMode = $this->config->item('debug_mode');
	        //$query = $this->db->query("SET group_concat_max_len = 8000;");
	
	        $rowsToSelect = $this->getDisplayOptions()->getRowGroup();
	         
	        //Build WHERE clause
	        $whereClause = $this->buildWhereClause();
	
	        //Determine fields to select
	
	        //TODO: Replace the [0] with a string that concatenates all values in the array with a comma,
	        //to handle cases where more than one field is shown in the row
	        //Construct SQL query
	         
	        $queryForReport = "SELECT first_umpire, second_umpire, umpire_type_name, SUM(match_count) AS match_count " .
	            "FROM " . $this->getReportTableName() . " " . $whereClause . " " .
	            "GROUP BY first_umpire, second_umpire, umpire_type_name;";
	
	        if ($debugMode) {
	            echo "<BR/>Query For Report ($queryForReport)<BR/>";
	        }
	        return $queryForReport;
	
	}
	
	
	private function buildWhereClause() {
	        $whereClause = NULL;
	         
	        //if ($pAgeGroup != 'All' || $pUmpireType != 'All' || $pLeague != 'All') {
	        $whereClause = "WHERE season_year = '". $this->requestedReport->getSeason() ."' ";
	        //}
	
	        $addAndKeyword = TRUE;
	        //if ($pAgeGroup != 'All') {
	        if ($this->requestedReport->getReportNumber() != 3 &&
	            $this->requestedReport->getReportNumber() != 4 &&
	            $this->requestedReport->getReportNumber() != 5) {
	                if ($addAndKeyword) {
	                    $whereClause .= "AND ";
	                    $addAndKeyword = FALSE;
	                }
	                $whereClause .= "age_group IN (".$this->getAgeGroupSQLValues().") ";
	                $addAndKeyword = TRUE;
	            }
	
	            //if ($pUmpireType != 'All') {
	            if ($this->requestedReport->getReportNumber() != 3 &&
	                $this->requestedReport->getReportNumber() != 4 &&
	                $this->requestedReport->getReportNumber() != 5) {
	                    if ($addAndKeyword) {
	                        $whereClause .= "AND ";
	                        $addAndKeyword = FALSE;
	                    }
	                    $whereClause .= "umpire_type_name IN (".$this->getUmpireTypeSQLValues().") ";
	                    $addAndKeyword = TRUE;
	                }
	                 
	                //if ($pLeague != 'All') {
	                if ($this->requestedReport->getReportNumber() != 3 &&
	                    $this->requestedReport->getReportNumber() != 4 &&
	                    $this->requestedReport->getReportNumber() != 5 &&
	                    $this->requestedReport->getReportNumber() != 6) {
	                        if ($addAndKeyword) {
	                            $whereClause .= "AND ";
	                            $addAndKeyword = FALSE;
	                        }
	
	                        $whereClause .= "short_league_name IN (".$this->getLeagueSQLValues().") ";
	                        $addAndKeyword = TRUE;
	                    }
	
	                    if ($this->requestedReport->getReportNumber() == 3 ||
	                        $this->requestedReport->getReportNumber() == 4 ||
	                        $this->requestedReport->getReportNumber() == 5 ||
	                        $this->requestedReport->getReportNumber() == 6) {
	                            if ($addAndKeyword) {
	                                $whereClause .= "AND ";
	                                $addAndKeyword = FALSE;
	                            }
	                            $whereClause .= "region IN (".$this->getRegionSQLValues().") ";
	                            $addAndKeyword = TRUE;
	                        }
	                         
	                        return $whereClause;
	}
	
	
	private function buildColumnLabelQuery() {
	        /*
	         * This query finds the column labels for the report
	         * It joins to the MV table for the report, as some of the criteria that has been selected is not available in the report_column tables.
	         * For example, the report 01 has report_column values for leagues, which it can filter on.
	         * It joins to the MV table so it can filter on age_group.
	         * It doesn't need to filter on the final criteria, umpire_type, as it is the same set of columns for all umpire type.
	         * The umpire_type filter is done in the data query, not the column query.
	         * For report 02, age group and league are in the report_column config. Umpire type is not, but it should be in the data query.
	         *
	         */
	         
	        $pAge = $this->getAgeGroupSQLValues();
	        $pUmpireType = $this->getUmpireTypeSQLValues();
	        $pLeague = $this->getLeagueSQLValues();
	        //echo "bioldColumnLabelQuery League: " . $pLeague . "<BR />";
	         
	         
	        //Find the columns to show from the UserReport
	         
	        $columnsToDisplay = $this->convertReportGroupingStructureArrayToSelectClause(
	            $this->reportDisplayOptions->getColumnGroup());
	         
	         
	        /*
	         echo "<pre>Columns To Display";
	         print_r($columnsToDisplay);
	         echo "</pre>";
	         */
	        $columnLabelQuery = "SELECT DISTINCT rc.column_name, rcld.report_column_id, ";
	        	
	        for ($i=0; $i < count($columnsToDisplay); $i++) {
	            $columnLabelQuery .= "(SELECT rcld". $i .".column_display_name " .
	                "FROM report_column_lookup_display rcld". $i ." " .
	                "WHERE rcld". $i .".report_column_id = rcld.report_column_id " .
	                "AND rcld". $i .".column_display_filter_name = '". $columnsToDisplay[$i] ."') AS ". $columnsToDisplay[$i] ." ";
	            if ($i <> count($columnsToDisplay)-1) {
	                //Add comma if it is not the last column to show
	                $columnLabelQuery .= ", ";
	            }
	             
	        }
	         
	        $columnLabelQuery .= "FROM report_column_lookup_display rcld " .
	            "JOIN report_column rc ON rcld.report_column_id = rc.report_column_id " .
	            "JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id " .
	            "JOIN report_table rt ON rcl.report_table_id = rt.report_table_id ";
	         
	        //TODO: Remove this hard-coding
	        if ($this->requestedReport->getReportNumber() == 1) {
	            $columnLabelQuery .= "JOIN ". $this->getReportTableName() ." mv ON rcld.column_display_name = mv.short_league_name ";
	        }
	         
	        /*$columnLabelQuery .= "WHERE ((rcl.filter_name = 'short_league_name' AND rcl.filter_value = '". $pLeague ."') " .
	    	    "OR (rcl.filter_name = 'age_group' AND rcl.filter_value = '". $pAge ."')) " .
	    	    "AND rt.report_name = ". $pReportName ." ";*/
	        	
	        $columnLabelQuery .= "WHERE rcl.filter_name = 'short_league_name' AND rcl.filter_value IN (". $pLeague .") " .
	            "AND rt.report_name = ". $this->requestedReport->getReportNumber() ." ";
	         
	        if ($this->requestedReport->getReportNumber() == 2) {
	            $columnLabelQuery .= "AND rc.report_column_id IN ( " .
	                "SELECT DISTINCT rcld2.report_column_id " .
	                "FROM report_column_lookup_display rcld2 " .
	                "INNER JOIN report_column rc2 ON rcld2.report_column_id = rc2.report_column_id " .
	                "INNER JOIN report_column_lookup rcl2 ON rc2.report_column_id = rcl2.report_column_id " .
	                "INNER JOIN report_table rt2 ON rcl2.report_table_id = rt2.report_table_id " .
	                "WHERE rcl2.filter_name = 'age_group' AND rcl2.filter_value IN (". $pAge .") " .
	                "AND rt2.report_name = ". $this->requestedReport->getReportNumber() ." ";
	            $columnLabelQuery .= "AND rcld2.report_column_id IN ( " .
	                "SELECT DISTINCT rcld3.report_column_id " .
	                "FROM report_column_lookup_display rcld3 " .
	                "INNER JOIN report_column rc3 ON rcld3.report_column_id = rc3.report_column_id " .
	                "INNER JOIN report_column_lookup rcl3 ON rc3.report_column_id = rcl3.report_column_id " .
	                "INNER JOIN report_table rt3 ON rcl3.report_table_id = rt3.report_table_id " .
	                "WHERE (rcl3.filter_name = 'umpire_type' AND rcl3.filter_value IN (". $pUmpireType .") " .
	                "AND rt3.report_name = ". $this->requestedReport->getReportNumber() ."))) ";
	        }
	         
	
	        if ($this->requestedReport->getReportNumber() == 1 && $pAge != "All") {
	            $columnLabelQuery .= "AND mv.age_group IN (". $pAge .") ";
	        }
	         
	        //$columnLabelQuery .= "ORDER BY rc.display_order, rcld.report_column_id, rcld.column_display_filter_name;";
	        $columnLabelQuery .= "ORDER BY rc.display_order, rc.column_name, rcld.column_display_filter_name;";
	
	        $debugMode = $this->config->item('debug_mode');
	        if ($debugMode) {
	            echo "<BR />columnLabelQuery " . $columnLabelQuery . "<BR />";
	        }
	        return $columnLabelQuery;
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
	
	
	private function findLastGameDateForSelectedSeason() {
	    $queryString = "SELECT DATE_FORMAT(MAX(match_time), '%a %d %b %Y, %h:%i %p') AS last_date " .
            "FROM match_played " .
            "INNER JOIN round ON round.id = match_played.round_id " .
            "INNER JOIN season ON season.id = round.season_id " .
            "WHERE season.season_year = ". $this->requestedReport->getSeason() .";";
	     
	    $query = $this->db->query($queryString);
	    $queryResultArray = $query->result_array();
	    return $queryResultArray[0]['last_date'];
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
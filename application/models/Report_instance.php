<?php
class Report_instance extends CI_Model {
	
	private $reportQuery;
	private $resultArray;
	private $reportTitle;
	private $columnLabelResultArray;
	private $rowLabelResultArray;
	
	private $resultOutputArray; //New variable to store the array so it can be directly output to the screen
	
	private $umpireTypeSQLValues;
	private $leagueSQLValues;
	private $ageGroupSQLValues;
	private $regionSQLValues;
	
	private $umpireTypeDisplayValues;
	private $leagueDisplayValues;
	private $ageGroupDisplayValues;
	private $regionDisplayValues;
	
	public $requestedReport;
	public $reportDisplayOptions;

	public function __construct() {
	    $this->load->model('Report_display_options');
	    $this->reportDisplayOptions = new Report_display_options();
	    $this->load->database();
	    $this->load->library('Debug_library');
	    $this->load->library('Array_library');
	    $this->load->model('report_param/Report_param_loader');
	    $this->load->model('report_param/Report_parameter');
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
	
	public function setColumnLabelResultArray($pColumnLabelArray) {
        //Find a distinct list of values to use as column headings
        $columnLabelQuery = $this->buildColumnLabelQuery();
        $query = $this->db->query($columnLabelQuery);
        $this->columnLabelResultArray = $query->result_array();
        $this->debug_library->debugOutput("columnLabelResultArray in setColumnLabelResultArray:", $this->getColumnLabelResultArray());
	}
	
	public function setResultOutputArray() {
	    $columnLabelResultArray = $this->getColumnLabelResultArray();
	    $resultArray = $this->getResultArray();
	    
	    $resultOutputArray = "";
	    
	    $countItemsInColumnHeadingSet = count($columnLabelResultArray[0]);
        $currentResultArrayRow = 0;
        
	    foreach ($resultArray as $rowKey => $currentRowItem) { //Maps to a single row of output
	        $columnNumber = 0;
	        $totalForRow = 0;
	        
	        if ($this->requestedReport->getReportNumber() == 5) {
	            $resultOutputArray[$currentResultArrayRow][0] = $currentRowItem[0]['umpire_type'];
	        } else {
	            $resultOutputArray[$currentResultArrayRow][0] = $rowKey;
	        }
	        
	        foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
	            $columnNumber++;
	            
	            foreach ($currentRowItem as $columnKey => $columnItem) { //Maps to a single match_count, not necessarily a column
    	            //Loop through each row and column intersection in the result array
    	        
	                if ($columnNumber == 1 && $this->requestedReport->getReportNumber() == 5) {
	                    //Add extra column for report 5
	                    $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['age_group'];
	                    $columnNumber++;
	                }
	                
    	            //Match the column headings to the values in the array
	               if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet)) {
	                   if($this->requestedReport->getReportNumber() == 2) { 
	                       //Add up total values for report 2, only if the "short_league_name" equivalent value is not "2 Umpires"
	                       if ($columnHeadingSet['short_league_name'] != '2 Umpires') {
	                           $totalForRow = $totalForRow + $columnItem['match_count'];
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
	                    } else {
	                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
	                    }
	                        
	                }
	            }
	        }
	        //Add on final column for report 2 and 5 for totals for the row
	        if ($this->requestedReport->getReportNumber() == 5 || 
	            $this->requestedReport->getReportNumber() == 2) {
	            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $totalForRow;
	        }
	        $currentResultArrayRow++;
	    }
	    $this->resultOutputArray = $resultOutputArray;
	}
	
	private function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet) {
	    switch (count($this->getReportColumnFields())) {
	        case 1:
	            if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]]) {
                    return true;	            
	            } else {
	                return false;
	            }
	            break;
	        case 2:
	            if ($this->requestedReport->getReportNumber() == 5) {
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
	    $simpleColumnFieldArray = "";
	    for ($i=0; $i < count($pReportGroupingStructureArray); $i++) {
	        if ($pReportGroupingStructureArray[$i]->getGroupingType() == 'Column') {
	            $simpleColumnFieldArray[] = $pReportGroupingStructureArray[$i]->getFieldName();
	        }
	    }
	    return $simpleColumnFieldArray;
	}
	
	public function getResultOutputArray() {
	    return $this->resultOutputArray;
	}
	
	public function getColumnLabelResultArray() {
	    return $this->columnLabelResultArray;
	}

	public function setReportType(Requested_report_model $pRequestedReport) {
	    $this->debug_library->debugOutput("reportParameters in setReportType", $pRequestedReport);
	    $this->debug_library->debugOutput("POST in setReportType", $_POST);
	    
	    $useNewDWTables = $this->config->item('use_new_dw_tables');
	    
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
	    
	    $reportParamLoader = new Report_param_loader();
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
	    $this->reportColumnFields = $this->translateRptGrStructureToSimpleArray($reportGroupingStructureArray);
	    $this->reportTitle = str_replace("%seasonYear", $pRequestedReport->getSeason(), $this->lookupParameterValue($reportParameterArray, 'Display Title'));
	    
	    $this->requestedReport = $pRequestedReport;

	    //Extract the ReportGroupingStructure into separate arrays for columns and rows
	    $columnGroupForReport = $this->extractGroupFromGroupingStructure($reportGroupingStructureArray, 'Column');
	    $rowGroupForReport = $this->extractGroupFromGroupingStructure($reportGroupingStructureArray, 'Row');	    
	    $this->reportDisplayOptions->setColumnGroup($columnGroupForReport);
	    $this->reportDisplayOptions->setRowGroup($rowGroupForReport);
	    
	    $this->convertParametersToSQLReadyValues();
	    $this->convertParametersToDisplayValues();
	    
		$this->reportDisplayOptions->setLastGameDate($this->findLastGameDateForSelectedSeason());
		
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
        $queryForReport = $this->buildSelectQueryForReportUsingDW();
        
        $query = $this->db->query($queryForReport);
        
        //Run query and store result in array
        $query = $this->db->query($queryForReport);
        
        //Transform array to pivot
        $queryResultArray = $query->result_array();
        	
        //Set result array (function includes logic for different reports
        $this->setResultArray($queryResultArray);
        
        //Pivot the array so it can be displayed
        $this->setColumnLabelResultArray($queryResultArray);
        
        $this->setResultOutputArray();

	}
	
	
	private function buildSelectQueryForReportUsingDW() {
	    switch ($this->requestedReport->getReportNumber()) {
	        case 1:
	            $queryString = "SELECT
	                last_first_name,
	                short_league_name,
	                club_name,
	                age_group,
	                SUM(match_count) AS match_count
	                FROM dw_mv_report_01
	                WHERE age_group IN (". $this->getAgeGroupSQLValues() .")
                    AND short_league_name IN (". $this->getLeagueSQLValues() .")
                    AND region_name IN (". $this->getRegionSQLValues() .")
                    AND umpire_type IN (". $this->getUmpireTypeSQLValues() .")
                    AND season_year = ". $this->requestedReport->getSeason() ."
                    GROUP BY last_first_name, short_league_name, club_name
                    ORDER BY last_first_name, short_league_name, club_name";

	            break;
	            
	        case 2:
	            $queryString = "SELECT
    	            last_first_name,
    	            age_group,
    	            age_sort_order,
    	            short_league_name,
    	            two_ump_flag,
    	            SUM(match_count) AS match_count
	                FROM dw_mv_report_02
	                WHERE age_group IN (". $this->getAgeGroupSQLValues() .")
    	            AND short_league_name IN ('2 Umpires', ". $this->getLeagueSQLValues() .")
    	            AND region_name IN (". $this->getRegionSQLValues() .")
    	            AND umpire_type IN (". $this->getUmpireTypeSQLValues() .")
    	            AND season_year = ". $this->requestedReport->getSeason() ."
    	            GROUP BY last_first_name, age_group, age_sort_order, short_league_name, two_ump_flag
    	            ORDER BY last_first_name, age_sort_order, short_league_name;";
	            
	            break;
	        case 3:
	            //This has remained as a query on staging tables instead of moving to a MV table, because of the subquery using parameters from the UI selection.
	            //Creating a MV would look similar to this and probably wouldn't improve performance.
	            $queryString = "SELECT 
                    weekend_date,
                    CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group,
                    short_league_name,
                    GROUP_CONCAT(team_names) AS team_list,
                    (
                    	SELECT
                    	COUNT(DISTINCT match_id)
                    	FROM staging_no_umpires s2
                    	WHERE s2.age_group = s.age_group
                    	AND s2.umpire_type = s.umpire_type
                        AND s2.weekend_date = s.weekend_date
                        AND short_league_name IN (". $this->getLeagueSQLValues() .")
                    ) AS match_count
                    FROM staging_no_umpires s
                    WHERE short_league_name IN (". $this->getLeagueSQLValues() .")
                    AND season_year = ". $this->requestedReport->getSeason() ."
                    AND CONCAT(age_group, ' ', umpire_type) IN (
                    	'Seniors Boundary',
                    	'Seniors Goal',
                    	'Reserve Goal',
                    	'Colts Field',
                    	'Under 16 Field',
                    	'Under 14 Field',
                    	'Under 12 Field'
                    )
                    GROUP BY weekend_date, age_group, umpire_type, short_league_name
                    ORDER BY weekend_date, age_group, umpire_type, short_league_name;";
	            
	            break;
	        case 4:
	            $queryString = "SELECT 
                    club_name,
                    age_group,
                    short_league_name,
                    umpire_type,
                    match_count
                    FROM dw_mv_report_04
	                WHERE region_name IN (". $this->getRegionSQLValues() .")
	                AND season_year = ". $this->requestedReport->getSeason() ."
                    ORDER BY club_name, age_sort_order, league_sort_order;";
	            
	            break;
	        
	        case 5:
	           $queryString = "SELECT umpire_type,
                    age_group,
                    short_league_name,
                    match_no_ump,
                    total_match_count,
                    match_pct
                    FROM dw_mv_report_05
	                WHERE short_league_name IN (". $this->getLeagueSQLValues() .")
	                AND region_name IN (". $this->getRegionSQLValues() .")
	                AND season_year = ". $this->requestedReport->getSeason() ."
                    ORDER BY umpire_type, age_sort_order, league_sort_order;";
	            break;
	        case 6:
	            $queryString = "SELECT
                    umpire_type,
	                age_group,
	                region_name,
	                first_umpire,
	                second_umpire,
	                match_count
                    FROM dw_mv_report_06
	                WHERE season_year IN (". $this->requestedReport->getSeason() .")
	                AND age_group IN (". $this->getAgeGroupSQLValues() .")
	                AND region_name IN (". $this->getRegionSQLValues() .")
	                AND umpire_type IN (". $this->getUmpireTypeSQLValues() .")
                    ORDER BY first_umpire, second_umpire;";
	            
	            break;
	            
	        case 7:
	            $queryString = "SELECT
                    umpire_type,
                    age_group,
                    short_league_name,
                    umpire_count,
                    match_count
                    FROM dw_mv_report_07
	                WHERE season_year IN (". $this->requestedReport->getSeason() .")
	                AND age_group IN (". $this->getAgeGroupSQLValues() .")
	                AND region_name IN (". $this->getRegionSQLValues() .")
	                AND umpire_type IN ('Field')
                    ORDER BY age_sort_order, league_sort_order, umpire_type, umpire_count;";
	            
	            break;
	    }
	    
	    $this->debug_library->debugOutput("SQL queryString:", $queryString);
	    return $queryString;
	}
	
	private function buildColumnLabelQuery() {
     /*
      * 
      *@TODO: Move this into the buildQueryForReportDW function and 
      *change the return to be an object with two queries:
      *one with the data query and one with the column query
      */
        switch ($this->requestedReport->getReportNumber()) {
            case 1:
    	       $columnLabelQuery = "SELECT DISTINCT short_league_name, club_name
    	           FROM dw_mv_report_01 
    	           WHERE age_group IN (". $this->getAgeGroupSQLValues() .")
                   AND short_league_name IN (". $this->getLeagueSQLValues() .")
                   AND region_name IN (". $this->getRegionSQLValues() .")
                   AND umpire_type IN (". $this->getUmpireTypeSQLValues() .")
                   ORDER BY short_league_name, club_name";
        	       
    	       break;
            case 2:
                $columnLabelQuery = "SELECT DISTINCT age_group, short_league_name
    	            FROM (
	                    SELECT
        	            age_group,
        	            age_sort_order,
                        league_sort_order,
        	            short_league_name
    	                FROM dw_mv_report_02
    	                WHERE age_group IN (". $this->getAgeGroupSQLValues() .")
        	            AND short_league_name IN ('2 Umpires', ". $this->getLeagueSQLValues() .")
        	            AND region_name IN (". $this->getRegionSQLValues() .")
        	            AND umpire_type IN (". $this->getUmpireTypeSQLValues() .")
        	            AND season_year = ". $this->requestedReport->getSeason() ."
        	            UNION ALL
        	            SELECT
    	                'Total',
    	                50,
    	                50,
    	                ''
    	            ) AS sub
    	            ORDER BY age_sort_order, league_sort_order;";
                
                break;
            case 3:
                $columnLabelQuery = "SELECT DISTINCT
                	CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group,
                	short_league_name
                	FROM (
                    	SELECT
                    	s.age_group,
                    	s.umpire_type,
                    	s.short_league_name,
	                    s.region_name,
                    	s.age_sort_order
                    	FROM staging_all_ump_age_league s
                    	UNION ALL
                    	SELECT
                    	s.age_group,
                    	s.umpire_type,
                    	'Total',
	                    'Total',
                    	s.age_sort_order
                    	FROM staging_all_ump_age_league s
                    ) sub
                    WHERE CONCAT(age_group, ' ', umpire_type) IN
                    	('Seniors Boundary' , 'Seniors Goal', 'Reserves Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field')
                    AND age_group IN (". $this->getAgeGroupSQLValues() .")
                    AND region_name IN ('Total', ". $this->getRegionSQLValues() .")
                    ORDER BY age_sort_order, umpire_type, short_league_name;";
                
                break;
            
            case 4:
                $columnLabelQuery = "SELECT DISTINCT
                    s.umpire_type,
                    s.age_group,
                    s.short_league_name
                    FROM staging_all_ump_age_league s
                    WHERE s.short_league_name IN (". $this->getLeagueSQLValues() .")
                    ORDER BY s.umpire_type, s.age_sort_order, s.league_sort_order;";
                
                break;
            case 5:
                $columnLabelQuery = "SELECT DISTINCT
                    l.short_league_name,
                    sub.subtotal
                    FROM dw_dim_league l
                    CROSS JOIN (
                        SELECT 'Games' AS subtotal
                        UNION
                        SELECT 'Total'
                        UNION
                        SELECT 'Pct'
                    ) AS sub
                    WHERE l.short_league_name IN (". $this->getLeagueSQLValues() .")
                    UNION ALL
                    SELECT 'All', 'Total';";
                
                break;
            case 6:
                $columnLabelQuery = "SELECT DISTINCT second_umpire
                FROM dw_mv_report_06
                WHERE season_year IN (". $this->requestedReport->getSeason() .")
                AND age_group IN (". $this->getAgeGroupSQLValues() .")
                AND region_name IN (". $this->getRegionSQLValues() .")
                AND umpire_type IN (". $this->getUmpireTypeSQLValues() .")
                ORDER BY second_umpire;";
                
                break;
            
            case 7:
                $columnLabelQuery = "SELECT DISTINCT
                    short_league_name,
                    umpire_count
                    FROM (
                    SELECT DISTINCT 
                    short_league_name,
                    league_sort_order,
                    '2 Umpires' AS umpire_count
                    FROM dw_mv_report_07
	                WHERE season_year IN (". $this->requestedReport->getSeason() .")
	                AND age_group IN (". $this->getAgeGroupSQLValues() .")
	                AND region_name IN (". $this->getRegionSQLValues() .")
	                AND umpire_type IN ('Field')
                    UNION ALL
                    SELECT DISTINCT 
                    short_league_name,
	                league_sort_order,
                    '3 Umpires'
                    FROM dw_mv_report_07
	                WHERE season_year IN (". $this->requestedReport->getSeason() .")
	                AND age_group IN (". $this->getAgeGroupSQLValues() .")
	                AND region_name IN (". $this->getRegionSQLValues() .")
	                AND umpire_type IN ('Field')
	                ) AS sub
                    ORDER BY league_sort_order, umpire_count;";
                
                break;
                    
        }
       $this->debug_library->debugOutput("columnLabelQuery:", $columnLabelQuery);
       return $columnLabelQuery;
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
	
	
	private function lookupParameterValue($reportParameterArray, $parameterName) {
	    $parameterValue = "";
	    
	    for($i=0; $i<count($reportParameterArray); $i++) {
	        $reportParameter = new Report_parameter();
	        $reportParameter = $reportParameterArray[$i];
	        
	        if($reportParameter->getParameterName() == $parameterName) {
	            $parameterValue = $reportParameter->getParameterValue();
	            break;
	        }
	    }
	    return $parameterValue;
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
        //TODO: Get these fields from the database
        switch ($this->requestedReport->getReportNumber()) {
            case 1:
               $columnLabelArray = array('short_league_name', 'club_name');
               $rowLabelField =  array('last_first_name');
               
               break;
            case 2:
                $columnLabelArray = array('age_group', 'short_league_name');
                $rowLabelField =  array('last_first_name');
                break;
            case 3:
                $columnLabelArray = array('umpire_type_age_group', 'short_league_name');
                $rowLabelField =  array('weekend_date');
                break;
            case 4:
                $columnLabelArray = array('umpire_type', 'age_group', 'short_league_name');
                $rowLabelField =  array('club_name');
                break;
            case 5:
                $columnLabelArray = array('short_league_name', 'subtotal');
                $rowLabelField =  array('umpire_type', 'age_group');
                break;
            case 6:
                $columnLabelArray = array('second_umpire');
                $rowLabelField =  array('first_umpire');
                break;
            case 7:
                $columnLabelArray = array('short_league_name', 'umpire_count');
                $rowLabelField =  array('age_group');
                break;
        }
        
        $this->resultArray = $this->pivotQueryArrayNew($pResultArray, $rowLabelField, $columnLabelArray);
	}
	
	private function convertParametersToSQLReadyValues() {
	    //Converts several of the reportParameters arrays into comma separate values that are ready for SQL queries
	    //Add a value of "All" and "None" to the League list, so that reports that users select for ages with no league (e.g. Colts) are still able to be loaded
	    //TODO: Replace the similar function calls with a single function
	    if ($this->requestedReport->getPDFMode()) {
	        $this->umpireTypeSQLValues = str_replace(",", "','", "'" . rtrim($this->requestedReport->getUmpireType(), ',')) . "'";
	        $this->leagueSQLValues = str_replace(",", "','", "'" . rtrim($this->requestedReport->getLeague(), ',')) . "'";
	        $this->ageGroupSQLValues = str_replace(",", "','", "'" . rtrim($this->requestedReport->getAgeGroup(), ',')) . "'";
	        $this->regionSQLValues = str_replace(",", "','", "'" . rtrim($this->requestedReport->getRegion(), ',')) . "'";
	    } else {
    	    $this->umpireTypeSQLValues = "'".implode("','", $this->requestedReport->getUmpireType())."'";
    	    $this->leagueSQLValues = "'".implode("','", $this->requestedReport->getLeague())."'";
    	    $this->ageGroupSQLValues = "'".implode("','", $this->requestedReport->getAgeGroup())."'";
    	    $this->regionSQLValues = str_replace(",", "','", "'" . rtrim($this->requestedReport->getRegion(), ',')) . "'";
	    }
	}
	
	private function convertParametersToDisplayValues() {
	    
	    if ($this->requestedReport->getPDFMode()) {
	        $this->umpireTypeDisplayValues = str_replace(",", ", ", rtrim($this->requestedReport->getUmpireType(), ',')) . "'";
	        $this->leagueDisplayValues = str_replace(",", ", ", rtrim($this->requestedReport->getLeague(), ',')) . "'";
	        $this->ageGroupDisplayValues = str_replace(",", ", ", rtrim($this->requestedReport->getAgeGroup(), ',')) . "'";
	    } else {
    	    $this->umpireTypeDisplayValues = implode(", ", $this->requestedReport->getUmpireType());
    	    $this->leagueDisplayValues = implode(", ", $this->requestedReport->getLeague());
    	    $this->ageGroupDisplayValues = implode(", ", $this->requestedReport->getAgeGroup());
	    }
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
	    
	    if (!isset($pResultArray[0])) {
	        throw new Exception("PivotedArray is empty. This is probably due to the SQL query not returning any results for report ". $this->requestedReport->getReportNumber() .".");
	    }
	    
	    $this->debug_library->debugOutput("pFieldForRowLabel:", $pFieldForRowLabel);
	    
	    $countRowGroups = count($pFieldForRowLabel);
	    
	    $pivotedArray = array();
	    $counterForRow = 0;
	    $previousRowLabel[0] = "";
	    foreach ($pResultArray as $resultRow) {
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
	                $pivotedArray[$rowArrayKey][$counterForRow]['short_league_name'] = $resultRow['short_league_name'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['age_group'] = $resultRow['age_group'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['umpire_type'] = $resultRow['umpire_type'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['match_no_ump'] = $resultRow['match_no_ump'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['total_match_count'] = $resultRow['total_match_count'];
	                $pivotedArray[$rowArrayKey][$counterForRow]['match_pct'] = $resultRow['match_pct'];
	            } else {
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField] = $resultRow[$columnField];
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
	            }
	            
	            if ($this->requestedReport->getReportNumber() == 3) {
	                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['team_list'] = $resultRow['team_list'];
	            }
	        }
	        $counterForRow++;
	    }
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
	                //$this->debug_library->debugOutput("CL J:", $columnLabelResults[$j][$columnLabels[$i]]);
	
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
	     
	    //TODO: Get these fields from the database
	    switch ($this->requestedReport->getReportNumber()) {
	        case 1:
	            $columnLabelArray = array('short_league_name', 'club_name');
	            $rowLabelField =  array('last_first_name');
	             
	            break;
	        case 2:
	            $columnLabelArray = array('age_group', 'short_league_name');
	            $rowLabelField =  array('last_first_name');
	            break;
	        case 3:
	            $columnLabelArray = array('umpire_type_age_group', 'short_league_name');
	            $rowLabelField =  array('weekend_date');
	            break;
	        case 4:
	            $columnLabelArray = array('umpire_type', 'age_group', 'short_league_name');
	            $rowLabelField =  array('club_name');
	            break;
	        case 5:
	            $columnLabelArray = array('short_league_name', 'subtotal');
	            $rowLabelField =  array('umpire_type', 'age_group');
	            break;
	        case 6:
	            $columnLabelArray = array('second_umpire');
	            $rowLabelField =  array('first_umpire');
	            break;
	        case 7:
	            $columnLabelArray = array('short_league_name', 'umpire_count');
	            $rowLabelField =  array('age_group');
	            break;
	    }
	
	    $this->resultArray = $this->pivotQueryArrayNew($pResultArray, $rowLabelField, $columnLabelArray);
	}
	
}
?>
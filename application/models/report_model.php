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
			
			
			//Find table name to select data from
			$reportTableName = $this->lookupReportTableName($reportParameters['reportName']);
			//echo "reportTableName (" . $reportTableName .")";
				
			//Build SELECT query for report
			$queryForReport = $this->buildSelectQueryForReport($reportTableName, $reportParameters['reportName'],
			    $reportParameters['season'], $reportParameters['age'],
			    $reportParameters['umpireType'], $reportParameters['league']);
				
			
			//$this->reportQuery = $queryForReport;

			$query = $this->db->query($queryForReport);
				
			$reportToDisplay->setResultArray($query->result_array());
			
			//Look up the column labels for this report
			$columnLabelQuery = $this->buildColumnLabelQuery($reportTableName, $reportToDisplay, $reportParameters['reportName'], 
			    $reportParameters['season'], $reportParameters['age'],
			    $reportParameters['umpireType'], $reportParameters['league']);
			
			$query = $this->db->query($columnLabelQuery);
			$reportToDisplay->setColumnLabelResultArray($query->result_array());
			
			/*
			echo "getColumnLabelResultArray<pre>";
			print_r($reportToDisplay->getColumnLabelResultArray());
			echo "</pre>";
			*/
			
			//Load the results from the queries for the column labels and row labels from the database and store them in an array
			//$columnLabelQuery = $this->db->query($reportToDisplay->getReportColumnLabelQuery());
			//$reportToDisplay->setColumnLabelResultArray($columnLabelQuery->result_array());
			
			//temp comment
			//$reportToDisplay->setColumnGroupingArray($columnLabelQuery->result_array());
			//$reportToDisplay->setColumnGroupingArray($reportToDisplay->getColumnLabelResultArray());
			//$reportToDisplay->setRowGroupingArray($rowLabelQuery->result_array());

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
	
	//Find the table to select the data for the report. This tablename is stored in the database
	public function lookupReportTableName($reportID) {
	    $tableNameQuery = "SELECT table_name FROM report_table WHERE report_name = ". $reportID .";";
	    $query = $this->db->query($tableNameQuery);
	    $tableNameResultArray = $query->result_array();
	    //print_r($tableNameResultArray);
	    //echo "ABC: ". $tableNameResultArray[0]["table_name"] . "<BR />";
	    //$tableName = $tableNameResultArray[0]['table_name'];
	    //echo "tableName: ". $tableName . "<BR />";
	    return $tableNameResultArray[0]['table_name'];
	}
	
	private function buildSelectQueryForReport($pReportTableName, $pReportName, $pSeason, $pAge, $pUmpireType, $pLeague) {
	    //Increase maximum length for GROUP_CONCAT value
	    $query = $this->db->query("SET group_concat_max_len = 8000;");
	     
	    //TODO: Merge this query with the buildColumnLabels query, as it is quite similar.
	    
	    //Find columns to select from
	    $columnQuery = "SELECT GROUP_CONCAT(gc.column_name SEPARATOR ', ') as COLS ".
	        "FROM (" .
	        "SELECT DISTINCT CONCAT('SUM(', '`',rc.column_name,'`', ') as `',rc.column_name,'`') as column_name " .
	        "FROM report_column rc " .
	        "JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id " .
	        "JOIN report_table rt ON rcl.report_table_id = rt.report_table_id " .
	        "JOIN report_column_lookup_display rcld ON rcld.report_column_id = rc.report_column_id ";
	        
	        //TODO: Remove this hard-coding
	        if ($pReportName == '01') {
	            $columnQuery .= "JOIN ". $pReportTableName ." mv ON rcld.column_display_name = mv.short_league_name ";
	        }
	        
	        /*$columnQuery .= "WHERE ((rcl.filter_name = 'short_league_name' AND rcl.filter_value = '". $pLeague ."') " .
        	    "OR (rcl.filter_name = 'age_group' AND rcl.filter_value = '". $pAge ."')) " . 
        	    "AND rt.report_name = ". $pReportName ." ";*/
	        
	        $columnQuery .= "WHERE rcl.filter_name = 'short_league_name' AND rcl.filter_value = '". $pLeague ."' " .
	            "AND rt.report_name = ". $pReportName ." ";
	        if ($pReportName == '02') {
	            $columnQuery .= "AND rc.report_column_id IN ( " .
	            "SELECT DISTINCT rcld2.report_column_id " .
	            "FROM report_column_lookup_display rcld2 " .
	            "INNER JOIN report_column rc2 ON rcld2.report_column_id = rc2.report_column_id " .
	            "INNER JOIN report_column_lookup rcl2 ON rc2.report_column_id = rcl2.report_column_id " .
	            "INNER JOIN report_table rt2 ON rcl2.report_table_id = rt2.report_table_id " .
	            "WHERE rcl2.filter_name = 'age_group' AND rcl2.filter_value = '". $pAge ."' " .
	            "AND rt2.report_name = ". $pReportName .")";
	        }
	    
    	    if ($pReportName == '01' && $pAge != "All") {
                $columnQuery .= "AND mv.age_group = '". $pAge ."' ";
    	    }
            $columnQuery .= "AND rcld.column_display_filter_name = 'short_league_name' " .
                "ORDER BY rc.column_name ASC" .
                ") gc;";
	    
	    //echo "Column Query: $columnQuery <BR/>";
	     
	    $query = $this->db->query($columnQuery);
	    $queryResultArray = $query->result_array();
	    $columnsToSelect = $queryResultArray[0]["COLS"];
	/*
	    echo "columnsToSelect<pre>";
	    print_r($columnsToSelect);
	    echo "</pre>";
	*/
	    //Build WHERE clause
	    $whereClause = $this->buildWhereClause($pSeason, $pAge, $pUmpireType, $pLeague);
	  //  echo "whereClause(". $whereClause .")<BR/>";
	     
	    //Construct SQL query
	    $queryForReport = "SELECT full_name, " .
	        $columnsToSelect . " " .
	        "FROM " . $pReportTableName . " " . $whereClause . " " .
	        "GROUP BY full_name";
	         
	    //echo "<BR/>Query For Report ($queryForReport)<BR/>";
	    /*
	    echo "<BR/>Query For Report Length (" .strlen($queryForReport) .")<BR/>";
	      */  
	    return $queryForReport;
	         
	}
	
	private function buildWhereClause($pSeason, $pAgeGroup, $pUmpireType, $pLeague) {
	    $whereClause = NULL;
	    
	    if ($pAgeGroup != 'All' || $pUmpireType != 'All' || $pLeague != 'All') {
	        $whereClause = "WHERE ";
	    }
	
	    $addAndKeyword = FALSE;
	    if ($pAgeGroup != 'All') {
	        $whereClause .= "age_group = '$pAgeGroup' ";
	        $addAndKeyword = TRUE;
	    }
	
	    if ($pUmpireType != 'All') {
	        if ($addAndKeyword) {
	            $whereClause .= "AND ";
	            $addAndKeyword = FALSE;
	        }
	        $whereClause .= "umpire_type_name = '$pUmpireType' ";
	        $addAndKeyword = TRUE;
	    }
	    
        if ($pLeague != 'All') {
            if ($addAndKeyword) {
                $whereClause .= "AND ";
                $addAndKeyword = FALSE;
            }
            $whereClause .= "short_league_name = '$pLeague'";
        }
         
	    return $whereClause;
	}
	
	private function buildColumnLabelQuery($pReportTableName, $userReport, $pReportName, $pSeason, $pAge, $pUmpireType, $pLeague) {
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
	        
	        
	        //Find the columns to show from the UserReport
	    $columnsToDisplay = $userReport->reportDisplayOptions->getColumnGroup();
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
	    if ($pReportName == '01') {
	        $columnLabelQuery .= "JOIN ". $pReportTableName ." mv ON rcld.column_display_name = mv.short_league_name ";
	    }
	    
	    /*$columnLabelQuery .= "WHERE ((rcl.filter_name = 'short_league_name' AND rcl.filter_value = '". $pLeague ."') " .
    	    "OR (rcl.filter_name = 'age_group' AND rcl.filter_value = '". $pAge ."')) " . 
    	    "AND rt.report_name = ". $pReportName ." ";*/
	   	    
	    $columnLabelQuery .= "WHERE rcl.filter_name = 'short_league_name' AND rcl.filter_value = '". $pLeague ."' " .
            "AND rt.report_name = ". $pReportName ." ";
	    
	    if ($pReportName == '02') {
            $columnLabelQuery .= "AND rc.report_column_id IN ( " .
	           "SELECT DISTINCT rcld2.report_column_id " .
	           "FROM report_column_lookup_display rcld2 " .
			   "INNER JOIN report_column rc2 ON rcld2.report_column_id = rc2.report_column_id " .
			   "INNER JOIN report_column_lookup rcl2 ON rc2.report_column_id = rcl2.report_column_id " .
			   "INNER JOIN report_table rt2 ON rcl2.report_table_id = rt2.report_table_id " .
               "WHERE rcl2.filter_name = 'age_group' AND rcl2.filter_value = '". $pAge ."' " .
               "AND rt2.report_name = ". $pReportName .")";
	    }
	    
	    /*
	    $columnLabelQuery = "SELECT DISTINCT rc.column_name, rcld.report_column_id, " .
            	"(SELECT rcld3.column_display_name " .
            	"FROM report_column_lookup_display rcld3 " .
            	"WHERE rcld3.report_column_id = rcld.report_column_id " .
            	"AND rcld3.column_display_filter_name = 'short_league_name') as short_league_name, " .
            	"(SELECT rcld2.column_display_name " .
            	"FROM report_column_lookup_display rcld2 " .
            	"WHERE rcld2.report_column_id = rcld.report_column_id " .
            	"AND rcld2.column_display_filter_name = 'club_name') as club_name " .
            "FROM report_column_lookup_display rcld " .
            "JOIN report_column rc ON rcld.report_column_id = rc.report_column_id " .
            "JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id " .
            "JOIN report_table rt ON rcl.report_table_id = rt.report_table_id " .
            "JOIN mv_report_05 mv ON rcld.column_display_name = mv.short_league_name " .
            "WHERE rcl.filter_name = 'short_league_name' " .
            "AND rcl.filter_value = '". $pLeague ."' " .
            "AND rt.report_name = ". $pReportName ." ";*/
        
        if ($pReportName == '01' && $pAge != "All") {
            $columnLabelQuery .= "AND mv.age_group = '". $pAge ."' ";
	    }
	    
        $columnLabelQuery .= "ORDER BY rcld.report_column_id, rcld.column_display_filter_name;";
	    
        //echo "columnLabelQuery " . $columnLabelQuery . "<BR />";
	    
	    return $columnLabelQuery;
	}
	
}

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
			
			if ($reportParameters['reportName'] == '06') {
			    //Build SELECT query for report data
			    $queryForReport = $this->buildSelectQueryForReport6($reportToDisplay, $reportTableName, $reportParameters['reportName'],
			        $reportParameters['season'], $reportParameters['age'],
			        $reportParameters['umpireType'], $reportParameters['league']);
			    
			    //Run query and store result in array
			    $query = $this->db->query($queryForReport);			    
			    
			    //Transform array to pivot
			    $queryResultArray = $query->result_array();
			    
			    $rowLabelArray = $this->getDistinctListOfValues('first_umpire', $queryResultArray);
			    $columnLabelArray = $this->getDistinctListOfValues('second_umpire', $queryResultArray);
			    
			    //Params: queryResultArray, field for row, field for columns
			    $pivotedResultArray = $this->pivotQueryArray($queryResultArray, 'first_umpire', 'second_umpire');
                
			    //Convert column labels into array for the output page
			    $columnLabelArray = $this->convertSimpleArrayToColumnLabelArray($columnLabelArray);
			    
			    //Set values for result array and column label array
			    $reportToDisplay->setResultArray($pivotedResultArray);
			    $reportToDisplay->setColumnLabelResultArray($columnLabelArray);
			    
			    return $reportToDisplay;
			    
			} else {
    			//Build SELECT query for report
    			$queryForReport = $this->buildSelectQueryForReport($reportToDisplay, $reportTableName, $reportParameters['reportName'],
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
    			
    			$columnLabelResultArray = $query->result_array();
    			
    			//Add an extra entry if it is report 2, for the Total column
    			if ($reportParameters['reportName'] == '02') {
        			$columnLabelResultArray[] = array(
        			    'column_name' => 'Total',
        			    'report_column_id' => '0',
        			    'age_group' => 'Total',
        			    'short_league_name' => ''
        			);
    			}
    			$reportToDisplay->setColumnLabelResultArray($columnLabelResultArray);
    			
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
    
    			/*
    			echo "getColumnLabelResultArray<pre>";
    			print_r($reportToDisplay->getColumnLabelResultArray());
    			echo "</pre>";
    			
    			echo "getResultArray<pre>";
    			print_r($reportToDisplay->getResultArray());
    			echo "</pre>";
    			*/
    			
    			return $reportToDisplay;
			
			}
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			
		}
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
	    /*
	    echo "pivotQueryArray<pre>";
	    print_r($pResultArray);
	    echo "</pre>";
	    */
	    //Get distinct list of values in the field for column and row labels

	    
	    /*
	     echo "rowLabelArray<pre>";
	     print_r($rowLabelArray);
	     echo "</pre>";
	     
	     echo "columnLabelArray<pre>";
	     print_r($columnLabelArray);
	     echo "</pre>";
	     */
	    
	    //Create new array to hold values for output
	    $pivotResultArray = array();
	    
	    $pivotedArray = array();
	    $first_umpire_names = array();
	    
	    /*
	    echo "<table border=1>";
	    //Headers
	    echo "<tr>";
	    for ($j=0; $j < count($columnLabelArray); $j++) {
	        //print_r($item);
	        echo "<td>". $columnLabelArray[$j] ."</td>";
	    }
	    echo "</tr>";
	    */
	    
	    //$arrayCounter = 0;

	    foreach ($pResultArray as $resultRow)
	    {
	        
	        $second_umpire_names[] = $resultRow['second_umpire'];
	        $pivotedArray[$resultRow['first_umpire']]['umpire_name'] = $resultRow['first_umpire'];
	        $pivotedArray[$resultRow['first_umpire']][$resultRow['second_umpire']] = $resultRow['match_count'];
	        //$pivotedArray[$arrayCounter]['umpire_name'] = $resultRow['first_umpire'];
	        //$pivotedArray[$arrayCounter][$resultRow['second_umpire']] = $resultRow['match_count'];
	        //$arrayCounter++;
	        //$total[$resultRow['first_umpire']] += $resultRow['match_count'];
	    }
	    
	    /*
	    echo "pivotedArray<pre>";
	    print_r($pivotedArray);
	    echo "</pre>";
	    */
	    
	    
	    
	    /*
	    $second_umpire_names = array_unique($second_umpire_names);
	    
	    echo "<table border=1>";
	    echo "<tr><td>Name</td>";
	    foreach ($second_umpire_names as $second_umpire) {
	        echo "<td>". $second_umpire ."</td>";
	        
	    }
	    echo "</tr>";
	    
	    foreach ($table as $firstUmpire => $secondUmpires)
	    {
	        
	        //echo "$firstUmpire\t";
	        echo "<tr>";
	        echo "<td>". $firstUmpire ."</td>";
	        foreach ($second_umpire_names as $second_umpire) {
	            
	            //echo "$secondUmpires[$second_umpire]\t";
	            
	            echo "<td>". $secondUmpires[$second_umpire] ."</td>";
	            //echo "<td>-</td>";
	            
	        }
	        echo "</tr>";
	    }
	    
	    echo "</table>";
	    */
	    
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
		/*
		echo "<pre>";
		print_r($uniqueFieldList);
		echo "</pre>";
		*/
		return $uniqueFieldList;
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

		//usort($uniqueFieldList, 'compareValues');
		
		/*
		echo "<pre>";
		print_r($uniqueFieldList);
		echo "</pre>";
		*/
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
	
	private function buildSelectQueryForReport($reportToDisplay, $pReportTableName, $pReportName, $pSeason, $pAge, $pUmpireType, $pLeague) {
	    //Increase maximum length for GROUP_CONCAT value
	    $debugMode = $this->config->item('debug_mode');
	    $query = $this->db->query("SET group_concat_max_len = 8000;");
	    
	    $rowsToSelect = $reportToDisplay->getDisplayOptions()->getRowGroup();
	     
	    //TODO: Merge this query with the buildColumnLabels query, as it is quite similar.
	    
	    //Find columns to select from
	    
	    
	    
    	    $columnQuery = "SELECT GROUP_CONCAT(gc.column_name SEPARATOR ', ') as COLS ".
    	        "FROM (" .
    	        "SELECT DISTINCT CASE " .
                    "WHEN rc.column_function IS NULL THEN CONCAT('`', rc.column_name, '` as `', rc.column_name, '`') " .
                    "ELSE CONCAT(rc.column_function, '(`', rc.column_name, '`', ') as `', rc.column_name, '`') " .
                "END AS column_name " .
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
	            "AND rt2.report_name = ". $pReportName ." ";
	            $columnQuery .= "AND rcld2.report_column_id IN ( " .
				"SELECT DISTINCT rcld3.report_column_id " .
				"FROM report_column_lookup_display rcld3 " .
				"INNER JOIN report_column rc3 ON rcld3.report_column_id = rc3.report_column_id " .
				"INNER JOIN report_column_lookup rcl3 ON rc3.report_column_id = rcl3.report_column_id " .
				"INNER JOIN report_table rt3 ON rcl3.report_table_id = rt3.report_table_id " .
				"WHERE (rcl3.filter_name = 'umpire_type' AND rcl3.filter_value = '". $pUmpireType ."' " .
				"AND rt3.report_name = ". $pReportName ."))) ";
	        }
	    
    	    if ($pReportName == '01' && $pAge != "All") {
                $columnQuery .= "AND mv.age_group = '". $pAge ."' ";
    	    }
            $columnQuery .= "AND rcld.column_display_filter_name = 'short_league_name' " .
                "ORDER BY rc.column_name ASC" .
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
	    
	    if ($pReportName == '02') {
	        
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

	        $columnQuery .= "WHERE rcl.filter_name = 'short_league_name' AND rcl.filter_value = '". $pLeague ."' " .
	            "AND rt.report_name = ". $pReportName ." " .
                "AND rc.report_column_id IN ( " .
                "SELECT DISTINCT rcld2.report_column_id " .
                "FROM report_column_lookup_display rcld2 " .
                "INNER JOIN report_column rc2 ON rcld2.report_column_id = rc2.report_column_id " .
                "INNER JOIN report_column_lookup rcl2 ON rc2.report_column_id = rcl2.report_column_id " .
                "INNER JOIN report_table rt2 ON rcl2.report_table_id = rt2.report_table_id " .
                "WHERE rcl2.filter_name = 'age_group' AND rcl2.filter_value = '". $pAge ."' " .
                "AND rt2.report_name = ". $pReportName .")";

	        $columnQuery .= "AND rcld.column_display_filter_name = 'short_league_name' " .
	   	        "AND rc.overall_total = 1 " .
	            "ORDER BY rc.column_name ASC" .
	            ") gc;";
	        
	        $query = $this->db->query($columnQuery);
	        $queryResultArray = $query->result_array();
	        
	        //Concatenate the results of this query to the original query
	        $columnsToSelect .= ", " . $queryResultArray[0]["COLS"] . " as Total";
	    
	    }
	    
	    
	    if ($debugMode) {
    	    /*echo "columnsToSelect<pre>";
    	    print_r($columnsToSelect);
    	    echo "</pre>";*/
	        echo "<BR />columnsToSelect (". $columnsToSelect .") <BR />";
	    }
	    //Build WHERE clause
	    $whereClause = $this->buildWhereClause($pSeason, $pAge, $pUmpireType, $pLeague, $pReportName);
	  //  echo "whereClause(". $whereClause .")<BR/>";
	     
	    
	    //Determine fields to select

	    //TODO: Replace the [0] with a string that concatenates all values in the array with a comma, 
	    //to handle cases where more than one field is shown in the row
	    //Construct SQL query
	    if ($pReportName == '03') {
	        //No GROUP BY for report 03
	        $queryForReport = "SELECT ". $rowsToSelect[0] .", " .
	            $columnsToSelect . " " .
	            "FROM " . $pReportTableName . " " . $whereClause;
	    
	    } elseif ($pReportName == '05') {
	        $queryForReport = "SELECT ". $rowsToSelect[0] .", " . $rowsToSelect[1] .", " .
	            $columnsToSelect . " " .
	            "FROM " . $pReportTableName . " " . $whereClause . " " .
	            "GROUP BY ". $rowsToSelect[0] . ", " . $rowsToSelect[1];
	            
	    } else {
    	    $queryForReport = "SELECT ". $rowsToSelect[0] .", " .
    	        $columnsToSelect . " " .
    	        "FROM " . $pReportTableName . " " . $whereClause . " " .
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
	
	
	
	private function buildSelectQueryForReport6($reportToDisplay, $pReportTableName, $pReportName, $pSeason, $pAge, $pUmpireType, $pLeague) {
	    //Increase maximum length for GROUP_CONCAT value
	    $debugMode = $this->config->item('debug_mode');
	    //$query = $this->db->query("SET group_concat_max_len = 8000;");
	     
	    $rowsToSelect = $reportToDisplay->getDisplayOptions()->getRowGroup();
	    
	    //Build WHERE clause
	    $whereClause = $this->buildWhereClause($pSeason, $pAge, $pUmpireType, $pLeague, $pReportName);
	

	     
	    /*
	    $columnQuery = "SELECT GROUP_CONCAT(gc.column_name SEPARATOR ', ') as COLS ".
	        "FROM (" .
	        "SELECT DISTINCT second_umpire " .
	        "FROM mv_report_06 ";
	    
	    $columnQuery .= $whereClause;
	    $columnQuery .= "ORDER BY second_umpire ASC) gc;";

	    if ($debugMode) {
	        echo "Column Query: $columnQuery <BR/>";
	    }
	
	    //Run the query to find what columns to select from the report table
	    $query = $this->db->query($columnQuery);
	    $queryResultArray = $query->result_array();
	    $columnsToSelect = $queryResultArray[0]["COLS"];
	     
	    //Add a Totals column for report 2.
	    //This is done as a separate query, then concatenated, to make it easier
	     

	    if ($debugMode) {

	        echo "<BR />columnsToSelect (". $columnsToSelect .") <BR />";
	    }
	    */
	    //Determine fields to select
	
	    //TODO: Replace the [0] with a string that concatenates all values in the array with a comma,
	    //to handle cases where more than one field is shown in the row
	    //Construct SQL query
	    
	    $queryForReport = "SELECT first_umpire, second_umpire, SUM(match_count) AS match_count " .
	    "FROM " . $pReportTableName . " " . $whereClause . " " .
	    "GROUP BY first_umpire, second_umpire;";
	
	    if ($debugMode) {
	        echo "<BR/>Query For Report ($queryForReport)<BR/>";
	    }
	    return $queryForReport;
	
	}
	
	
	private function buildWhereClause($pSeason, $pAgeGroup, $pUmpireType, $pLeague, $pReportName) {
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
            $whereClause .= "short_league_name = '$pLeague' ";
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
               "AND rt2.report_name = ". $pReportName ." ";
            $columnLabelQuery .= "AND rcld2.report_column_id IN ( " .
                "SELECT DISTINCT rcld3.report_column_id " .
                "FROM report_column_lookup_display rcld3 " .
                "INNER JOIN report_column rc3 ON rcld3.report_column_id = rc3.report_column_id " .
                "INNER JOIN report_column_lookup rcl3 ON rc3.report_column_id = rcl3.report_column_id " .
                "INNER JOIN report_table rt3 ON rcl3.report_table_id = rt3.report_table_id " .
                "WHERE (rcl3.filter_name = 'umpire_type' AND rcl3.filter_value = '". $pUmpireType ."' " .
                "AND rt3.report_name = ". $pReportName ."))) ";
	    }
	    
        
        if ($pReportName == '01' && $pAge != "All") {
            $columnLabelQuery .= "AND mv.age_group = '". $pAge ."' ";
	    }
	    
        $columnLabelQuery .= "ORDER BY rcld.report_column_id, rcld.column_display_filter_name;";
        
        $debugMode = $this->config->item('debug_mode');
        if ($debugMode) {
            echo "<BR />columnLabelQuery " . $columnLabelQuery . "<BR />";
        }
	    return $columnLabelQuery;
	}
	
}

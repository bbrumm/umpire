<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report2 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "last_first_name, ".
            "age_group, ".
            "age_sort_order, ".
            "short_league_name, ".
            "two_ump_flag, ".
            "SUM(match_count) AS match_count ".
            "FROM dw_mv_report_02 ".
            "WHERE age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND short_league_name IN ('2 Umpires', ". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "AND two_ump_flag = 0 ".
            "GROUP BY last_first_name, age_group, age_sort_order, short_league_name, two_ump_flag ".
            "UNION ALL ".
            "SELECT ".
            "last_first_name, ".
            "age_group, ".
            "age_sort_order, ".
            "'2 Umpires', ".
            "two_ump_flag, ".
            "SUM(match_count) AS match_count ".
            "FROM dw_mv_report_02 ".
            "WHERE age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND short_league_name IN ('2 Umpires', ". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "AND two_ump_flag = 1 ".
            "GROUP BY last_first_name, age_group, age_sort_order, two_ump_flag ".
            "ORDER BY last_first_name, age_sort_order, short_league_name;";

        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT age_group, short_league_name ".
            "FROM ( ".
                "SELECT ".
	            "age_group, ".
	            "age_sort_order, ".
                "league_sort_order, ".
	            "short_league_name ".
                "FROM dw_mv_report_02 ".
                "WHERE age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
	            "AND short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
	            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
	            "AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .") ".
	            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
	            "UNION ALL ".
	            "SELECT 'Total', 1000, 1000, '' ".
                "UNION ALL ".
	            "SELECT 'Seniors', 1, 50, '2 Umpires' ".
            ") AS sub ".
            "ORDER BY age_sort_order, league_sort_order;";
        
        return $queryString;
    }
	


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
    public function transformQueryResultsIntoOutputArray($pResultArray, $columnLabelResultArray, $pReportColumnFields) {
        $resultOutputArray = [];

        //$countItemsInColumnHeadingSet = count($columnLabelResultArray[0]);
        $currentResultArrayRow = 0;

        foreach ($pResultArray as $rowKey => $currentRowItem) { //Maps to a single row of output
            $columnNumber = 0;
            $totalForRow = 0;
            $twoUmpGamesForRow = 0;
            $resultOutputArray[$currentResultArrayRow][0] = $rowKey;

            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                $columnNumber++;
                //Loops through each value of $columnLabelResultArray.
                //This comes from the results found in the separate_reports.ReportX.getReportColumnQuery() function.
                //E.g. if Report 8's column query returns 4 rows, then this columnHeadingSet has 4 records in it
                foreach ($currentRowItem as $columnKey => $columnItem) { //Maps to a single match_count, not necessarily a column
                    //Loop through each row and column intersection in the result array
                    //Match the column headings to the values in the array
                    if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
			if ($columnHeadingSet['short_league_name'] != '2 Umpires') {
			    $totalForRow = $totalForRow + $columnItem['match_count'];
			}
		    	//Set the "2 Umpires" match count to the total so far of rows marked as two_ump_flag=1
		   	 if ($columnHeadingSet['short_league_name'] == '2 Umpires') {
				$twoUmpGamesForRow = $twoUmpGamesForRow + $columnItem['match_count'];
				//$this->debug_library->debugOutput("twoUmpGamesForRow:", $twoUmpGamesForRow);
				$resultOutputArray[$currentResultArrayRow][$columnNumber] = $twoUmpGamesForRow;
		 	   }

                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                        

                    }
                }
            }
            //Add on final column for totals for the row
            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $totalForRow;
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }


    public function pivotQueryArray($pResultArray, array $pFieldForRowLabel, array $pFieldsForColumnLabel) {
        $pivotedArray = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        foreach ($pResultArray as $resultRow) {

            $counterForRow = $this->resetCounterForRow($counterForRow, $resultRow, $pFieldForRowLabel, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pFieldForRowLabel[0]];
            if (array_key_exists(1, $pFieldForRowLabel)) {
                $previousRowLabel[1] = $resultRow[$pFieldForRowLabel[1]];
            }
            foreach ($pFieldsForColumnLabel as $columnField) {

                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, $columnField, $columnField);
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "match_count", "match_count");

                if ($resultRow['two_ump_flag'] == 1) {
                    //$this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "short_league_name", "2 Umpires");
                    $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "short_league_name", "short_league_name");
                }
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }


}

<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report5 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT umpire_type, ".
            "age_group, ".
            "short_league_name, ".
            "match_no_ump, ".
            "total_match_count, ".
            "match_pct ".
            "FROM dw_mv_report_05 ".
            "WHERE short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "ORDER BY umpire_type, age_sort_order, league_sort_order;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT ".
            "l.short_league_name, ".
            "sub.subtotal ".
            "FROM dw_dim_league l ".
            "CROSS JOIN ( ".
                "SELECT 'Games' AS subtotal ".
                "UNION ".
                "SELECT 'Total' ".
                "UNION ".
                "SELECT 'Pct' ".
            ") AS sub ".
            "WHERE l.short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "UNION ALL ".
            "SELECT 'All', 'Total';";
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
            $resultOutputArray[$currentResultArrayRow][0] = $currentRowItem[0]['umpire_type'];
            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                $columnNumber++;
                //Loops through each value of $columnLabelResultArray.
                //This comes from the results found in the separate_reports.ReportX.getReportColumnQuery() function.
                //E.g. if Report 8's column query returns 4 rows, then this columnHeadingSet has 4 records in it
                foreach ($currentRowItem as $columnKey => $columnItem) { //Maps to a single match_count, not necessarily a column
                    //Loop through each row and column intersection in the result array
                    if ($columnNumber == 1) {
                        //Add extra column for report 5
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['age_group'];
                        $columnNumber++;
                    }
                    
                    //Match the column headings to the values in the array
                    $subtotalToColumnMap = array(
                        'Games'=>'match_no_ump',
                        'Total'=>'total_match_count',
                        'Pct'=>'match_pct'
                    );

                    $columnItem['subtotal'] = $columnHeadingSet['subtotal'];

                    if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem[$subtotalToColumnMap[$columnHeadingSet['subtotal']]];
                        if ($this->isSubtotalGames($columnHeadingSet)) {
                            $totalForRow = $totalForRow + $columnItem['match_no_ump'];
                        }
                    }
                }
            }
            //Add on final column for report 2 and 5 and 8 for totals for the row
            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $totalForRow;
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }
    
    private function isSubtotalGames($columnHeadingSet) {
        return ($columnHeadingSet['subtotal'] == 'Games');
    }


    public function pivotQueryArray($pResultArray, array $pFieldForRowLabel, array $pFieldsForColumnLabel) {
        $pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRow($counterForRow, $resultRow, $pFieldForRowLabel, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pFieldForRowLabel[0]];
            //Check if there is a second row that is being grouped.
            //This should only exist for report 5, according to the report_grouping_structure table
            if (array_key_exists(1, $pFieldForRowLabel)) {
                $previousRowLabel[1] = $resultRow[$pFieldForRowLabel[1]];
            }
            foreach ($pFieldsForColumnLabel as $columnField) {
                $rowArrayKey = $resultRow[$pFieldForRowLabel[0]] . " " . $resultRow[$pFieldForRowLabel[1]];
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, "short_league_name");
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, "age_group");
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, "umpire_type");
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, "match_no_ump");
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, "total_match_count");
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, "match_pct");
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }


    private function setPivotedArrayNamedValue(&$pPivotedArray, $pRowArrayKey, $pCounterForRow, $pResultRow, $pResultArrayKey) {
        $pPivotedArray[$pRowArrayKey][$pCounterForRow][$pResultArrayKey] = $pResultRow[$pResultArrayKey];
    }
    
}

<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report4 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "club_name, ".
            "age_group, ".
            "short_league_name, ".
            "umpire_type, ".
            "match_count ".
            "FROM dw_mv_report_04 ".
            "WHERE region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "ORDER BY club_name, age_sort_order, league_sort_order;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT ".
            "s.umpire_type, ".
            "s.age_group, ".
            "s.short_league_name ".
            "FROM dw_mv_report_04 s ".
            "WHERE s.region_name = ". $pReportInstance->filterParameterRegion->getFilterSQLValues() ." " .
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "GROUP BY s.umpire_type, s.age_group, s.short_league_name " .
            "ORDER BY s.umpire_type, MIN(s.age_sort_order), MIN(s.league_sort_order);";
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
        $currentResultArrayRow = 0;
        foreach ($pResultArray as $rowKey => $currentRowItem) { //Maps to a single row of output
            $columnNumber = 0;
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
                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                    }
                }
            }
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }


    public function pivotQueryArray($pResultArray, array $pFieldForRowLabel, array $pFieldsForColumnLabel) {
        $pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRow($counterForRow, $resultRow, $pFieldForRowLabel, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pFieldForRowLabel[0]];
            foreach ($pFieldsForColumnLabel as $columnField) {
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, $columnField, $columnField);
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "match_count", "match_count");
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }
}

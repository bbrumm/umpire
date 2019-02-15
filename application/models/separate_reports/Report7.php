<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report7 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "umpire_type, ".
            "age_group, ".
            "short_league_name, ".
            "umpire_count, ".
            "match_count ".
            "FROM dw_mv_report_07 ".
            "WHERE season_year IN (". $pReportInstance->requestedReport->getSeason() .") ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN ('Field') ".
            "ORDER BY age_sort_order, league_sort_order, umpire_type, umpire_count;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
    $queryString = "SELECT DISTINCT ".
            "short_league_name, ".
            "umpire_count ".
            "FROM ( ".
            "SELECT DISTINCT ".
            "short_league_name, ".
            "league_sort_order, ".
            "'2 Umpires' AS umpire_count ".
            "FROM dw_mv_report_07 ".
            "WHERE season_year IN (". $pReportInstance->requestedReport->getSeason() .") ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN ('Field') ".
            "UNION ALL ".
            "SELECT DISTINCT ".
            "short_league_name, ".
            "league_sort_order, ".
            "'3 Umpires' ".
            "FROM dw_mv_report_07 ".
            "WHERE season_year IN (". $pReportInstance->requestedReport->getSeason() .") ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN ('Field') ".
            ") AS sub ".
            "ORDER BY league_sort_order, umpire_count;";
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
            //Add on final column for report 2 and 5 and 8 for totals for the row
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
            foreach ($pFieldsForColumnLabel as $columnField) {
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, $columnField, $columnField);
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "match_count", "match_count");
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }
}

<?php
require_once 'IReport.php';

class Report3 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        //This has remained as a query on staging tables instead of moving to a MV table, because of the subquery using parameters from the UI selection.
        //Creating a MV would look similar to this and probably wouldn't improve performance.
        $queryString = "SELECT ".
            "weekend_date, ".
            "CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group, ".
            "short_league_name, ".
            "GROUP_CONCAT(team_names) AS team_list, (".
                "SELECT ".
            	"COUNT(DISTINCT match_id) ".
            	"FROM staging_no_umpires s2 ".
            	"WHERE s2.age_group = s.age_group ".
            	"AND s2.umpire_type = s.umpire_type ".
                "AND s2.weekend_date = s.weekend_date ".
                "AND short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            ") AS match_count ".
            "FROM staging_no_umpires s ".
            "WHERE short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "AND CONCAT(age_group, ' ', umpire_type) IN ( ".
                "'Seniors Boundary', ".
            	"'Seniors Goal', ".
            	"'Reserve Goal', ".
            	"'Colts Field', ".
            	"'Under 16 Field', ".
            	"'Under 14 Field', ".
            	"'Under 12 Field' ".
            ") ".
            "GROUP BY weekend_date, age_group, umpire_type, short_league_name ".
            "ORDER BY weekend_date, age_group, umpire_type, short_league_name;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT ".
        	"CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group, ".
        	"short_league_name ".
        	"FROM ( ".
            	"SELECT ".
            	"s.age_group, ".
            	"s.umpire_type, ".
            	"s.short_league_name, ".
                "s.region_name, ".
            	"s.age_sort_order ".
            	"FROM staging_all_ump_age_league s ".
            	"UNION ALL ".
            	"SELECT ".
            	"s.age_group, ".
            	"s.umpire_type, ".
            	"'Total', ".
                "'Total', ".
            	"s.age_sort_order ".
            	"FROM staging_all_ump_age_league s ".
            ") sub ".
            "WHERE CONCAT(age_group, ' ', umpire_type) IN ".
            	"('Seniors Boundary' , 'Seniors Goal', 'Reserves Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field') ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN ('Total', ". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "ORDER BY age_sort_order, umpire_type, short_league_name;";
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
                            if ($columnHeadingSet['short_league_name'] == 'Total') {
                                //Output the Total column values for report 3
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                            } else {
                                //Output the team list value for non-total columns
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['team_list'];
                            }

                    }
                }
            }
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }

    


    private function isFieldMatchingTwoColumnsWithTotal($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnHeadingSet[$pReportColumnFields[1]] == 'Total');
    }

    public function isFieldMatchingColumnReport3($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        switch (count($pReportColumnFields)) {
            case 1:
                return $this->isFieldMatchingOneColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
                break;
            case 2:
                return $this->isFieldMatchingTwoColumnsWithTotal($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
                break;
            case 3:
                return $this->isFieldMatchingThreeColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
                break;
            default:
                throw new InvalidArgumentException("Count of report column fields needs to be between 1 and 3.");
        }
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
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "team_list", "team_list");

            }
            $counterForRow++;
        }
        return $pivotedArray;
    }
    
}

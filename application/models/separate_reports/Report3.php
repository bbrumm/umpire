<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report3 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        //This has remained as a query on staging tables instead of moving to a MV table, because of the subquery using parameters from the UI selection.
        //Creating a MV would look similar to this and probably wouldn't improve performance.
        $sqlFilename = "report3_data.sql";
        return $this->constructReportQuery($sqlFilename, $pReportInstance);
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report3_columns.sql";
        return $this->constructReportQuery($sqlFilename, $pReportInstance);
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
                    if ($this->isFieldMatchingColumnReport3($columnItem, $columnHeadingSet, $pReportColumnFields)) {
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
        //TODO: Write a test to ensure that the report grouping structure only returns 2 columns for this report,
        //and the right number of rows for other reports.
        if ($pColumnHeadingSet[$pReportColumnFields[1]] == 'Total') {
                    return $this->isFieldMatchingTwoColumnsWithTotal($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
                } else {
                    return $this->isFieldMatchingTwoColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
                }
        /*
        switch (count($pReportColumnFields)) {
            case 1:
                return $this->isFieldMatchingOneColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
            case 2:
                if ($pColumnHeadingSet[$pReportColumnFields[1]] == 'Total') {
                    return $this->isFieldMatchingTwoColumnsWithTotal($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
                } else {
                    return $this->isFieldMatchingTwoColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
                }
            case 3:
                return $this->isFieldMatchingThreeColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
            default:
                throw new InvalidArgumentException("Count of report column fields needs to be between 1 and 3.");
        }
        */
    }


    public function pivotQueryArray($pResultArray, array $pFieldForRowLabel, Report_cell_collection $pColumnLabelFields) {
        $pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        $pFieldsForColumnLabel = $pColumnLabelFields->getReportCellArray();
        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRow($counterForRow, $resultRow, $pFieldForRowLabel, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pFieldForRowLabel[0]->getCellValue()];
            foreach ($pFieldsForColumnLabel as $singleColumnCell) {
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, $singleColumnCell->getCellValue(), $singleColumnCell->getCellValue());
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "match_count", "match_count");
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "team_list", "team_list");

            }
            $counterForRow++;
        }
        return $pivotedArray;
    }
    
}

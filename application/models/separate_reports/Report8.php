<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report8 extends Parent_report implements IReport {

    const COL_TOTAL_GEELONG = 7;
    const COL_TOTAL_OVERALL = 9;

    public function getReportDataQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report8_data.sql";
        return $this->constructReportQuery($sqlFilename, $pReportInstance);
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report8_columns.sql";
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
            $totalGeelong = 0;
            $totalForRow = 0;
            $resultOutputArray[$currentResultArrayRow][0] = $rowKey;
            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                $columnNumber++;
                //Loops through each value of $columnLabelResultArray.
                //This comes from the results found in the separate_reports.ReportX.getReportColumnQuery() function.
                //E.g. if Report 8's column query returns 4 rows, then this columnHeadingSet has 4 records in it
                foreach ($currentRowItem as $columnKey => $columnItem) { //Maps to a single match_count, not necessarily a column
                    //Loop through each row and column intersection in the result array
                    $resultOutputArray = $this->addColumnHeadingsForTotals($resultOutputArray, $currentResultArrayRow, $columnNumber);
                    
                    //Match the column headings to the values in the array
                    if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];

                        if ($this->seasonYearNeedsTotal($columnItem)) {
                            $totalGeelong = $this->addMatchCountToTotal($totalGeelong, $columnItem);
                            $totalForRow = $this->addMatchCountToTotal($totalForRow, $columnItem);
                        }
                        if ($columnItem['season_year'] == 'Games Other Leagues') {
                            $totalForRow = $this->addMatchCountToTotal($totalForRow, $columnItem);
                        }
                    }
                }
            }
            //Add on final column for totals for the row
            $resultOutputArray[$currentResultArrayRow][Report8::COL_TOTAL_GEELONG] = $totalGeelong;
            $resultOutputArray[$currentResultArrayRow][Report8::COL_TOTAL_OVERALL] = $totalForRow;
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }

    private function addMatchCountToTotal($totalValue, $columnItem) {
        return $totalValue + $columnItem['match_count'];

    }

    private function addColumnHeadingsForTotals($resultOutputArray, $currentResultArrayRow, $columnNumber) {
        //TODO: Update this logic to remove the specific year numbers, and the hardcoding of column 6 and 8
        if ($columnNumber == Report8::COL_TOTAL_GEELONG) {
            //Add extra column for report 8, after column 5 (array index 5 which is column 6).
            //Column heading is called Total Geelong, the heading does not come from column data.
            $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Total Geelong';
        }
        if ($columnNumber == Report8::COL_TOTAL_OVERALL) {
            $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Total Overall';
        }
        return $resultOutputArray;
    }

    private function seasonYearNeedsTotal($columnItem) {
        $validSeasonArray = array('Games Prior', '2015', '2016', '2017', '2018', '2019');
        return (in_array($columnItem['season_year'], $validSeasonArray));
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
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }
    
}

<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report6 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report6_data.sql";
        return $this->constructReportQuery($sqlFilename, $pReportInstance);
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report6_columns.sql";
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
                    if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                    }
                }
            }
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }
    


    public function pivotQueryArray($pResultArray, Report_cell_collection $pRowLabelFields, Report_cell_collection $pColumnLabelFields) {
        $pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        //$pFieldForRowLabel = $pRowLabelFields->getReportCellArray();
        //$pFieldsForColumnLabel = $pColumnLabelFields->getReportCellArray();

        $matchCountCell = new Report_cell();
        $matchCountCell->setCellValue("match_count");
        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRow($counterForRow, $resultRow, $pRowLabelFields, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pRowLabelFields->getReportCellArray()[0]->getCellValue()];
            foreach ($pColumnLabelFields->getReportCellArray() as $singleColumnCell) {
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pRowLabelFields, $counterForRow, $singleColumnCell);
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pRowLabelFields, $counterForRow, $matchCountCell);
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }
    
}

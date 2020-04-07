<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report5 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report5_data.sql";
        return $this->constructReportQuery($sqlFilename, $pReportInstance);
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report5_columns.sql";
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


    public function pivotQueryArray($pResultArray, Report_cell_collection $mainReportCellCollection) {
        $pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";

        $shortLeagueNameCell = new Report_cell();
        $shortLeagueNameCell->setCellValue("short_league_name");

        $ageGroupCell = new Report_cell();
        $ageGroupCell->setCellValue("age_group");

        $umpireTypeCell = new Report_cell();
        $umpireTypeCell->setCellValue("umpire_type");

        $matchNoUmpCell = new Report_cell();
        $matchNoUmpCell->setCellValue("match_no_ump");

        $totalMatchCountCell = new Report_cell();
        $totalMatchCountCell->setCellValue("total_match_count");

        $matchPctCell = new Report_cell();
        $matchPctCell->setCellValue("match_pct");

        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRow($counterForRow, $resultRow, $mainReportCellCollection, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$mainReportCellCollection->getRowLabelFields()[0]->getCellValue()];
            //Check if there is a second row that is being grouped.
            //This should only exist for report 5, according to the report_grouping_structure table
            if (array_key_exists(1, $mainReportCellCollection->getRowLabelFields())) {
                $previousRowLabel[1] = $resultRow[$mainReportCellCollection->getRowLabelFields()[1]->getCellValue()];
            }
            foreach ($mainReportCellCollection->getColumnLabelFields() as $singleColumnCell) {
                $rowArrayKey = $resultRow[$mainReportCellCollection->getRowLabelFields()[0]->getCellValue()] . " " . $resultRow[$mainReportCellCollection->getRowLabelFields()[1]->getCellValue()];
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $shortLeagueNameCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $ageGroupCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $umpireTypeCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $matchNoUmpCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $totalMatchCountCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $matchPctCell);
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }


    private function setPivotedArrayNamedValue(&$pPivotedArray, $pRowArrayKey, $pCounterForRow, $pResultRow, Report_cell $pKeyCell) {
        $pPivotedArray[
            $pRowArrayKey
        ][
            $pCounterForRow
        ][
            $pKeyCell->getCellValue()
        ] = $pResultRow[
            $pKeyCell->getCellValue()
        ];
    }

    
}

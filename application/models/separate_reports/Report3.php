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

    //Old function?
    public function transformQueryResultsIntoOutputArray(Report_cell_collection $pReportCellCollection, $columnLabelResultArray, $pReportColumnFields) {
        $resultOutputArray = [];
        $currentResultArrayRow = 0;
        $pResultArray = $pReportCellCollection->getCollection();

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

    //New function
    public function transformReportCellCollectionIntoOutputArray(Report_cell_collection $pReportCellCollection, $columnLabelResultArray, $pReportColumnFields) {
        $resultOutputArray = [];
        $currentResultArrayRowNumber = 0;

        //Loop through each set of Report_cells. Each entry here is a single person or row of data.
        foreach ($pReportCellCollection->getReportCellArray() as $setOfReportCellsForOneRow) {
            $columnNumber = 0;

            //Add row labels to output array. Once for each row
            $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $setOfReportCellsForOneRow[0]->getCellValue();
            $columnNumber++;
            //Loop through each cell in this list

            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                foreach($setOfReportCellsForOneRow as $singleReportCell) {
                    //Match the column headings to the values in the array
                    if ($this->isFieldMatchingColumnReport3($singleReportCell, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $singleReportCell->getCellValue();
                    }


                    /*
                     if ($this->isFieldMatchingColumn($singleReportCell, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $singleReportCell->getCellValue();
                    }
                    */

                }
                $columnNumber++;
            }
            $currentResultArrayRowNumber++;
        }
        return $resultOutputArray;
    }

    


    private function isFieldMatchingTwoColumnsWithTotal($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        //return ($pColumnItem->getColumnHeaderValueFirst() == $pColumnHeadingSet[$pReportColumnFields[0]] &&
        //    $pColumnHeadingSet[$pReportColumnFields[1]] == 'Total');
        return ($pColumnItem->getColumnHeaderValueFirst() == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnItem->getColumnHeaderValueSecond() == 'Total');
    }

    public function isFieldMatchingColumnReport3($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        //TODO: Write a test to ensure that the report grouping structure only returns 2 columns for this report,
        //and the right number of rows for other reports.
        if ($pColumnHeadingSet[$pReportColumnFields[1]] == 'Total') {
            return $this->isFieldMatchingTwoColumnsWithTotal($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
        } else {
            return $this->isFieldMatchingTwoColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
        }
    }


    public function pivotQueryArray($pResultArray, Report_display_options $pReportDisplayOptions) {
        //$pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        $mainReportCellCollection = new Report_cell_collection();

        $columnNameForDataValues = "match_count";
        $columnNameForTeamList = "team_list";

        /*$matchCountCell = new Report_cell();
        $matchCountCell->setCellValue("match_count");

        $teamListCell = new Report_cell();
        $teamListCell->setCellValue("team_list");*/


        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRowUsingDisplayOptions($counterForRow, $resultRow, $pReportDisplayOptions, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()];
            $currentRowLabelFieldName = $pReportDisplayOptions->getRowGroup()[0]->getFieldName();
            foreach ($pReportDisplayOptions->getColumnGroup() as $singleReportGroupingStructure) {
                /*$this->setPivotedArrayValue($pivotedArray, $resultRow, $mainReportCellCollection, $counterForRow, $singleColumnCell);
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $mainReportCellCollection, $counterForRow, $matchCountCell);
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $mainReportCellCollection, $counterForRow, $teamListCell);
                */

                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $singleReportGroupingStructure->getFieldName(), $currentRowLabelFieldName);
                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $columnNameForDataValues, $currentRowLabelFieldName);
                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $columnNameForTeamList, $currentRowLabelFieldName);

            }
            $counterForRow++;
        }
        //return $mainReportCellCollection->getCollection();
        return $mainReportCellCollection;
    }

    
    
}

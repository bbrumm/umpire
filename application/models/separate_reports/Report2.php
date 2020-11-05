<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report2 extends Parent_report implements IReport {

    public $reportDataQueryFilename = "report2_data.sql";
    public $reportColumnQueryFilename = "report2_columns.sql";


    public function __construct() {
        $this->load->model('separate_reports/Report_query_builder');
        $this->load->model('separate_reports/Field_column_matcher');
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

    /*
    public function transformQueryResultsIntoOutputArray(Report_cell_collection $pReportCellCollection, $columnLabelResultArray, $pReportColumnFields) {
        $resultOutputArray = [];
        //$countItemsInColumnHeadingSet = count($columnLabelResultArray[0]);
        $currentResultArrayRow = 0;
        $pResultArray = $pReportCellCollection->getCollection();
        $fieldColumnMatcher = new Field_column_matcher();

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
                    if ($fieldColumnMatcher->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
                        if($this->isShortLeagueNameSetTo2Umpires($columnHeadingSet)) {
                            //Set the "2 Umpires" match count to the total so far of rows marked as two_ump_flag=1
                            //$twoUmpGamesForRow = $twoUmpGamesForRow + $columnItem['match_count'];
                            $twoUmpGamesForRow = $twoUmpGamesForRow + $columnItem->getCellValue();
                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $twoUmpGamesForRow;
                        } else {
                            $totalForRow = $totalForRow + $columnItem->getCellValue();
                        }
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem->getCellValue();
                    }
                }
            }
            //Add on final column for totals for the row
            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $totalForRow;
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }
    */

    /*
    private function isShortLeagueNameSetTo2Umpires($pColumnHeadingSet) {
        if ($pColumnHeadingSet['short_league_name'] == '2 Umpires') {
            return true;
        } else {
            return false;
        }
    }
*/
/*
    public function pivotQueryArray($pResultArray, Report_display_options $pReportDisplayOptions) {
        //$pivotedArray = array();
	    $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        $mainReportCellCollection = new Report_cell_collection();

        //$matchCountCell = new Report_cell();
        //$matchCountCell->setCellValue("match_count");
        $columnNameForDataValues = "match_count";

        //$shortLeagueNameCell = new Report_cell();
        //$shortLeagueNameCell->setCellValue("short_league_name");
        $columnNameForShortLeagueName = "short_league_name";

        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRowUsingDisplayOptions($counterForRow, $resultRow, $pReportDisplayOptions, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()];
            $currentRowLabelFieldName = $pReportDisplayOptions->getRowGroup()[0]->getFieldName();

            foreach ($pReportDisplayOptions->getColumnGroup() as $singleReportGroupingStructure) {
                //$this->setPivotedArrayValue($pivotedArray, $resultRow, $mainReportCellCollection, $counterForRow, $singleColumnCell);
                //$this->setPivotedArrayValue($pivotedArray, $resultRow, $mainReportCellCollection, $counterForRow, $matchCountCell);

                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $singleReportGroupingStructure->getFieldName(), $currentRowLabelFieldName);
                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $columnNameForDataValues, $currentRowLabelFieldName);

                if ($resultRow['two_ump_flag'] == 1) {
                    //$this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "short_league_name", "2 Umpires");
                    //$this->setPivotedArrayValue($pivotedArray, $resultRow, $mainReportCellCollection, $counterForRow, $shortLeagueNameCell);

                    $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $columnNameForShortLeagueName, $currentRowLabelFieldName);
                }
            }
            $counterForRow++;
        }
        //return $mainReportCellCollection->getCollection();
        return $mainReportCellCollection;
    }
*/

    //TODO: This was copied from Parent_report so I should refactor this
    public function transformReportCellCollectionIntoOutputArray(Report_cell_collection $pReportCellCollection, $columnLabelResultArray, $pReportColumnFields) {
        $resultOutputArray = [];
        $currentResultArrayRowNumber = 0;
        $columnNumberForTotalValues = count($columnLabelResultArray);
        $fieldColumnMatcher = new Field_column_matcher();

        //Loop through each set of Report_cells. Each entry here is a single person or row of data.
        foreach ($pReportCellCollection->getReportCellArray() as $setOfReportCellsForOneRow) {
            $columnNumber = 0;
            $gamesTotalForRow = 0;

            //Set total to 0 as a starting value
            //$resultOutputArray[$currentResultArrayRowNumber][$columnNumberForTotalValues] = 0;

            //Add row labels to output array. Once for each row
            $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $setOfReportCellsForOneRow[0]->getCellValue();
            $columnNumber++;
            //Loop through each cell in this list

            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                foreach($setOfReportCellsForOneRow as $singleReportCell) {
                    //Match the column headings to the values in the array
                    if ($fieldColumnMatcher->isFieldMatchingColumn($singleReportCell, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $singleReportCell->getCellValue();
                        $gamesTotalForRow = $this->updateTotalForRow($gamesTotalForRow, $singleReportCell, $columnHeadingSet);

                    }

                }
                $columnNumber++;
            }

            //Add total value to output array
            $resultOutputArray[$currentResultArrayRowNumber][$columnNumberForTotalValues] = $gamesTotalForRow;

            $currentResultArrayRowNumber++;
        }
        return $resultOutputArray;
    }

    private function updateTotalForRow($pGamesTotalForRow, $singleReportCell, $columnHeadingSet) {
        //Don't add total if the current column is for 2 Umpires. These are to be excluded from the total.
        if ($columnHeadingSet['short_league_name'] != '2 Umpires') {
            $pGamesTotalForRow = $pGamesTotalForRow + $singleReportCell->getCellValue();
        }
        return $pGamesTotalForRow;
    }




}

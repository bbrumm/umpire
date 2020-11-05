<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report7 extends Parent_report implements IReport {

    public $reportDataQueryFilename = "report7_data.sql";
    public $reportColumnQueryFilename = "report7_columns.sql";

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
        $currentResultArrayRow = 0;
        $pResultArray = $pReportCellCollection->getCollection();
        $fieldColumnMatcher = new Field_column_matcher();

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
                    if ($fieldColumnMatcher->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {

                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                    }
                }
            }
            //Add on final column for report 2 and 5 and 8 for totals for the row
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }
    */


    public function pivotQueryArray($pResultArray, Report_display_options $pReportDisplayOptions) {
        //$pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        $mainReportCellCollection = new Report_cell_collection();
        $columnNameForDataValues = "match_count";

        //$matchCountCell = new Report_cell();
        //$matchCountCell->setCellValue("match_count");

        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRowUsingDisplayOptions($counterForRow, $resultRow, $pReportDisplayOptions, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()];
            $currentRowLabelFieldName = $pReportDisplayOptions->getRowGroup()[0]->getFieldName();
            foreach ($pReportDisplayOptions->getColumnGroup() as $singleReportGroupingStructure) {
                //$this->setPivotedArrayValue($pivotedArray, $resultRow, $mainReportCellCollection, $counterForRow, $singleColumnCell);
                //$this->setPivotedArrayValue($pivotedArray, $resultRow, $mainReportCellCollection, $counterForRow, $matchCountCell);

                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $singleReportGroupingStructure->getFieldName(), $currentRowLabelFieldName);
                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $columnNameForDataValues, $currentRowLabelFieldName);
            }
            $counterForRow++;
        }
        //return $mainReportCellCollection->getCollection();
        return $mainReportCellCollection;
    }


}

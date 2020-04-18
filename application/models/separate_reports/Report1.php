<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report1 extends Parent_report implements IReport {
    
    public function __construct() {
        $this->load->library('Debug_library');
    }
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report1_data.sql";
        return $this->constructReportQuery($sqlFilename, $pReportInstance);
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report1_columns.sql";
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



/*
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
                    //Match the column headings to the values in the array
                    if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
                        //$resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem->getCellValue();
                    }
                }
            }
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }
*/
/*
    public function pivotQueryArray($pResultArray, Report_display_options $pReportDisplayOptions) {
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        $mainReportCellCollection = new Report_cell_collection();

        $columnNameForDataValues = "match_count";

        //Loop through each row of results
        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRowUsingDisplayOptions($counterForRow, $resultRow, $pReportDisplayOptions, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()];
            $currentRowLabelFieldName = $pReportDisplayOptions->getRowGroup()[0]->getFieldName();
            //Can previousRowLabel be a text value not an array? Maybe eventually, but it's inherited and other reports have multiple row labels


            //$newReportCell = new Report_cell();
            //$newReportCell->setCellValue($resultRow[$currentRowLabelFieldName]);
            //$newReportCell->setSourceResultRow($resultRow);

            //$mainReportCellCollection->addReportCellToCollection()


            //Loop through each column
            foreach ($pReportDisplayOptions->getColumnGroup() as $singleReportGroupingStructure) {
                //TODO: Refactor this function call and internals

                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $singleReportGroupingStructure->getFieldName(), $currentRowLabelFieldName);
                $mainReportCellCollection->addCurrentRowToCollection($resultRow, $counterForRow, $columnNameForDataValues, $currentRowLabelFieldName);

                $mainReportCellCollection->addCurrentRowToCellCollection($resultRow, $counterForRow, $currentRowLabelFieldName);
                $mainReportCellCollection->addCurrentRowToCellCollection($resultRow, $counterForRow, $columnNameForDataValues);

            }
            $counterForRow++;
        }
        //return $mainReportCellCollection->getCollection();
        return $mainReportCellCollection;
    }

*/

}

<?php
//A collection of report cell objects.
//The collection represents the table that is written to the screen and displayed.
class Report_cell_collection extends CI_Model {

    public function __construct() {}

    private $reportCellArray;
    private $rowLabelFields; //TODO: These variables will need to be refactored, they are only temporary
    private $columnLabelFields; //As above
    private $pivotedArray;

    //TODO: Remove this function once the pivotedArray feature is no longer needed
    public function getPivotedArray() {
        return $this->pivotedArray;
    }

    public function setReportCellArray($pValue) {
        $this->reportCellArray = $pValue;
    }

    public function getReportCellArray() {
        return $this->reportCellArray;
    }

    public function setRowLabelFields($pValue) {
        $this->rowLabelFields = $pValue;
    }

    public function getRowLabelFields() {
        return $this->rowLabelFields;
    }

    public function setColumnLabelFields($pValue) {
        $this->columnLabelFields = $pValue;
    }

    public function getColumnLabelFields() {
        return $this->columnLabelFields;
    }

    public function addCurrentRowToCollection($pResultRow, $pCounterForRow, $pFieldName, $pRowLabelFieldName) {
        //Add to pivotedArray to allow existing code to work
         $this->pivotedArray[
            $pResultRow[
                $pRowLabelFieldName
            ]
        ][
            $pCounterForRow
        ][
            $pFieldName
        ] =
        $pResultRow[
            $pFieldName
        ];
    }

    public function addCurrentRowToCellCollection($pResultRow, $pCounterForRow, $pFieldName) {
         //Also add it to a new Report_cell for the future code to work
        $newReportCell = new Report_cell();
        $newReportCell->setCellValue($pResultRow[$pFieldName]);
        $newReportCell->setSourceResultRow($pResultRow);

        $this->reportCellArray[$pCounterForRow][] = $newReportCell;

    }

    public function addReportCellToCollection($pRowNumber, Report_cell $pReportCell) {
        $this->reportCellArray[$pRowNumber][] = $pReportCell;
    }

    public function addReportTotalCellToCollection($pRowNumber, Report_cell $pReportCell) {
        $this->reportCellArray[$pRowNumber]["rowTotal"] = $pReportCell;
    }

    public function updateTotalReportCell($pRowNumber, Report_cell $pReportCell) {
        if (!array_key_exists("rowTotal", $this->reportCellArray[$pRowNumber])) {
            $this->reportCellArray[$pRowNumber]["rowTotal"] = $pReportCell;
        } else {
            //Get the current reportCell in the total column
            $currentRowTotalReportCell = $this->reportCellArray[$pRowNumber]["rowTotal"];
            //Add the new total value to it
            $currentRowTotalReportCell->setCellValue($currentRowTotalReportCell->getCellValue() + $pReportCell->getCellValue());
            //Update the report cell to the array
            $this->reportCellArray[$pRowNumber]["rowTotal"] = $currentRowTotalReportCell;
        }
    }

    public function addCurrentRowToCollectionWithName($pResultRow, $pCounterForRow, $pFieldName, $pRowArrayKey) {
        $this->pivotedArray[
            $pRowArrayKey
        ][
            $pCounterForRow
        ][
            $pFieldName
        ] = $pResultRow[
            $pFieldName
        ];
    }

    public function getCollection() {
        //return $this->pivotedArray;
        return $this->reportCellArray;
    }

}
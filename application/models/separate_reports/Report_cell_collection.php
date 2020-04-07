<?php
//A collection of report cell objects.
//The collection represents the table that is written to the screen and displayed.
class Report_cell_collection extends CI_Model {

    public function __construct() {

    }

    private $reportCellArray;
    private $rowLabelFields; //TODO: These variables will need to be refactored, they are only temporary
    private $columnLabelFields; //As above
    private $pivotedArray;



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

    public function setPivotedArrayValue($pResultRow, $pCounterForRow, Report_cell $pivotArrayKeyCell) {

        $this->pivotedArray[
            $pResultRow[
                $this->getRowLabelFields()[0]->getCellValue()
            ]
        ][
            $pCounterForRow
        ][
            $pivotArrayKeyCell->getCellValue()
        ] = $pResultRow[
            $pivotArrayKeyCell->getCellValue()
        ];
    }

    public function getPivotedArray() {
        return $this->pivotedArray;
    }

}
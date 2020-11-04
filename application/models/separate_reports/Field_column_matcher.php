<?php
class Field_column_matcher extends CI_Model {

    public function __construct() {

    }

    public function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        switch (count($pReportColumnFields)) {
            case 1:
                return $this->isFieldMatchingOneColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
            case 2:
                return $this->isFieldMatchingTwoColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
            case 3:
                return $this->isFieldMatchingThreeColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
            default:
                throw new InvalidArgumentException("Count of report column fields needs to be between 1 and 3.");
        }
    }


    private function isFieldMatchingOneColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        //return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]]);
        return ($pColumnItem->getColumnHeaderValueFirst() == $pColumnHeadingSet[$pReportColumnFields[0]]);
    }

    //This is used by Report3
    //TODO: Clean up the link to Report3 so these function calls are consistent
    public function isFieldMatchingTwoColumns(Report_cell $pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        //$pColumnItem is now a Report_cell
        /*return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnItem[$pReportColumnFields[1]] == $pColumnHeadingSet[$pReportColumnFields[1]]);
        */

        return ($pColumnItem->getColumnHeaderValueFirst() == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnItem->getColumnHeaderValueSecond() == $pColumnHeadingSet[$pReportColumnFields[1]]);
    }

    private function isFieldMatchingThreeColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        return ($pColumnItem->getColumnHeaderValueFirst() == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnItem->getColumnHeaderValueSecond() == $pColumnHeadingSet[$pReportColumnFields[1]] &&
            $pColumnItem->getColumnHeaderValueThird() == $pColumnHeadingSet[$pReportColumnFields[2]]);
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
}
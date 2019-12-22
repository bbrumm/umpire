<?php

class Parent_report extends CI_Model {
    
    public function __construct() {
        $this->load->model('Cell_formatting_helper');
	$this->load->model('separate_reports/Report_array_output_formatter');
    }

    public function getReportDataQuery(Report_instance $pReportInstance) {}

    public function replaceRegionInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pRegion", $pReportInstance->filterParameterRegion->getFilterSQLValues(), $sqlQuery);
    }

    public function replaceLeagueInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pLeague", $pReportInstance->filterParameterLeague->getFilterSQLValues(), $sqlQuery);
    }

    public function replaceSeasonYearInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pSeasonYear", $pReportInstance->requestedReport->getSeason(), $sqlQuery);
    }

	

    /* Explanation:
     * - pColumnItem: An array that contains values from the report query that could go into a column.
     * Array
        (
            [season_year] => 2017
            [match_count] => 25
            [total_match_count] => 174
        )
     * - $this->getReportColumnFields(): Returns an array that contains the fields from the results to use as columns:
     * Array
        (
            [0] => season_year
            [1] => total_match_count
        )
     * - pColumnHeadingSet: Array that contains... the column names and values that apply to this row??
     * Array
        (
            [season_year] => 2015
        )
     *
     *
     */
    //Add common methods here which the subclasses can use
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
        return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]]);
    }

	//This is used by Report3
	//TODO: Clean up the link to Report3 so these function calls are consistent
    public function isFieldMatchingTwoColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnItem[$pReportColumnFields[1]] == $pColumnHeadingSet[$pReportColumnFields[1]]);
    }

    private function isFieldMatchingThreeColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnItem[$pReportColumnFields[1]] == $pColumnHeadingSet[$pReportColumnFields[1]] &&
            $pColumnItem[$pReportColumnFields[2]] == $pColumnHeadingSet[$pReportColumnFields[2]]);
    }


    public function formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells) {
	$outputFormatter = new Report_array_output_formatter();
	return $outputFormatter->formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells);

    }
	
	
	/*
        *IMPORTANT: If the SQL query DOES NOT order by the row labels (e.g. the umpire name),
        *then this loop structure will cause all values to be set to the last column,
        *and show incorrect data in the report.
        *If this happens, ensure the SELECT query inside the Report_data_query object for this report (e.g. Report8.php)
        *orders by the correct column
        *
        */
    public function resetCounterForRow($pCurrentCounterForRow, $pResultRow, $pFieldForRowLabel, $pPreviousRowLabel) {
        if ($pResultRow[$pFieldForRowLabel[0]] != $pPreviousRowLabel[0]) {
            //New row label, so reset counter
            return 0;
        } elseif (array_key_exists(1, $pFieldForRowLabel)) {
            if ($pResultRow[$pFieldForRowLabel[1]] != $pPreviousRowLabel[1]) {
                //New row label, so reset counter
                return 0;
            }
        } else {
            return $pCurrentCounterForRow;
        }
    }

    //Uses an & character to pass by reference, because pivotedArray should be updated on each call
    public function setPivotedArrayValue(&$pPivotedArray,
                                         $pResultRow, $pFieldForRowLabel,
                                         $pCounterForRow, $pivotArrayKeyName, $resultKeyName) {
        $pPivotedArray[$pResultRow[$pFieldForRowLabel[0]]][$pCounterForRow][$pivotArrayKeyName] = $pResultRow[$resultKeyName];
    }
}

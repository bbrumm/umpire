<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report1 extends Parent_report implements IReport {

    //private $debugLibrary;
    
    public function __construct() {
        $this->load->library('Debug_library');
    }
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report1_data.sql";
        $sqlQuery = file_get_contents(SQL_REPORT_FILE_PATH . $sqlFilename);
        $sqlQuery = $this->replaceRegionInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceLeagueInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceAgeGroupInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceUmpireTypeInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceSeasonYearInQueryString($sqlQuery, $pReportInstance);

        return $sqlQuery;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $sqlFilename = "report1_columns.sql";
        $sqlQuery = file_get_contents(SQL_REPORT_FILE_PATH . $sqlFilename);
        $sqlQuery = $this->replaceRegionInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceLeagueInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceAgeGroupInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceUmpireTypeInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceSeasonYearInQueryString($sqlQuery, $pReportInstance);

        return $sqlQuery;
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

    public function pivotQueryArray($pResultArray, array $pFieldForRowLabel, array $pFieldsForColumnLabel) {
        $pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRow($counterForRow, $resultRow, $pFieldForRowLabel, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pFieldForRowLabel[0]];
            foreach ($pFieldsForColumnLabel as $columnField) {
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, $columnField, $columnField);
                $this->setPivotedArrayValue($pivotedArray, $resultRow, $pFieldForRowLabel, $counterForRow, "match_count", "match_count");
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }
    
}

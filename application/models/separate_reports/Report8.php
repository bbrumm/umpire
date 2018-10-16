<?php
require_once 'IReport.php';

class Report8 extends Parent_report implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "season_year, ".
            "full_name, ".
            "match_count ".
            "FROM dw_mv_report_08 ".
            "ORDER BY full_name, season_year;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT column_heading AS season_year ".
            "FROM report_column_display_order ".
            "WHERE report_id = 8 ".
            "ORDER BY display_order;";
        return $queryString;
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
            $totalGeelong = 0;
            $totalForRow = 0;
            $resultOutputArray[$currentResultArrayRow][0] = $rowKey;
            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                $columnNumber++;
                //Loops through each value of $columnLabelResultArray.
                //This comes from the results found in the separate_reports.ReportX.getReportColumnQuery() function.
                //E.g. if Report 8's column query returns 4 rows, then this columnHeadingSet has 4 records in it
                foreach ($currentRowItem as $columnKey => $columnItem) { //Maps to a single match_count, not necessarily a column
                    //Loop through each row and column intersection in the result array
                    if ($columnNumber == 6) {
                        //Add extra column for report 8, after column 5 (array index 5 which is column 6).
                        //Column heading is called Total Geelong, the heading does not come from column data.
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Total Geelong';
                    }
                    if ($columnNumber == 8) {
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Total Overall';
                    }
                    
                    //Match the column headings to the values in the array
                    if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                            //TODO: Update this logic to remove the specific year numbers, and the hardcoding of column 6 and 8
                            if ($columnItem['season_year'] == 'Games Prior' ||
                                $columnItem['season_year'] == '2015' ||
                                $columnItem['season_year'] == '2016' ||
                                $columnItem['season_year'] == '2017' ||
                                $columnItem['season_year'] == '2018') {
                                $totalGeelong = $totalGeelong + $columnItem['match_count'];
                                $totalForRow = $totalForRow+ $columnItem['match_count'];
                            }
                            if ($columnItem['season_year'] == 'Games Other Leagues') {
                                $totalForRow = $totalForRow+ $columnItem['match_count'];
                            }
                    }
                }
            }
            //Add on final column for totals for the row
            $resultOutputArray[$currentResultArrayRow][6] = $totalGeelong;
            $resultOutputArray[$currentResultArrayRow][8] = $totalForRow;
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
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
    public function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        switch (count($pReportColumnFields)) {
            case 1:
                    if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet['season_year']) {
                        return true;
                    } else {
                        return false;
                    }
                break;
            case 2:

                if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]]) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 3:
                if ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$this->$pReportColumnFields[0]] &&
                    $pColumnItem[$pReportColumnFields[1]] == $pColumnHeadingSet[$this->$pReportColumnFields[1]] &&
                    $pColumnItem[$pReportColumnFields[2]] == $pColumnHeadingSet[$this->$pReportColumnFields[2]]) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }
    
    
    public function formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells) {
        $outputArray = [];
        //Set up header
        $countItemsInColumnHeadingSet = count($pLoadedColumnGroupings[0]);
        $columnCountForHeadingCells = $pColumnCountForHeadingCells;
        $columnLabels = $pReportDisplayOptions->getColumnGroup();
        $thOutput = "<thead>";
        for ($i=0; $i < $countItemsInColumnHeadingSet; $i++) {
            $thOutput .= "<tr class='header'>";
            $thClassNameToUse = "";
            if ($pReportDisplayOptions->getFirstColumnFormat() == "text") {
                $thClassNameToUse = "columnHeadingNormal cellNameSize";
            } elseif ($pReportDisplayOptions->getFirstColumnFormat() == "date") {
                $thClassNameToUse = "columnHeadingNormal cellDateSize";
            } else {
                $thClassNameToUse = "columnHeadingNormal cellNameSize";
            }
            $arrReportRowGroup = $pReportDisplayOptions->getRowGroup(); //Array of ReportGroupingStructure objects
            $arrReportColumnGroup = $pReportDisplayOptions->getColumnGroup();
            for ($r = 0; $r < count($arrReportRowGroup); $r++) {
                $thOutput .= "<th class='" . $thClassNameToUse . "'>";
                if ($i == 0) {
                    $thOutput .= $arrReportRowGroup[$r]->getGroupHeading();
                    $thOutput .= "<BR /><span class='columnSizeText'>" . $arrReportRowGroup[$r]->getGroupSizeText() . "</span>";
                }
                $thOutput .= "</th>";
            }
            $countLoadedColumnGroupings = count($columnCountForHeadingCells[$i]);
            for ($j = 0; $j < $countLoadedColumnGroupings; $j++) {
                //Check if cell should be merged
                if ($j == 0) {
                    if ($columnLabels[$i]->getFieldName() == 'club_name') {
                        //Some reports have their headers displayed differently
                        $colspanForCell = 1;
                        $cellClass = "rotated_cell";
                        $divClass = "rotated_text";
                    } else {
                        //Increase the colspan if this column group is to be merged
                        if ($arrReportColumnGroup[$i]->getMergeField() == 1) {
                            $colspanForCell = $columnCountForHeadingCells[$i][$j]["count"];
                        } else {
                            $colspanForCell = 1;
                        }
                        $cellClass = "columnHeadingNormal";
                        $divClass = "normalHeadingText";
                    }
                    $thOutput .= "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>" . $columnCountForHeadingCells[$i][$j]["label"] . "</div></th>";
                }
            }
        }
        $thOutput .= "</thead>";
        $outputArray[0] = $thOutput;
        //Set up table body
        $countRows = count($pResultOutputArray);
        $countColumns = count($pLoadedColumnGroupings);
        $cellClassToUse = "";
        for ($rowCounter=0; $rowCounter < $countRows; $rowCounter++) {
            $tableRowOutput = "<tr class='altRow'>";
            for ($columnCounter=0; $columnCounter <= $countColumns; $columnCounter++) {
                if(array_key_exists($columnCounter, $pResultOutputArray[$rowCounter])) {
                    if ($columnCounter == 0) { //First column
                        if ($pReportDisplayOptions->getFirstColumnFormat() == "text") {
                            $cellValue = $pResultOutputArray[$rowCounter][$columnCounter];
                            $cellClassToUse = "cellText cellNormal";
                        } elseif ($pReportDisplayOptions->getFirstColumnFormat() == "date") {
                            $weekDate = date_create($pResultOutputArray[$rowCounter][$columnCounter]);
                            $cellValue = date_format($weekDate, 'd/m/Y');
                            $cellClassToUse = "cellNumber cellNormal";
                        }
                    } else {
                        if ($pReportDisplayOptions->getColourCells() == 1) {
                            $cellClassToUse = getCellClassNameFromOutputValue($pResultOutputArray[$rowCounter][$columnCounter], TRUE);
                        } elseif(is_numeric($pResultOutputArray[$rowCounter][$columnCounter])) {
                            $cellClassToUse = "cellNumber cellNormal";
                        } else {
                            $cellClassToUse = "cellText cellNormal";
                        }
                        $cellValue = $pResultOutputArray[$rowCounter][$columnCounter];
                    }
                } else {
                    $cellClassToUse = "cellNormal";
                    $cellValue = "";
                }
                $tableRowOutput .= "<td class='$cellClassToUse'>$cellValue</td>";
            }
            $tableRowOutput .=  "</tr>";
            $outputArray[$rowCounter+1] = $tableRowOutput;
        }
        return $outputArray;
    }
    public function pivotQueryArray($pResultArray, array $pFieldForRowLabel, array $pFieldsForColumnLabel) {
        $pivotedArray = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        foreach ($pResultArray as $resultRow) {
            /*
             *IMPORTANT: If the SQL query DOES NOT order by the row labels (e.g. the umpire name),
             *then this loop structure will cause all values to be set to the last column,
             *and show incorrect data in the report.
             *If this happens, ensure the SELECT query inside the Report_data_query object for this report (e.g. Report8.php)
             *orders by the correct column
             *
             */
            if ($resultRow[$pFieldForRowLabel[0]] != $previousRowLabel[0]) {
                //New row label, so reset counter
                $counterForRow = 0;
            } elseif (array_key_exists(1, $pFieldForRowLabel)) {
                if ($resultRow[$pFieldForRowLabel[1]] != $previousRowLabel[1]) {
                    //New row label, so reset counter
                    $counterForRow = 0;
                }
            }
            $previousRowLabel[0] = $resultRow[$pFieldForRowLabel[0]];
            if (array_key_exists(1, $pFieldForRowLabel)) {
                $previousRowLabel[1] = $resultRow[$pFieldForRowLabel[1]];
            }
            foreach ($pFieldsForColumnLabel as $columnField) {
                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField] = $resultRow[$columnField];
                $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
            }
            $counterForRow++;
        }
        return $pivotedArray;
    }
    
}

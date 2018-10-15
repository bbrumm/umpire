<?php
require_once 'IReport.php';

class Report5 extends CI_Model implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT umpire_type, ".
            "age_group, ".
            "short_league_name, ".
            "match_no_ump, ".
            "total_match_count, ".
            "match_pct ".
            "FROM dw_mv_report_05 ".
            "WHERE short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "ORDER BY umpire_type, age_sort_order, league_sort_order;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT ".
            "l.short_league_name, ".
            "sub.subtotal ".
            "FROM dw_dim_league l ".
            "CROSS JOIN ( ".
                "SELECT 'Games' AS subtotal ".
                "UNION ".
                "SELECT 'Total' ".
                "UNION ".
                "SELECT 'Pct' ".
            ") AS sub ".
            "WHERE l.short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "UNION ALL ".
            "SELECT 'All', 'Total';";
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
        //$countItemsInColumnHeadingSet = count($columnLabelResultArray[0]);
        $currentResultArrayRow = 0;
        foreach ($pResultArray as $rowKey => $currentRowItem) { //Maps to a single row of output
            $columnNumber = 0;
            //$totalGeelong = 0;
            //$totalForRow = 0;
            //$twoUmpGamesForRow = 0;
            if ($this->requestedReport->getReportNumber() == 5) {
                $resultOutputArray[$currentResultArrayRow][0] = $currentRowItem[0]['umpire_type'];
            } else {
                $resultOutputArray[$currentResultArrayRow][0] = $rowKey;
            //}
            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                $columnNumber++;
                //Loops through each value of $columnLabelResultArray.
                //This comes from the results found in the separate_reports.ReportX.getReportColumnQuery() function.
                //E.g. if Report 8's column query returns 4 rows, then this columnHeadingSet has 4 records in it
                foreach ($currentRowItem as $columnKey => $columnItem) { //Maps to a single match_count, not necessarily a column
                    //Loop through each row and column intersection in the result array
                    
                    if ($columnNumber == 1 && $this->requestedReport->getReportNumber() == 5) {
                        //Add extra column for report 5
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['age_group'];
                        $columnNumber++;
                    }
                    
                    /*
                    if ($this->requestedReport->getReportNumber() == 8) {
                        if ($columnNumber == 6) {
                            //Add extra column for report 8, after column 5 (array index 5 which is column 6).
                            //Column heading is called Total Geelong, the heading does not come from column data.
                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Total Geelong';
                        }
                        if ($columnNumber == 8) {
                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = 'Total Overall';
                        }
                    }
                    */
                    //Match the column headings to the values in the array
                    if ($this->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
                        /*
                        if($this->requestedReport->getReportNumber() == 2) {
                            //Add up total values for report 2, only if the "short_league_name" equivalent value is not "2 Umpires"
                            if ($columnHeadingSet['short_league_name'] != '2 Umpires') {
                                $totalForRow = $totalForRow + $columnItem['match_count'];
                            }
                            //Set the "2 Umpires" match count to the total so far of rows marked as two_ump_flag=1
                            if ($columnHeadingSet['short_league_name'] == '2 Umpires') {
                                $twoUmpGamesForRow = $twoUmpGamesForRow + $columnItem['match_count'];
                                $this->debug_library->debugOutput("twoUmpGamesForRow:", $twoUmpGamesForRow);
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $twoUmpGamesForRow;
                            }
                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                        } elseif($this->requestedReport->getReportNumber() == 3) {
                            if ($columnHeadingSet['short_league_name'] == 'Total') {
                                //Output the Total column values for report 3
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                            } else {
                                //Output the team list value for non-total columns
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['team_list'];
                            }
                        } else
                        */
                        if ($this->requestedReport->getReportNumber() == 5) {
                            //TODO: Clean this code up
                            if ($columnHeadingSet['subtotal'] == 'Games') {
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_no_ump'];
                                $totalForRow = $totalForRow + $columnItem['match_no_ump'];
                            } elseif ($columnHeadingSet['subtotal'] == 'Total') {
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['total_match_count'];
                            } elseif ($columnHeadingSet['subtotal'] == 'Pct') {
                                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_pct'];
                            }
                            /*
                        } elseif ($this->requestedReport->getReportNumber() == 8) {
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
                            //$resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['total_match_count'];
                            //$totalForRow = $columnItem['total_match_count'];
                        } else {
                        
                            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['match_count'];
                            */
                        }
                    } //end isFieldMatchingColumn
                }
            }
            //Add on final column for report 2 and 5 and 8 for totals for the row
            /*
            if ($this->requestedReport->getReportNumber() == 2 ||
                $this->requestedReport->getReportNumber() == 5) {
                $resultOutputArray[$currentResultArrayRow][$columnNumber] = $totalForRow;
            }
            if ($this->requestedReport->getReportNumber() == 8) {
                //$this->debug_library->debugOutput("columnitem array:", $columnItem);
                $resultOutputArray[$currentResultArrayRow][6] = $totalGeelong;
                $resultOutputArray[$currentResultArrayRow][8] = $totalForRow;
            }
            */
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
        //$this->debug_library->debugOutput("getReportColumnFields count:", count($this->getReportColumnFields()));
        switch (count($pReportColumnFields)) {
            case 1:
                /*
                if($this->getReportTitle() == 8) {
                    if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet['season_year']) {
                        return true;
                    } else {
                        return false;
                    }
                } elseif ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]]) {
                */
                if ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]]) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 2:
                /*if ($this->requestedReport->getReportNumber() == 5) {
                    if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]]) {
                        return true;
                    } else {
                        return false;
                    }
                } elseif ($this->requestedReport->getReportNumber() == 8) {
                    if ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]]) {
                        return true;
                    } else {
                        return false;
                    }
                } elseif ($pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]] &&
                */
                if ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]] &&
                    $pColumnItem[$pReportColumnFields[1]] == $pColumnHeadingSet[$pReportColumnFields[1]]) {
                    return true;
                    /*
                } elseif ($this->requestedReport->getReportNumber() == 3 &&
                    $pColumnItem[$this->getReportColumnFields()[0]] == $pColumnHeadingSet[$this->getReportColumnFields()[0]] &&
                    $pColumnHeadingSet[$this->getReportColumnFields()[1]] == 'Total') {
                    return true;
                    */
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
            //$this->debug_library->debugOutput("arrReportRowGroup count:", count($arrReportRowGroup));
            $arrReportColumnGroup = $pReportDisplayOptions->getColumnGroup();
            for ($r = 0; $r < count($arrReportRowGroup); $r++) {
                $thOutput .= "<th class='" . $thClassNameToUse . "'>";
                if ($i == 0) {
                    $thOutput .= $arrReportRowGroup[$r]->getGroupHeading();
                    $thOutput .= "<BR /><span class='columnSizeText'>" . $arrReportRowGroup[$r]->getGroupSizeText() . "</span>";
                }
                $thOutput .= "</th>";
                //echo $thOutput;
            }
            $countLoadedColumnGroupings = count($columnCountForHeadingCells[$i]);
            /*if ($debugMode) {
                echo "<BR />countLoadedColumnGroupings DW:" . $countLoadedColumnGroupings;
            }*/
            for ($j = 0; $j < $countLoadedColumnGroupings; $j++) {
                //Check if cell should be merged
                if ($j == 0) {
                    $proceed = true;
                }
                if ($proceed) {
                    //print cell with colspan value
                    //if ($debugMode) {
                    //echo $columnLabels[$i]->getFieldName() . "rotate cell 1<BR />";
                    //}
                    //if ($columnLabels[$i]->getFieldName() == 'club_name' || $reportID == 6) {
                    if ($columnLabels[$i]->getFieldName() == 'club_name') {
                        //Some reports have their headers displayed differently
                        $colspanForCell = 1;
                        /*
                        if ($PDFLayout) {
                            $cellClass = "rotated_cell_pdf";
                            $divClass = "rotated_text_pdf";
                        } else {
                        */
                        //if ($debugMode) {
                        //    echo "<BR />rotate cell YES<BR />";
                        //}
                        $cellClass = "rotated_cell";
                        $divClass = "rotated_text";
                        //}
                    } else {
                        //if ($debugMode) {
                        //echo "dont rotate cell 2<BR />";
                        //}
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
        /*
        if ($reportID == 5) {
            //TODO: Fix bug where report 5 is not getting the right number of columns.
            //This happens because the COUNT here is only looking at data columns, not the row label columns,
            //and in report 5, there are two of them.
            $countColumns = count($loadedColumnGroupings) + 1;
        } else {
        */
            $countColumns = count($pLoadedColumnGroupings);
        //}
        /*
        if ($debugMode) {
            echo "<BR />loadedColumnGroupings DW:<pre>";
            print_r($loadedColumnGroupings);
            echo "</pre><BR />";
        }
        */
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
                        //} elseif ($reportID == 2 && $loadedColumnGroupings[$columnCounter-1]['age_group'] == 'Total') {
                        //    $cellClassToUse = "cellNumber cellTextTotal";
                        } elseif(is_numeric($pResultOutputArray[$rowCounter][$columnCounter])) {
                            $cellClassToUse = "cellNumber cellNormal";
                        } else {
                            $cellClassToUse = "cellText cellNormal";
                        }
                        $cellValue = $pResultOutputArray[$rowCounter][$columnCounter];
                        //TODO: Fix this and find the correct array reference
                        /*
                        if ($reportID == 5) {
                            if ($columnCounter >= 2) {
                                if ($loadedColumnGroupings[$columnCounter-2]["subtotal"] == "Pct") {
                                    $cellValue .= "%";
                                }
                            }
                        }
                        */
                    }
                } else {
                    $cellClassToUse = "cellNormal";
                    $cellValue = "";
                }
                /*
                if ($reportID == 6 && $columnCounter > 0) {
                    if ($resultOutputArray[$rowCounter][0] == $loadedColumnGroupings[$columnCounter-1]["second_umpire"]) {
                        //echo "ROW resultOutputArray $rowCounter matches COL loadedColumnGroupings $columnCounter <BR />";
                        $cellClassToUse .= " cellRowMatchesColumn";
                    }
                }
                */
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
                /*
                if ($this->requestedReport->getReportNumber() == 5) {
                    $rowArrayKey = $resultRow[$pFieldForRowLabel[0]] . " " . $resultRow[$pFieldForRowLabel[1]];
                    //$this->debug_library->debugOutput("pFieldForRowLabel:",  $pFieldForRowLabel);
                    $pivotedArray[$rowArrayKey][$counterForRow]['short_league_name'] = $resultRow['short_league_name'];
                    $pivotedArray[$rowArrayKey][$counterForRow]['age_group'] = $resultRow['age_group'];
                    $pivotedArray[$rowArrayKey][$counterForRow]['umpire_type'] = $resultRow['umpire_type'];
                    $pivotedArray[$rowArrayKey][$counterForRow]['match_no_ump'] = $resultRow['match_no_ump'];
                    $pivotedArray[$rowArrayKey][$counterForRow]['total_match_count'] = $resultRow['total_match_count'];
                    $pivotedArray[$rowArrayKey][$counterForRow]['match_pct'] = $resultRow['match_pct'];
                } elseif ($this->requestedReport->getReportNumber() == 8) {
                    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField] = $resultRow[$columnField];
                    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
                } elseif ($this->requestedReport->getReportNumber() == 2) {
                    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField] = $resultRow[$columnField];
                    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
                    if ($resultRow['two_ump_flag'] == 1) {
                        $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['short_league_name'] = '2 Umpires';
                    }
                } else {
                */
                    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow][$columnField] = $resultRow[$columnField];
                    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['match_count'] = $resultRow['match_count'];
                /*}
                if ($this->requestedReport->getReportNumber() == 3) {
                    $pivotedArray[$resultRow[$pFieldForRowLabel[0]]][$counterForRow]['team_list'] = $resultRow['team_list'];
                }
                */
            }
            $counterForRow++;
        }
        //$this->debug_library->debugOutput("pivotedArray:", $pivotedArray);
        return $pivotedArray;
    }
    
}

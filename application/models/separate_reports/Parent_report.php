<?php

class Parent_report extends CI_Model {
    
    public function __construct() {
        $this->load->helper('Cell_formatting_helper');
    }

    public function getReportDataQuery(Report_instance $pReportInstance) {}


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


    public function isFieldMatchingOneColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]]);
    }

    public function isFieldMatchingTwoColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnItem[$pReportColumnFields[1]] == $pColumnHeadingSet[$pReportColumnFields[1]]);
    }

    public function isFieldMatchingThreeColumns($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {
        return ($pColumnItem[$pReportColumnFields[0]] == $pColumnHeadingSet[$pReportColumnFields[0]] &&
            $pColumnItem[$pReportColumnFields[1]] == $pColumnHeadingSet[$pReportColumnFields[1]] &&
            $pColumnItem[$pReportColumnFields[2]] == $pColumnHeadingSet[$pReportColumnFields[2]]);
    }


    public function formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells) {

        //echo "formatOutputArrayForView <BR />";

        $outputArray = array();
        //Set up header
        $countItemsInColumnHeadingSet = count($pLoadedColumnGroupings[0]);
        $columnCountForHeadingCells = $pColumnCountForHeadingCells;
        $thOutput = "<thead>";
        $outputRowNumber = 0;
        //First add heading cells
        for ($i = 0; $i < $countItemsInColumnHeadingSet; $i++) {
            $thOutput .= "<tr class='header'>";

            $thClassNameToUse = $this->determineClassNameToUse($pReportDisplayOptions);
            $thOutput .= $this->addTableHeaderCellsToOutput($pReportDisplayOptions, $thClassNameToUse, $i);
            $thOutput .= $this->addTableHeaderMergedCells($columnCountForHeadingCells, $pReportDisplayOptions, $i);

        }

        $thOutput .= "</thead>";
        $outputArray[0] = $thOutput;
        //echo "outputArray: " . $outputArray[$outputRowNumber] . "<BR />";
        $outputRowNumber++;
        //Set up table body
        $countRows = count($pResultOutputArray);

        //Then add data cells
        for ($rowCounter = 0; $rowCounter < $countRows; $rowCounter++) {
            $outputArray[$outputRowNumber] = $this->constructTableRowOutput($pResultOutputArray, $pLoadedColumnGroupings, $pReportDisplayOptions, $rowCounter);
            //echo "outputArray: " . $outputArray[$outputRowNumber] . "<BR />";
            $outputRowNumber++;

        }
        return $outputArray;
    }


        private function determineClassNameToUse($pReportDisplayOptions) {
            if ($pReportDisplayOptions->getFirstColumnFormat() == "date") {
                return "columnHeadingNormal cellDateSize";
            } else {
                return "columnHeadingNormal cellNameSize";
            }
        }

        private function addTableHeaderCellsToOutput($pReportDisplayOptions, $pTHClassNameToUse, $pLoopIteration) {
            $thOutput = "";
            $arrReportRowGroup = $pReportDisplayOptions->getRowGroup(); //Array of ReportGroupingStructure objects
            $countRecords = count($arrReportRowGroup);
            for ($r = 0; $r < $countRecords; $r++) {
                $thOutput .= "<th class='" . $pTHClassNameToUse . "'>";
                if ($pLoopIteration == 0) {
                    $thOutput .= $arrReportRowGroup[$r]->getGroupHeading();
                    $thOutput .= "<BR /><span class='columnSizeText'>" . $arrReportRowGroup[$r]->getGroupSizeText() . "</span>";
                }
                $thOutput .= "</th>";
            }

            return $thOutput;

        }


        private function addTableHeaderMergedCells($columnCountForHeadingCells, $pReportDisplayOptions, $pLoopIteration) {
            $countLoadedColumnGroupings = count($columnCountForHeadingCells[$pLoopIteration]);
            $arrReportColumnGroup = $pReportDisplayOptions->getColumnGroup();
            $columnLabels = $pReportDisplayOptions->getColumnGroup();
            $thOutput = "";
            for ($j = 0; $j < $countLoadedColumnGroupings; $j++) {
                //Check if cell should be merged
                //if ($j == 0) {
                    $cellClass = $this->determineHeadingCellClass($columnLabels, $pLoopIteration);
                    $divClass = $this->determineHeadingDivClass($columnLabels, $pLoopIteration);
                    $colspanForCell = $this->determineHeadingColspan($columnLabels, $arrReportColumnGroup, $columnCountForHeadingCells, $pLoopIteration, $j);

                    $thOutput .= "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>" . $columnCountForHeadingCells[$pLoopIteration][$j]["label"] . "</div></th>";
                //}
            }

            return $thOutput;
        }

        private function determineHeadingCellClass($pColumnLabels, $pLoopIteration) {
            if ($pColumnLabels[$pLoopIteration]->getFieldName() == 'club_name') {
                return "rotated_cell";
            } else {
                return "columnHeadingNormal";
            }
        }

        private function determineHeadingDivClass($pColumnLabels, $pLoopIteration) {
            if ($pColumnLabels[$pLoopIteration]->getFieldName() == 'club_name') {
                return "rotated_text";
            } else {
                return "normalHeadingText";
            }
        }


        private function determineHeadingColspan($pColumnLabels, $pArrReportColumnGroup, $columnCountForHeadingCells, $pLoopIteration, $pSubLoopIteration) {
            $colspanForCell = 1;
            if ($pColumnLabels[$pLoopIteration]->getFieldName() == 'club_name') {
                $colspanForCell = 1;
            } else {
                if ($pArrReportColumnGroup[$pLoopIteration]->getMergeField() == 1) {
                    $colspanForCell = $columnCountForHeadingCells[$pLoopIteration][$pSubLoopIteration]["count"];
                } else {
                    $colspanForCell = 1;
                }
            }
            return $colspanForCell;
        }


        private function constructTableRowOutput($pResultOutputArray, $pLoadedColumnGroupings, $pReportDisplayOptions, $pRowCounter) {
            $tableRowOutput = "<tr class='altRow'>";
            $countColumns = count($pLoadedColumnGroupings);
            for ($columnCounter=0; $columnCounter <= $countColumns; $columnCounter++) {

                $cellValue = $this->determineCellValueToUse($columnCounter, $pResultOutputArray, $pReportDisplayOptions, $pRowCounter);
                $cellClassToUse = $this->determineCellClassToUse($columnCounter, $pResultOutputArray, $pReportDisplayOptions, $pRowCounter);

                $tableRowOutput .= "<td class='$cellClassToUse'>$cellValue</td>";
            }
            $tableRowOutput .= "</tr>";
            return $tableRowOutput;

        }


        private function determineCellClassToUse($columnCounter, $pResultOutputArray, $pReportDisplayOptions, $pRowCounter) {
            $cellClassToUse = "";
            $cellFormatter = new Cell_formatting_helper();
            if(array_key_exists($columnCounter, $pResultOutputArray[$pRowCounter])) {
                if ($columnCounter == 0) { //First column
                    if ($pReportDisplayOptions->getFirstColumnFormat() == "text") {
                        $cellClassToUse = "cellText cellNormal";
                    } elseif ($pReportDisplayOptions->getFirstColumnFormat() == "date") {
                        $cellClassToUse = "cellNumber cellNormal";
                    }
                } else {
                    if ($pReportDisplayOptions->getColourCells() == 1) {
                        //TODO: Refactor this to remove boolean parameter and use two different functions
                        $cellClassToUse = $cellFormatter->getCellClassNameForTableFromOutputValue($pResultOutputArray[$pRowCounter][$columnCounter], TRUE);
                    } elseif(is_numeric($pResultOutputArray[$pRowCounter][$columnCounter])) {
                        $cellClassToUse = "cellNumber cellNormal";
                    } else {
                        $cellClassToUse = "cellText cellNormal";
                    }
                }
            } else {
                $cellClassToUse = "cellNormal";
            }
            return $cellClassToUse;

        }


        private function determineCellValueToUse($columnCounter, $pResultOutputArray, $pReportDisplayOptions, $pRowCounter) {
            $cellValue = "";
            if(array_key_exists($columnCounter, $pResultOutputArray[$pRowCounter])) {
                if ($columnCounter == 0) { //First column
                    if ($pReportDisplayOptions->getFirstColumnFormat() == "text") {
                        $cellValue = $pResultOutputArray[$pRowCounter][$columnCounter];
                    } elseif ($pReportDisplayOptions->getFirstColumnFormat() == "date") {
                        $weekDate = date_create($pResultOutputArray[$pRowCounter][$columnCounter]);
                        $cellValue = date_format($weekDate, 'd/m/Y');
                    }
                } else {
                    $cellValue = $pResultOutputArray[$pRowCounter][$columnCounter];
                }
            } else {
                $cellValue = "";
            }
            return $cellValue;
        }




        public function resetCounterForRow($pCurrentCounterForRow, $pResultRow, $pFieldForRowLabel, $pPreviousRowLabel) {
            /*
            *IMPORTANT: If the SQL query DOES NOT order by the row labels (e.g. the umpire name),
            *then this loop structure will cause all values to be set to the last column,
            *and show incorrect data in the report.
            *If this happens, ensure the SELECT query inside the Report_data_query object for this report (e.g. Report8.php)
            *orders by the correct column
            *
            */
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
        public function setPivotedArrayValue(&$pPivotedArray, $pResultRow, $pFieldForRowLabel, $pCounterForRow, $pivotArrayKeyName, $resultKeyName) {
            $pPivotedArray[$pResultRow[$pFieldForRowLabel[0]]][$pCounterForRow][$pivotArrayKeyName] = $pResultRow[$resultKeyName];
        }
}

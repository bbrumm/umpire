<?php

class Parent_report extends CI_Model {
    
    public function __construct() {
        $this->load->model('Cell_formatting_helper');
	$this->load->model('separate_reports/Report_array_output_formatter');
    }

    public function getReportDataQuery(Report_instance $pReportInstance) {}

    private function replaceBindVariables($sqlQuery, $pReportInstance) {
        $sqlQuery = $this->replaceRegionInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceLeagueInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceSeasonYearInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceAgeGroupInQueryString($sqlQuery, $pReportInstance);
        $sqlQuery = $this->replaceUmpireTypeInQueryString($sqlQuery, $pReportInstance);
        return $sqlQuery;
    }

    private function replaceRegionInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pRegion", $pReportInstance->filterParameterRegion->getFilterSQLValues(), $sqlQuery);
    }

    private function replaceLeagueInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pLeague", $pReportInstance->filterParameterLeague->getFilterSQLValues(), $sqlQuery);
    }

    private function replaceSeasonYearInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pSeasonYear", $pReportInstance->requestedReport->getSeason(), $sqlQuery);
    }

    private function replaceAgeGroupInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pAgeGroup", $pReportInstance->filterParameterAgeGroup->getFilterSQLValues(), $sqlQuery);
    }

    private function replaceUmpireTypeInQueryString($sqlQuery, $pReportInstance) {
        return str_replace(":pUmpireType", $pReportInstance->filterParameterUmpireType->getFilterSQLValues(), $sqlQuery);
    }

    public function constructReportQuery($pSQLFilename, $pReportInstance) {
        $sqlQuery = file_get_contents(SQL_REPORT_FILE_PATH . $pSQLFilename);
        $sqlQuery = $this->replaceBindVariables($sqlQuery, $pReportInstance);
        return $sqlQuery;
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
    public function resetCounterForRow($pCurrentCounterForRow, $pResultRow, Report_cell_collection $pMainReportCellCollection, $pPreviousRowLabel) {
        if ($pResultRow[
            $pMainReportCellCollection->getRowLabelFields()[0]->getCellValue()
            ] != $pPreviousRowLabel[0]) {
            //New row label, so reset counter
            return 0;
        } elseif (array_key_exists(1, $pMainReportCellCollection->getRowLabelFields())) {
            if ($pResultRow[$pMainReportCellCollection->getRowLabelFields()[1]->getCellValue()] != $pPreviousRowLabel[1]) {
                //New row label, so reset counter
                return 0;
            }
        } else {
            return $pCurrentCounterForRow;
        }

    }

    public function resetCounterForRowUsingDisplayOptions($pCurrentCounterForRow, $pResultRow, Report_display_options $pReportDisplayOptions, $pPreviousRowLabel) {
        if ($pResultRow[
            $pReportDisplayOptions->getRowGroup()[0]->getFieldName()
            ] != $pPreviousRowLabel[0]) {
            //New row label, so reset counter
            return 0;
        } elseif (array_key_exists(1, $pReportDisplayOptions->getRowGroup())) {
            if ($pResultRow[$pReportDisplayOptions->getRowGroup()[1]->getFieldName()] != $pPreviousRowLabel[1]) {
                //New row label, so reset counter
                return 0;
            }
        } else {
            return $pCurrentCounterForRow;
        }
    }

    //Uses an & character to pass by reference, because pivotedArray should be updated on each call
    public function setPivotedArrayValue(&$pPivotedArray,
                                         $pResultRow,
                                         Report_cell_collection $pMainReportCellCollection,
                                         $pCounterForRow,
                                         Report_cell $pivotArrayKeyCell) {

        //$pPivotedArray[$pResultRow[$pFieldForRowLabel[0]]][$pCounterForRow][$pivotArrayKeyName] = $pResultRow[$resultKeyName];
        //$pPivotedArray[$pResultRow[$pFieldForRowLabel[0]->getCellValue()]][$pCounterForRow][$pivotArrayKeyName] = $pResultRow[$resultKeyName];
        $pPivotedArray[
            $pResultRow[
            $pMainReportCellCollection->getRowLabelFields()[0]->getCellValue()
            ]
        ][
            $pCounterForRow
        ][
            $pivotArrayKeyCell->getCellValue()
        ] = $pResultRow[
            $pivotArrayKeyCell->getCellValue()
        ];
    }

    public function transformReportCellCollectionIntoOutputArray(Report_cell_collection $pReportCellCollection, $columnLabelResultArray, $pReportColumnFields) {
        $resultOutputArray = [];
        $currentResultArrayRowNumber = 0;

        //Loop through each set of Report_cells. Each entry here is a single person or row of data.
        foreach ($pReportCellCollection->getReportCellArray() as $setOfReportCellsForOneRow) {
            $columnNumber = 0;

            //Add row labels to output array. Once for each row
            $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $setOfReportCellsForOneRow[0]->getCellValue();
            $columnNumber++;
            //Loop through each cell in this list

            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                foreach($setOfReportCellsForOneRow as $singleReportCell) {
                    //Match the column headings to the values in the array
                    if ($this->isFieldMatchingColumn($singleReportCell, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $singleReportCell->getCellValue();
                    }

                }
                $columnNumber++;
            }
            $currentResultArrayRowNumber++;
        }
        return $resultOutputArray;
    }


    public function translateResultsToReportCellCollection($pResultArray, Report_display_options $pReportDisplayOptions) {
        $mainReportCellCollection = new Report_cell_collection();

        $previousRowLabel = "";
        $outputRowNumber = 0;

        //Loop through each row of results
        foreach ($pResultArray as $resultRow) {

            $currentRowLabel = $resultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()];

            //Increase output row number if row label is different
            if ($currentRowLabel != $previousRowLabel) {
                //TODO: Move this increment to another place. It's causing the first index to be 1.
                //But the first row's Name value needs to be added somewhere. Maybe two IF statements?

                if ($previousRowLabel != "") {
                    $outputRowNumber++;
                }

                //Add new cell for row label
                $newReportCell = new Report_cell();
                $newReportCell->setCellValue($currentRowLabel);
                $newReportCell->setColumnHeaderValueFirst("Name");
                $newReportCell->setSourceResultRow($resultRow);
                $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);


            }
            $previousRowLabel = $resultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()];


            if (get_class($this) == 'Report5') {
                $newReportCell = $this->populateReportCell($resultRow, $pReportDisplayOptions, "match_no_ump");
                $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);

                $newReportCell = $this->populateReportCell($resultRow, $pReportDisplayOptions, "total_match_count");
                $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);

                $newReportCell = $this->populateReportCell($resultRow, $pReportDisplayOptions, "match_pct");
                $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);

                //$newReportCell = $this->populateReportCell($resultRow, $pReportDisplayOptions, "subtotal");
                //$mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);
            } else {
                $newReportCell = $this->populateReportCell($resultRow, $pReportDisplayOptions, "match_count");
                $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);
            }


        }

        return $mainReportCellCollection;
    }


    private function populateReportCell($pResultRow, Report_display_options $pReportDisplayOptions, $pCellValue) {
        $newReportCell = new Report_cell();

        $newReportCell->setCellValue($pResultRow[$pCellValue]);

        //Set column header values. Add these to separate functions
        $countColumnGroup = count($pReportDisplayOptions->getColumnGroup());
        $newReportCell->setColumnHeaderValueFirst($pResultRow[$pReportDisplayOptions->getColumnGroup()[0]->getFieldName()]);

        //Set the second col value if one exists
        //TODO: this whole section should be refactored. It's too long.
        if ($countColumnGroup > 1) {
            if(get_class($this) == 'Report5') {
                //Temporary code check to allow subtotals to be added. A subtotal means Games/Total/Pct.
                //Match the column headings to the values in the array
                $subtotalToColumnMap = array(
                    'match_no_ump'=>'Games',
                    'total_match_count'=>'Total',
                    'match_pct'=>'Pct'
                );

                $newReportCell->setColumnHeaderValueSecond($subtotalToColumnMap[$pCellValue]);


            } else {
                $newReportCell->setColumnHeaderValueSecond($pResultRow[$pReportDisplayOptions->getColumnGroup()[1]->getFieldName()]);
            }
        }

        if ($countColumnGroup > 2) {
            $newReportCell->setColumnHeaderValueThird($pResultRow[$pReportDisplayOptions->getColumnGroup()[2]->getFieldName()]);
        }



        $newReportCell->setSourceResultRow($pResultRow);

        return $newReportCell;
    }


}

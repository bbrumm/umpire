<?php
/*
* @property Object reportColumnQueryFilename
*/
class Parent_report extends CI_Model {
    
    public function __construct() {
        $this->load->model('Cell_formatting_helper');
	    $this->load->model('separate_reports/Report_array_output_formatter');
        $this->load->model('separate_reports/Report_query_builder');
        $this->load->model('separate_reports/Field_column_matcher');
    }

    public function getReportDataQuery(Report_instance $pReportInstance) {
        $reportQueryBuilder = new Report_query_builder();
        return $reportQueryBuilder->constructReportQuery($this->reportDataQueryFilename, $pReportInstance);
    }

    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $reportQueryBuilder = new Report_query_builder();
        return $reportQueryBuilder->constructReportQuery($this->reportColumnQueryFilename, $pReportInstance);
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
        $fieldColumnMatcher = new Field_column_matcher();

        //Loop through each set of Report_cells. Each entry here is a single person or row of data.
        foreach ($pReportCellCollection->getReportCellArray() as $setOfReportCellsForOneRow) {
            $columnNumber = 0;

            //Add row labels to output array. Once for each row
            $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $setOfReportCellsForOneRow[0]->getCellValue();
            $columnNumber++;

            //Add second column of row labels for report 5
            if (get_class($this) == 'Report5') {
                $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $setOfReportCellsForOneRow[1]->getCellValue();
                $columnNumber++;
            }
            //Loop through each cell in this list

            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                foreach($setOfReportCellsForOneRow as $singleReportCell) {
                    //Match the column headings to the values in the array
                    if ($fieldColumnMatcher->isFieldMatchingColumn($singleReportCell, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRowNumber][$columnNumber] = $singleReportCell->getCellValue();
                    }

                }
                $columnNumber++;
            }
            $currentResultArrayRowNumber++;
        }
        return $resultOutputArray;
    }

    public function hasRowLabelChanged($currentRowLabel, $previousRowLabel) {
        return $currentRowLabel != $previousRowLabel;
    }

    public function setRowLabel($pResultRow, $pReportDisplayOptions) {
        return array(
            $pResultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()]
        );
    }


    public function translateResultsToReportCellCollection($pResultArray, Report_display_options $pReportDisplayOptions) {
        $mainReportCellCollection = new Report_cell_collection();
        $outputRowNumber = 0;

        $rowCount = count($pResultArray);

        //Loop through each row of results
        for ($i=0; $i < $rowCount; $i++) {
            $currentRowLabel = $this->calculateCurrentRowLabel($pResultArray, $i, $pReportDisplayOptions);
            $previousRowLabel = $this->calculatePreviousRowLabel($pResultArray, $i, $pReportDisplayOptions);
            $outputRowNumber = $this->determineOutputRowNumber($outputRowNumber, $currentRowLabel, $previousRowLabel);

            //Increase output row number if row label is different
            if ($this->hasRowLabelChanged($currentRowLabel, $previousRowLabel)) {
                //Add new cell for row label
                $mainReportCellCollection->addReportCellToCollection($outputRowNumber,
                    $this->addNameCellForRowLabel($currentRowLabel, $pResultArray[$i]));

            }

            $newReportCell = $this->populateReportCell($pResultArray[$i], $pReportDisplayOptions, "match_count");
            $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);
        }

        return $mainReportCellCollection;
    }

    private function determineOutputRowNumber($outputRowNumber, $currentRowLabel, $previousRowLabel) {
        if ($this->hasRowLabelChanged($currentRowLabel, $previousRowLabel) &&
            $previousRowLabel != "" ) {
            return ++$outputRowNumber;
        } else {
            return $outputRowNumber;
        }
    }

    private function calculatePreviousRowLabel($pResultArray, $pRowCounter, $pReportDisplayOptions) {
        //Return blank if this is the first result, otherwise calculate it.
        if ($pRowCounter == 0) {
            return "";
        } else {
            return $this->setRowLabel($pResultArray[$pRowCounter - 1], $pReportDisplayOptions);
        }
    }

    private function calculateCurrentRowLabel($pResultArray, $pRowCounter, $pReportDisplayOptions) {
        return $this->setRowLabel($pResultArray[$pRowCounter], $pReportDisplayOptions);;
    }

    public function addNameCellForRowLabel($currentRowLabel, $resultRow) {
        return $this->addCellForRowLabel($currentRowLabel, $resultRow, 0, "Name");
    }

    public function addAgeGroupCellForRowLabel($currentRowLabel, $resultRow) {
        return $this->addCellForRowLabel($currentRowLabel, $resultRow, 1, "Age Group");
    }


    private function addCellForRowLabel($currentRowLabel, $resultRow, $currentRowLabelOffset, $columnHeaderValueFirst) {
        //Add new cell for row label
        $newReportCell = new Report_cell();
        $newReportCell->setCellValue($currentRowLabel[$currentRowLabelOffset]);
        $newReportCell->setColumnHeaderValueFirst($columnHeaderValueFirst);
        $newReportCell->setSourceResultRow($resultRow);
        return $newReportCell;
    }


    public function populateReportCell($pResultRow, Report_display_options $pReportDisplayOptions, $pCellValue) {
        $countColumnGroup = count($pReportDisplayOptions->getColumnGroup());

        $newReportCell = new Report_cell();
        $newReportCell->setCellValue($pResultRow[$pCellValue]);
        $newReportCell->setColumnHeaderValueFirst(
            $this->extractColumnHeaderValue($pResultRow, $pReportDisplayOptions, 0));

        //Set the second col value if one exists
        if ($countColumnGroup > 1) {
            $newReportCell->setColumnHeaderValueSecond(
                $this->determineSecondColumnHeaderValue($pResultRow, $pReportDisplayOptions, $pCellValue));
        }

        if ($countColumnGroup > 2) {
            $newReportCell->setColumnHeaderValueThird(
                $this->extractColumnHeaderValue($pResultRow, $pReportDisplayOptions, 2));
        }

        $newReportCell->setSourceResultRow($pResultRow);
        return $newReportCell;
    }

    public function extractColumnHeaderValue($pResultRow, $pReportDisplayOptions, $pColumnIndex) {
        return $pResultRow[$pReportDisplayOptions->getColumnGroup()[$pColumnIndex]->getFieldName()];
    }

    public function determineSecondColumnHeaderValue($pResultRow, $pReportDisplayOptions, $pCellValue) {
        return $this->extractColumnHeaderValue($pResultRow, $pReportDisplayOptions, 1);
    }

}

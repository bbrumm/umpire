<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report5 extends Parent_report implements IReport {

    public $reportDataQueryFilename = "report5_data.sql";
    public $reportColumnQueryFilename = "report5_columns.sql";

    public function __construct() {
        $this->load->model('separate_reports/Report_query_builder');
        $this->load->model('separate_reports/Field_column_matcher');
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

    /*
    public function transformQueryResultsIntoOutputArray(Report_cell_collection $pReportCellCollection, $columnLabelResultArray, $pReportColumnFields) {
        $resultOutputArray = [];
        //$countItemsInColumnHeadingSet = count($columnLabelResultArray[0]);
        $currentResultArrayRow = 0;
        $pResultArray = $pReportCellCollection->getCollection();
        $fieldColumnMatcher = new Field_column_matcher();

        foreach ($pResultArray as $rowKey => $currentRowItem) { //Maps to a single row of output
            $columnNumber = 0;
            $totalForRow = 0;
            //$resultOutputArray[$currentResultArrayRow][0] = $currentRowItem[0]['umpire_type'];
            $resultOutputArray[$currentResultArrayRow][0] = $rowKey;

            foreach ($columnLabelResultArray as $columnHeadingSet) { //Maps to an output column
                $columnNumber++;
                //Loops through each value of $columnLabelResultArray.
                //This comes from the results found in the separate_reports.ReportX.getReportColumnQuery() function.
                //E.g. if Report 8's column query returns 4 rows, then this columnHeadingSet has 4 records in it
                foreach ($currentRowItem as $columnKey => $columnItem) { //Maps to a single match_count, not necessarily a column
                    //Loop through each row and column intersection in the result array
                    if ($columnNumber == 1) {
                        //Add extra column for report 5
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem['age_group'];
                        $columnNumber++;
                    }
                    
                    //Match the column headings to the values in the array
                    $subtotalToColumnMap = array(
                        'Games'=>'match_no_ump',
                        'Total'=>'total_match_count',
                        'Pct'=>'match_pct'
                    );

                    $columnItem['subtotal'] = $columnHeadingSet['subtotal'];

                    if ($fieldColumnMatcher->isFieldMatchingColumn($columnItem, $columnHeadingSet, $pReportColumnFields)) {
                        $resultOutputArray[$currentResultArrayRow][$columnNumber] = $columnItem[$subtotalToColumnMap[$columnHeadingSet['subtotal']]];
                        if ($this->isSubtotalGames($columnHeadingSet)) {
                            $totalForRow = $totalForRow + $columnItem['match_no_ump'];
                        }
                    }
                }
            }
            //Add on final column for report 2 and 5 and 8 for totals for the row
            $resultOutputArray[$currentResultArrayRow][$columnNumber] = $totalForRow;
            $currentResultArrayRow++;
        }
        return $resultOutputArray;
    }

    */


    /*
    private function isSubtotalGames($columnHeadingSet) {
        return ($columnHeadingSet['subtotal'] == 'Games');
    }
    */

    public function setRowLabel($pResultRow, $pReportDisplayOptions) {
        //Return two columns for report 5: umpire type and age group
        return array(
            $pResultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()],
            $pResultRow[$pReportDisplayOptions->getRowGroup()[1]->getFieldName()]
        );

    }


    public function pivotQueryArray($pResultArray, Report_display_options $pReportDisplayOptions) {
        //$pivotedArray = array();
        $previousRowLabel = array();
        $counterForRow = 0;
        $previousRowLabel[0] = "";
        $mainReportCellCollection = new Report_cell_collection();


        $columnNameForShortLeagueName = "short_league_name";
        $columnNameForAgeGroup = "age_group";
        $columnNameForUmpireType = "umpire_type";
        $columnNameForMatchNoUmp = "match_no_ump";
        $columnNameForTotalMatchCount = "total_match_count";
        $columnNameForMatchPct = "match_pct";


        foreach ($pResultArray as $resultRow) {
            $counterForRow = $this->resetCounterForRowUsingDisplayOptions($counterForRow, $resultRow, $pReportDisplayOptions, $previousRowLabel);
            $previousRowLabel[0] = $resultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()];
            $currentRowLabelFieldName = $pReportDisplayOptions->getRowGroup()[0]->getFieldName();

            //Check if there is a second row that is being grouped.
            //This should only exist for report 5, according to the report_grouping_structure table
            if (array_key_exists(1, $pReportDisplayOptions->getRowGroup())) {
                $previousRowLabel[1] = $resultRow[$pReportDisplayOptions->getRowGroup()[1]->getFieldName()];
            }
            foreach ($pReportDisplayOptions->getColumnGroup() as $singleReportGroupingStructure) {
                $rowArrayKey = $resultRow[$pReportDisplayOptions->getRowGroup()[0]->getFieldName()] . " " . $resultRow[$pReportDisplayOptions->getRowGroup()[1]->getFieldName()];
                /*
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $shortLeagueNameCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $ageGroupCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $umpireTypeCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $matchNoUmpCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $totalMatchCountCell);
                $this->setPivotedArrayNamedValue($pivotedArray, $rowArrayKey, $counterForRow, $resultRow, $matchPctCell);
                */

                //$mainReportCellCollection->addCurrentRowToCollectionWithName($resultRow, $counterForRow, $singleReportGroupingStructure->getFieldName(), $rowArrayKey);
                $mainReportCellCollection->addCurrentRowToCollectionWithName($resultRow, $counterForRow, $columnNameForShortLeagueName, $rowArrayKey);
                $mainReportCellCollection->addCurrentRowToCollectionWithName($resultRow, $counterForRow, $columnNameForAgeGroup, $rowArrayKey);
                $mainReportCellCollection->addCurrentRowToCollectionWithName($resultRow, $counterForRow, $columnNameForUmpireType, $rowArrayKey);
                $mainReportCellCollection->addCurrentRowToCollectionWithName($resultRow, $counterForRow, $columnNameForMatchNoUmp, $rowArrayKey);
                $mainReportCellCollection->addCurrentRowToCollectionWithName($resultRow, $counterForRow, $columnNameForTotalMatchCount, $rowArrayKey);
                $mainReportCellCollection->addCurrentRowToCollectionWithName($resultRow, $counterForRow, $columnNameForMatchPct, $rowArrayKey);


            }
            $counterForRow++;
        }
        //return $mainReportCellCollection->getCollection();
        return $mainReportCellCollection;
    }


    public function translateResultsToReportCellCollection($pResultArray, Report_display_options $pReportDisplayOptions) {
        $mainReportCellCollection = new Report_cell_collection();
        $previousRowLabel = "";
        $outputRowNumber = 0;

        //Loop through each row of results
        foreach ($pResultArray as $resultRow) {

            $currentRowLabel = $this->setRowLabel($resultRow, $pReportDisplayOptions);

            //Increase output row number if row label is different
            if ($this->hasRowLabelChanged($currentRowLabel, $previousRowLabel)) {
                if ($previousRowLabel != "") {
                    $outputRowNumber++;
                }

                //Add new cell for row label
                $mainReportCellCollection->addReportCellToCollection($outputRowNumber,
                    $this->addNameCellForRowLabel($currentRowLabel, $resultRow));


                $mainReportCellCollection->addReportCellToCollection($outputRowNumber,
                    $this->addAgeGroupCellForRowLabel($currentRowLabel, $resultRow));

            }
            $previousRowLabel = $this->setRowLabel($resultRow, $pReportDisplayOptions);

            $newReportCell = $this->populateReportCell($resultRow, $pReportDisplayOptions, "match_no_ump");
            $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);

            $newReportCell = $this->populateReportCell($resultRow, $pReportDisplayOptions, "total_match_count");
            $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);

            $newReportCell = $this->populateReportCell($resultRow, $pReportDisplayOptions, "match_pct");
            $mainReportCellCollection->addReportCellToCollection($outputRowNumber, $newReportCell);

            //Update total for this row
            $newReportCell = new Report_cell();
            $newReportCell->setCellValue($resultRow["match_no_ump"]);
            $newReportCell->setColumnHeaderValueFirst("All");
            $newReportCell->setColumnHeaderValueSecond("Total");
            $mainReportCellCollection->updateTotalReportCell($outputRowNumber, $newReportCell);


        }

        return $mainReportCellCollection;
    }

    public function determineSecondColumnHeaderValue($pResultRow, $pReportDisplayOptions, $pCellValue) {
        //Temporary code check to allow subtotals to be added. A subtotal means Games/Total/Pct.
        //Match the column headings to the values in the array
        $subtotalToColumnMap = array(
            'match_no_ump' => 'Games',
            'total_match_count' => 'Total',
            'match_pct' => 'Pct'
        );

        return $subtotalToColumnMap[$pCellValue];

    }

    
}

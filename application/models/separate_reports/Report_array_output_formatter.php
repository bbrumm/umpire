<?php
class Report_array_output_formatter extends CI_Model {
    
    public function __construct() {
        
    }


    public function formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells) {
        $outputArray = array();
        //First add heading cells
        $outputArray[0] = $this->addHeadingCellsToOutput(
            $pReportDisplayOptions, $pColumnCountForHeadingCells, $pLoadedColumnGroupings);
        //Then add data cells
        $outputArray = $this->addDataCellsToOutput(
            $outputArray, $pResultOutputArray, $pLoadedColumnGroupings, $pReportDisplayOptions);
        return $outputArray;
    }
	
	private function addHeadingCellsToOutput($pReportDisplayOptions, $pColumnCountForHeadingCells, $pLoadedColumnGroupings) {
		$thOutput = "<thead>";
		$countItemsInColumnHeadingSet = count($pLoadedColumnGroupings[0]);
		    for ($i = 0; $i < $countItemsInColumnHeadingSet; $i++) {
                $thOutput .= "<tr class='header'>";
                $thClassNameToUse = $this->determineClassNameToUse($pReportDisplayOptions);
                $thOutput .= $this->addTableHeaderCellsToOutput($pReportDisplayOptions, $thClassNameToUse, $i);
                $thOutput .= $this->addTableHeaderMergedCells($pColumnCountForHeadingCells, $pReportDisplayOptions, $i);
		    }
		    $thOutput .= "</thead>\n";
		    return $thOutput;
	}
	
	private function addDataCellsToOutput($outputArray, $pResultOutputArray, $pLoadedColumnGroupings, $pReportDisplayOptions) {
	    $countRows = count($pResultOutputArray);
	    $outputRowNumber = 1;
            for ($rowCounter = 0; $rowCounter < $countRows; $rowCounter++) {
                $outputArray[$outputRowNumber] = $this->constructTableRowOutput($pResultOutputArray, $pLoadedColumnGroupings, $pReportDisplayOptions, $rowCounter);
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
            $thOutput .= "</th>\n";
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
                $thOutput .= "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>" . $columnCountForHeadingCells[$pLoopIteration][$j]["label"] . "</div></th>\n";
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
        //Some reports are grouped by 2 rows (e.g. report 5) so we need to add an extra column for that
        $countColumns = count($pLoadedColumnGroupings) + count($pReportDisplayOptions->getRowGroup()) - 1;
        for ($columnCounter=0; $columnCounter <= $countColumns; $columnCounter++) {
            $cellValue = $this->determineCellValueToUse($columnCounter, $pResultOutputArray, $pReportDisplayOptions, $pRowCounter);
            $cellClassToUse = $this->determineCellClassToUse($columnCounter, $pResultOutputArray, $pReportDisplayOptions, $pRowCounter);
            $tableRowOutput .= $this->constructTableCellOutput($cellValue, $cellClassToUse);
            //$tableRowOutput .= "<td class='$cellClassToUse'>$cellValue</td>";
        }
        $tableRowOutput .= "</tr>\n";
        return $tableRowOutput;
    }
    private function constructTableCellOutput($cellValue, $cellClassToUse) {
        return "<td class='$cellClassToUse'>$cellValue</td>";
    }
    private function determineCellClassToUse($columnCounter, $pResultOutputArray, $pReportDisplayOptions, $pRowCounter) {
       $cellFormatter = new Cell_formatting_helper();
       if ($this->isValueInResults($columnCounter, $pResultOutputArray, $pRowCounter)  === false) {
           return "cellNormal";
       }
       /*
       if ($this->isFirstColumn($columnCounter) === false) {
           return "cellText cellNormal";
       }
       */
       if ($this->isFirstColumn($columnCounter)) {
           return $this->getCellClassFromFirstColumnFormat($pReportDisplayOptions);
       }
       if ($this->shouldCellBeColouredForReport($pReportDisplayOptions)) {
           return $cellFormatter->getCellClassNameForTableFromOutputValue(
                        $pResultOutputArray[$pRowCounter][$columnCounter]);
       }
       if (is_numeric($pResultOutputArray[$pRowCounter][$columnCounter])) {
          return "cellNumber cellNormal";
       }
       return "cellText cellNormal";
    }
	
    private function getCellClassFromFirstColumnFormat($pReportDisplayOptions) {
	$cellClass = "";
        if ($this->isFirstColumnFormatText($pReportDisplayOptions)) {
            $cellClass = "cellText cellNormal";
        } elseif ($this->isFirstColumnFormatDate($pReportDisplayOptions)) {
            $cellClass = "cellNumber cellNormal";
        }
	return $cellClass;
    }
    private function isFirstColumn($pColumnCounter) {
        return ($pColumnCounter == 0);
    }
    private function shouldCellBeColouredForReport($pReportDisplayOptions) {
        return ($pReportDisplayOptions->getColourCells() == 1);
    }
    private function isFirstColumnFormatText($pReportDisplayOptions) {
        return ($pReportDisplayOptions->getFirstColumnFormat() == "text");
    }
    private function isFirstColumnFormatDate($pReportDisplayOptions) {
        return ($pReportDisplayOptions->getFirstColumnFormat() == "date");
    }
    private function determineCellValueToUse($columnCounter, $pResultOutputArray, $pReportDisplayOptions, $pRowCounter) {
        if ($this->isValueInResults($columnCounter, $pResultOutputArray, $pRowCounter)  === false) {
            return "";
        }
        if ($this->isFirstColumn($columnCounter) === false) {
            return $pResultOutputArray[$pRowCounter][$columnCounter];
        }
        if ($this->isFirstColumnFormatText($pReportDisplayOptions)) {
            return  $pResultOutputArray[$pRowCounter][$columnCounter];
        }
        if ($this->isFirstColumnFormatDate($pReportDisplayOptions)) {
            $weekDate = date_create($pResultOutputArray[$pRowCounter][$columnCounter]);
            return date_format($weekDate, 'd/m/Y');
        }
    }
    
    private function isValueInResults($columnCounter, $pResultOutputArray, $pRowCounter) {
      return array_key_exists($columnCounter, $pResultOutputArray[$pRowCounter]);
    }



}

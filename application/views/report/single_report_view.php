<?php
//LoadedReportItem is an object of type ReportInstance
$loadedColumnGroupings = $loadedReportItem->getColumnLabelResultArray();
$loadedResultArray = $loadedReportItem->getResultArray(); 
$resultOutputArray = $loadedReportItem->getResultOutputArray(); //this might be the only object I need if I use the DW method
$reportDisplayOptions = $loadedReportItem->getDisplayOptions();
$columnLabels = $reportDisplayOptions->getColumnGroup();
$colourCells = $reportDisplayOptions->getColourCells();
$rowLabels = $reportDisplayOptions->getRowGroup();
$fieldToDisplay = $reportDisplayOptions->getFieldToDisplay();
$noDataValueToDisplay = $reportDisplayOptions->getNoDataValue();
$debugMode = $this->config->item('debug_mode');
$reportID = $loadedReportItem->getReportID();
$countFirstLoadedColumnGroupings = count($columnLabels);
$useNewDWTables = $this->config->item('use_new_dw_tables');
$columnCountForHeadingCells = $loadedReportItem->getColumnCountForHeadingCells();

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<div class='reportInformation'><span class='boldedText'>Last Game Date</span>: ". $reportDisplayOptions->getLastGameDate() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>Umpire Discipline</span>: ". $loadedReportItem->getUmpireTypeDisplayValues() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>League</span>: ". $loadedReportItem->getLeagueDisplayValues() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>Age Group</span>: ". $loadedReportItem->getAgeGroupDisplayValues() ."</div>";
echo "<br />";
?>
<!-- 
<div style="width:3000px">
        some really really wide content goes here
    </div>
     -->

<!-- This table is used to test the new Data Warehouse style output. -->
<div id='panelBelow'>
<div id='moveLeftDown'>
<table class='reportTable tableWithFloatingHeader'	>
<thead>
<?php 
if ($useNewDWTables) {
    $debugLibrary->debugOutput("resultOutputArray DW", $resultOutputArray);
    $debugLibrary->debugOutput("columnLabelResultArray DW", $loadedColumnGroupings);
    //$debugLibrary->debugOutput("reportDisplayOptions DW", $reportDisplayOptions);
    
    $countItemsInColumnHeadingSet = count($loadedColumnGroupings[0]);
    for ($i=0; $i < $countItemsInColumnHeadingSet; $i++) {
        echo "<tr class='header'>";
        
        $thOutput = "";
        $thClassNameToUse = "";
        
        if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
            $thClassNameToUse = "columnHeadingNormal cellNameSize";
        } elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
            $thClassNameToUse = "columnHeadingNormal cellDateSize";
        } else {
            $thClassNameToUse = "columnHeadingNormal cellNameSize";
        }
        
        $arrReportRowGroup = $reportDisplayOptions->getRowGroup(); //Array of ReportGroupingStructure objects
        $arrReportColumnGroup = $reportDisplayOptions->getColumnGroup();
        for ($r=0; $r < count($arrReportRowGroup); $r++) {
            $thOutput = "<th class='". $thClassNameToUse ."'>";
            if ($i == 0) {
                	
                $thOutput .= $arrReportRowGroup[$r]->getGroupHeading();
                $thOutput .= "<BR /><span class='columnSizeText'>". $arrReportRowGroup[$r]->getGroupSizeText() ."</span>";
            }
            $thOutput .= "</th>";
            echo $thOutput;
        }

        $countLoadedColumnGroupings = count($columnCountForHeadingCells[$i]);
        if ($debugMode) {
            echo "<BR />countLoadedColumnGroupings DW:" . $countLoadedColumnGroupings;
        }
        
        for ($j=0; $j < $countLoadedColumnGroupings; $j++) {
            //Check if cell should be merged
            if ($j==0) {
                $proceed = true;
            }
            if ($proceed) {
                //print cell with colspan value
                if ($debugMode) {
                    //echo $columnLabels[$i]->getFieldName() . "rotate cell 1<BR />";
                }
                if ($columnLabels[$i]->getFieldName() == 'club_name' || $reportID == 6) {
                    //Some reports have their headers displayed differently
                    $colspanForCell = 1;
                    if ($PDFLayout) {
                        $cellClass = "rotated_cell_pdf";
                        $divClass = "rotated_text_pdf";
        
                    } else {
                        if ($debugMode) {
                            echo "<BR />rotate cell YES<BR />";
                        }
                        $cellClass = "rotated_cell";
                        $divClass = "rotated_text";
                    }
                } else {
                    if ($debugMode) {
                        //echo "dont rotate cell 2<BR />";
                    }
                    //Increase the colspan if this column group is to be merged
                    if ($arrReportColumnGroup[$i]->getMergeField() == 1) {
                        $colspanForCell = $columnCountForHeadingCells[$i][$j]["count"];
                    } else {
                        $colspanForCell = 1;
                    }
                    $cellClass = "columnHeadingNormal";
                    $divClass = "normalHeadingText";
                }
                echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$columnCountForHeadingCells[$i][$j]["label"]."</div></th>";
            }
        }
    }
    
    echo "</thead>";
    
    $countRows = count($resultOutputArray);
    if ($reportID == 5) {
        //TODO: Fix bug where report 5 is not getting the right number of columns.
        //This happens because the COUNT here is only looking at data columns, not the row label columns,
        //and in report 5, there are two of them.
        $countColumns = count($loadedColumnGroupings) + 1;
    } else {
        $countColumns = count($loadedColumnGroupings);
    }
    
    if ($debugMode) {
        echo "<BR />loadedColumnGroupings DW:<pre>";
        print_r($loadedColumnGroupings);
        echo "</pre><BR />";
    }
    $cellClassToUse = "";
    for ($rowCounter=0; $rowCounter < $countRows; $rowCounter++) {
        $tableRowOutput = "<tr>";
        for ($columnCounter=0; $columnCounter <= $countColumns; $columnCounter++) {
            if(array_key_exists($columnCounter, $resultOutputArray[$rowCounter])) {
                if ($columnCounter == 0) { //First column
                    if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
                        $cellValue = $resultOutputArray[$rowCounter][$columnCounter];
                        $cellClassToUse = "cellText cellNormal";
                    } elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
                        $weekDate = date_create($resultOutputArray[$rowCounter][$columnCounter]);
                        $cellValue = date_format($weekDate, 'd/m/Y');
                        $cellClassToUse = "cellNumber cellNormal";
                    }
                } else {
                    if ($colourCells == 1) {
                        $cellClassToUse = getCellClassNameFromOutputValue($resultOutputArray[$rowCounter][$columnCounter], TRUE);
                    } elseif ($reportID == 2 && $loadedColumnGroupings[$columnCounter-1]['age_group'] == 'Total') {
                        $cellClassToUse = "cellNumber cellTextTotal";
                    } elseif(is_numeric($resultOutputArray[$rowCounter][$columnCounter])) {
                        $cellClassToUse = "cellNumber cellNormal";
                    } else {
                        $cellClassToUse = "cellText cellNormal";
                    }
                    $cellValue = $resultOutputArray[$rowCounter][$columnCounter];
                    
                    //TODO: Fix this and find the correct array reference
                    if ($reportID == 5) {
                        if ($columnCounter >= 2) {
                            if ($loadedColumnGroupings[$columnCounter-2]["subtotal"] == "Pct") {
                                $cellValue .= "%";
                            }
                        }
                    }
                }
            } else {
                $cellClassToUse = "cellNormal";
                $cellValue = "";
            }
            if ($reportID == 6 && $columnCounter > 0) {
                if ($resultOutputArray[$rowCounter][0] == $loadedColumnGroupings[$columnCounter-1]["second_umpire"]) {
                    //echo "ROW resultOutputArray $rowCounter matches COL loadedColumnGroupings $columnCounter <BR />";
                    $cellClassToUse .= " cellRowMatchesColumn";
                }
            }
            $tableRowOutput .= "<td class='$cellClassToUse'>$cellValue</td>";
        }
        $tableRowOutput .=  "</tr>";
        echo $tableRowOutput;
    }
    ?>
    </table>
    </div>
    </div>
<?php 
//End UseNew TRUE
?>
<!--
</div>
</section>
-->
<?php 

}
?>
<BR />
<?php
$loadedColumnGroupings = $loadedReportItem->getColumnLabelResultArray();
$loadedResultArray = $loadedReportItem->getResultArray();
$reportDisplayOptions = $loadedReportItem->getDisplayOptions();
$columnLabels = $reportDisplayOptions->getColumnGroup();
$mergeColumnGroup = $reportDisplayOptions->getMergeColumnGroup();
$colourCells = $reportDisplayOptions->getColourCells();
$rowLabels = $reportDisplayOptions->getRowGroup();
$fieldToDisplay = $reportDisplayOptions->getFieldToDisplay();
$noDataValueToDisplay = $reportDisplayOptions->getNoDataValue();
//$debugLibrary = new DebugLibrary();
$debugMode = $this->config->item('debug_mode');
$reportID = $loadedReportItem->requestedReport->getReportNumber();

//$countLoadedColumnGroupings = count($loadedColumnGroupings);
$countFirstLoadedColumnGroupings = count($columnLabels);

$columnCountForHeadingCells = $loadedReportItem->getColumnCountForHeadingCells();

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<div class='reportInformation'><span class='boldedText'>Last Game Date</span>: ". $reportDisplayOptions->getLastGameDate() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>Umpire Discipline</span>: ". $loadedReportItem->getUmpireTypeDisplayValues() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>League</span>: ". $loadedReportItem->getLeagueDisplayValues() ."</div>";
echo "<div class='reportInformation'><span class='boldedText'>Age Group</span>: ". $loadedReportItem->getAgeGroupDisplayValues() ."</div>";
echo "<br />";

ini_set('memory_limit', '1024M'); // or you could use 1G

?>
<?php 
if ($debugMode) {
    echo "<BR />rowLabels<pre>";
    print_r($rowLabels);
    echo "</pre><BR />";
    
    echo "<BR />columnLabels<pre>";
    print_r($columnLabels);
    echo "</pre><BR />";
    
    echo "<BR />mergeColumnGroup<pre>";
    print_r($mergeColumnGroup);
    echo "</pre><BR />";
    
    echo "<BR />loadedColumnGroupings<pre>";
    print_r($loadedColumnGroupings);
    echo "</pre><BR />";
    
    echo "<BR />loadedResultArray<pre>";
    print_r($loadedResultArray);
    echo "</pre><BR />";
    
   
}

$columnHeadingLabels = $reportDisplayOptions->getColumnHeadingLabel();
$columnHeadingSizeText = $reportDisplayOptions->getColumnHeadingSizeText();

//echo "countFirstLoadedColumnGroupings: ". $countFirstLoadedColumnGroupings;

echo "<div class='divTableOuter'>";
echo "<div class='divTable'>";

//Show one header row for each group
for ($i=0; $i < $countFirstLoadedColumnGroupings; $i++) {
    //Load array that shows each column heading and the number of records it has.
	//$countOfEachColumnHeading = array_count_values(array_column($loadedColumnGroupings, $columnLabels[$i]));
	//echo "<div class='divRotatedHeader'>";
    echo "<div class='divRow'>";
    /*
	$thOutput;
	$thClassNameToUse;
	
	if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
	    $thClassNameToUse = "columnHeadingNormal cellNameSize divCell";
	} elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
	    $thClassNameToUse = "columnHeadingNormal cellDateSize divCell";
	} else {
	    $thClassNameToUse = "columnHeadingNormal cellNameSize divCell";
	}
*/
    $arrReportRowGroup = $reportDisplayOptions->getRowGroup(); //Array of ReportGroupingStructure objects
	$arrReportColumnGroup = $reportDisplayOptions->getColumnGroup();
	
	if ($debugMode) {
	    echo "<BR />arrReportRowGroup<pre>";
	    print_r($arrReportRowGroup);
	    echo "</pre><BR />";
	    
	    echo "<BR />arrReportColumnGroup<pre>";
	    print_r($arrReportColumnGroup);
	    echo "</pre><BR />";
	    
	}
	
	for ($r=0; $r < count($rowLabels); $r++) {
    	$thOutput = "<div class='divCell'>";
    	if ($i == 0) {    
    	    $thOutput .= $columnHeadingLabels[$r];
    	    $thOutput .= "<BR /><span class='columnSizeText'>". $arrReportRowGroup[$r]->getGroupSizeText() ."</span>";
    	}
    	$thOutput .= "</div>";
    	echo $thOutput;
	}
	
	//$countLoadedColumnGroupings = count($columnCountForHeadingCells[$i]);
	$countLoadedColumnGroupings = count($loadedColumnGroupings);
	
	$columnHeadingFieldToDisplay = $arrReportColumnGroup[$i]->getFieldName();

	//echo "countLoadedColumnGroupings(". $countLoadedColumnGroupings .")<BR />";
	
	for ($j=0; $j < $countLoadedColumnGroupings; $j++) {
	    if ($debugMode) {
	        echo "<pre>LCG:";
	        print_r( $loadedColumnGroupings[$j]);
	        echo "</pre>";
	        echo "Field: " . $columnHeadingFieldToDisplay . "<BR/>";
	    }
		    
		    //Check if cell should be merged
		//if ($j==0) {
			$proceed = true;
		//} 
		
		if ($proceed) {
			//print cell with colspan value
			if ($columnLabels[$i] == 'club_name' || $reportID == 6) {
				//Some reports have their headers displayed differently
				$colspanForCell = 1;
				$cellClass = "divCell";
				/*
				$innerDivClass = "divRotatedHeaderPDF";
				$outerDivClass = "divRotatedHeaderOuterPDF";
				*/
				$innerDivClass = "divCell divCellSmallText";
				/*$outerDivClass = "divCell";*/
				
				
				
				/*
				$cellClass = "rotated_cell_pdf";
				$divClass = "rotated_text_pdf";
				*/
			} else {
			    //Increase the colspan if this column group is to be merged
			    //if($columnLabels[$i]->getMergeField() == 1){
				    //$colspanForCell = $columnCountForHeadingCells[$i][$j]["count"];
			    //} else {
			        $colspanForCell = 1;
			    //}
			    
			    //$cellClass = "divCell";
			    $innerDivClass = "divCell";
			    
			    
			    
			    
				//$cellClass = "columnHeadingNormal";
				//$divClass = "normalHeadingText";
			}
			/*
			if ($colspanForCell == 1) {
			    echo "<div class='$cellClass' style='width:3%;'>";
			} else {
			    echo "<div class='$cellClass' colspan='$colspanForCell' style='width:3%;'>";
			}
			*/
			//echo "<div class='$innerDivClass'>";
			//echo "<div class='$innerDivClass'>";
			//$outputValue = $columnCountForHeadingCells[$i][$j]["label"];
			$outputValue = $loadedColumnGroupings[$j][$columnHeadingFieldToDisplay];
			
			//$outputValueNoSpaces = str_replace(' ', '_', $columnCountForHeadingCells[$i][$j]["label"]);
			//echo "<div class='$outerDivClass ". $columnCountForHeadingCells[$i][$j]["label"] ."'>" . $columnCountForHeadingCells[$i][$j]["label"]."</div></div></td>";
			//echo "<div class='$outerDivClass ". $outputValueNoSpaces ."'></div></div></td>";
			//echo "<div class='$outerDivClass'>". $outputValue ."</div></div></span>";
			//echo "<div class='$outerDivClass'>". $outputValue ."</div></div></div>";
			//echo "<div class='$outerDivClass'>". $outputValue ."</div></div>";
			
			
			//echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$columnCountForHeadingCells[$i][$j]["label"]."</div></th>";
			//echo "<div class='$cellClass'><div class='$divClass'>".$outputValue."</div></div>";
			
			//echo "<div class='$outerDivClass'>";
			
			
			echo "<div class='$innerDivClass'>".$outputValue."</div>";
			//echo "</div>";
			
			
		}
		//else
		//nothing - don't even write a td		
	}

	echo "</div>"; //Close Div Header
}

$loopCounter = 0;

$matchFound = false;

//** Loop through rows and output them to the page **
$tableRowOutput = "";
$maximumRowsToDisplay = 50;
$countOfRowsDisplayed = 0;
/*$rowsBeforeCreatingNewTable = 1;*/

//echo "<div class='divRowGroup'>";

foreach ($loadedResultArray as $resultRow): 
    if ($debugMode) {
        if ($countOfRowsDisplayed == $maximumRowsToDisplay) {
            //Exit the foreach if we have reached the maximum.
            break;
        }
    }
    
    
	if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
	   //$tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]->getFieldName()] . "</td>";
	    if ($reportID == 6) {
	        $cellClass = 'divCell divCellSmallText';
	    } else {
	        $cellClass = 'divCell';
	    }
	    $tableRowOutput = "<div class='divRow'><div class='$cellClass'>" . $resultRow[$rowLabels[0]->getFieldName()] . "</div>";
	   if (count($rowLabels) > 1) {    
	       //Output a second row label
	       //$tableRowOutput .= "<td class='cellNormal'>" . $resultRow[$rowLabels[1]->getFieldName()] . "</td>";
	       $tableRowOutput .= "<div class='$cellClass'>" . $resultRow[$rowLabels[1]->getFieldName()] . "</div>";
	   }
	} elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
	    $weekDate = date_create($resultRow[$rowLabels[0]]);
	    //$tableRowOutput = "<tr><td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
	    $tableRowOutput = "<div class='divRow'><div class='$cellClass'>" . date_format($weekDate, 'd/m/Y') . "</div>";
	    if (count($rowLabels) > 1) {
	       //Output a second row label
	       //$tableRowOutput .= "<td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
	        $tableRowOutput .= "<div class='$cellClass'>" . date_format($weekDate, 'd/m/Y') . "</div>";
	   }
	} else {
	    //$tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]->getFieldName()] . "</td>";
	    $tableRowOutput = "<div class='divRow'><div class='$cellClass'>" . $resultRow[$rowLabels[0]->getFieldName()] . "</div>";
	    if (count($rowLabels) > 1) {
	        //Output a second row label
	        //$tableRowOutput .= "<td class='cellNormal'>" . $resultRow[$rowLabels[1]->getFieldName()] . "</td>";
	        $tableRowOutput .= "<div class='$cellClass'>" . $resultRow[$rowLabels[1]->getFieldName()] . "</div>";
	    }
	}
	
	//Loop through each of the columns to be displayed
	for ($i=0; $i < $countLoadedColumnGroupings; $i++) {
        if(array_key_exists($loadedColumnGroupings[$i]["column_name"], $resultRow)) {
    		if (
    		    ($resultRow[$loadedColumnGroupings[$i]["column_name"]] > 0) ||
    		    (($resultRow[$loadedColumnGroupings[$i]["column_name"]] !== 0) &&
    		     ($resultRow[$loadedColumnGroupings[$i]["column_name"]] !== '0'))
    		    ) {
    		        /*echo "(Y)";*/
    		        //Match found for columns. Write record
    		        if ($colourCells == 1) {
    		            if ($reportID == 6) {
    		                $umpireTypeName = $resultRow['umpire_type_name'];
    		            } else {
    		                $umpireTypeName = NULL;
    		            }
    		            //$cellClassToUse = getCellClassNameFromOutputValue((int)$resultRow[$loadedColumnGroupings[$i]["column_name"]], FALSE);
    		            $cellClassToUse = "divCell";
    		        } elseif(is_numeric($resultRow[$loadedColumnGroupings[$i]["column_name"]])) {
    		            $cellClassToUse = "divCell divCellNumber";
    		        } else {
    		            $cellClassToUse = "divCell divCellText";
    		        }
    		        
    		        if ($loadedColumnGroupings[$i]["column_name"] == "Total") {
    		            $cellClassToUse .= " cellTextTotal";
    		        }
    		        $cellValue = $resultRow[$loadedColumnGroupings[$i]["column_name"]];
    		        
    		} else {
    		    if(is_numeric($resultRow[$loadedColumnGroupings[$i]["column_name"]])) {
    		      $cellClassToUse = "divCell divCellNumber";
    		    } else {
    		      $cellClassToUse = "divCell divCellText";
    		    }
    		    $cellValue = $noDataValueToDisplay;
    		}
		} else {
		    //Array key does not exist
		    $cellClassToUse = "divCell divCellText";
		    $cellValue = $noDataValueToDisplay;
		}
		
		//Add on formatting for cells if matrix values are the same (e.g. row = column)
		if ($reportID == 6) {
		    //echo "A(". $resultRow[$rowLabels[0]] .") B(". $loadedColumnGroupings[$i]["column_name"] .") ";
		    if ($resultRow["umpire_name"] == $loadedColumnGroupings[$i]["column_name"]) {
		        $cellClassToUse .= " divCellRowMatchesColumn";
		    }
		}
		//$tableRowOutput .=  "<div class='divCell'>" . $cellValue . "</div>";
		$tableRowOutput .=  "<div class='". $cellClassToUse ."'>" . $cellValue . "</div>";
	}   
	
	/*if (count($rowLabels) == 1) {
	    
	}*/

	$tableRowOutput .= "</div>";
	echo $tableRowOutput;
		       
	$countOfRowsDisplayed++;

endforeach;
echo "</div>";
echo "</div>";

?>

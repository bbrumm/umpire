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
$reportID = $loadedReportItem->getReportID();

//$countLoadedColumnGroupings = count($loadedColumnGroupings);
$countFirstLoadedColumnGroupings = count($columnLabels);

$columnCountForHeadingCells = $loadedReportItem->getColumnCountForHeadingCells();

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
?>
<div width='90%'>
<table class='reportTable'>

<thead>

<?php 
//$debugLibrary->debugOutput("test");

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

//Show one header row for each group
for ($i=0; $i < $countFirstLoadedColumnGroupings; $i++) {
    //Load array that shows each column heading and the number of records it has.
	//$countOfEachColumnHeading = array_count_values(array_column($loadedColumnGroupings, $columnLabels[$i]));
	?>
	
	<tr class='table_summary_thead'>
	
	<?php
	$thOutput;
	$thClassNameToUse;
	
	if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
	    $thClassNameToUse = "columnHeadingNormal cellNameSize";
	} elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
	    $thClassNameToUse = "columnHeadingNormal cellDateSize";
	} else {
	    $thClassNameToUse = "columnHeadingNormal cellNameSize";
	}

	//$thClassNameToUse = 'rotated_vertical_td';
	
	for ($r=0; $r < count($rowLabels); $r++) {
    	$thOutput = "<td class='". $thClassNameToUse ."'>";
    	if ($i == 0) {
    	    
    	    $thOutput .= $columnHeadingLabels[$r];
    	    $thOutput .= "<BR /><span class='columnSizeText'>". $columnHeadingSizeText[$r] ."</span>";
    	}
    	$thOutput .= "</th>";
    	echo $thOutput;
	}
	
	
	$countLoadedColumnGroupings = count($columnCountForHeadingCells[$i]);
	//echo "countLoadedColumnGroupings: ". $countLoadedColumnGroupings;
	?>
	

	<?php
	for ($j=0; $j < $countLoadedColumnGroupings; $j++) {
		//Check if cell should be merged
		if ($j==0) {
			$proceed = true;
		} 
		
		if ($proceed) {
			//print cell with colspan value
			if ($columnLabels[$i] == 'club_name' || $reportID == 6) {
				//Some reports have their headers displayed differently
				$colspanForCell = 1;
				$cellClass = "rotated_vertical_td";
				$innerDivClass = "rotated_vertical_outer";
				$outerDivClass = "rotated_vertical_inner";
			} else {
			    //Increase the colspan if this column group is to be merged
			    if ($mergeColumnGroup[$i] == TRUE) {
				    //$colspanForCell = $countOfEachColumnHeading[$loadedColumnGroupings[$j][$columnLabels[$i]]];
				    //$arrayKeyNumber = $loadedReportItem->findKeyFromValue($columnCountForHeadingCells[$i], $columnLabels[$i], "label")
			        $colspanForCell = $columnCountForHeadingCells[$i][$j]["count"];
			    } else {
			        $colspanForCell = 1;
			    }
			    
			    $cellClass = "columnHeadingNormal";
			    $innerDivClass = "normalHeadingText";
			    $outerDivClass = "normalHeadingText";
			    
			    
				//$cellClass = "columnHeadingNormal";
				//$divClass = "normalHeadingText";
			}
			//echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$loadedColumnGroupings[$j][$columnLabels[$i]]."</div></th>";
			//echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$columnCountForHeadingCells[$i][$j]["label"]."</div></th>";
			if ($colspanForCell == 1) {
			    echo "<td class='$cellClass' style='width:3%;'>";
			} else {
			    echo "<td class='$cellClass' colspan='$colspanForCell' style='width:3%;'>";
			}
			echo "<div class='$innerDivClass'>";
			$outputValue = $columnCountForHeadingCells[$i][$j]["label"];
			$outputValueNoSpaces = str_replace(' ', '_', $columnCountForHeadingCells[$i][$j]["label"]);
			//echo "<div class='$outerDivClass ". $columnCountForHeadingCells[$i][$j]["label"] ."'>" . $columnCountForHeadingCells[$i][$j]["label"]."</div></div></td>";
			//echo "<div class='$outerDivClass ". $outputValueNoSpaces ."'></div></div></td>";
			echo "<div class='$outerDivClass'>". $outputValue ."</div></div></td>";
			
			//echo "<style type='text/css'>.". $outputValueNoSpaces .":after {content:'". $outputValueNoSpaces ."'; }</style>";
			
			//TODO: Does a DIV need to go here??
		}
		//else
		//nothing - don't even write a td		
	}
	/*
	if ($loadedReportItem->getReportID() == 2) {
	    if ($i == 0) {
	        //Display the Total column if we are looking at report 2
	        echo "<th class='columnHeadingNormal' colspan='1'><div class='$divClass'>Total</div></th>";
	    } else {
	        echo "<th class='columnHeadingNormal' colspan='1'><div class='$divClass'> </div></th>";
	    }
	}*/
	//echo "<td>".$a."</td>";
	?>
	</tr>
	
<?php	
}
echo "</thead>";

$loopCounter = 0;

$matchFound = false;

echo "<tbody>";


//** Loop through rows and output them to the page **

//$currentReportRowLabel;
//$currentColumnLabelFirst;
//$currentColumnLabelSecond;

$tableRowOutput = "";


foreach ($loadedResultArray as $resultRow): 
	if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
	   $tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]] . "</td>";
	   if (count($rowLabels) > 1) {
	       //Output a second row label
	       $tableRowOutput .= "<td class='cellNormal'>" . $resultRow[$rowLabels[1]] . "</td>";
	   }
	} elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
	    $weekDate = date_create($resultRow[$rowLabels[0]]);
	    $tableRowOutput = "<tr><td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
	    if (count($rowLabels) > 1) {
	       //Output a second row label
	       $tableRowOutput .= "<td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
	   }
	} else {
	    $tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]] . "</td>";
	    if (count($rowLabels) > 1) {
	        //Output a second row label
	        $tableRowOutput .= "<td class='cellNormal'>" . $resultRow[$rowLabels[1]] . "</td>";
	    }
	}
	
	//$currentReportRowLabel = $resultRow[$rowLabels[0]];
	
	
	//Loop through each of the columns to be displayed
	for ($i=0; $i < $countLoadedColumnGroupings; $i++) {
		//$currentColumnLabelFirst = $loadedColumnGroupings[$i][$columnLabels[0]];
		//$currentColumnLabelSecond = $loadedColumnGroupings[$i][$columnLabels[1]];
/*echo "X=(". $resultRow[$loadedColumnGroupings[$i]['column_name']] ."),";*/
	    
		if (
		    ($resultRow[$loadedColumnGroupings[$i]["column_name"]] > 0) ||
		    (($resultRow[$loadedColumnGroupings[$i]["column_name"]] !== 0) &&
		     ($resultRow[$loadedColumnGroupings[$i]["column_name"]] !== '0'))
		    ) {
		        /*echo "(Y)";*/
		        //Match found for columns. Write record
		        if ($colourCells) {
		            $cellClassToUse = getCellClassNameFromOutputValue((int)$resultRow[$loadedColumnGroupings[$i]["column_name"]]);
		            
    		        //$tableRowOutput .=  "<td class='". getCellClassNameFromOutputValue($resultRow[$loadedColumnGroupings[$i]["column_name"]]) ."'>" . $resultRow[$loadedColumnGroupings[$i]["column_name"]] . "</td>";
		        } elseif(is_numeric($resultRow[$loadedColumnGroupings[$i]["column_name"]])) {
		            $cellClassToUse = "cellNumber cellNormal";
		        } else {
		            $cellClassToUse = "cellText cellNormal";
		        }
		        
		        if ($loadedColumnGroupings[$i]["column_name"] == "Total") {
		            $cellClassToUse .= " cellTextTotal";
		        }
		        
		        
		        
		        $cellValue = $resultRow[$loadedColumnGroupings[$i]["column_name"]];
		        
		        /*
		        $tableRowOutput .=  "<td class='". $cellClassToUse ."'>" . $resultRow[$loadedColumnGroupings[$i]["column_name"]] . 
		        "A(". $resultRow[$rowLabels[0]] .") B(". $loadedColumnGroupings[$i]["column_name"] .") " .
		        "</td>";
		        */
		} else {
		    if(is_numeric($resultRow[$loadedColumnGroupings[$i]["column_name"]])) {
		      $cellClassToUse = "cellNumber cellNormal";
		    } else {
		      $cellClassToUse = "cellNormal";
		    }
		    $cellValue = $noDataValueToDisplay;
		    //$tableRowOutput .= "<td class='cellNormal'>". $noDataValueToDisplay ."</td>";
		    //$tableRowOutput .= "<td class='cellNormal'>". $noDataValueToDisplay ."</td>";
		}
		
		//Add on formatting for cells if matrix values are the same (e.g. row = column)
		if ($reportID == 6) {
		    //print "A(". $resultRow[$rowLabels[0]] .") B(". $loadedColumnGroupings[$i]["column_name"] .") ";
		    if ($resultRow[$rowLabels[0]] == $loadedColumnGroupings[$i]["column_name"]) {
		        $cellClassToUse .= " cellRowMatchesColumn";
		    }
		}
		
		$tableRowOutput .=  "<td class='". $cellClassToUse ."'>" . $cellValue . "</td>";
     
	}    
	
	
	
	
	$tableRowOutput .= "</tr>";
	echo $tableRowOutput;
		        

endforeach;

echo "</tbody>";
?>

</table>
</div>
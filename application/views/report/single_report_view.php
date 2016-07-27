<?php


//LoadedReportItem is an object of type UserReport
$loadedColumnGroupings = $loadedReportItem->getColumnLabelResultArray();
$loadedResultArray = $loadedReportItem->getResultArray();
$reportDisplayOptions = $loadedReportItem->getDisplayOptions();
$columnLabels = $reportDisplayOptions->getColumnGroup();
//$mergeColumnGroup = $reportDisplayOptions->getMergeColumnGroup();
$colourCells = $reportDisplayOptions->getColourCells();
$rowLabels = $reportDisplayOptions->getRowGroup();
$fieldToDisplay = $reportDisplayOptions->getFieldToDisplay();
$noDataValueToDisplay = $reportDisplayOptions->getNoDataValue();
//$debugLibrary = new DebugLibrary();
$debugMode = $this->config->item('debug_mode');
$reportID = $loadedReportItem->getReportID();

//$countLoadedColumnGroupings = count($loadedColumnGroupings);
$countFirstLoadedColumnGroupings = count($columnLabels);
/*if ($debugMode) {
    echo "<BR />loadedReportItem<pre>";
    print_r($loadedReportItem);
    echo "</pre><BR />";
}*/
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

<!--<section class=''>
<div class="container">-->
<div id='panelBelow'>
<div id='moveLeftDown'>
<table class='reportTable tableWithFloatingHeader'>

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
 
}

//Show one header row for each group
for ($i=0; $i < $countFirstLoadedColumnGroupings; $i++) {
    //Load array that shows each column heading and the number of records it has.
	//$countOfEachColumnHeading = array_count_values(array_column($loadedColumnGroupings, $columnLabels[$i]));
	?>
	
	<tr class='header'>
	
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
	
	$arrReportRowGroup = $reportDisplayOptions->getRowGroup(); //Array of ReportGroupingStructure objects
	$arrReportColumnGroup = $reportDisplayOptions->getColumnGroup();
	
	//for ($r=0; $r < count($rowLabels); $r++) {
	for ($r=0; $r < count($arrReportRowGroup); $r++) {
    	$thOutput = "<th class='". $thClassNameToUse ."'>";
    	if ($i == 0) {
    	    
    	    $thOutput .= $arrReportRowGroup[$r]->getGroupHeading();
    	    $thOutput .= "<BR /><span class='columnSizeText'>". $arrReportRowGroup[$r]->getGroupSizeText() ."</span>";
    	    //$thOutput .= $columnHeadingLabels[$r];
    	    //$thOutput .= "<BR /><span class='columnSizeText'>". $columnHeadingSizeText[$r] ."</span>";
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
				if ($PDFLayout) {
					//$cellClass = "rotatePDF";
					$cellClass = "rotated_cell_pdf";
					$divClass = "rotated_text_pdf";
					
				} else {
					$cellClass = "rotated_cell";
					$divClass = "rotated_text";
				}
			} else {
			    //Increase the colspan if this column group is to be merged
			    if ($arrReportColumnGroup[$i]->getMergeField() == 1) {
				    //$colspanForCell = $countOfEachColumnHeading[$loadedColumnGroupings[$j][$columnLabels[$i]]];
				    //$arrayKeyNumber = $loadedReportItem->findKeyFromValue($columnCountForHeadingCells[$i], $columnLabels[$i], "label")
			        /*TODO: There is a bug here. When a report has two column headings with the same value,
			        but they don't need to be grouped (i.e. Report 2, for Seniors and Reservers, should show
			        Seniors, then Reserves, then Seniors 2 Umpires), this code is grouping it anyway.
			        This is because it counts the number of values in the heading group, and in this case, 
			        it finds 2.
			        How do I fix it? 
			        Is it better to redesign this "column label" logic to improve the query,
			        and add this config data into the database?
			        What config would I need to add? It would need to be specific for a report (i.e. report 2)
			        Columns in the report could have "merge groups", which are independent of the column labels.
			        This way, a column with the heading of "Seniors" could be separated from other columns with 
			        headings of "Seniors".
			        When I do this, I can also move other report config data to the database.
			        This would make maintenance easier and clean up the code.
			        A lot of this information currently sits in UserReport.php.
			        
			        Possible Solution:
			        - Add report parameters to database
			        - Move this colspan count to a function
			        - The function would not include the 2 Umpires field in the count of cells
			        
			        UPDATE: This has been resolved by updating the report_column.display_order of a column.
			        Now, the report shows similar columns together. E.g. Seniors BFL, Seniors 2 Umpires, then Reserves BFL.
			        Instead of Seniors BFL, Reserves BFL, Seniors 2 Umpires.
			        
			        */
			        
			        $colspanForCell = $columnCountForHeadingCells[$i][$j]["count"];
			         
			        
			    } else {
			        $colspanForCell = 1;
			    }
				
				
				$cellClass = "columnHeadingNormal";
				$divClass = "normalHeadingText";
			}
			//echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$loadedColumnGroupings[$j][$columnLabels[$i]]."</div></th>";
			echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$columnCountForHeadingCells[$i][$j]["label"]."</div></th>";
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
if ($debugMode) {
    echo "<pre>rowLabels ";
    print_r($rowLabels);
    echo "</pre>";
}

foreach ($loadedResultArray as $resultRow): 
	if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
	   $tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]->getFieldName()] . "</td>";
	   if (count($rowLabels) > 1) {
	       //Output a second row label
	       $tableRowOutput .= "<td class='cellNormal'>" . $resultRow[$rowLabels[1]->getFieldName()] . "</td>";
	   }
	} elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
	    $weekDate = date_create($resultRow[$rowLabels[0]]);
	    $tableRowOutput = "<tr><td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
	    if (count($rowLabels) > 1) {
	       //Output a second row label
	       $tableRowOutput .= "<td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
	   }
	} else {
	    $tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]->getFieldName()] . "</td>";
	    if (count($rowLabels) > 1) {
	        //Output a second row label
	        $tableRowOutput .= "<td class='cellNormal'>" . $resultRow[$rowLabels[1]->getFieldName()] . "</td>";
	    }
	}
	
	//$currentReportRowLabel = $resultRow[$rowLabels[0]];
	/*
	echo "<pre>";
	print_r($resultRow);
	echo "</pre>";
	*/
	//Loop through each of the columns to be displayed
	for ($i=0; $i < $countLoadedColumnGroupings; $i++) {
		//$currentColumnLabelFirst = $loadedColumnGroupings[$i][$columnLabels[0]];
		//$currentColumnLabelSecond = $loadedColumnGroupings[$i][$columnLabels[1]];
		
	    
//echo "X=(". $resultRow[$loadedColumnGroupings[$i]['column_name']] ."),";
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
    		        
    		        
    		        
    		        if (strpos($loadedColumnGroupings[$i]["column_name"],"Pct") !== false) {
    		            $cellValue .= "%";
    		        }
    		        
    		        
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
    	} else {
    	    //Array key does not exist
    	    $cellClassToUse = "cellNormal";
    	    $cellValue = $noDataValueToDisplay;
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
</div>
<!--
</div>
</section>
-->


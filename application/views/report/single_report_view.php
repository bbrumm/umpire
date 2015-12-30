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
$countLoadedColumnGroupings = count($loadedColumnGroupings);
$countFirstLoadedColumnGroupings = count($columnLabels);
$columnCountForHeadingCells = $loadedReportItem->getColumnCountForHeadingCells();

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
?>
<!-- 
<div style="width:3000px">
        some really really wide content goes here
    </div>
     -->

<!--<section class=''>
<div class="container">-->
<table class='reportTable tableWithFloatingHeader'>

<thead>

<?php 


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
/*
echo "<BR />loadedResultArray<pre>";
print_r($loadedResultArray);
echo "</pre><BR />";
*/




//Show one header row for each group
for ($i=0; $i < $countFirstLoadedColumnGroupings; $i++) {
    //Load array that shows each column heading and the number of records it has.
	$countOfEachColumnHeading = array_count_values(array_column($loadedColumnGroupings, $columnLabels[$i]));
	
	
	
	echo "<BR />countOfEachColumnHeading<pre>";
	print_r($countOfEachColumnHeading);
	echo "</pre><BR />";
	
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
	
	$thOutput = "<th class='". $thClassNameToUse ."'>";
	
	if ($i == ($countFirstLoadedColumnGroupings-1)) {
	    $thOutput .= $reportDisplayOptions->getFirstColumnHeadingLabel();
	}
	$thOutput .= "</th>";
	echo $thOutput;
	?>
	

	<?php
	for ($j=0; $j < $countLoadedColumnGroupings; $j++) {
		//Check if cell should be merged
		if ($j==0) {
			$proceed = true;
		} else {
			if (($mergeColumnGroup[$i] != TRUE) || ($loadedColumnGroupings[$j][$columnLabels[$i]] != $loadedColumnGroupings[$j-1][$columnLabels[$i]])) {
				//proceed
				$proceed = true;
			} else {
				$proceed = false;
			}
		}
		
		if ($proceed) {
			//print cell with colspan value
			if ($columnLabels[$i] == 'club_name') {
				//Club names are displayed differently
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
			    if ($mergeColumnGroup[$i] == TRUE) {
				    $colspanForCell = $countOfEachColumnHeading[$loadedColumnGroupings[$j][$columnLabels[$i]]];
			    } else {
			        $colspanForCell = 1;
			    }
				
				
				$cellClass = "columnHeadingNormal";
				$divClass = "normalHeadingText";
			}
			echo "<th class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$loadedColumnGroupings[$j][$columnLabels[$i]]."</div></th>";
			//TODO: Does a DIV need to go here??
		}
		//else
		//nothing - don't even write a td		
	}
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
$currentColumnLabelFirst;
$currentColumnLabelSecond;

$tableRowOutput;


foreach ($loadedResultArray as $resultRow): 
	if ($reportDisplayOptions->getFirstColumnFormat() == "text") {
	   $tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]] . "</td>";
	} elseif ($reportDisplayOptions->getFirstColumnFormat() == "date") {
	    $weekDate = date_create($resultRow[$rowLabels[0]]);
	    $tableRowOutput = "<tr><td class='cellNormal'>" . date_format($weekDate, 'd/m/Y') . "</td>";
	} else {
	    $tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]] . "</td>";
	}
	
	//$currentReportRowLabel = $resultRow[$rowLabels[0]];
	
	//Loop through each of the columns to be displayed
	for ($i=0; $i < $countLoadedColumnGroupings; $i++) {
		$currentColumnLabelFirst = $loadedColumnGroupings[$i][$columnLabels[0]];
		$currentColumnLabelSecond = $loadedColumnGroupings[$i][$columnLabels[1]];
/*echo "X=(". $resultRow[$loadedColumnGroupings[$i]['column_name']] ."),";*/
		if (
		    ($resultRow[$loadedColumnGroupings[$i]["column_name"]] > 0) ||
		    ($resultRow[$loadedColumnGroupings[$i]["column_name"]] !== 0)
		    ) {
		        /*echo "(Y)";*/
		        //Match found for columns. Write record
		        if ($colourCells) {
		            $cellClassToUse = getCellClassNameFromOutputValue($resultRow[$loadedColumnGroupings[$i]["column_name"]]);
		            
    		        //$tableRowOutput .=  "<td class='". getCellClassNameFromOutputValue($resultRow[$loadedColumnGroupings[$i]["column_name"]]) ."'>" . $resultRow[$loadedColumnGroupings[$i]["column_name"]] . "</td>";
		        } elseif(is_numeric($resultRow[$loadedColumnGroupings[$i]["column_name"]])) {
		            $cellClassToUse = "cellNumber cellNormal";
		        } else {
		            $cellClassToUse = "cellText cellNormal";
		        }
		        $tableRowOutput .=  "<td class='". $cellClassToUse ."'>" . $resultRow[$loadedColumnGroupings[$i]["column_name"]] . "</td>";
		} else {
		    $tableRowOutput .= "<td class='cellNormal'>". $noDataValueToDisplay ."</td>";
		}
     
	}    
		        
	$tableRowOutput .= "</tr>";
	echo $tableRowOutput;
		        

endforeach;

echo "</tbody>";
?>

</table>
<!--
</div>
</section>
-->
<?php
$loadedColumnGroupings = $loadedReportItem->getColumnLabelResultArray();
$loadedResultArray = $loadedReportItem->getResultArray();
$reportDisplayOptions = $loadedReportItem->getDisplayOptions();
$columnLabels = $reportDisplayOptions->getColumnGroup();
$mergeColumnGroup = $reportDisplayOptions->getMergeColumnGroup(); //Not yet implemented. It can be used if it is needed.
$rowLabels = $reportDisplayOptions->getRowGroup();
$fieldToDisplay = $reportDisplayOptions->getFieldToDisplay();
$noDataValueToDisplay = $reportDisplayOptions->getNoDataValue();
$countLoadedColumnGroupings = count($loadedColumnGroupings);
$countFirstLoadedColumnGroupings = count($columnLabels);

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<table class='reportTable'>";

echo "<thead>";

//Show one header row for each group
for ($i=0; $i < $countFirstLoadedColumnGroupings; $i++) {
    //Load array that shows each column heading and the number of records it has.
	$countOfEachColumnHeading = array_count_values(array_column($loadedColumnGroupings, $columnLabels[$i]));
	?>
	
	<tr>
	<td class='columnHeadingNormal cellNameSize'>
	<?php
	if ($i == ($countFirstLoadedColumnGroupings-1)) {
		echo "Name";
	}
	?>
	</td>

	<?php
	for ($j=0; $j < $countLoadedColumnGroupings; $j++) {
		//Check if cell should be merged
		if ($j==0) {
			$proceed = true;
		} else {
			if ($loadedColumnGroupings[$j][$columnLabels[$i]] != $loadedColumnGroupings[$j-1][$columnLabels[$i]]) {
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
				$colspanForCell = $countOfEachColumnHeading[$loadedColumnGroupings[$j][$columnLabels[$i]]];
				$cellClass = "columnHeadingNormal";
				$divClass = "normalHeadingText";
			}
			echo "<td class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$loadedColumnGroupings[$j][$columnLabels[$i]]."</div></td>";
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
//Loop through rows

$currentReportRowLabel;
$currentColumnLabelFirst;
$currentColumnLabelSecond;

$tableRowOutput;


foreach ($loadedResultArray as $resultRow): 
	
	$tableRowOutput = "<tr><td class='cellNormal'>" . $resultRow[$rowLabels[0]] . "</td>";
	
	$currentReportRowLabel = $resultRow[$rowLabels[0]];
	
	//Loop through each of the columns to be displayed
	for ($i=0; $i < $countLoadedColumnGroupings; $i++) {
		$currentColumnLabelFirst = $loadedColumnGroupings[$i][$columnLabels[0]];
		$currentColumnLabelSecond = $loadedColumnGroupings[$i][$columnLabels[1]];

		if ($resultRow[$loadedColumnGroupings[$i]["column_name"]] > 0) {
		        //Match found for columns. Write record
		        $tableRowOutput .=  "<td class='". getCellClassNameFromOutputValue($resultRow[$loadedColumnGroupings[$i]["column_name"]]) ."'>" . $resultRow[$loadedColumnGroupings[$i]["column_name"]] . "</td>";
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


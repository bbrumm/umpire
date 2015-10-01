<?php

$loadedColumnGroupings = $loadedReportItem->getColumnGroupingArray();
$loadedRowGroupings = $loadedReportItem->getRowGroupingArray();
$loadedResultArray = $loadedReportItem->getResultArray();
$reportDisplayOptions = $loadedReportItem->getDisplayOptions();
$columnLabels = $reportDisplayOptions->getColumnGroup();
$mergeColumnGroup = $reportDisplayOptions->getMergeColumnGroup(); //Not yet implemented. It can be used if it is needed.
$rowLabels = $reportDisplayOptions->getRowGroup();
$fieldToDisplay = $reportDisplayOptions->getFieldToDisplay();
$noDataValueToDisplay = $reportDisplayOptions->getNoDataValue();



echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<table class='reportTable'>";

echo "<thead>";
//Show one header row for each group
for ($i=0; $i < count($loadedColumnGroupings[0]); $i++) {
	
	$countOfEachColumnHeading = array_count_values(array_column($loadedColumnGroupings, $i));
	/*
	echo "Count Group:<BR /><pre>";
	print_r($countOfEachColumnHeading);
	echo "</pre>";
	*/
	?>
	
	<tr>
	<td class='columnHeadingNormal'>
	<?php
	if ($i == (count($loadedColumnGroupings[0])-1)) {
		echo "Name";
	}
	?>
	</td>

	<?php
	for ($j=0; $j < count($loadedColumnGroupings); $j++) {
		//Check if cell should be merged
		if ($j==0) {
			$proceed = true;
		} else {
			if ($loadedColumnGroupings[$j][$i] != $loadedColumnGroupings[$j-1][$i]) {
				//proceed
				$proceed = true;
			} else {
				$proceed = false;
			}
		}
		
		if ($proceed) {
			//print cell with colspan value
			//TODO: RESUME HERE
			//echo "input(".$loadedColumnGroupings[$j][$i].")";
			$colspanForCell = $countOfEachColumnHeading[$loadedColumnGroupings[$j][$i]];
			//echo "COLSPAN FOR CELL(".$colspanForCell.")<BR />";
			if ($columnLabels[$i] == 'club_name') {
				//Club names are displayed differently
				$cellClass = "rotate";
			} else {
				$cellClass = "columnHeadingNormal";
			}
			
			echo "<th class='$cellClass' colspan='$colspanForCell'><div>".$loadedColumnGroupings[$j][$i]."</div></th>";
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
/*
echo count($loadedRowGroupings) . "<BR />"; 
echo count($loadedResultArray) . "<BR />"; 
echo count($loadedColumnGroupings[0]) . "<BR />";
echo count($loadedColumnGroupings) . "<BR />";
*/

$loopCounter = 0;

$matchFound = false;
echo "<tbody>";
//Loop through rows
//foreach ($loadedResultArray as $reportItem): 
foreach ($loadedRowGroupings as $reportRow): 
	$loopCounter++;
	
	echo "<tr>";
	echo "<td class='cellNormal'>";
	echo $reportRow[0];
	echo "</td>";
	$this->benchmark->mark('point 05.1');
	//Check if result array matches
	for ($i=0; $i < count($loadedColumnGroupings); $i++) {
		$loopCounter++;
		$matchFound = false;
	
		foreach ($loadedResultArray as $resultRow): 
			$loopCounter++;
			
			if ($resultRow[$rowLabels[0]] == $reportRow[0]) {
				//Row value matches. Check columns
				if ($resultRow[$columnLabels[0]] == $loadedColumnGroupings[$i][0] && $resultRow[$columnLabels[1]] == $loadedColumnGroupings[$i][1]) {
					//Column values match.
					//Check for value, then apply conditional formatting
					echo "<td class='". getCellClassNameFromOutputValue($resultRow[$fieldToDisplay]) ."'>" . $resultRow[$fieldToDisplay] . "</td>";
					$matchFound = true;
					break 1;
				}
			}
		endforeach;
		
		if ($matchFound == false) {
			echo "<td class='cellNormal'>". $noDataValueToDisplay ."</td>";
		}
	}
	echo "</tr>";
	$this->benchmark->mark('point 05.2');
endforeach;
	

echo "</tbody>";
//echo "Loop counter: " . $loopCounter ."<BR />";	




//echo "05.2(". $this->benchmark->elapsed_time('point 05.1', 'point 05.2') .")<BR />";

?>

</table>


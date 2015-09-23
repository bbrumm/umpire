<?php

$loadedColumnGroupings = $loadedReportItem->getColumnGroupingArray();
$loadedRowGroupings = $loadedReportItem->getRowGroupingArray();
$loadedResultArray = $loadedReportItem->getResultArray();
$reportDisplayOptions = $loadedReportItem->getDisplayOptions();
$columnLabels = $reportDisplayOptions->getColumnGroup();
$rowLabels = $reportDisplayOptions->getRowGroup();
$fieldToDisplay = $reportDisplayOptions->getFieldToDisplay();
$noDataValueToDisplay = $reportDisplayOptions->getNoDataValue();

/*echo "Column groups:";
echo "<BR />";
*/
echo "<pre>";
print_r($_POST);
echo "</pre>";

/*
echo "Row groups:";
echo "<BR />";
echo "<pre>";
//print_r($loadedRowGroupings);
echo "</pre>";
*/

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<table border='1'>";

//Show one header row for each group
for ($i=0; $i < count($loadedColumnGroupings[0]); $i++) {
	?>
	<tr>
	<td>
	<?php
	if ($i == (count($loadedColumnGroupings[0])-1)) {
		echo "Name";
	}
	?>
	</td>

	<?php
	for ($j=0; $j < count($loadedColumnGroupings); $j++) {
		echo "<td>".$loadedColumnGroupings[$j][$i]."</td>";
	}
	//echo "<td>".$a."</td>";
	?>
	</tr>
<?php	
}
/*
echo count($loadedRowGroupings) . "<BR />"; 
echo count($loadedResultArray) . "<BR />"; 
echo count($loadedColumnGroupings[0]) . "<BR />";
echo count($loadedColumnGroupings) . "<BR />";
*/

$loopCounter = 0;

$matchFound = false;

//Loop through rows
//foreach ($loadedResultArray as $reportItem): 
foreach ($loadedRowGroupings as $reportRow): 
	$loopCounter++;
	
	echo "<tr>";
	echo "<td>";
	echo $reportRow[0];
	echo "</td>";
	
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
					echo "<td>" . $resultRow[$fieldToDisplay] . "</td>";
					
					$matchFound = true;
					break 1;
				}
			}
		endforeach;
		
		if ($matchFound == false) {
			echo "<td>". $noDataValueToDisplay ."</td>";
		}
	}
endforeach;
echo "</tr>";	

echo "Loop counter: " . $loopCounter;	

?>

</table>


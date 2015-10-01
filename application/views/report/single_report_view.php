<?php
$this->benchmark->mark('point 01');
$loadedColumnGroupings = $loadedReportItem->getColumnGroupingArray();
$loadedRowGroupings = $loadedReportItem->getRowGroupingArray();
$loadedResultArray = $loadedReportItem->getResultArray();
$reportDisplayOptions = $loadedReportItem->getDisplayOptions();
$columnLabels = $reportDisplayOptions->getColumnGroup();
$mergeColumnGroup = $reportDisplayOptions->getMergeColumnGroup();
$rowLabels = $reportDisplayOptions->getRowGroup();
$fieldToDisplay = $reportDisplayOptions->getFieldToDisplay();
$noDataValueToDisplay = $reportDisplayOptions->getNoDataValue();
$this->benchmark->mark('point 02');
$countOfEachColumnHeading = array_count_values(array_column($loadedColumnGroupings, 0));
$this->benchmark->mark('point 03');
/*echo "Column groups:";
echo "<BR />";
*/
echo "<pre>";
print_r($_POST);
echo "</pre>";


echo "Merge Group:";
echo "<BR />";
echo "<pre>";
print_r($countOfEachColumnHeading);
echo "</pre>";
$this->benchmark->mark('point 04');

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<table class='reportTable'>";


//Show one header row for each group
for ($i=0; $i < count($loadedColumnGroupings[0]); $i++) {
	?>
	<tr>
	<td class='cellNormal'>
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
			//$colspanForCell = $countOfEachColumnHeading[$loadedColumnGroupings[$j][$i]];
			//echo "COLSPAN FOR CELL(".$colspanForCell.")<BR />";
			if ($columnLabels[$i] == 'club_name') {
				//Club names are displayed differently
				$cellClass = "rotate";
			} else {
				$cellClass = "columnHeadingNormal";
			}
			
			echo "<th class='$cellClass'><div>".$loadedColumnGroupings[$j][$i]."</div></td>";
		}
		//else
		//nothing - don't even write a td		
	}
	//echo "<td>".$a."</td>";
	?>
	</tr>
<?php	
}
$this->benchmark->mark('point 05');
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
	$this->benchmark->mark('point 05.2');
endforeach;
echo "</tr>";	

echo "Loop counter: " . $loopCounter ."<BR />";	

$this->benchmark->mark('point 06');

echo "02(". $this->benchmark->elapsed_time('point 01', 'point 02') .")<BR />";
echo "03(". $this->benchmark->elapsed_time('point 02', 'point 03') .")<BR />";
echo "04(". $this->benchmark->elapsed_time('point 03', 'point 04') .")<BR />";
echo "05(". $this->benchmark->elapsed_time('point 04', 'point 05') .")<BR />";
echo "06(". $this->benchmark->elapsed_time('point 05', 'point 06') .")<BR />";
echo "05.2(". $this->benchmark->elapsed_time('point 05.1', 'point 05.2') .")<BR />";

?>

</table>


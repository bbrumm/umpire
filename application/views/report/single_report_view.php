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

$countLoadedColumnGroupings = count($loadedColumnGroupings);
$countFirstLoadedColumnGroupings = count($loadedColumnGroupings[0]);

$startTime = time();
/*
echo "<pre>";
print_r($loadedColumnGroupings);
echo "</pre>";
*/

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<table class='reportTable'>";

echo "<thead>";
//Show one header row for each group
for ($i=0; $i < $countFirstLoadedColumnGroupings; $i++) {
	//echo "<BR/>Count loadedColumnGroupings[0] = ". count($loadedColumnGroupings[0]) ."<BR/>";
	$countOfEachColumnHeading = array_count_values(array_column($loadedColumnGroupings, $i));
	/*
	echo "Count Group:<BR /><pre>";
	print_r($countOfEachColumnHeading);
	echo "</pre>";
	*/
	?>
	
	<tr>
	<td class='columnHeadingNormal cellNameSize'>
	<?php
	if ($i == ($countFirstLoadedColumnGroupings-1)) {
		echo "Name";
		/*echo "<div class='cellNameSize'> </div>";*/
	}
	?>
	</td>

	<?php
	//echo "<BR/>Count loadedColumnGroupings = ". count($loadedColumnGroupings) ."<BR/>";
	
	for ($j=0; $j < $countLoadedColumnGroupings; $j++) {
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
			
			if ($columnLabels[$i] == 'club_name') {
				//Club names are displayed differently
				$colspanForCell = 1;
				if ($PDFLayout) {
					$cellClass = "rotatePDF";
					$divClass = "rotate_text";
					
				} else {
					$cellClass = "rotated_cell";
					$divClass = "rotate_text";
				}
			} else {
				$colspanForCell = $countOfEachColumnHeading[$loadedColumnGroupings[$j][$i]];
				$cellClass = "columnHeadingNormal";
				$divClass = "normalHeadingText";
			}
			echo "<td class='$cellClass' colspan='$colspanForCell'><div class='$divClass'>".$loadedColumnGroupings[$j][$i]."</div></td>";
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

$set1 = 0;
$set2 = 0;
$set3 = 0;
$set4 = 0;
$set5 = 0;

echo "<tbody>";
//Loop through rows
//foreach ($loadedResultArray as $reportItem): 
//$this->benchmark->mark('point 05.1');
foreach ($loadedRowGroupings as $reportRow): 
	$loopCounter++;
	
	echo "<tr>";
	echo "<td class='cellNormal'>";
	echo $reportRow[0];
	echo "</td>";
	
	//Check if result array matches
	
	//echo "<BR/>Count loadedColumnGroupings = ". count($loadedColumnGroupings) ."<BR/>";
	//$set1start = time();
	
	for ($i=0; $i < $countLoadedColumnGroupings; $i++) {
		$loopCounter++;
		$matchFound = false;
		//$set2start = time();
		foreach ($loadedResultArray as $resultRow): 
			$loopCounter++;
			//$set3start = time();
			if ($resultRow[$rowLabels[0]] == $reportRow[0]) {
				//Row value matches. Check columns
				//$set4start = time();
				if ($resultRow[$columnLabels[0]] == $loadedColumnGroupings[$i][0]) {
					if($resultRow[$columnLabels[1]] == $loadedColumnGroupings[$i][1]) {
						//Column values match.
						//Check for value, then apply conditional formatting
						//$set5start = time();
						//echo "<td class='". getCellClassNameFromOutputValue($resultRow[$fieldToDisplay]) ."'>" . $resultRow[$fieldToDisplay] . "</td>";
						echo "<td class='cellNumber cellNormal'>" . $resultRow[$fieldToDisplay] . "</td>";
						$matchFound = true;
						break 1;
						//$set5 = $set5 + time() - $set5start;
					}
				}
				//$set4 = $set4 + time() - $set4start;
			}
			
			//$set3 = $set3 + time() - $set3start;
		endforeach;
		
		//$set2 = $set2 + time() - $set2start;
		
		
		if ($matchFound == false) {
			echo "<td class='cellNormal'>". $noDataValueToDisplay ."</td>";
		}
	}
	
	//$set1 = $set1 + time() - $set1start;
	
	echo "</tr>";
	
endforeach;
	//$this->benchmark->mark('point 05.2');

echo "</tbody>";
//echo "Loop counter: " . $loopCounter ."<BR />";	

$endTime = time();
echo "<BR />Display Report Time Taken (s) (" . ($endTime - $startTime) . ")<BR/>";
/*
echo "set1 ($set1)<BR/>";
echo "set2 ($set2)<BR/>";
echo "set3 ($set3)<BR/>";
echo "set4 ($set4)<BR/>";
echo "set5 ($set5)<BR/>";
*/
//echo "05.2(". $this->benchmark->elapsed_time('point 05.1', 'point 05.2') .")<BR />";

?>

</table>


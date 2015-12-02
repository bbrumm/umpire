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
echo "<BR/>loadedResultArray:<pre>";
print_r($loadedResultArray);
echo "</pre>";
*/

echo "<h1>". $loadedReportItem->getReportTitle() ."</h1>";
echo "<table class='reportTable'>";

echo "<thead>";
//Show one header row for each group
for ($i=0; $i < $countFirstLoadedColumnGroupings; $i++) {
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
					$cellClass = "rotated_cell";
					$divClass = "rotate_text";
					
				} else {
					$cellClass = "rotated_cell";
					$divClass = "rotate_text";
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
//$this->benchmark->mark('point 05.1');

$currentReportRowLabel;
$currentColumnLabelFirst;
$currentColumnLabelSecond;

/*
$set1 = 0;
$set2 = 0;
$set3 = 0;
$set4 = 0;
$set5 = 0;
*/

$tableRowOutput;


foreach ($loadedRowGroupings as $reportRow): 
	//$loopCounter++;
/*
	echo "reportRow:<BR /><pre>";
	print_r($reportRow);
	echo "</pre>";
	
	echo "columnLabels:<BR /><pre>";
	print_r($columnLabels);
	echo "</pre>";
	*/
	
	$tableRowOutput = "<tr><td class='cellNormal'>" . $reportRow[$rowLabels[0]] . "</td>";
	
	
	//Check if result array matches
	//$set1start = time();
	
	$currentReportRowLabel = $reportRow[$rowLabels[0]];
	
	//Loop through each of the columns to be displayed
	for ($i=0; $i < $countLoadedColumnGroupings; $i++) {
		//$loopCounter++;
		$matchFound = false;
		//$set2start = time();
		//$this->benchmark->mark('point 02 start');
		$currentColumnLabelFirst = $loadedColumnGroupings[$i][$columnLabels[0]];
		$currentColumnLabelSecond = $loadedColumnGroupings[$i][$columnLabels[1]];
		
		//Loop through each row record from the database
		/*
		loadedResultArray is in this format:
			[3559] => Array
			(
				[full_name] => Zarb, Jonathan
				[club_name] => Torquay
				[short_league_name] => None
				[match_count] => 1
			)
		
		*/
		
		
		foreach ($loadedResultArray as $resultRow): 
		
			
			//$set3start = time();
			//$this->benchmark->mark('point 03 start');
			
			//Check row labels match first, as there are more distinct values
			if ($resultRow[$rowLabels[0]] == $currentReportRowLabel) {
				//Row value matches. Check columns
				if ($resultRow[$columnLabels[0]] == $currentColumnLabelFirst) {
					if($resultRow[$columnLabels[1]] == $currentColumnLabelSecond) {
						
						//$loopCounter++;
						
						//Column values match.
						//Check for value, then apply conditional formatting
						//$set5start = time();
						$tableRowOutput .=  "<td class='". getCellClassNameFromOutputValue($resultRow[$fieldToDisplay]) ."'>" . $resultRow[$fieldToDisplay] . "</td>";
						//$tableRowOutput .= "<td class='cellNumber cellNormal'>" . $resultRow[$fieldToDisplay] . "</td>";
						$matchFound = true;
						
						//$set5 = $set5 + time() - $set5start;
						break 1;
						
					}
					
				}
				
				//$set4 = $set4 + time() - $set4start;
			}
			
			//$set3 = $set3 + time() - $set3start;
			//$this->benchmark->mark('point 03 end');
			
		endforeach;
		
		
		//$set2 = $set2 + time() - $set2start;
		//$this->benchmark->mark('point 02 end');
		
		if ($matchFound == false) {
			$tableRowOutput .= "<td class='cellNormal'>". $noDataValueToDisplay ."</td>";
		}
		
	}
	
	//$set1 = $set1 + time() - $set1start;
	
	//Output entire line here
	
	$tableRowOutput .= "</tr>";
	echo $tableRowOutput;
	
endforeach;

$this->benchmark->mark('point 05.2');

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
//echo "02(". $this->benchmark->elapsed_time('point 02 start', 'point 02 end') .")<BR />";
//echo "03(". $this->benchmark->elapsed_time('point 03 start', 'point 03 end') .")<BR />";

?>

</table>


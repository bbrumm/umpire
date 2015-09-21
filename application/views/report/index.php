<h2><?php echo $title; ?></h2>

<table border="1">

<?php
$loadedColumnGroupings = $loadedReportItem->getColumnGroupingArray();
$loadedRowGroupings = $loadedReportItem->getRowGroupingArray();
$loadedResultArray = $loadedReportItem->getResultArray();

/*echo "Column groups:";
echo "<BR />";
echo "<pre>";
print_r($loadedColumnGroupings);
echo "</pre>";
*/
/*
echo "Row groups:";
echo "<BR />";
echo "<pre>";
//print_r($loadedRowGroupings);
echo "</pre>";
*/

//Show one header row for each group
for ($i=0; $i < count($loadedColumnGroupings[0]); $i++) {
	?>
	<tr>
	<td>Name</td>

		
	<?php
	for ($j=0; $j < count($loadedColumnGroupings); $j++) {
	//foreach ($loadedColumnGroupings as $firstRowValue) {
		echo "<td>".$loadedColumnGroupings[$j][$i]."</td>";
	}
	
	//echo "<td>".$a."</td>";
	
	
	?>
		

	</tr>
<?php	
}

echo count($loadedRowGroupings) . "<BR />"; 
echo count($loadedResultArray) . "<BR />"; 
echo count($loadedColumnGroupings[0]) . "<BR />";
echo count($loadedColumnGroupings) . "<BR />";

$loopCounter = 0;


//Loop through entire result array
foreach ($loadedResultArray as $reportItem): 
$loopCounter++;

	//Check if result array matches
	foreach ($loadedRowGroupings as $reportRow): 
	$loopCounter++;
		
		if ($reportItem['full_name'] == $reportRow[0]) {
			//Matches the current output row. Check against the first row of column headings
			
			echo "<tr>";
			echo "<td>";
			echo $reportRow[0];
			echo "</td>";	
			
			for ($i=0; $i < count($loadedColumnGroupings); $i++) {
				$loopCounter++;
				if ($reportItem['short_league_name'] == $loadedColumnGroupings[$i][0]) {
					//Matches the first column grouping. Check against the second row of the column headings
					for ($j=0; $j < count($loadedColumnGroupings); $j++) {
						$loopCounter++;
						echo "TEST: " . $reportRow[0] . ", " . $loadedColumnGroupings[$i][0] . ", " . $loadedColumnGroupings[$i][1] . "<BR />";
						if ($reportItem['club_name'] == $loadedColumnGroupings[$i][1]) {
							//Matches the second column grouping. Output row.
							echo "<td>" . $loadedColumnGroupings[$i][0] . ", " . $loadedColumnGroupings[$i][1] . ", " . $reportItem['match_count'] . "</td>";
						
							//break 1;
						} else {
							//No match. Display 0
							echo "<td>0</td>";
							
						}
					}
					break 1;
				} else {
					echo "<td>n/a</td>";
				}
			}
			
			echo "</tr>";
			//break 1;
		}
	endforeach;

endforeach;	
echo "</tr>";

	/*
foreach ($loadedRowGroupings as $reportRow): 	
	echo "<tr>";
	echo "<td>";
	echo $reportRow[0];
	echo "</td>";
	

	
	
	foreach ($loadedResultArray as $reportItem): 			
		for ($i=0; $i < count($loadedColumnGroupings[0]); $i++) {
			for ($j=0; $j < count($loadedColumnGroupings); $j++) {
				//echo "TEST";
				//echo "<pre>";
				//print_r($reportItem);
				//echo "</pre>";
				//echo $reportItem['short_league_name'];
				
				if ($reportItem['short_league_name'] == $loadedColumnGroupings[$j][0] && $reportItem['club_name'] == $loadedColumnGroupings[$j][1]) {
					echo "<td>" . $reportItem['match_count'] . "</td>";
				} else {
					//No match. Display 0
					echo "<td>0</td>";
				}
				
			
			}
		}
	

	endforeach; 
	
	
	
	
	
	
endforeach; 
*/
?>

</table>


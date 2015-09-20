<h2><?php echo $title; ?></h2>

<table border="1">

<?php
$loadedColumnGroupings = $loadedReportItem->getColumnGroupingArray();
$loadedRowGroupings = $loadedReportItem->getRowGroupingArray();
$loadedResultArray = $loadedReportItem->getResultArray();

echo "Column groups:";
echo "<BR />";
//print_r($loadedColumnGroupings);

echo "Row groups:";
echo "<BR />";
//print_r($loadedRowGroupings);

//Show one header row for each group
foreach ($loadedColumnGroupings as $a) {
	?>
	<tr>
	<td>Name</td>

		
	<?php
	foreach ($a as $subvalue) {
		echo "<td>".$subvalue."</td>";
	}
	?>
		

	</tr>
<?php	
}


foreach ($loadedResultArray as $reportItem): ?>
	<tr>
		<td><?php echo $reportItem['full_name']; ?></td>
		<td><?php echo $reportItem['club_name']; ?></td>
		<td><?php echo $reportItem['short_league_name']; ?></td>
		<td><?php echo $reportItem['match_count']; ?></td>
	</tr>

<?php endforeach; ?>

</table>

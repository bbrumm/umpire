<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Umpire Report</title>
		
	</head>
	
	<?php 
	echo "<link rel='stylesheet' type='text/css' media='all' href='". asset_url() . $css ."' />";
	?>
<body>

<?php
echo "<h2>Data Test</h2>";

/*
echo "<pre>";
print_r($umpireTestResultsArray);
echo "</pre>";
*/
?>
<h3>Latest File Import Statistics</h3>
<table>
<thead>
<tr>
<th>Date and Time</th>
<th>Operation Name</th>
<th>Table Name</th>
<th>Row Count</th>
</tr>
</thead>
<?php 
foreach ($umpireTestResultsArray['tableOperations'] as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['operation_datetime'] ."</td>";
    echo "<td>". $val['operation_name'] ."</td>";
    echo "<td>". $val['table_name'] ."</td>";
    echo "<td>". $val['rowcount'] ."</td>";
    echo "</tr>";
}
?>
</table>
<br />
<h3>Umpire Match Count - Report 01 - Missing Data</h3>

<table>
<thead>
<tr>
<th>Umpire</th>
<th>Club</th>
<th>Region</th>
<th>Matches - Field (Match Import)</th>
<th>Matches - Field (MV)</th>
<th>Matches - Boundary (Match Import)</th>
<th>Matches - Boundary (MV)</th>
<th>Matches - Goal (Match Import)</th>
<th>Matches - Goal (MV)</th>
</tr>
</thead>
<?php 
foreach ($umpireTestResultsArray['report01'] as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['umpire_name'] ."</td>";
    echo "<td>". $val['club_name'] ."</td>";
    echo "<td>". $val['region_name'] ."</td>";
    echo "<td>". $val['mi_sum_field'] ."</td>";
    echo "<td>". $val['mv_sum_field'] ."</td>";
    echo "<td>". $val['mi_sum_boundary'] ."</td>";
    echo "<td>". $val['mv_sum_boundary'] ."</td>";
    echo "<td>". $val['mi_sum_goal'] ."</td>";
    echo "<td>". $val['mv_sum_goal'] ."</td>";
    echo "</tr>";
}
?>
</table>

<br />
<h3>Umpire Match Count - Report 08 - Data Differences</h3>

<table>
<thead>
<tr>
<th>Last Name</th>
<th>First Name</th>
<th>Prior Games - Umpire</th>
<th>Prior Games - Baseline</th>
</tr>
</thead>
<?php 
foreach ($umpireTestResultsArray['report08'] as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['last_name'] ."</td>";
    echo "<td>". $val['first_name'] ."</td>";
    echo "<td>". $val['umpire_prior'] ."</td>";
    echo "<td>". $val['baseline_prior'] ."</td>";
    echo "</tr>";
}
?>
</table>


</body>
</html>
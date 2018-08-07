<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Umpire Report - ETL Test</title>
		
	</head>
	
	<?php 
	echo "<link rel='stylesheet' type='text/css' media='all' href='". asset_url() . $css ."' />";
	?>
<body>

<?php
echo "<h2>ETL Test</h2>";

/*
echo "<pre>";
print_r($umpireTestResultsArray);
echo "</pre>";
*/
?>
<h3>Table Output</h3>
<table>
<thead>
<tr>
<th>ID</th>
<th>Season Year</th>
</tr>
</thead>
<?php 
foreach ($testResultsArray['testQuery'] as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['id'] ."</td>";
    echo "<td>". $val['season_year'] ."</td>";
    echo "</tr>";
}
?>
</table>
<br />



</body>
</html>
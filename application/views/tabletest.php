<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Umpire Report</title>
		
	</head>
	
	<?php 
	echo "<link rel='stylesheet' type='text/css' media='all' href='". asset_url() . $css ."' />";
	?>
<body>
<h2>Table Test</h2>

<h3>Missing Tables</h3>
<?php 
if (count($umpireTestResultsArray['tableNames']) > 0) {

?>

<table>
<thead>
<tr>
<th>Table Name</th>
<th>Engine</th>
<th>Collation</th>
</tr>
</thead>
<?php 
foreach ($umpireTestResultsArray['tableNames'] as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['table_name'] ."</td>";
    echo "<td>". $val['engine'] ."</td>";
    echo "<td>". $val['table_collation'] ."</td>";
    echo "</tr>";
}
?>
</table>
<br />

<?php 
} else {
    //No results found. Show message
    echo "All data matches.<br />";
    
}
?>

<h3>Missing Columns</h3>

<?php 
if (count($umpireTestResultsArray['missingColumns']) > 0) {

?>
<table>
<thead>
<tr>
<th>Table Name</th>
<th>Column Name</th>
<th>Column Default</th>
<th>Is Nullable</th>
<th>Data Type</th>
<th>Char Max Length</th>
<th>Num Precision</th>
<th>Num Scale</th>
<th>Date Precision</th>
<th>Charset</th>
<th>Collation</th>
<th>Column Type</th>
<th>Column Key</th>
<th>Extra</th>
</tr>
</thead>
<?php 
foreach ($umpireTestResultsArray['missingColumns'] as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['table_name'] ."</td>";
    echo "<td>". $val['column_name'] ."</td>";
    echo "<td>". $val['column_default'] ."</td>";
    echo "<td>". $val['is_nullable'] ."</td>";
    echo "<td>". $val['data_type'] ."</td>";
    echo "<td>". $val['character_maximum_length'] ."</td>";
    echo "<td>". $val['numeric_precision'] ."</td>";
    echo "<td>". $val['numeric_scale'] ."</td>";
    echo "<td>". $val['datetime_precision'] ."</td>";
    echo "<td>". $val['character_set_name'] ."</td>";
    echo "<td>". $val['collation_name'] ."</td>";
    echo "<td>". $val['column_type'] ."</td>";
    echo "<td>". $val['column_key'] ."</td>";
    echo "<td>". $val['extra'] ."</td>";
    echo "</tr>";
}
?>
</table>
<br />

<?php 
} else {
    //No results found. Show message
    echo "All data matches.<br />";
    
}
?>

<h3>Column Differences</h3>

<?php 
if (count($umpireTestResultsArray['columnDifferences']) > 0) {

?>

<table>
<thead>
<tr>
<th>Table Name</th>
<th>Column Name - L</th>
<th>Column Name - R</th>
<th>Column Default - L</th>
<th>Column Default - R</th>
<th>Is Nullable - L</th>
<th>Is Nullable - R</th>
<th>Data Type - L</th>
<th>Data Type - R</th>
<th>Char Max Length - L</th>
<th>Char Max Length - R</th>
<th>Num Precision - L</th>
<th>Num Precision - R</th>
<th>Num Scale - L</th>
<th>Num Scale - R</th>
<th>Date Precision - L</th>
<th>Date Precision - R</th>
<th>Charset - L</th>
<th>Charset - R</th>
<th>Collation - L</th>
<th>Collation - R</th>
<th>Column Type - L</th>
<th>Column Type - R</th>
<th>Column Key - L</th>
<th>Column Key - R</th>
<th>Extra - L</th>
<th>Extra - R</th>
</tr>
</thead>
<?php 
foreach ($umpireTestResultsArray['columnDifferences'] as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['table_name'] ."</td>";
    echo "<td>". $val['column_name'] ."</td>";
    echo "<td>". $val['column_default'] ."</td>";
    echo "<td>". $val['column_default_r'] ."</td>";
    echo "<td>". $val['is_nullable'] ."</td>";
    echo "<td>". $val['is_nullable_r'] ."</td>";
    echo "<td>". $val['data_type'] ."</td>";
    echo "<td>". $val['data_type_r'] ."</td>";
    echo "<td>". $val['character_maximum_length'] ."</td>";
    echo "<td>". $val['character_maximum_length_r'] ."</td>";
    echo "<td>". $val['numeric_precision'] ."</td>";
    echo "<td>". $val['numeric_precision_r'] ."</td>";
    echo "<td>". $val['numeric_scale'] ."</td>";
    echo "<td>". $val['numeric_scale_r'] ."</td>";
    echo "<td>". $val['datetime_precision'] ."</td>";
    echo "<td>". $val['datetime_precision_r'] ."</td>";
    echo "<td>". $val['character_set_name'] ."</td>";
    echo "<td>". $val['character_set_name_r'] ."</td>";
    echo "<td>". $val['collation_name'] ."</td>";
    echo "<td>". $val['collation_name_r'] ."</td>";
    echo "<td>". $val['column_type'] ."</td>";
    echo "<td>". $val['column_type_r'] ."</td>";
    echo "<td>". $val['column_key'] ."</td>";
    echo "<td>". $val['column_key_r'] ."</td>";
    echo "<td>". $val['extra'] ."</td>";
    echo "<td>". $val['extra_r'] ."</td>";
    echo "</tr>";
}
?>
</table>
<br />

<?php 
} else {
    //No results found. Show message
    echo "All data matches.<br />";
    
}
?>

<h3>Table Differences</h3>

<?php 
/*
echo "<pre>Table differences<br />";
print_r($umpireTestResultsArray['data_differences']);
echo "</pre>";
*/

//TODO: When outputting a table, add a check to show an error if there is a database error, and move on to the next table.
//This is in case a SELECT query has an error because of a missing column.

/*
 Format of this array is:
 Array
(
    [age_group] => Array
        (
            [local] => Array
                (
                    [0] => Array
                        (
                            [ID] => 1
                        )

                    [1] => Array
                        (
                            [ID] => 2
  
  
 */
foreach ($umpireTestResultsArray['data_differences'] as $key => $val) {
    
    /*
    echo "<pre>Data diff:<br />";
    print_r($key);
    echo "</pre>";
    */
    
    //Output Local-Focused Records
    echo "<br /><h2>". $key ."</h2> <br />";
    echo "<h3>Local Records not found or different in Remote Table</h3>";
    
    if (count($umpireTestResultsArray['data_differences'][$key]['local']) > 0) {
        echo "<table>";
        echo "<thead><tr>";
        //Loop through each of the available columns, using the first row of data, and output a header.
        foreach ($umpireTestResultsArray['data_differences'][$key]['local'][0] AS $keyColumnName => $keyFirstRowValue) {
            echo "<th>". $keyColumnName."</th>";
        }
        echo "</tr></thead>";
        
        for ($i=0; $i < count($umpireTestResultsArray['data_differences'][$key]['local']); $i++) {
            echo "<tr>";
            foreach ($umpireTestResultsArray['data_differences'][$key]['local'][0] AS $keyColumnName => $keyFirstRowValue) {
                echo "<td>". $umpireTestResultsArray['data_differences'][$key]['local'][$i][$keyColumnName] ."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    
    } else {
        //No results found. Show message
        echo "All data matches.<br />";
    }
    
    
    //Output Remote-Focused Records
    echo "<h3>Remote Records not found or different in Local Table</h3>";
    
    if (count($umpireTestResultsArray['data_differences'][$key]['remote']) > 0) {
        echo "<table>";
        echo "<thead>";
        //Loop through each of the available columns, using the first row of data, and output a header.
        foreach ($umpireTestResultsArray['data_differences'][$key]['remote'][0] AS $keyColumnName => $keyFirstRowValue) {
            echo "<th>". $keyColumnName."</th>";
        }
        echo "</thead>";
        
        for ($i=0; $i < count($umpireTestResultsArray['data_differences'][$key]['remote']); $i++) {
            echo "<tr>";
            foreach ($umpireTestResultsArray['data_differences'][$key]['remote'][0] AS $keyColumnName => $keyFirstRowValue) {
                echo "<td>". $umpireTestResultsArray['data_differences'][$key]['remote'][$i][$keyColumnName] ."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        //No results found. Show message
        echo "All data matches.<br />";
    }
}
?>

</body>
</html>
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
<th>Date and Time</th>
<th>Operation Name</th>
<th>Table Name</th>
<th>Row Count</th>
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
<BR />
<h3>Umpire Match Count Check - Report 01</h3>
<table>
<thead>
<th>Umpire Name</th>
<th>Club</th>
<th>Short League Name</th>
<th>Age Group</th>
<th>Umpire Type</th>
<th>Match Count - Source</th>
<th>Match Count - Destination</th>
</thead>
<?php 
foreach ($umpireTestResultsArray['report01'] as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['umpire_full_name'] ."</td>";
    echo "<td>". $val['club_name'] ."</td>";
    echo "<td>". $val['short_league_name'] ."</td>";
    echo "<td>". $val['age_group'] ."</td>";
    echo "<td>". $val['umpire_type'] ."</td>";
    echo "<td>". $val['match_count_staging'] ."</td>";
    echo "<td>". $val['match_count_report01'] ."</td>";
    echo "</tr>";
}
?>
</table>
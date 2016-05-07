<?php
echo "<h2>Data Test</h2>";

echo "<div>Umpire Match Count Check - Report 01</div>";
/*echo "<pre>";
print_r($umpireTestResultsArray);
echo "</pre>";
*/
echo "<table>";
?>
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
foreach ($umpireTestResultsArray as $key => $val) {
    echo "<tr>";
    echo "<td>". $val['umpire_full_name'] ."</td>";
    echo "<td>". $val['team'] ."</td>";
    echo "<td>". $val['short_league_name'] ."</td>";
    echo "<td>". $val['age_group'] ."</td>";
    echo "<td>". $val['umpire_type'] ."</td>";
    echo "<td>". $val['match_count'] ."</td>";
    echo "<td>". $val['match_count_after'] ."</td>";
    echo "</tr>";
}
?>
</table>
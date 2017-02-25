<?php
$debugMode = $this->config->item('debug_mode');



echo "<div class='uploadSuccessMessage'>Upload completed!</div>";
echo "<div class='centerText'>Return to the Home page to generate reports.</div><BR />";

$countMissingCompetitions = count($missing_data);

if ($countMissingCompetitions > 0) {
    echo "<div class='uploadSuccessMessage'>Missing Data</div><BR />";
    echo "<div class='centerText'>The following competition names have been imported, but are new to the system. Please contact Support to get them updated and ready for the reports.</div><BR />";

    /*
    echo "<pre>";
    print_r($missing_data);
    echo "</pre>";
    */
    echo "<table align='center'>";
    echo "<tr>";
    echo "<th>Competition Name</th>";
    echo "</tr>";
    for ($i=0; $i < $countMissingCompetitions; $i++) {
        
        echo "<tr>";
        echo "<td>". $missing_data[$i]['competition_name'] ."</td>";
        echo "</tr>";
    
    }
    
    echo "</table>";

}


?>





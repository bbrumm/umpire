<?php
//$debugMode = $this->config->item('debug_mode');



echo "<div class='uploadSuccessMessage'>Upload completed!</div>";
echo "<div class='centerText'>Return to the Home page to generate reports.</div><BR />";

//$countMissingData = count($missing_data);

if (isset ($missing_data['competition'])) {
    $countMissingCompetitions = count($missing_data['competition']);
} else {
    $countMissingCompetitions = 0;
}
/*
echo "countmissingdata(". $countMissingData .")<BR/>";
echo "missingdata value(". $missing_data .")<BR/>";
echo "missingdata empty(". empty($missing_data) .")<BR/>";
*/
if (!empty($missing_data)) {
    echo "<div class='uploadSuccessMessage'>Missing Data</div><BR />";
    echo "<div class='centerText'>The following information has been imported, but is new to the system.</div><BR />";

    /*
    echo "<pre>";
    print_r($possibleLeaguesForComp);
    echo "</pre>";
    */
    
    echo form_open_multipart('FileImport/runETLProcess');
    
    if ($countMissingCompetitions > 0) {
        
    
        echo "<table align='center'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Competition Name</th>";
        echo "<th>Select League</th>";
        echo "</tr>";
        for ($i=0; $i < $countMissingCompetitions; $i++) {
            
            echo "<tr>";
            echo "<td>". $missing_data['competition'][$i]['source_id'] ."</td>";
            echo "<td>". $missing_data['competition'][$i]['source_value'] ."</td>";
            echo "<td><div class='divSubTable'>";
            echo "<div class='divSubTableHeading'>";
                echo "<div class='divSubTableHead'>&nbsp;</div>";
                echo "<div class='divSubTableHead'>ID</div>";
                echo "<div class='divSubTableHead'>League Name</div>";
                echo "<div class='divSubTableHead'>Short League Name</div>";
                echo "<div class='divSubTableHead'>Age Group</div>";
                echo "<div class='divSubTableHead'>Division</div>";
                echo "<div class='divSubTableHead'>Region</div>";
            echo "</div>";
            echo "<div class='divSubTableBody'>";
            foreach ($possibleLeaguesForComp as $possibleLeagueItem) {
                echo "<div class='divSubTableRow'>";
                echo "<div class='divSubTableCell'><input type='radio' name='competition[". $missing_data['competition'][$i]['source_id'] ."]' value='". $possibleLeagueItem['id'] ."'/></div>";
                echo "<div class='divSubTableCell'>". $possibleLeagueItem['id'] ."</div>";
                echo "<div class='divSubTableCell'>". $possibleLeagueItem['league_name'] ."</div>";
                echo "<div class='divSubTableCell'>". $possibleLeagueItem['short_league_name'] ."</div>";
                echo "<div class='divSubTableCell'>". $possibleLeagueItem['age_group'] ."</div>";
                echo "<div class='divSubTableCell'>". $possibleLeagueItem['division_name'] ."</div>";
                echo "<div class='divSubTableCell'>". $possibleLeagueItem['region_name'] ."</div>";
                echo "</div>";
            }
            echo "</div>";
            echo "</div></td>";
            echo "</tr>";
        
        }
        
        echo "</table>";
    }
    echo "<BR />";
    echo "<div class='centerText'>Once you have made your selections, press this button. This will update the report data, and can take up to 30 seconds.</div><BR />";
    echo "<div class='reportSelectorRow'><input type='submit' id='submit' value='Update Reports' class='btn'></div><BR />";
    echo form_close();
}
?>
<BR />

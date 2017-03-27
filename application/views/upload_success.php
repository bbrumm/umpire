<?php
echo "<div class='uploadSuccessMessage'>Upload completed!</div>";
echo "<div class='centerText'>Return to the Home page to generate reports.</div><BR />";

if (isset ($missing_data['competition'])) {
    $countMissingCompetitions = count($missing_data['competition']);
} else {
    $countMissingCompetitions = 0;
}

if (isset ($missing_data['team'])) {
    $countMissingTeams = count($missing_data['team']);
} else {
    $countMissingTeams = 0;
}

if (!empty($missing_data)) {
    echo "<div class='uploadSuccessMessage'>Missing Data</div><BR />";
    echo "<div class='centerText'>The following information has been imported, but is new to the system.</div><BR />";
    
    echo form_open_multipart('FileImport/runETLProcess');
    
    if ($countMissingCompetitions > 0) {
        echo "<div class='centerText'>Competitions</div><BR />";
        echo "<table align='center'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Competition Name</th>";
        echo "<th>Select League</th>";
        echo "</tr>";
        for ($i=0; $i < $countMissingCompetitions; $i++) {
            echo "<tr>";
            echo "<td class='missingDataCell'>". $missing_data['competition'][$i]['source_id'] ."</td>";
            echo "<td class='missingDataCell'>". $missing_data['competition'][$i]['source_value'] ."</td>";
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
    
    if ($countMissingTeams > 0) {
        echo "<div class='centerText'>Teams</div><BR />";
        echo "<table align='center'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Team Name</th>";
        echo "<th>Select Club</th>";
        echo "</tr>";
        for ($i=0; $i < $countMissingTeams; $i++) {
            echo "<tr>";
            echo "<td class='missingDataCell'>". $missing_data['team'][$i]['source_id'] ."</td>";
            echo "<td class='missingDataCell'>". $missing_data['team'][$i]['source_value'] ."</td>";
            echo "<td class='missingDataCell'>";
            echo "<div class='divSubTableInvisible'>";
            echo "<div class='divSubTableBody'>";
            echo "<div class='divSubTableRow'>";
            echo "<div class='divSubTableCellInvisible'><input type='radio' name='rdTeam[". $missing_data['team'][$i]['source_id'] ."]' value='existing' checked/></div>";
            echo "<div class='divSubTableCellInvisible'>Select an existing club:</div>";
            echo "<div class='divSubTableCellInvisible'><select class='newData' name='cboTeam[". $missing_data['team'][$i]['source_id'] ."]'>";

            foreach ($possibleClubsForTeam as $possibleTeamItem) {
                echo "<option value='". $possibleTeamItem['id'] ."'>". $possibleTeamItem['club_name'] ."</option>";
            }

            echo "</select></div>";
            echo "</div>";
            echo "<div class='divSubTableRow'>";
            echo "<div class='divSubTableCellInvisible'><input type='radio' name='rdTeam[". $missing_data['team'][$i]['source_id'] ."]' value='new'/></div>";
            echo "<div class='divSubTableCellInvisible'>Or, enter a new club:</div>";
            echo "<div class='divSubTableCellInvisible'><input type='text' name='txtTeam[". $missing_data['team'][$i]['source_id'] ."]' value='' class='customTextBox'/></div>";
            echo "</div>";
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

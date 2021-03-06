<?php
if (!function_exists('outputSelectionRow')) {
    //TODO: Move all this code to a Model object and pass an array of output to this file. Logic should not go in the View.
    function outputSelectionRow($pMissingData, $pIterationNumber, $pPossibleSelections, $pLabel, $pCboBoxName, $pValueFieldName) {
        echo "<div class='divSubTableRow'>";
        echo "<div class='divSubTableCellInvisible'>$pLabel:</div>";
        echo "<div class='divSubTableCellInvisible'>";
        echo "<select class='newData' name='" . $pCboBoxName . "[" . $pMissingData['competition'][$pIterationNumber]['source_id'] . "]' ";
        echo "id='" . $pCboBoxName . "[" . $pMissingData['competition'][$pIterationNumber]['source_id'] . "]'>";
        echo outputAllOptionTags($pMissingData, $pIterationNumber,  $pPossibleSelections , $pValueFieldName, $pLabel );
        echo "</select></div>";
        echo "</div>";
    }

    function  outputAllOptionTags( $pMissingData, $pIterationNumber, $pPossibleSelections, $pValueFieldName, $pLabel) {
        $allTags = "";
        foreach ($pPossibleSelections as $possibleSelectionItem) {
            $allTags .= outputOptionTag($pMissingData, $pIterationNumber, $possibleSelectionItem, $pValueFieldName, $pLabel);

        }
        return  $allTags;
    }

    function isDropDownValueInCompetitionName($pMissingData, $pIterationNumber, $possibleSelectionItem, $pValueFieldName) {
        if (strpos($pMissingData['competition'][$pIterationNumber]['source_value'], $possibleSelectionItem[$pValueFieldName]) !== FALSE) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *Set the default league to GJFL. This is because most new age groups seem to be Juniors,
    *and only the Seniors and Reserves have BFL. We want to avoid users just leaving the default as BFL and messing up their reports.
    */
    function isLabelLeagueAndContainsGJFL($pLabel, $possibleSelectionItem, $pValueFieldName) {
        if($pLabel == "League" && $possibleSelectionItem[$pValueFieldName] == "GJFL") {
            return true;
        } else {
            return false;
        }

    }

    function outputOptionOpenTag($pMissingData, $pIterationNumber, $possibleSelectionItem, $pValueFieldName, $pLabel) {
        if (isDropDownValueInCompetitionName($pMissingData, $pIterationNumber, $possibleSelectionItem, $pValueFieldName)
            ||  isLabelLeagueAndContainsGJFL($pLabel, $possibleSelectionItem, $pValueFieldName) ) {
            return "<option selected ";
        } else {
            return "<option ";
        }
    }

    function determineValueToUse($pLabel, $possibleSelectionItem, $pValueFieldName) {
        if ($pLabel == "League") {
            return $possibleSelectionItem[$pValueFieldName];
        } else {
            return $possibleSelectionItem['id'];
            }
    }

    function outputOptionTag ($pMissingData, $pIterationNumber, $possibleSelectionItem, $pValueFieldName, $pLabel) {
        $optionTag = outputOptionOpenTag($pMissingData, $pIterationNumber, $possibleSelectionItem, $pValueFieldName, $pLabel);
        $valueToUse = determineValueToUse($pLabel, $possibleSelectionItem, $pValueFieldName);
        $optionTag .= "value='" . $valueToUse . "'>" . $possibleSelectionItem[$pValueFieldName];
        $optionTag .= "</option>";

        return $optionTag;
    }

}
?>

<script type="text/javascript">
function updateCompetition(competitionID) {
	//TODO: Pass the name of the button that was clicked, then extact the number from the end of the button to find the ID of the competition.
	var selectedRegion = document.getElementById('cboRegion['+ competitionID +']').value;
	var selectedAgeGroup = document.getElementById('cboAgeGroup['+ competitionID +']').value;
	var selectedDivision = document.getElementById('cboDivision['+ competitionID +']').value;
	var selectedLeague = document.getElementById('cboLeague['+ competitionID +']').value;

	var xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ... 
		xhr = new XMLHttpRequest(); 
	} else if (window.ActiveXObject) { 
		// IE 8 and older 
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var data = "selectedRegion=" + selectedRegion +
	"&selectedAgeGroup=" + selectedAgeGroup +
	"&selectedDivision=" + selectedDivision +
	"&selectedLeague=" + selectedLeague +
	"&competitionID=" + competitionID;

	console.log("Current file location:");
	console.log(window.location.pathname)
	console.log(data);
	//xhr.open("POST", "../../application/libraries/updateCompetition.php", true);
	xhr.open("POST", "<?php echo base_url(); ?>" + "index.php/ajax_post_controller/updateCompetition", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xhr.send(data);


	xhr.onreadystatechange = display_data;

	function display_data() {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				//document.getElementById("suggestion").innerHTML = xhr.responseText;
				if (xhr.responseText == "OK") {
					document.getElementById("competitionRow"+competitionID).innerHTML = "<td class='importDataUpdated' colspan=4>Data updated!</td>";
				} else {
					document.getElementById("updateFailedMsg"+competitionID).innerHTML = "No league found with these selections.";
				}
			} else {
				alert('There was a problem with the request.');
			}
		}
	}	
}



//When the document is ready
//$(document).ready(startDataImport);	


</script>

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
        echo "<th>Select League/Region/Age Group</th>";
        echo "<th>Update</th>";
        echo "</tr>";
        for ($i=0; $i < $countMissingCompetitions; $i++) {
            echo "<tr id='competitionRow". $missing_data['competition'][$i]['source_id']."'>";
            echo "<td class='missingDataCell'>". $missing_data['competition'][$i]['source_id'] ."</td>";
            echo "<td class='missingDataCell'>". $missing_data['competition'][$i]['source_value'] ."</td>";
            echo "<td><div class='divSubTable'>";
           
            outputSelectionRow($missing_data, $i, $possibleRegions, "Region", "cboRegion", "region_name");
            outputSelectionRow($missing_data, $i, $possibleAgeGroups, "Age Group", "cboAgeGroup", "age_group");
            outputSelectionRow($missing_data, $i, $possibleDivisions, "Division", "cboDivision", "division_name");
            outputSelectionRow($missing_data, $i, $possibleShortLeagueNames, "League", "cboLeague", "short_league_name");
            
            echo "</div></td>";
            echo "<td><input type='button' class='btn' ";
            echo "'name='btnUpdateCompetition". $missing_data['competition'][$i]['source_id']."' ";
            echo "value='Update' onClick='updateCompetition(". $missing_data['competition'][$i]['source_id'].")'/>";
            echo "<div id='updateFailedMsg". $missing_data['competition'][$i]['source_id']."'></div>";
            echo "</td>";
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
            
            $isTeamMatchFound = false;
            foreach ($possibleClubsForTeam as $possibleTeamItem) {
                if ($isTeamMatchFound === false && strpos($missing_data['team'][$i]['source_value'], $possibleTeamItem['club_name']) !== FALSE) {
                    echo "<option selected ";
                    $isTeamMatchFound = true;
                    //echo "value='". $possibleTeamItem['id'] ."'>(". $possibleTeamItem['club_name'] .") CHECKED ((". $missing_data['team'][$i]['source_value'] .") ". strpos($missing_data['team'][$i]['source_value'], $possibleTeamItem['club_name']).")</option>";
                } else {
                    echo "<option ";
                    //echo "value='". $possibleTeamItem['id'] ."'>(". $possibleTeamItem['club_name'] .") None ((". $missing_data['team'][$i]['source_value'] .") ". strpos($missing_data['team'][$i]['source_value'], $possibleTeamItem['club_name']).")</option>";
                }
                echo "value='". $possibleTeamItem['id'] ."'>". $possibleTeamItem['club_name'] ."</option>";
                
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


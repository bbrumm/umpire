
<script type="text/javascript">
function updateCompetition(competitionID) {
	//TODO: Pass the name of the button that was clicked, then extact the number from the end of the button to find the ID of the competition.
	var selectedRegion = document.getElementById('cboRegion['+ competitionID +']').value;
	var selectedAgeGroup = document.getElementById('cboAgeGroup['+ competitionID +']').value;
	var selectedDivision = document.getElementById('cboDivision['+ competitionID +']').value;
	var selectedLeague = document.getElementById('cboLeague['+ competitionID +']').value;

	//alert(selectedRegion);
	//alert(selectedRegion && ", " && selectedAgeGroup && ", " && selectedDivision && ", " && selectedLeague);

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


function updateProgressBar() {
	var xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ... 
		xhr = new XMLHttpRequest(); 
	} else if (window.ActiveXObject) { 
		// IE 8 and older 
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xhr.open("GET", "<?php echo base_url(); ?>" + "index.php/ajax_post_controller/updateProgressBar", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xhr.send();
	
	//Call the display_data function when the ready state changes (onreadystatechange)
	xhr.onreadystatechange = display_data;

	function display_data() {
		var pctComplete = xhr.responseText;

		if (xhr.readyState == 4 && xhr.status == 200) {
			if (pctComplete == 100) {
			document.getElementById("progressbar").innerHTML = "Done";
			window.clearInterval(timer);
            timer = window.setInterval(completed, 1000);
			
    		} else {
    			document.getElementById("progressbar").innerHTML = "Loading... ("+ pctComplete +"% complete)";
    		}
		}
	}

	function completed() {
      $("#message").html("Completed");
      window.clearInterval(timer);
	}
}

/*
function startTestDataImport() {
	// Trigger the process in web server.
    console.log ("test5 start data import");
  $.ajax({url: " echo base_url(); " + "index.php/ajax_post_controller/startDataImport"});
  // Refresh the progress bar every 1 second.
  timer = window.setInterval(updateProgressBar, 1000);
}
*/


//When the document is ready
//$(document).ready(startDataImport);	


</script>

<?php
echo "<div class='uploadSuccessMessage'>Upload completed!</div>";
echo "<div class='centerText'>Return to the Home page to generate reports.</div><BR />";

/*
echo "<div class='centerText' id='progressbar'>Status message...</div>";
echo "<input type='button' name='btnTestMessage' onClick='updateProgressBar()' value='Test Message'/>";
echo "<input type='button' name='btnStart' onClick='startTestDataImport()' value='Test Data Import'/>";
*/
function outputSelectionRow($pMissingData, $pIterationNumber, $pPossibleSelections, $pLabel, $pCboBoxName, $pValueFieldName) {
    
    echo "<div class='divSubTableRow'>";
    echo "<div class='divSubTableCellInvisible'>$pLabel:</div>";
    echo "<div class='divSubTableCellInvisible'>";
    echo "<select class='newData' name='".$pCboBoxName."[". $pMissingData['competition'][$pIterationNumber]['source_id'] ."]' ";
    echo "id='".$pCboBoxName."[". $pMissingData['competition'][$pIterationNumber]['source_id'] ."]'>";
    foreach ($pPossibleSelections as $possibleSelectionItem) {
        //This IF statement checks if the value in the drop-down box is contained within the Competition Name.
        //If it is found, the option is automatically selected.
        if (strpos($pMissingData['competition'][$pIterationNumber]['source_value'], $possibleSelectionItem[$pValueFieldName]) !== FALSE) {
            echo "<option selected ";
        } elseif ($pLabel == "League" && $possibleSelectionItem[$pValueFieldName] == "GJFL") {
            /* 
             *Set the default league to GJFL. This is because most new age groups seem to be Juniors, 
             *and only the Seniors and Reserves have BFL. We want to avoid users just leaving the default as BFL and messing up their reports.
             *TODO: Improve this logic to make it not dependent on a specific value, somehow.
             */
            echo "<option selected ";
        } else {
            echo "<option ";
        }
        
        $valueToUse = "";
	    if ($pLabel == "League") {
	        $valueToUse = $possibleSelectionItem[$pValueFieldName];
	    } else {
	        $valueToUse = $possibleSelectionItem['id'];
	    }
        
	    echo "value='". $valueToUse."'>". $possibleSelectionItem[$pValueFieldName];
        echo "</option>";
        
        
    }
    echo "</select></div>";
    //echo "(" . $pMissingData['competition'][$pIterationNumber]['source_value'] . ")";
    echo "</div>";
    
    
    
}

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
                if ($isTeamMatchFound == false && strpos($missing_data['team'][$i]['source_value'], $possibleTeamItem['club_name']) !== FALSE) {
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


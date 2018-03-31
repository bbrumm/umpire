function updatePageFromCheckboxSelection() {
	reportID = document.getElementById('reportName').value;
	//console.log("selected report ID: " + reportID);
	
	updateControlSelection("rdRegion", reportList[reportID].region_enabled);
	updateControlSelection("chkLeague[]", reportList[reportID].league_enabled);
	updateControlSelection("chkUmpireDiscipline[]", reportList[reportID].umpire_type_enabled);
	updateControlSelection("chkAgeGroup[]", reportList[reportID].age_group_enabled)
	//console.log("valid combination 1: " + validCombinations[1]['region']);
}

function updateControlSelection(elementName, controlEnabled) {
	//console.log("league enabled inside funciton:" + )
	var groupOfCheckboxes = document.getElementsByName(elementName);
	//console.log("report name: " + document.getElementById('reportName').value);
	//console.log("count: " + document.getElementsByName("chkLeague[]").length);
	//console.log("checkbox count: " + leagueCheckboxes.length);
	
	//Find valid leagues based on this region
	validLeagueList = findValidLeagues();
	
	//Find valid age groups
	validAgeGroupList = findValidAgeGroups();
	
	var i;
	for (i = 0; i < groupOfCheckboxes.length; i++) {
    	//console.log("chk id: " + document.getElementById(leagueCheckboxes[i].id).id);
		controlToUpdate = document.getElementById(groupOfCheckboxes[i].id);
		
		
		
		//Then check if it is a valid combination
		if(elementName == "chkLeague[]") {
			if (validLeagueList.indexOf(controlToUpdate.id) != -1) {
				//Valid.
				//console.log("valid league: " + controlToUpdate.id);
				if (controlEnabled == 1) {
					singleControlEnabled = true;
				} else {
					singleControlEnabled = false;
				}
			} else {
				singleControlEnabled = false;
			}
		} else if (elementName == "chkAgeGroup[]") {
			//console.log("age group: " + controlToUpdate.id);
			if (validAgeGroupList.indexOf(controlToUpdate.id) != -1) {
				//Valid.
				//console.log("valid age group: " + controlToUpdate.id);
				if (controlEnabled == 1) {
					singleControlEnabled = true;
				} else {
					singleControlEnabled = false;
				}
			} else {
				singleControlEnabled = false;
			}
		} else {
			if (controlEnabled == 0) {
				singleControlEnabled = false;
			} else {
				singleControlEnabled = true;
			}
		}
		
		
		if(singleControlEnabled) {
			disabledStatus = false;
			bgColour = "#FFFFFF";
			
		} else {
			disabledStatus = true;
			bgColour = "#d9d9d9";
		}
		
		
		controlToUpdate.disabled = disabledStatus;
    	if(controlToUpdate.type == "checkbox" && disabledStatus && controlEnabled == 0) {
    		//Only change the checkbox to be checked if it is disabled because the entire control group is disabled
    		controlToUpdate.checked = true;
    	}
		controlToUpdate.parentNode.style.background=bgColour;
    	
	}	
	//console.log("league updated");
	
	updateSelectAllCheckboxes(elementName, groupOfCheckboxes);
}

function selectAll(selectAllCheckbox, matchingElementName) {
	/*
	 * Find the group that matches the provided selectAll checkbox
	 * Check all checkboxes in the group that are enabled. Or, uncheck them all
	 * Run the validation on all checkboxes too
	 */
	
	//console.log("select all: " + selectAllCheckbox.id);
	//console.log("select all group: " + matchingElementName);
	
	relatedCheckboxes = document.getElementsByName(matchingElementName);
	
	countChecked = 0;
	countCheckable = 0;
	for (i=0; i < relatedCheckboxes.length; i++) {
		if (relatedCheckboxes[i].disabled != true) {
			countCheckable++;
			if(relatedCheckboxes[i].checked == true) {
				countChecked++;
			}
		}
	}
	
	if(countChecked == countCheckable) {
		//All checkable checkboxes are checked. Make them unchecked.
		selectAllMakesCheckboxesChecked = false;
	} else {
		selectAllMakesCheckboxesChecked = true;
	}
	
	for (i=0; i < relatedCheckboxes.length; i++) {
		if (relatedCheckboxes[i].disabled != true) {
			//Checkbox not disabled. Set it to "checked".
			document.getElementById(relatedCheckboxes[i].id).checked = selectAllMakesCheckboxesChecked;
		}
	}
	
	
}

function updateSelectAllCheckboxes(groupName, groupOfCheckboxes) {
	selectAllCheckboxId = "";
	switch(groupName) {
		case "chkLeague[]":
			selectAllCheckboxId = "LeagueSelectAll";
			break;
		case "chkUmpireDiscipline[]":
			selectAllCheckboxId = "UmpireDisciplineSelectAll";
			break;
		case "chkAgeGroup[]":
			selectAllCheckboxId = "AgeGroupSelectAll";
			break;
		default:
			return null;
	}
	
	//console.log("group name element:" + groupName);
	//console.log("select all element:" + document.getElementById(selectAllCheckboxId).id);
	
	countChecked = 0;
	countCheckable = 0;
	for (i=0; i < groupOfCheckboxes.length; i++) {
		if (groupOfCheckboxes[i].disabled != true) {
			countCheckable++;
			if(groupOfCheckboxes[i].checked == true) {
				countChecked++;
			}
		}
	}
	
	if(countChecked == countCheckable) {
		//All checkable checkboxes are checked. Check the Select All checkbox
		document.getElementById(selectAllCheckboxId).checked = true;
		//console.log("select all is rechecked");
	} else {
		document.getElementById(selectAllCheckboxId).checked = false;
		//console.log("select all is NOT rechecked");
	}
	
}



function findSelectedRegion() {
	regionElements = document.getElementsByName('rdRegion');
	for(i=0; i < regionElements.length; i++) {
		if (regionElements[i].checked == true) {
			return regionElements[i].id;
		}
	}
}

function findSelectedLeagues() {
	var selectedLeagues = [];
	
	leagueElements = document.getElementsByName('chkLeague[]');
	for(i=0; i < leagueElements.length; i++) {
		if (leagueElements[i].checked == true) {
			//return leagueElements[i].id;
			selectedLeagues.push(leagueElements[i].id);
		}
	}
	
	return selectedLeagues;
}

function findValidLeagues() {
	var validLeagues = [];
	
	//Find the selected region:
	selectedRegionValue = findSelectedRegion();
	//console.log("Selected region: " + selectedRegionValue);
	
	//Variable validCombinations is initialised in report_home.php.
	for(i=0; i < validCombinations.length; i++) {
		//console.log("Valid league: " + validCombinations[i]['region']);
		//console.log("Region value: " + regionValue);
		if(validCombinations[i]['region'] == selectedRegionValue) {
			//validLeagues.push(validCombinations[i]['league']);
			//Add item to array only if it does not exist
			validLeagues.indexOf(validCombinations[i]['league']) === -1 ? validLeagues.push(validCombinations[i]['league']) : null;
		}
	}
	
	return validLeagues;
}

function findValidAgeGroups() {
	var validAgeGroups = [];
	
	//Find the selected region:
	selectedRegionValue = findSelectedRegion();
	
	//Find the selected leagues:
	selectedLeagueValues = findSelectedLeagues();
	
	for(i=0; i < validCombinations.length; i++) {
		
		//console.log("Region value: " + regionValue);
		for(j=0; j < selectedLeagueValues.length; j++) {
			//console.log("Age group check: " + selectedLeagueValues[j]);
			if(validCombinations[i]['region'] == selectedRegionValue &&
		        validCombinations[i]['league'] == selectedLeagueValues[j]) {
				//validLeagues.push(validCombinations[i]['league']);
				//Add item to array only if it does not exist
				validAgeGroups.indexOf(validCombinations[i]['age_group']) === -1 ? validAgeGroups.push(validCombinations[i]['age_group']) : null;
			}
		}
	}
	
	return validAgeGroups;
	
}



function validateReportSelections() {
    checkboxesLeague = document.getElementsByName("chkLeague[]");
    checkboxesUmpireDiscipline = document.getElementsByName("chkUmpireDiscipline[]");
    checkboxesAgeGroup = document.getElementsByName("chkAgeGroup[]");
    
    convertedStringLeague = convertValueArrayToString(checkboxesLeague);
    convertedStringUmpireDiscipline = convertValueArrayToString(checkboxesUmpireDiscipline);
    convertedStringAgeGroup = convertValueArrayToString(checkboxesAgeGroup);
    convertedStringRegion = convertValueArrayToString(document.getElementsByName("rdRegion"));
    
    document.getElementById("chkLeagueHidden").value = convertedStringLeague;
    document.getElementById("chkUmpireDisciplineHidden").value = convertedStringUmpireDiscipline;
    document.getElementById("chkAgeGroupHidden").value = convertedStringAgeGroup;
    document.getElementById("chkRegionHidden").value = convertedStringRegion;
    
    //console.log("convertedString " + convertValueArrayToString(checkboxesUmpireDiscipline));
    
    //Reset the validation error message
    document.getElementById("validationError").innerHTML = "";
    
    var leagueCheckboxesValid = isCheckboxSelected(checkboxesLeague);
    var umpireDisciplineCheckboxesValid = isCheckboxSelected(checkboxesUmpireDiscipline);
    var ageGroupCheckboxesValid = isCheckboxSelected(checkboxesAgeGroup);

    if (leagueCheckboxesValid && umpireDisciplineCheckboxesValid && ageGroupCheckboxesValid) {
    	document.getElementById("submitForm").submit();
    } else {
    	if (!leagueCheckboxesValid) {
    		document.getElementById("validationError").innerHTML += "Please select at least one League. <br />";
    	} 
    	if (!umpireDisciplineCheckboxesValid) {
    		document.getElementById("validationError").innerHTML += "Please select at least one Umpire Discipline. <br />";
    	} 
    	if (!ageGroupCheckboxesValid) {
    		document.getElementById("validationError").innerHTML += "Please select at least one Age Group. <br />";
    	}
    }
    
    
   
	
}


function isCheckboxSelected(checkboxElements) {
	var isSelected = false;
	for(var i=0, n=checkboxElements.length; i<n; i++) {
		if (checkboxElements[i].checked) {
			isSelected = true;
			break;
		}
	}
	return isSelected;
}

function isCheckboxGroupDisabled(checkboxElements) {
	var isDisabled = false;
	for(var i=0, n=checkboxElements.length; i<n; i++) {
		if (checkboxElements[i].disabled) {
			isDisabled = true;
			break;
		}
	}
	return isDisabled;
}

function convertValueArrayToString(nodeListToConvert) {
	var nodeListLength = nodeListToConvert.length;
	var convertedString = '';
	for(var i=0; i<nodeListLength; i++) {
		if (nodeListToConvert[i].checked) {
			//if (i==nodeListLength) {
			//	convertedString = convertedString + nodeListToConvert[i].value
			//} else {
				convertedString = convertedString + nodeListToConvert[i].value + ","
			//}
		}
	}
	//console.log("converted string: " + convertedString);
	return convertedString;
	
	
}

/*
function setCheckboxStatusNew(checkboxID, enabledStatus, checkedStatus) {
	if (typeof checkedStatus === 'undefined') { checkedStatus = 'default'; }
	
	document.getElementById(checkboxID).disabled = !enabledStatus;
	if (checkedStatus != 'default') {
		//console.log("Set checked status for ID " + checkboxID + " to " + checkedStatus);
		document.getElementById(checkboxID).checked = checkedStatus;
	}
	
	if (enabledStatus) {
		//Enabled, set colour to something else 
		document.getElementById(checkboxID).parentNode.style.background='#FFFFFF';
	} else {
		//Disabled, set colour to grey
		document.getElementById(checkboxID).parentNode.style.background='#d9d9d9';
	}

	
}
*/
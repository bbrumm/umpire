function updatePageFromCheckboxSelection() {
	var reportID = document.getElementById('reportName').value;
	/** global: reportList */
	updateControlSelection("rdRegion", reportList[reportID].region_enabled);
	updateControlSelection("chkLeague[]", reportList[reportID].league_enabled);
	updateControlSelection("chkUmpireDiscipline[]", reportList[reportID].umpire_type_enabled);
	updateControlSelection("chkAgeGroup[]", reportList[reportID].age_group_enabled)
}

function updateControlSelection(elementName, controlEnabled) {
	var groupOfCheckboxes = document.getElementsByName(elementName);

	//Find valid leagues based on this region
	var validLeagueList = findValidLeagues();
	
	//Find valid age groups
	var validAgeGroupList = findValidAgeGroups();
	var singleControlEnabled;
	
	var i;
	for (i = 0; i < groupOfCheckboxes.length; i++) {
		var controlToUpdate = document.getElementById(groupOfCheckboxes[i].id);
		//Then check if it is a valid combination
		singleControlEnabled = calculateSingleControlEnabled(elementName, validLeagueList, validAgeGroupList, controlToUpdate, controlEnabled);
		controlToUpdate.disabled = calculateDisabledStatus(singleControlEnabled);
		
    	        if(controlToUpdate.type == "checkbox" && controlToUpdate.disabled && parseInt(controlEnabled) === 0) {
    		    //Only change the checkbox to be checked if it is disabled because the entire control group is disabled
    		    controlToUpdate.checked = true;
    	        }
		controlToUpdate.parentNode.style.background = calculatebgColour(singleControlEnabled);
    	
	}	
	
	updateSelectAllCheckboxes(elementName, groupOfCheckboxes);
}

function calculateSingleControlEnabled(elementName, validLeagueList, validAgeGroupList, controlToUpdate, controlEnabled) {
	var singleControlEnabled;
	if(elementName == "chkLeague[]") {
			if (validLeagueList.indexOf(controlToUpdate.id) !== -1) {
				//Valid.
				singleControlEnabled = (parseInt(controlEnabled) === 1);
			} else {
				singleControlEnabled = false;
			}
		} else if (elementName == "chkAgeGroup[]") {
			if (validAgeGroupList.indexOf(controlToUpdate.id) != -1) {
				//Valid.
				singleControlEnabled = (parseInt(controlEnabled) === 1);
			} else {
				singleControlEnabled = false;
			}
		} else {
			singleControlEnabled = (parseInt(controlEnabled) === 0);
		}
        return singleControlEnabled;
}

function calculateDisabledStatus(singleControlEnabled) {
	return !singleControlEnabled;
}

function calculatebgColour(singleControlEnabled) {
	var bgColour;
    if(singleControlEnabled) {
        bgColour = "#FFFFFF";
    } else {
        bgColour = "#d9d9d9";
    }
	return bgColour;
}

function selectAll(selectAllCheckbox, matchingElementName) {
	/*
	 * Find the group that matches the provided selectAll checkbox
	 * Check all checkboxes in the group that are enabled. Or, uncheck them all
	 * Run the validation on all checkboxes too
	 */
	
	var relatedCheckboxes = document.getElementsByName(matchingElementName);
	var countChecked = 0;
	var countCheckable = 0;
	for (i=0; i < relatedCheckboxes.length; i++) {
		if (relatedCheckboxes[i].disabled !== true) {
			countCheckable++;
			if(relatedCheckboxes[i].checked === true) {
				countChecked++;
			}
		}
	}
	var selectAllMakesCheckboxesChecked;
	if(countChecked == countCheckable) {
		//All checkable checkboxes are checked. Make them unchecked.
		selectAllMakesCheckboxesChecked = false;
	} else {
		selectAllMakesCheckboxesChecked = true;
	}
	
	for (var i=0; i < relatedCheckboxes.length; i++) {
		if (relatedCheckboxes[i].disabled !== true) {
			//Checkbox not disabled. Set it to "checked".
			document.getElementById(relatedCheckboxes[i].id).checked = selectAllMakesCheckboxesChecked;
		}
	}
	
	
}

function updateSelectAllCheckboxes(groupName, groupOfCheckboxes) {
	var selectAllCheckboxId = "";
	selectAllCheckboxId = calculateCheckboxID(groupName);
	var countChecked = 0;
	var countCheckable = 0;
	for (var i=0; i < groupOfCheckboxes.length; i++) {
		if (groupOfCheckboxes[i].disabled !== true) {
			countCheckable++;
			if(groupOfCheckboxes[i].checked === true) {
				countChecked++;
			}
		}
	}
	setSelectAllCheckbox(countChecked, countCheckable);
	return true;
}

function calculateCheckboxID(groupName) {
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
	return selectAllCheckboxId;
}

function setSelectAllCheckbox(countChecked, countCheckable) {
    if(countChecked == countCheckable) {
		//All checkable checkboxes are checked. Check the Select All checkbox
		document.getElementById(selectAllCheckboxId).checked = true;
	} else {
		document.getElementById(selectAllCheckboxId).checked = false;
	}
}

function findSelectedRegion() {
	var regionElements = document.getElementsByName('rdRegion');
	for(var i=0; i < regionElements.length; i++) {
		if (regionElements[i].checked === true) {
			return regionElements[i].id;
		}
	}
}

function findSelectedLeagues() {
	var selectedLeagues = [];
	var leagueElements = document.getElementsByName('chkLeague[]');
	for(var i=0; i < leagueElements.length; i++) {
		if (leagueElements[i].checked === true) {
			selectedLeagues.push(leagueElements[i].id);
		}
	}
	
	return selectedLeagues;
}

function findValidLeagues() {
	var validLeagues = [];
	
	//Find the selected region:
	var selectedRegionValue = findSelectedRegion();
	//Variable validCombinations is initialised in report_home.php.
	// /** global: validCombinations */
	for(var i=0; i < validCombinations.length; i++) {
		if(validCombinations[i]['region'] == selectedRegionValue) {
			//Add item to array only if it does not exist
			validLeagues.indexOf(validCombinations[i]['league']) === -1 ? validLeagues.push(validCombinations[i]['league']) : null;
		}
	}
	
	return validLeagues;
}

function findValidAgeGroups() {
	var validAgeGroups = [];
	
	//Find the selected region:
	var selectedRegionValue = findSelectedRegion();
	
	//Find the selected leagues:
	var selectedLeagueValues = findSelectedLeagues();
	/** global: validCombinations */
	for(var i=0; i < validCombinations.length; i++) {
		
		for(var j=0; j < selectedLeagueValues.length; j++) {
			if(validCombinations[i]['region'] == selectedRegionValue &&
		        validCombinations[i]['league'] == selectedLeagueValues[j]) {
				//Add item to array only if it does not exist
				validAgeGroups.indexOf(validCombinations[i]['age_group']) === -1 ? validAgeGroups.push(validCombinations[i]['age_group']) : null;
			}
		}
	}
	return validAgeGroups;
}

function validateReportSelections() {
    var checkboxesLeague = document.getElementsByName("chkLeague[]");
    var checkboxesUmpireDiscipline = document.getElementsByName("chkUmpireDiscipline[]");
    var checkboxesAgeGroup = document.getElementsByName("chkAgeGroup[]");
    
    var convertedStringLeague = convertValueArrayToString(checkboxesLeague);
    var convertedStringUmpireDiscipline = convertValueArrayToString(checkboxesUmpireDiscipline);
    var convertedStringAgeGroup = convertValueArrayToString(checkboxesAgeGroup);
    var convertedStringRegion = convertValueArrayToString(document.getElementsByName("rdRegion"));
    
    document.getElementById("chkLeagueHidden").value = convertedStringLeague;
    document.getElementById("chkUmpireDisciplineHidden").value = convertedStringUmpireDiscipline;
    document.getElementById("chkAgeGroupHidden").value = convertedStringAgeGroup;
    document.getElementById("chkRegionHidden").value = convertedStringRegion;

    var leagueCheckboxesValid = isCheckboxSelected(checkboxesLeague);
    var umpireDisciplineCheckboxesValid = isCheckboxSelected(checkboxesUmpireDiscipline);
    var ageGroupCheckboxesValid = isCheckboxSelected(checkboxesAgeGroup);
	
    var errorHTML;

    if (leagueCheckboxesValid && umpireDisciplineCheckboxesValid && ageGroupCheckboxesValid) {
    	submitForm();
    } else {
	updateErrorMessage(leagueCheckboxesValid, umpireDisciplineCheckboxesValid, ageGroupCheckboxesValid);
    }
}

function submitForm() {
	document.getElementById("submitForm").submit();
}

function updateErrorMessage() {
        document.getElementById("validationError").innerHTML;
	document.getElementById("validationError").innerHTML = calculateErrorHTML();
}

function calculateErrorHTML(leagueCheckboxesValid, umpireDisciplineCheckboxesValid, ageGroupCheckboxesValid) {
	var errorHTML;
        if (!leagueCheckboxesValid) {
    		errorHTML = "Please select at least one League. <br />";
    	} 
    	if (!umpireDisciplineCheckboxesValid) {
    		errorHTML += "Please select at least one Umpire Discipline. <br />";
    	} 
    	if (!ageGroupCheckboxesValid) {
    		errorHTML += "Please select at least one Age Group. <br />";
    	}	
    return errorHTML;
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
		    convertedString = convertedString + nodeListToConvert[i].value + ","
		}
	}
	return convertedString;
}

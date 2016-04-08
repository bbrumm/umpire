/**
 * 
 */


function updateHiddenValues() {
	
	document.getElementById('umpireTypeHidden').value = document.getElementById('umpireType').value;
	document.getElementById('ageHidden').value = document.getElementById('age').value;
	document.getElementById('leagueHidden').value = document.getElementById('league').value;
	
	
}

function updatePageFromCheckboxSelection(selectAllCheckbox, individualCheckboxName) {
	
	//Toggle the Select All checkbox
	toggleSelectAll(selectAllCheckbox, individualCheckboxName.name);
	console.log("selectAllCheckbox " + selectAllCheckbox);
	console.log("individualCheckboxName " + individualCheckboxName.name);
	
	updateCheckboxEnabledStatus();
	
	
}

function updateCheckboxEnabledStatus() {
	
	var reportSelectionValue = document.getElementById("reportName").value;
	
	console.log("Geelong checked = " + document.getElementById("Geelong").checked);
	console.log("Colac checked = " + document.getElementById("Colac").checked);
	if (document.getElementById("Geelong").checked) {
		if (reportSelectionValue == '03' || reportSelectionValue == '04' || reportSelectionValue == '05') {
			console.log('geelong 345');
			//Disable Umpire, Age Group and League selections
			setCheckboxStatus("BFL", false, true);
			setCheckboxStatus("GFL", false, true);
			setCheckboxStatus("GDFL", false, true);
			setCheckboxStatus("GJFL", false, true);
			setCheckboxStatus("CDFNL", false, false);
			
			setCheckboxStatus("Seniors", false, true);
			setCheckboxStatus("Reserves", false, true);
			setCheckboxStatus("Colts", false, true);
			setCheckboxStatus("Under 16", false, true);
			setCheckboxStatus("Under 14", false, true);
			setCheckboxStatus("Under 12", false, true);
			setCheckboxStatus("Junior Girls", false, true);
			setCheckboxStatus("Youth Girls", false, true);
			setCheckboxStatus("Under 17.5", false, false);
			setCheckboxStatus("Under 14.5", false, false);
			
			setCheckboxStatus("Field", false, true);
			setCheckboxStatus("Boundary", false, true);
			setCheckboxStatus("Goal", false, true);
			
			setCheckboxStatus("LeagueSelectAll", false, true);
			setCheckboxStatus("AgeGroupSelectAll", false, true);
			setCheckboxStatus("UmpireDisciplineSelectAll", false, true);
			
		} else if (reportSelectionValue == '06') {
			console.log('geelong 6');
			//Enable the Umpire Discipline and Age Group selections
			setCheckboxStatus("BFL", false, true);
			setCheckboxStatus("GFL", false, true);
			setCheckboxStatus("GDFL", false, true);
			setCheckboxStatus("GJFL", false, true);
			setCheckboxStatus("CDFNL", false, false);
			
			setCheckboxStatus("Under 17.5", false, false);
			setCheckboxStatus("Under 14.5", false, false);
			
			if (document.getElementById("GJFL").checked == true) {
				setCheckboxStatus("Colts", true);
				setCheckboxStatus("Under 16", true);
				setCheckboxStatus("Under 14", true);
				setCheckboxStatus("Under 12", true);
				setCheckboxStatus("Junior Girls", true);
				setCheckboxStatus("Youth Girls", true);
			} else {
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
			}
			
			if (document.getElementById("BFL").checked == true || 
				document.getElementById("GFL").checked == true ||
				document.getElementById("GDFL").checked == true) {
				setCheckboxStatus("Seniors", true);
				setCheckboxStatus("Reserves", true);
			} else {
				setCheckboxStatus("Seniors", false, false);
				setCheckboxStatus("Reserves", false, false);
			}
			
			setCheckboxStatus("LeagueSelectAll", false, false);
			setCheckboxStatus("AgeGroupSelectAll", true, false);
			setCheckboxStatus("UmpireDisciplineSelectAll", true, false);
			
			
		} else {
			console.log('geelong other');
			//Enable the Umpire Discipline, Age Group, and League selections
			setCheckboxStatus("BFL", true);
			setCheckboxStatus("GFL", true);
			setCheckboxStatus("GDFL", true);
			setCheckboxStatus("GJFL", true);
			setCheckboxStatus("CDFNL", false, false);
			
			
			setCheckboxStatus("Under 17.5", false, false);
			setCheckboxStatus("Under 14.5", false, false);
			
			if (document.getElementById("GJFL").checked == true) {
				setCheckboxStatus("Colts", true);
				setCheckboxStatus("Under 16", true);
				setCheckboxStatus("Under 14", true);
				setCheckboxStatus("Under 12", true);
				setCheckboxStatus("Junior Girls", true);
				setCheckboxStatus("Youth Girls", true);
			} else {
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
			}
			
			if (document.getElementById("BFL").checked == true || 
				document.getElementById("GFL").checked == true ||
				document.getElementById("GDFL").checked == true) {
				setCheckboxStatus("Seniors", true);
				setCheckboxStatus("Reserves", true);
			} else {
				setCheckboxStatus("Seniors", false, false);
				setCheckboxStatus("Reserves", false, false);
			}
			
			
			setCheckboxStatus("LeagueSelectAll", true, false);
			setCheckboxStatus("AgeGroupSelectAll", true, false);
			setCheckboxStatus("UmpireDisciplineSelectAll", true, false);
		}

		} else if (document.getElementById("Colac").checked) {
			if (reportSelectionValue == '03' || reportSelectionValue == '04' || reportSelectionValue == '05') {
				console.log('colac 345');
				//Disable Umpire, Age Group and League selections
				setCheckboxStatus("BFL", false, false);
				setCheckboxStatus("GFL", false, false);
				setCheckboxStatus("GDFL", false, false);
				setCheckboxStatus("GJFL", false, false);
				setCheckboxStatus("CDFNL", false, true);
				
				
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				setCheckboxStatus("Seniors", false, true);
				setCheckboxStatus("Reserves", false, true);
				setCheckboxStatus("Under 17.5", false, true);
				setCheckboxStatus("Under 14.5", false, true);
				
				setCheckboxStatus("Field", false, true);
				setCheckboxStatus("Boundary", false, true);
				setCheckboxStatus("Goal", false, true);
				
				/*
				setCheckboxStatus("LeagueSelectAll", false, true);
				setCheckboxStatus("AgeGroupSelectAll", false, true);
				setCheckboxStatus("UmpireDisciplineSelectAll", false, true);
				*/
				
				
			} else if (reportSelectionValue == '06') {
				console.log('colac 6');
				//Enable the Umpire Discipline and Age Group selections
				setCheckboxStatus("BFL", false, false);
				setCheckboxStatus("GFL", false, false);
				setCheckboxStatus("GDFL", false, false);
				setCheckboxStatus("GJFL", false, false);
				setCheckboxStatus("CDFNL", false, true);
				
				
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				
				if (document.getElementById("CDFNL").checked == true) {
					setCheckboxStatus("Seniors", true);
					setCheckboxStatus("Reserves", true);
					setCheckboxStatus("Under 17.5", true);
					setCheckboxStatus("Under 14.5", true);
				} else {
					setCheckboxStatus("Seniors", false, false);
					setCheckboxStatus("Reserves", false, false);
					setCheckboxStatus("Under 17.5", false, false);
					setCheckboxStatus("Under 14.5", false, false);
				}
				
				setCheckboxStatus("Field", true, false);
				setCheckboxStatus("Boundary", true, false);
				setCheckboxStatus("Goal", true, false);
				
				/*
				setCheckboxStatus("LeagueSelectAll", false, false);
				setCheckboxStatus("AgeGroupSelectAll", true, false);
				setCheckboxStatus("UmpireDisciplineSelectAll", true, false);
				*/
				
			} else {
				//Enable the Umpire Discipline, Age Group, and League selections
				console.log('colac other');
				setCheckboxStatus("BFL", false, false);
				setCheckboxStatus("GFL", false, false);
				setCheckboxStatus("GDFL", false, false);
				setCheckboxStatus("GJFL", false, false);
				setCheckboxStatus("CDFNL", true);
				
				
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				
				if (document.getElementById("CDFNL").checked == true) {
					setCheckboxStatus("Seniors", true);
					setCheckboxStatus("Reserves", true);
					setCheckboxStatus("Under 17.5", true);
					setCheckboxStatus("Under 14.5", true);
				} else {
					setCheckboxStatus("Seniors", false, false);
					setCheckboxStatus("Reserves", false, false);
					setCheckboxStatus("Under 17.5", false, false);
					setCheckboxStatus("Under 14.5", false, false);
				}
				/*
				setCheckboxStatus("LeagueSelectAll", true, false);
				setCheckboxStatus("AgeGroupSelectAll", true, false);
				setCheckboxStatus("UmpireDisciplineSelectAll", true, false);
			*/
			}
			
		}
	
	//Update the hidden values
	convertedStringLeague = convertValueArrayToString(document.getElementsByName("chkLeague[]"));
    convertedStringUmpireDiscipline = convertValueArrayToString(document.getElementsByName("chkUmpireDiscipline[]"));
    convertedStringAgeGroup = convertValueArrayToString(document.getElementsByName("chkAgeGroup[]"));
    document.getElementById("chkLeagueHidden").value = convertedStringLeague;
    document.getElementById("chkUmpireDisciplineHidden").value = convertedStringUmpireDiscipline;
    document.getElementById("chkAgeGroupHidden").value = convertedStringAgeGroup;
    
}

function setCheckboxStatus(checkboxID, enabledStatus, checkedStatus) {
	if (typeof checkedStatus === 'undefined') { checkedStatus = 'default'; }
	
	document.getElementById(checkboxID).disabled = !enabledStatus;
	if (checkedStatus != 'default') {
		console.log("Set checked status for ID " + checkboxID + " to " + checkedStatus);
		document.getElementById(checkboxID).checked = checkedStatus;
	}
	
}



function toggle(source, controlName) {
	//alert(controlName);
	checkboxes = document.getElementsByName(controlName);
	for(var i=0, n=checkboxes.length;i<n;i++) {
		if (checkboxes[i].disabled == false) {
			checkboxes[i].checked = source.checked;
		} else {
			checkboxes[i].checked = false;
		}
	}
	
	updateCheckboxEnabledStatus();
}

function toggleSelectAll(selectAllCheckbox, individualCheckboxName) {
	var checkboxes = document.getElementsByName(individualCheckboxName.name);
	var selectAllCheckbox = document.getElementById(selectAllCheckbox.id);
	for(var i=0, n=checkboxes.length;i<n;i++) {
		if (!checkboxes[i].checked) {
			//Found a checkbox that is not checked. Set the Select All to unchecked
			document.getElementById(selectAllCheckbox.id).checked = false;
			break;
		}
	}
	
	
	
}

function validateReportSelections() {
    checkboxesLeague = document.getElementsByName("chkLeague[]");
    checkboxesUmpireDiscipline = document.getElementsByName("chkUmpireDiscipline[]");
    checkboxesAgeGroup = document.getElementsByName("chkAgeGroup[]");
    
    convertedStringLeague = convertValueArrayToString(checkboxesLeague);
    convertedStringUmpireDiscipline = convertValueArrayToString(checkboxesUmpireDiscipline);
    convertedStringAgeGroup = convertValueArrayToString(checkboxesAgeGroup);
    
    console.log("convertedString " + convertValueArrayToString(checkboxesUmpireDiscipline));
    
    //Reset the validation error message
    document.getElementById("validationError").innerHTML = "";
    
    var leagueCheckboxesValid = isCheckboxSelected(checkboxesLeague);
    var umpireDisciplineCheckboxesValid = isCheckboxSelected(checkboxesUmpireDiscipline);
    var ageGroupCheckboxesValid = isCheckboxSelected(checkboxesAgeGroup);
    
    //Set the hidden input values to the checkbox values
    /*
    document.getElementById("chkLeagueHidden").value = convertedStringLeague;
    document.getElementById("chkUmpireDisciplineHidden").value = convertedStringUmpireDiscipline;
    document.getElementById("chkAgeGroupHidden").value = convertedStringAgeGroup;
    */
    //document.getElementsByName("chkLeagueHidden").value = checkboxesLeague;
    //document.getElementsByName("chkUmpireDisciplineHidden").value = checkboxesUmpireDiscipline;
    //document.getElementsByName("chkAgeGroupHidden").value = checkboxesAgeGroup;
    /*
    console.log("chkLeagueHidden: " + document.getElementById("chkLeagueHidden").value);
    console.log("chkUmpireDisciplineHidden: " + document.getElementById("chkUmpireDisciplineHidden").value);
    console.log("chkAgeGroupHidden: " + document.getElementById("chkAgeGroupHidden").value);
    */

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
	return convertedString;
	
}



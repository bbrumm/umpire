/**
 * 
 */



/*
  New logic from database:
  Look up the permissions tables for the current user
  Load them into memory when the page loads (so we don't need to check the database each time a checkbox is changed)
  If they have the permissions (e.g. view report, or select report option), then show the control as enabled
  If they don't have permissions, show it as grey and disabled (same as current formatting)
  If a selection determines that this selection is not valid (e.g. region Geelong and league CDFNL) then show it as grey
  
  The JavaScript code would need to:
  - Set the enabled status in an array for each control 
  - update the array in memory of all of the statuses each time a selection is made
  - Then update the controls on the page 
 
 - The logic of what controls are selected in what situations should be stored in the database
 
 
 */


function updateHiddenValues() {
	
	document.getElementById('umpireTypeHidden').value = document.getElementById('umpireType').value;
	document.getElementById('ageHidden').value = document.getElementById('age').value;
	document.getElementById('leagueHidden').value = document.getElementById('league').value;
	document.getElementById('regionHidden').value = document.getElementById('region').value;
	
	
}

function updatePageFromCheckboxSelection(individualCheckboxName, selectAllCheckbox) {
	if (typeof selectAllCheckbox === 'undefined') { selectAllCheckbox = 'default'; }
	
	//Toggle the Select All checkbox
	if (selectAllCheckbox != 'default') {
		toggleSelectAll(selectAllCheckbox, individualCheckboxName.name);
	}
	console.log("selectAllCheckbox " + selectAllCheckbox);
	console.log("individualCheckboxName " + individualCheckboxName.name);
	
	updateCheckboxEnabledStatus();
	
	
}

function updateCheckboxEnabledStatus() {
	
	var reportSelectionValue = document.getElementById("reportName").value;
	
	console.log("Geelong checked = " + document.getElementById("Geelong").checked);
	console.log("Colac checked = " + document.getElementById("Colac").checked);
	console.log("Report Selection Value = " + reportSelectionValue);
	
	//Re-enable the two radio buttons
	setRadioButtonStatus("Geelong", true);
	setRadioButtonStatus("Colac", true);
	
	if (document.getElementById("Geelong").checked) {
		if (reportSelectionValue == 3 || reportSelectionValue == 4 || reportSelectionValue == 5) {
			console.log('geelong 345');
			//Disable Umpire, Age Group and League selections
			setCheckboxStatus("BFL", false, true);
			setCheckboxStatus("GFL", false, true);
			setCheckboxStatus("GDFL", false, true);
			setCheckboxStatus("GJFL", false, true);
			setCheckboxStatus("CDFNL", false, false);
			setCheckboxStatus("Women", false, true);
			
			setCheckboxStatus("Seniors", false, true);
			setCheckboxStatus("Reserves", false, true);
			setCheckboxStatus("Colts", false, true);
			setCheckboxStatus("Under 16", false, true);
			setCheckboxStatus("Under 14", false, true);
			setCheckboxStatus("Under 12", false, true);
			setCheckboxStatus("Junior Girls", false, true);
			setCheckboxStatus("Youth Girls", false, true);
			setCheckboxStatus("Under 19 Girls", false, true);
			setCheckboxStatus("Under 15 Girls", false, true);
			setCheckboxStatus("Under 12 Girls", false, true);
			setCheckboxStatus("Under 17.5", false, false);
			setCheckboxStatus("Under 14.5", false, false);
			
			setCheckboxStatus("Field", false, true);
			setCheckboxStatus("Boundary", false, true);
			setCheckboxStatus("Goal", false, true);
			
			setCheckboxStatus("LeagueSelectAll", false, true);
			setCheckboxStatus("AgeGroupSelectAll", false, true);
			setCheckboxStatus("UmpireDisciplineSelectAll", false, true);
			
		} else if (reportSelectionValue == 6) {
			console.log('geelong 6');
			//Enable the Umpire Discipline and Age Group selections
			setCheckboxStatus("BFL", false, true);
			setCheckboxStatus("GFL", false, true);
			setCheckboxStatus("GDFL", false, true);
			setCheckboxStatus("GJFL", false, true);
			setCheckboxStatus("CDFNL", false, false);
			setCheckboxStatus("Women", false, true);

			setCheckboxStatus("Under 17.5", false, false);
			setCheckboxStatus("Under 14.5", false, false);
			
			if (document.getElementById("GJFL").checked == true) {
				setCheckboxStatus("Colts", true);
				setCheckboxStatus("Under 16", true);
				setCheckboxStatus("Under 14", true);
				setCheckboxStatus("Under 12", true);
				setCheckboxStatus("Junior Girls", true);
				setCheckboxStatus("Youth Girls", true);
				setCheckboxStatus("Under 19 Girls", true);
				setCheckboxStatus("Under 15 Girls", true);
				setCheckboxStatus("Under 12 Girls", true);
				
			} else {
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				setCheckboxStatus("Under 19 Girls", false, false);
				setCheckboxStatus("Under 15 Girls", false, false);
				setCheckboxStatus("Under 12 Girls", false, false);
				
			}
			
			if (document.getElementById("BFL").checked == true || 
				document.getElementById("GFL").checked == true ||
				document.getElementById("GDFL").checked == true||
				document.getElementById("Women").checked == true) {
				setCheckboxStatus("Seniors", true);
				setCheckboxStatus("Reserves", true);
			} else {
				setCheckboxStatus("Seniors", false, false);
				setCheckboxStatus("Reserves", false, false);
			}
			
			setCheckboxStatus("LeagueSelectAll", false, false);
			setCheckboxStatus("AgeGroupSelectAll", true, false);
			setCheckboxStatus("UmpireDisciplineSelectAll", true, false);
		
			
		} else if (reportSelectionValue == 7) {
			console.log('geelong 7');
			//Disable the Umpire Discipline selections
			
			setCheckboxStatus("BFL", true);
			setCheckboxStatus("GFL", true);
			setCheckboxStatus("GDFL", true);
			setCheckboxStatus("GJFL", true);
			setCheckboxStatus("CDFNL", false, false);
			setCheckboxStatus("Women", true);
			
			setCheckboxStatus("Field", false, true);
			setCheckboxStatus("Boundary", false, false);
			setCheckboxStatus("Goal", false, false);

			setCheckboxStatus("Under 17.5", false, false);
			setCheckboxStatus("Under 14.5", false, false);
			
			if (document.getElementById("GJFL").checked == true) {
				setCheckboxStatus("Colts", true);
				setCheckboxStatus("Under 16", true);
				setCheckboxStatus("Under 14", true);
				setCheckboxStatus("Under 12", true);
				setCheckboxStatus("Junior Girls", true);
				setCheckboxStatus("Youth Girls", true);
				setCheckboxStatus("Under 19 Girls", true);
				setCheckboxStatus("Under 15 Girls", true);
				setCheckboxStatus("Under 12 Girls", true);
			} else {
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				setCheckboxStatus("Under 19 Girls", false, false);
				setCheckboxStatus("Under 15 Girls", false, false);
				setCheckboxStatus("Under 12 Girls", false, false);
			}
			
			if (document.getElementById("BFL").checked == true || 
				document.getElementById("GFL").checked == true ||
				document.getElementById("GDFL").checked == true ||
				document.getElementById("Women").checked == true) {
				setCheckboxStatus("Seniors", true);
				setCheckboxStatus("Reserves", true);
			} else {
				setCheckboxStatus("Seniors", false, false);
				setCheckboxStatus("Reserves", false, false);
			}
			
			/*
			setCheckboxStatus("LeagueSelectAll", true, false);
			setCheckboxStatus("AgeGroupSelectAll", true, false);
			setCheckboxStatus("UmpireDisciplineSelectAll", true, false);
			*/
		
		} else {
			console.log('geelong other');
			//Enable the Umpire Discipline, Age Group, and League selections
			setCheckboxStatus("BFL", true);
			setCheckboxStatus("GFL", true);
			setCheckboxStatus("GDFL", true);
			setCheckboxStatus("GJFL", true);
			setCheckboxStatus("CDFNL", false, false);
			setCheckboxStatus("Women", true);
			
			
			setCheckboxStatus("Under 17.5", false, false);
			setCheckboxStatus("Under 14.5", false, false);
			
			if (document.getElementById("GJFL").checked == true) {
				setCheckboxStatus("Colts", true);
				setCheckboxStatus("Under 16", true);
				setCheckboxStatus("Under 14", true);
				setCheckboxStatus("Under 12", true);
				setCheckboxStatus("Junior Girls", true);
				setCheckboxStatus("Youth Girls", true);
				setCheckboxStatus("Under 19 Girls", true);
				setCheckboxStatus("Under 15 Girls", true);
				setCheckboxStatus("Under 12 Girls", true);
			} else {
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				setCheckboxStatus("Under 19 Girls", false, false);
				setCheckboxStatus("Under 15 Girls", false, false);
				setCheckboxStatus("Under 12 Girls", false, false);
			}
			
			if (document.getElementById("BFL").checked == true || 
				document.getElementById("GFL").checked == true ||
				document.getElementById("GDFL").checked == true ||
				document.getElementById("Women").checked == true) {
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
			if (reportSelectionValue == 3 || reportSelectionValue == 4 || reportSelectionValue == 5) {
				console.log('colac 345');
				//Disable Umpire, Age Group and League selections
				setCheckboxStatus("BFL", false, false);
				setCheckboxStatus("GFL", false, false);
				setCheckboxStatus("GDFL", false, false);
				setCheckboxStatus("GJFL", false, false);
				setCheckboxStatus("CDFNL", false, true);
				setCheckboxStatus("Women", false, false);
				
				
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				setCheckboxStatus("Under 19 Girls", false, false);
				setCheckboxStatus("Under 15 Girls", false, false);
				setCheckboxStatus("Under 12 Girls", false, false);
				setCheckboxStatus("Seniors", false, true);
				setCheckboxStatus("Reserves", false, true);
				setCheckboxStatus("Under 17.5", false, true);
				setCheckboxStatus("Under 14.5", false, true);
				
				setCheckboxStatus("Field", false, true);
				setCheckboxStatus("Boundary", false, true);
				setCheckboxStatus("Goal", false, true);
				
				setCheckboxStatus("LeagueSelectAll", false, true);
				setCheckboxStatus("AgeGroupSelectAll", false, true);
				setCheckboxStatus("UmpireDisciplineSelectAll", false, true);
				
				
			} else if (reportSelectionValue == 6) {
				console.log('colac 6');
				//Enable the Umpire Discipline and Age Group selections
				setCheckboxStatus("BFL", false, false);
				setCheckboxStatus("GFL", false, false);
				setCheckboxStatus("GDFL", false, false);
				setCheckboxStatus("GJFL", false, false);
				setCheckboxStatus("CDFNL", false, true);
				setCheckboxStatus("Women", false, false);

				
				
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				setCheckboxStatus("Under 19 Girls", false, false);
				setCheckboxStatus("Under 15 Girls", false, false);
				setCheckboxStatus("Under 12 Girls", false, false);
				
				
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
				setCheckboxStatus("Field", true, false);
				setCheckboxStatus("Boundary", true, false);
				setCheckboxStatus("Goal", true, false);
				*/
				setCheckboxStatus("LeagueSelectAll", false, false);
				setCheckboxStatus("AgeGroupSelectAll", true, false);
				setCheckboxStatus("UmpireDisciplineSelectAll", true, false);
			
			} else if (reportSelectionValue == 7) {
				console.log('colac 7');
				//Enable the Umpire Discipline and Age Group selections
				setCheckboxStatus("BFL", false, false);
				setCheckboxStatus("GFL", false, false);
				setCheckboxStatus("GDFL", false, false);
				setCheckboxStatus("GJFL", false, false);
				setCheckboxStatus("CDFNL", false, true);
				setCheckboxStatus("Women", false, false);
				
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				setCheckboxStatus("Under 19 Girls", false, false);
				setCheckboxStatus("Under 15 Girls", false, false);
				setCheckboxStatus("Under 12 Girls", false, false);
				
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
				
				setCheckboxStatus("Field", false, true);
				setCheckboxStatus("Boundary", false, false);
				setCheckboxStatus("Goal", false, false);
				
				setCheckboxStatus("LeagueSelectAll", false, false);
				setCheckboxStatus("AgeGroupSelectAll", true, false);
				setCheckboxStatus("UmpireDisciplineSelectAll", true, false);
				
				
			} else {
				//Enable the Umpire Discipline, Age Group, and League selections
				console.log('colac other');
				setCheckboxStatus("BFL", false, false);
				setCheckboxStatus("GFL", false, false);
				setCheckboxStatus("GDFL", false, false);
				setCheckboxStatus("GJFL", false, false);
				setCheckboxStatus("CDFNL", true);
				setCheckboxStatus("Women", false, false);
				
				
				setCheckboxStatus("Colts", false, false);
				setCheckboxStatus("Under 16", false, false);
				setCheckboxStatus("Under 14", false, false);
				setCheckboxStatus("Under 12", false, false);
				setCheckboxStatus("Junior Girls", false, false);
				setCheckboxStatus("Youth Girls", false, false);
				setCheckboxStatus("Under 19 Girls", false, false);
				setCheckboxStatus("Under 15 Girls", false, false);
				setCheckboxStatus("Under 12 Girls", false, false);
				
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
	
	//Perform this logic regardless of the league selected
	if (reportSelectionValue == 8) {
		console.log('either 8');
		//Disable the Umpire Discipline selections
		
		//Disable Umpire, Age Group and League selections
		setCheckboxStatus("BFL", false, true);
		setCheckboxStatus("GFL", false, true);
		setCheckboxStatus("GDFL", false, true);
		setCheckboxStatus("GJFL", false, true);
		setCheckboxStatus("CDFNL", false, true);
		setCheckboxStatus("Women", false, true);
		
		setCheckboxStatus("Seniors", false, true);
		setCheckboxStatus("Reserves", false, true);
		setCheckboxStatus("Colts", false, true);
		setCheckboxStatus("Under 16", false, true);
		setCheckboxStatus("Under 14", false, true);
		setCheckboxStatus("Under 12", false, true);
		setCheckboxStatus("Junior Girls", false, true);
		setCheckboxStatus("Youth Girls", false, true);
		setCheckboxStatus("Under 19 Girls", false, true);
		setCheckboxStatus("Under 15 Girls", false, true);
		setCheckboxStatus("Under 12 Girls", false, true);
		setCheckboxStatus("Under 17.5", false, false);
		setCheckboxStatus("Under 14.5", false, false);
		
		setCheckboxStatus("Field", false, true);
		setCheckboxStatus("Boundary", false, true);
		setCheckboxStatus("Goal", false, true);
		
		setCheckboxStatus("LeagueSelectAll", false, true);
		setCheckboxStatus("AgeGroupSelectAll", false, true);
		setCheckboxStatus("UmpireDisciplineSelectAll", false, true);
		
		setRadioButtonStatus("Geelong", false);
		setRadioButtonStatus("Colac", false);
	}
		
	
	//Update the hidden values
	convertedStringLeague = convertValueArrayToString(document.getElementsByName("chkLeague[]"));
    convertedStringUmpireDiscipline = convertValueArrayToString(document.getElementsByName("chkUmpireDiscipline[]"));
    convertedStringAgeGroup = convertValueArrayToString(document.getElementsByName("chkAgeGroup[]"));
    convertedStringRegion = convertValueArrayToString(document.getElementsByName("rdRegion"));
    //console.log('regionHidden' + document.getElementsByName("rdRegion").value);
    //console.log('regionHidden' + convertedStringRegion);
    document.getElementById("chkLeagueHidden").value = convertedStringLeague;
    document.getElementById("chkUmpireDisciplineHidden").value = convertedStringUmpireDiscipline;
    document.getElementById("chkAgeGroupHidden").value = convertedStringAgeGroup;
    document.getElementById("chkRegionHidden").value = convertedStringRegion;
    
}

function setCheckboxStatus(checkboxID, enabledStatus, checkedStatus) {
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

function setRadioButtonStatus(radioButonID, enabledStatus) {
	if (enabledStatus) {
		//Enabled, set colour to something else 
		document.getElementById(radioButonID).parentNode.style.background='#FFFFFF';
	} else {
		//Disabled, set colour to grey
		document.getElementById(radioButonID).parentNode.style.background='#d9d9d9';
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
	var checkboxes = document.getElementsByName(individualCheckboxName);
	var selectAllCheckbox = document.getElementById(selectAllCheckbox.id);
	var newSelectAllCheckboxCheckedStatus = true;
	console.log("Checkbox: " + individualCheckboxName + ", length("+ checkboxes.length +")");
	for(var i=0, n=checkboxes.length;i<n;i++) {
		if (!checkboxes[i].checked && !checkboxes[i].disabled) {
			//Found a checkbox that is not checked. Set the Select All to unchecked
			//document.getElementById(selectAllCheckbox.id).checked = false;
			newSelectAllCheckboxCheckedStatus = false;
			console.log("Checkbox not checked: " + checkboxes[i].value)
			break;
		}
	}
	console.log("Setting Select All checkbox to " + newSelectAllCheckboxCheckedStatus);
	document.getElementById(selectAllCheckbox.id).checked = newSelectAllCheckboxCheckedStatus;
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
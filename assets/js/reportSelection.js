/**
 * 
 */


function disableSelectBoxes() {
	
	var reportSelectionValue = document.getElementById("reportName").value;
	//window.alert(reportSelectionValue);
	if (reportSelectionValue == '03' || reportSelectionValue == '04' || reportSelectionValue == '05') {
		//Enable the Umpire Discipline, Age Group, and League selections
		document.getElementById('Umpire Discipline').disabled = true;
		document.getElementById('Age Group').disabled = true;
		document.getElementById('League').disabled = true;
		
		//Enable the "All" option in the Umpire Discipline and Age Group selections
		document.getElementById('umpireType').options[0].disabled = false;
		document.getElementById('Age Group').options[0].disabled = false;
		
		//Set the hidden values so that they are used correctly for the report generation
		document.getElementById('umpireTypeHidden').value = 'All';
		document.getElementById('ageHidden').value = 'All';
		document.getElementById('leagueHidden').value = 'All';
	
	} else if (reportSelectionValue == '06') {
		//Enable the Umpire Discipline and Age Group selections
		document.getElementById('Umpire Discipline').disabled = false;
		document.getElementById('Age Group').disabled = false;
		//Disable the League selection
		document.getElementById('League').disabled = true;
		
		//Disable the "All" Umpire Discipline and auto-select the first option after that
		document.getElementById('Umpire Discipline').options[0].disabled = true;
		document.getElementById('Umpire Discipline').options[1].selected = true;
		
		//Disable the "All" Age Group and auto-select the first option after that
		document.getElementById('Age Group').options[0].disabled = true;
		document.getElementById('Age Group').options[1].selected = true;
		
		//Set the hidden values so that they are used correctly for the report generation
		document.getElementById('umpireTypeHidden').value = document.getElementById('umpireType').value;
		document.getElementById('ageHidden').value = document.getElementById('age').value;
		document.getElementById('leagueHidden').value = 'All';
		
		
	} else {
		//Enable the Umpire Discipline, Age Group, and League selections
		document.getElementById('Umpire Discipline').disabled = false;
		document.getElementById('Age Group').disabled = false;
		document.getElementById('League').disabled = false;
		
		//Enable the "All" option in the Umpire Discipline and Age Group selections
		document.getElementById('Umpire Discipline').options[0].disabled = false;
		document.getElementById('Age Group').options[0].disabled = false;
		
		//Set the hidden values so that they are used correctly for the report generation
		document.getElementById('umpireTypeHidden').value = document.getElementById('umpireType').value;
		document.getElementById('ageHidden').value = document.getElementById('age').value;
		document.getElementById('leagueHidden').value = document.getElementById('league').value;
	}
	
}

function updateHiddenValues() {
	
	document.getElementById('umpireTypeHidden').value = document.getElementById('umpireType').value;
	document.getElementById('ageHidden').value = document.getElementById('age').value;
	document.getElementById('leagueHidden').value = document.getElementById('league').value;
	
	
}

function toggle(source, controlName) {
	//alert(controlName);
	checkboxes = document.getElementsByName(controlName);
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
}


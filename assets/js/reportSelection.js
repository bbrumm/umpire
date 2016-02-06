/**
 * 
 */


function disableSelectBoxes() {
	
	var reportSelectionValue = document.getElementById("reportName").value;
	//window.alert(reportSelectionValue);
	if (reportSelectionValue == '03' || reportSelectionValue == '04' || reportSelectionValue == '05') {
		//Enable the Umpire Discipline, Age Group, and League selections
		document.getElementById('umpireType').disabled = true;
		document.getElementById('age').disabled = true;
		document.getElementById('league').disabled = true;
		
		//Enable the "All" option in the Umpire Discipline and Age Group selections
		document.getElementById('umpireType').options[0].disabled = false;
		document.getElementById('age').options[0].disabled = false;
		
		//Set the hidden values so that they are used correctly for the report generation
		document.getElementById('umpireTypeHidden').value = 'All';
		document.getElementById('ageHidden').value = 'All';
		document.getElementById('leagueHidden').value = 'All';
	
	} else if (reportSelectionValue == '06') {
		//Enable the Umpire Discipline and Age Group selections
		document.getElementById('umpireType').disabled = false;
		document.getElementById('age').disabled = false;
		//Disable the League selection
		document.getElementById('league').disabled = true;
		
		//Disable the "All" Umpire Discipline and auto-select the first option after that
		document.getElementById('umpireType').options[0].disabled = true;
		document.getElementById('umpireType').options[1].selected = true;
		
		//Disable the "All" Age Group and auto-select the first option after that
		document.getElementById('age').options[0].disabled = true;
		document.getElementById('age').options[1].selected = true;
		
		//Set the hidden values so that they are used correctly for the report generation
		document.getElementById('umpireTypeHidden').value = document.getElementById('umpireType').value;
		document.getElementById('ageHidden').value = document.getElementById('age').value;
		document.getElementById('leagueHidden').value = 'All';
		
		
	} else {
		//Enable the Umpire Discipline, Age Group, and League selections
		document.getElementById('umpireType').disabled = false;
		document.getElementById('age').disabled = false;
		document.getElementById('league').disabled = false;
		
		//Enable the "All" option in the Umpire Discipline and Age Group selections
		document.getElementById('umpireType').options[0].disabled = false;
		document.getElementById('age').options[0].disabled = false;
		
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


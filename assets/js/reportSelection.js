/**
 * 
 */


function disableSelectBoxes() {
	
	var reportSelectionValue = document.getElementById("reportName").value;
	//window.alert(reportSelectionValue);
	if (reportSelectionValue == '03') {
		//Disable selection and update the hidden element
		document.getElementById('umpireType').disabled = true;
		document.getElementById('age').disabled = true;
		document.getElementById('league').disabled = true;
		
		document.getElementById('umpireTypeHidden').value = 'All';
		document.getElementById('ageHidden').value = 'All';
		document.getElementById('leagueHidden').value = 'All';
		
	} else {
		//Enable selection and update the hidden elements to match the selection element
		document.getElementById('umpireType').disabled = false;
		document.getElementById('age').disabled = false;
		document.getElementById('league').disabled = false;
		
		document.getElementById('umpireTypeHidden').value = document.getElementById('umpireType').value;
		document.getElementById('ageHidden').value = document.getElementById('age').value;
		document.getElementById('leagueHidden').value = document.getElementById('league').value;
		
	}
	
	
}
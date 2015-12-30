/**
 * 
 */


function disableSelectBoxes() {
	
	var reportSelectionValue = document.getElementById("reportName").value;
	//window.alert(reportSelectionValue);
	if (reportSelectionValue == '03') {
		document.getElementById('umpireType').disabled=true;
		document.getElementById('age').disabled=true;
		document.getElementById('league').disabled=true;
		
	} else {
		document.getElementById('umpireType').disabled=false;
		document.getElementById('age').disabled=false;
		document.getElementById('league').disabled=false;
		
	}
	
	
}
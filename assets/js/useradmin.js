/**
 * 
 */


function toggleUserAdminOptionsSection(selectionID, optionsSectionID) {
	
	var selectionElement = document.getElementById(selectionID);
	var optionsSectionElement = document.getElementById(optionsSectionID);
	//alert(selectionElement.value);
	if (selectionElement.value == 'Administrator' || selectionElement.value == 'Super User') {
		optionsSectionElement.style.display = 'none';
		//console.log('hide');
		
	} else {
		optionsSectionElement.style.display = 'block';
		//console.log('show');
	}
	
}


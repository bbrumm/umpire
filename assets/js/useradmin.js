/**
 * 
 */


function toggleUserAdminOptionsSection(selectionID, optionsSectionID) {
	
	var selectionElement = document.getElementById(selectionID);
	var optionsSectionElement = document.getElementById(optionsSectionID);
	//alert(selectionElement.value);
	//console.log(selectionElement.value);
	if (selectionElement.value == 2 || 
			selectionElement.value == 3|| 
			selectionElement.value == 5) {
		optionsSectionElement.style.display = 'none';
		//console.log('hide');
		
	} else {
		optionsSectionElement.style.display = 'block';
		//console.log('show');
	}
	
}


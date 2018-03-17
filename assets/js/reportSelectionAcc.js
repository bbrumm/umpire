$(document).ready(function() {
	function close_accordion_section() {
		$('.accordion .accordion-section-title').removeClass('active');
		$('.accordion .accordion-section-content').slideUp(300).removeClass('open');
	}
	$('.accordion-section-title').click(function(e) {
		// Grab current anchor value
		var currentAttrValue = $(this).attr('href');
		if($(e.target).is('.active')) {
			close_accordion_section();
		} else {
			close_accordion_section();
			// Add active class to section title
			$(this).addClass('active');
			// Open up the hidden content panel
			$('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
		}
	e.preventDefault();
	});
	
	// Activate first accordion item on page load. Change CSS selector as needed to work with your own HTML structure.
	$('.accordion:first-child .accordion-section-title').addClass('active');
	$('.accordion:first-child .accordion-section-content').slideDown(0).addClass('open');
});

function toggleRadioButton(elementID) {
	
	console.log("element: " + elementID.name)	
	document.getElementById(elementID.name).style.background='#d9d9d9';
	
	document.getElementById(elementID.name).parentNode.style.background='#CCCCCC';
	document.getElementById(elementID.name).parentNode.children('input.radioInvisible').checked = true;
	/*
	  
	 if (document.getElementById("Geelong").checked) {
		document.getElementById(elementID).style.background='#FFFFFF';
	} else {
		document.getElementById(elementID).style.background='#d9d9d9';
	}*/
}
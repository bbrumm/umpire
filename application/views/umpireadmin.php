<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Umpire Reporting - User Administration</title>
 
<script type="text/javascript">
function updateUmpireGames() {
	//TODO: Update this to be relevant for umpire data update, as it was copy and pasted from the file import page.
	//Then add an onClick to the Submit button to call this function, instead of Form Submit.
/*
	var selectedRegion = document.getElementById('cboRegion['+ competitionID +']').value;
	var selectedAgeGroup = document.getElementById('cboAgeGroup['+ competitionID +']').value;
	var selectedDivision = document.getElementById('cboDivision['+ competitionID +']').value;
	var selectedLeague = document.getElementById('cboLeague['+ competitionID +']').value;
*/
	//Get a list of elements from the page
	//var formInputElements = document.forms['form_name'].getElementsByTagName('input');
	//Alternative?
	//var formElements = document.getElementById('form_name').elements;

	var data = $('#formUpdateGames').serialize();

	//var formElement = document.getElementById('formUpdateGames');
	//var data = formElement.serialize();

	//$.post('url', data);

	//alert(selectedRegion);
	//alert(selectedRegion && ", " && selectedAgeGroup && ", " && selectedDivision && ", " && selectedLeague);

	var xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ... 
		xhr = new XMLHttpRequest(); 
	} else if (window.ActiveXObject) { 
		// IE 8 and older 
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}

	/*
	var data = "selectedRegion=" + selectedRegion +
	"&selectedAgeGroup=" + selectedAgeGroup +
	"&selectedDivision=" + selectedDivision +
	"&selectedLeague=" + selectedLeague +
	"&competitionID=" + competitionID;
	*/
	
	console.log("Current file location:");
	console.log(window.location.pathname)
	console.log(data);
	xhr.open("POST", "<?php echo base_url(); ?>" + "index.php/ajax_post_controller/updateUmpireGames", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xhr.send(data);


	xhr.onreadystatechange = display_data;

	function display_data() {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				//document.getElementById("suggestion").innerHTML = xhr.responseText;
				if (xhr.responseText == "OK") {
					document.getElementById("umpireGameDataUpdated").innerHTML = "Data updated!";
				} else {
					document.getElementById("umpireGameDataUpdated").innerHTML = "Data not updated:" + xhr.responseText;
				}
			} else {
				alert('There was a problem with the request.');
			}
		}
	}	
}

</script> 
 </head>
 <body>
	<h2>Umpire Administration</h2>
	<br />

<div>
	<p class="regularUserOptionsHeading">Update Umpire Games Played</p>

<?php

if (isset($userAddedMessage)) {
    echo "<BR /><div class='successMessage' id='umpireGameDataUpdated'>" . $userAddedMessage . "</div>";
}

//echo "<BR /><div class='successMessage' id='umpireGameDataUpdated'>" . $userAddedMessage . "</div>";


?>

<?php echo form_open('UmpireAdmin/updateUmpireGamesPlayed', array('id'=>'formUpdateGames')); ?>
	<div align='center'>
	<span class="submitButton">
			<input type="submit" value="Save Changes" class="btn" />
		</span>
	<br />
	<table class="umpireGamesTable">
    	<thead>
    		<th class="umpireGamesTableHeader">Umpire Name</th>
    		<th class="umpireGamesTableHeader">Games Played:<br />Geelong Prior to 2015</th>
    		<th class="umpireGamesTableHeader">Games Played:<br />Other Leagues</th>
    	</thead>
    	
    	<?php 
        for($i=0; $i<count($umpireArray); $i++) {
            $umpireIteration = $umpireArray[$i];
            ?>
    	<tr>
        	<td class="umpireGamesCellLabel"><span class="umpireGamesLabel"><?php 
        	echo $umpireIteration->getFirstName() . " " . $umpireIteration->getLastName();
        	?></span></td>
        	<td><span class="fieldControl">
        	<?php 
        	echo "<input type='text' size='5' id='". $umpireIteration->getID()."[geelong_prior]' 
            name='". $umpireIteration->getID()."[geelong_prior]' class='umpireGamesTextBox' value='". $umpireIteration->getGamesPlayedPrior()."'/>";
        	?>
        	</span></td>
        	<td><span class="fieldControl">
        	<?php 
        	echo "<input type='text' size='5' id='". $umpireIteration->getID()."[other_leagues]
        	' name='". $umpireIteration->getID()."[other_leagues]' class='umpireGamesTextBox' value='". $umpireIteration->getGamesPlayedOtherLeagues()."'/>";
        	?>
        	</span></td>
    	</tr>
    	<?php 
    	
    	//$newUmpire = new Umpire();
    	$umpireIteration->setOldGamesPlayedPrior($umpireIteration->getGamesPlayedPrior());
    	$umpireIteration->setOldGamesPlayedOtherLeagues($umpireIteration->getGamesPlayedOtherLeagues());
    	
    	/*
    	$umpireData = $umpireIteration->getFirstName() . "," .
        	$umpireIteration->getLastName() . ",".
        	$umpireIteration->getGamesPlayedPrior() . ",".
        	$umpireIteration->getGamesPlayedOtherLeagues();
        	
        	
    	
    	 echo "<input type='hidden' id='". $umpireIteration->getID()."[other_leagues]
    	' name='". $umpireIteration->getID()."[umpireData]' value='". $umpireData."'/>";
    	*/
        }
    	?>
	
	</table>
	</div>
</div>
	
</body>


</html>
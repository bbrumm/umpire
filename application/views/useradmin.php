<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Umpire Reporting - User Administration</title>
 </head>
 <body>
	<h2>User Administration</h2>
	<br />
<div class="roleDefinitions">
<p class="regularUserOptionsHeading">Definitions:</p>
<p><span class="boldedText">Administrator</span>: Can import files, view all reports and options in their competition, and set other user's privileges here.</p>
<p><span class="boldedText">Super User</span>: Can view all reports in their competition for all umpire disciplines, age groups, and leagues, but cannot import files or set other user's privileges.</p>
<p><span class="boldedText">Regular User</span>: Can only view certain reports for their specified umpire disciplines, age groups, and leagues.</p>
</div>
	<br />
	
	<?php 
	/*
	echo "<pre>";
	print_r($userArray);
	echo "</pre>";
	*/
for($i=0; $i<count($userArray); $i++) {
    $userIteration = $userArray[$i];
    //echo "Username :" . $userIteration->getUsername() ."<BR />";
    


?>
	
	<div class="userNameRow">
		<span class="userNameLabel"><label for="username">
		<?php 
		echo $userIteration->getFirstName() . " " . $userIteration->getLastName() . " (". $userIteration->getUsername() .")";
		?>
		</label></span>
		<span class="userAdminLevel">
		
    		
    		<?php
    		echo "<select id='". $userIteration->getUsername() ."' onchange=\"toggleUserAdminOptionsSection('". $userIteration->getUsername() ."', '". $userIteration->getUsername() ."Options')\">";
    		for($j=0; $j<count($roleArray); $j++) {
    		    echo "<option value='". $roleArray[$j]['role_name'] ."' ";
    		    if ($userIteration->getRoleName() == $roleArray[$j]['role_name']) {
    		        echo "selected";
    		    }
    		    echo ">". $roleArray[$j]['role_name'] ."</option>";
    		}
    		echo "</select>";
            ?>
            
        	
        	<select>
    		<?php 
    		for($k=0; $k<count($subRoleArray); $k++) {
    		    echo "<option value='". $subRoleArray[$k]['sub_role_name'] ."' ";
    		    if ($userIteration->getSubRoleName() == $subRoleArray[$k]['sub_role_name']) {
    		        echo "selected";
    		    }
    		    echo ">". $subRoleArray[$k]['sub_role_name'] ."</option>";
    		}
     		?>
        	</select>
		</span>
		<br/>
		<div class="regularUserDetails">
		<?php 
		echo "<div class='allOptionsSections'  id='". $userIteration->getUsername() ."Options' ";
		if ($userIteration->getRoleName() != "Regular User") {
		    echo "style=\"display:none\"";
		}
		echo ">";
		?>
			<p class="regularUserOptionsHeading">Options</p>
			<div class="optionsSection">
            	<div class="optionsSubHeading">Report</div> <br />
            	<?php 
            	for($k=0; $k<count($reportSelectionArray); $k++) {
            	    echo "<input type='checkbox' ";
            	    if ($userIteration->userHasSpecificPermission("VIEW_REPORT", $reportSelectionArray[$k]['report_title'])) {
            	        echo "checked";
            	    }
            	    echo "></input><label>". $reportSelectionArray[$k]['report_title'] ."</label> <br />";
            	}
            	?>
            </div>
            
            <div class="optionsSection">
            	<div class="optionsSubHeading">Region</div> <br />
            	<?php 
            	for($k=0; $k<count($regionSelectionArray); $k++) {
            	    echo "<input type='checkbox' ";
            	    if ($userIteration->userHasSpecificPermission("SELECT_REPORT_OPTION", $regionSelectionArray[$k]['region_name'])) {
            	        echo "checked";
            	    }
            	    echo "></input><label>". $regionSelectionArray[$k]['region_name'] ."</label> <br />";
            	}
            	?>
            </div>
			
			<div class="optionsSection">
            	<div class="optionsSubHeading">Umpire Discipline</div> <br />
            	<?php 
            	for($k=0; $k<count($umpireDisciplineSelectionArray); $k++) {
            	    echo "<input type='checkbox' ";
            	    if ($userIteration->userHasSpecificPermission("SELECT_REPORT_OPTION", $umpireDisciplineSelectionArray[$k]['umpire_type_name'])) {
            	        echo "checked";
            	    }
            	    echo "></input><label>". $umpireDisciplineSelectionArray[$k]['umpire_type_name'] ."</label> <br />";
            	}
            	?>
            </div>

            <div class="optionsSection">
            	<div class="optionsSubHeading">Age</div> <br />
            	<?php 
            	for($k=0; $k<count($ageGroupSelectionArray); $k++) {
            	    echo "<input type='checkbox' ";
            	    if ($userIteration->userHasSpecificPermission("SELECT_REPORT_OPTION", $ageGroupSelectionArray[$k]['age_group'])) {
            	        echo "checked";
            	    }
            	    echo "></input><label>". $ageGroupSelectionArray[$k]['age_group'] ."</label> <br />";
            	}
            	?>
            </div>

            <div class="optionsSection">
            	<div class="optionsSubHeading">League</div> <br />
            	<?php 
            	for($k=0; $k<count($leagueSelectionArray); $k++) {
            	    echo "<input type='checkbox' ";
            	    if ($userIteration->userHasSpecificPermission("SELECT_REPORT_OPTION", $leagueSelectionArray[$k]['short_league_name'])) {
            	        echo "checked";
            	    }
            	    echo "></input><label>". $leagueSelectionArray[$k]['short_league_name'] ."</label> <br />";
            	}
            	?>
            </div>

		<?php 
		echo "</div>";
		?>
		
		</div>
		<br/>
	</div>
    <?php 
}
    ?>
    


	<?php 
	/*
	<div class="loginFieldRow">
		<span class="submitButton">
			<input type="submit" value="Save Changes" class="btn" />
		</span>
	</div>
	*/
	?>


 </body>
</html>
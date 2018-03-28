<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Umpire Reporting - User Administration</title>
 </head>
 <body>
	<h2>User Administration</h2>
	<br />
	<?php 
	if (isset($userAddedMessage)) {
	    echo "<BR /><div class='successMessage'>" . $userAddedMessage . "</div>";
	}
	?>
	<br/>
	<?php echo form_open('UserAdmin/addNewUser'); ?>
    <div class="addNewUser">
	<p class="regularUserOptionsHeading">Add New User</p>
	<div class="newUserFieldRow">
		<span class="fieldLabel"><label for="username">Username:</label></span>
		<span class="fieldControl"><input type="text" size="20" id="username" name="username" class="customTextBox"/></span>
	</div>
	<div class="newUserFieldRow">
		<span class="fieldLabel"><label for="username">First Name:</label></span>
		<span class="fieldControl"><input type="text" size="50" id="firstname" name="firstname" class="customTextBox"/></span>
	</div>
	<div class="newUserFieldRow">
		<span class="fieldLabel"><label for="username">Last Name:</label></span>
		<span class="fieldControl"><input type="text" size="50" id="lastname" name="lastname" class="customTextBox"/></span>
	</div>
	<div class="newUserFieldRow">
		<span class="fieldLabel"><label for="password">Password:</label></span>
		<span class="fieldControl"><input type="password" size="50" id="password" name="password" class="customTextBox"/></span>
	</div>
	
	<br/>
	<div class="reportSelectorRow">
		<input type="submit" value="Add New User" class="btn" />
	</div>
	
	<br/>

</div>	
	<?php echo form_close(); ?>
	
<div class="roleDefinitions">
<p class="regularUserOptionsHeading">Definitions:</p>
<p><span class="boldedText">Administrator</span>: Can import files, view all reports and options in their competition, and set other user's privileges here.</p>
<p><span class="boldedText">Super User</span>: Can view all reports in their competition for all umpire disciplines, age groups, and leagues, but cannot import files or set other user's privileges.</p>
<p><span class="boldedText">Regular User</span>: Can only view certain reports for their specified umpire disciplines, age groups, and leagues.</p>
</div>
<br />
<div class='userRoleList'>
<?php 
echo form_open('UserAdmin/saveUserPrivileges');

for($i=0; $i<count($userArray); $i++) {
    $userIteration = $userArray[$i];
    ?>
	
	<div class="userNameRow">
		<span class="userLabel"><label for="username">
		<?php 
		echo $userIteration->getFirstName() . " " . $userIteration->getLastName() . " (". $userIteration->getUsername() .")";
		?>
		</label></span>
		<span class="userAdminLevel">
    		<?php
    		echo "<select id='". $userIteration->getUsername() . "' " .
        	"name='userRole[". $userIteration->getUsername() . "]' " .
    		"onchange=\"toggleUserAdminOptionsSection('". $userIteration->getUsername() ."', '". $userIteration->getUsername() ."Options')\">";
    		for($j=0; $j<count($roleArray); $j++) {
    		    echo "<option value='". $roleArray[$j]['id'] ."' ";
    		    if ($userIteration->getRoleName() == $roleArray[$j]['role_name']) {
    		        echo "selected";
    		    }
    		    echo ">". $roleArray[$j]['role_name'] ."</option>";
    		}
    		echo "</select>";
            ?>
            
            <?php 
			echo "<input type='checkbox' name='userActive[". $userIteration->getUsername() . "]'";
			if ($userIteration->isActive()) {
			    echo "checked";
			}
			echo "></input><label>Active</label> <br />";
			?>
		</span>
		
		<br />
		
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
            	//for($k=0; $k<count($reportSelectionArray); $k++) {
            	for($k=0; $k<count($permissionSelectionArray); $k++) {
            	    if ($permissionSelectionArray[$k]['category'] == 'Report') {
            	        echo "<input type='checkbox' name='userPrivilege[". $userIteration->getUsername() . "][". $permissionSelectionArray[$k]['id']."]'";
                	    //If the checkbox is checked, it gets passed via POST. If not, then it does not get sent to POST at all.
            	        if ($userIteration->userHasSpecificPermission("VIEW_REPORT", $permissionSelectionArray[$k]['selection_name'])) {
                	        echo "checked";
                	    }
                	    echo "></input><label>". $permissionSelectionArray[$k]['selection_name']."</label> <br />";
            	    }
            	}
            	?>
            </div>
            
            <div class="optionsSection">
            	<div class="optionsSubHeading">Region</div> <br />
            	<?php 
            	//for($k=0; $k<count($regionSelectionArray); $k++) {
            	for($k=0; $k<count($permissionSelectionArray); $k++) {
            	    if ($permissionSelectionArray[$k]['category'] == 'Region') {
                	    echo "<input type='checkbox' name='userPrivilege[". $userIteration->getUsername() . "][". $permissionSelectionArray[$k]['id']."]'";
                	    if ($userIteration->userHasSpecificPermission("SELECT_REPORT_OPTION", $permissionSelectionArray[$k]['selection_name'])) {
                	        echo "checked";
                	    }
                	    echo "></input><label>". $permissionSelectionArray[$k]['selection_name']."</label> <br />";
            	    }
            	}
            	?>
            </div>
			
			<div class="optionsSection">
            	<div class="optionsSubHeading">Umpire Discipline</div> <br />
            	<?php 
            	//for($k=0; $k<count($umpireDisciplineSelectionArray); $k++) {
            	for($k=0; $k<count($permissionSelectionArray); $k++) {
            	    if ($permissionSelectionArray[$k]['category'] == 'Umpire Type') {
                	    echo "<input type='checkbox' name='userPrivilege[". $userIteration->getUsername() . "][". $permissionSelectionArray[$k]['id']."]'";
                	    if ($userIteration->userHasSpecificPermission("SELECT_REPORT_OPTION", $permissionSelectionArray[$k]['selection_name'])) {
                	        echo "checked";
                	    }
                	    echo "></input><label>". $permissionSelectionArray[$k]['selection_name']."</label> <br />";
            	    }
            	}
            	?>
            </div>

            <div class="optionsSection">
            	<div class="optionsSubHeading">Age</div> <br />
            	<?php 
            	//for($k=0; $k<count($ageGroupSelectionArray); $k++) {
            	for($k=0; $k<count($permissionSelectionArray); $k++) {
            	    if ($permissionSelectionArray[$k]['category'] == 'Age Group') {
                	    echo "<input type='checkbox' name='userPrivilege[". $userIteration->getUsername() . "][". $permissionSelectionArray[$k]['id']."]'";
                	    if ($userIteration->userHasSpecificPermission("SELECT_REPORT_OPTION", $permissionSelectionArray[$k]['selection_name'])) {
                	        echo "checked";
                	    }
                	    echo "></input><label>". $permissionSelectionArray[$k]['selection_name']."</label> <br />";
            	    }
            	}
            	?>
            </div>

            <div class="optionsSection">
            	<div class="optionsSubHeading">League</div> <br />
            	<?php 
            	//for($k=0; $k<count($leagueSelectionArray); $k++) {
            	for($k=0; $k<count($permissionSelectionArray); $k++) {
            	    if ($permissionSelectionArray[$k]['category'] == 'League') {
                	    echo "<input type='checkbox' name='userPrivilege[". $userIteration->getUsername() . "][". $permissionSelectionArray[$k]['id']."]'";
                	    if ($userIteration->userHasSpecificPermission("SELECT_REPORT_OPTION", $permissionSelectionArray[$k]['selection_name'])) {
                	        echo "checked";
                	    }
                	    echo "></input><label>". $permissionSelectionArray[$k]['selection_name']."</label> <br />";
            	    }
            	}
            	?>
            </div>
		
		</div>

		</div>
		<br/>
	</div>
    <?php 
}
    ?>
    
</div>

	<div class="loginFieldRow">
		<span class="submitButton">
			<input type="submit" value="Save Changes" class="btn" />
		</span>
	</div>
	<?php echo form_close(); ?>
	<br /><br />

 </body>
</html>
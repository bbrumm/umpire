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
    		<select>
    		<?php 
    		//TODO: Replace this section with a query of all roles from the database
    		for($j=0; $j<count($roleArray); $j++) {
    		    echo "<option value='". $roleArray[$j]['role_name'] ."' ";
    		    if ($userIteration->getRoleName() == $roleArray[$j]['role_name']) {
    		        echo "selected";
    		    }
    		    echo ">". $roleArray[$j]['role_name'] ."</option>";
    		}
?>
</select>
        	
        	<select>
    		<?php 
    		//TODO: Replace this section with a query of all roles from the database
    		
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
	</div>
	

    
    <?php 
    
}
    ?>
    <br/>
	<h2>Test data:</h2>
	
	
	<div class="userNameRow">
		<span class="userNameLabel"><label for="username">Jane Doe</label></span>
		<span class="userAdminLevel">
    		<select id="JaneDoe" onchange="toggleUserAdminOptionsSection('JaneDoe', 'JaneDoeOptions')">
        		<option value="Administrator">Administrator</option>
        		<option value="Super User">Super User</option>
        		<option value="Regular User" selected>Regular User</option>
        	</select>
		</span>
	</div>
	
	<div class="regularUserDetails">
		<div class="allOptionsSections"  id='JaneDoeOptions'>
			<p class="regularUserOptionsHeading">Options</p>
			
			<div class="optionsSection">
            	<div class="optionsSubHeading">Umpire Discipline</div> <br />
            	<input type="checkbox"></input><label>Field</label> <br />
            	<input type="checkbox"></input><label>Boundary</label> <br />
            	<input type="checkbox"></input><label>Goal</label> <br />
               </div>

            <div class="optionsSection">
            	<div class="optionsSubHeading">Age</div> <br />
            	<input type="checkbox"></input><label>Seniors</label> <br />
            	<input type="checkbox"></input><label>Reserves</label> <br />
            	<input type="checkbox"></input><label>Colts</label> <br />
            	<input type="checkbox"></input><label>Under 16</label> <br />
            	<input type="checkbox"></input><label>Under 14</label> <br />
            	<input type="checkbox"></input><label>Youth Girls</label> <br />
            	<input type="checkbox"></input><label>Junior Girls</label> <br />
            </div>

            <div class="optionsSection">
            	<div class="optionsSubHeading">League</div> <br />
            	<input type="checkbox"></input><label>BFL</label> <br />
            	<input type="checkbox"></input><label>GFL</label> <br />
            	<input type="checkbox"></input><label>GDFL</label> <br />
            </div>

		</div>
	</div>

	<br/>
	
	<div class="loginFieldRow">
		<span class="submitButton">
			<input type="submit" value="Save Changes" class="btn" />
		</span>
	</div>



 </body>
</html>
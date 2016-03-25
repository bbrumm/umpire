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
	<div class="userNameRow">
		<span class="userNameLabel"><label for="username">Ben Brumm</label></span>
		<span class="userAdminLevel">
    		<select>
        		<option value="Administrator" selected>Administrator</option>
        		<option value="Super User">Super User</option>
        		<option value="Regular User">Regular User</option>
        	</select>
		</span>
	</div>
	
    <br/>
    
    <div class="userNameRow">
		<span class="userNameLabel"><label for="username">Brendan Beveridge</label></span>
		<span class="userAdminLevel">
    		<select>
        		<option value="Administrator" selected>Administrator</option>
        		<option value="Super User">Super User</option>
        		<option value="Regular User">Regular User</option>
        	</select>
		</span>
	</div>
	
	<br/>
	
	<div class="userNameRow">
		<span class="userNameLabel"><label for="username">John Smith</label></span>
		<span class="userAdminLevel">
    		<select>
        		<option value="Administrator">Administrator</option>
        		<option value="Super User" selected>Super User</option>
        		<option value="Regular User">Regular User</option>
        	</select>
		</span>
	</div>
	
	<br/>
	
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
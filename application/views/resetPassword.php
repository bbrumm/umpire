<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Umpire Reporting - Password Reset</title>
 </head>
 <body>
	<h2>Password Reset</h2>
	<?php 
	//Check if activation ID matches
	if ($activationIDMatches == true) {
	   //TODO: Look up how to disable "variable not declared" for CodeIgniter
    	echo form_open('ResetPasswordEntry/submitNewPassword'); 
    	/*echo "activation ID: ". $activationID ."<br />";
    	echo "username: ". $username ."<br />";
    	*/
    	echo "<input type='hidden' name='username' value='". $username ."' />";
    	echo "<input type='hidden' name='activationID' value='". $activationID."' />";
    	if (isset($statusMessage)) {
    	    echo "<div class='validationError'>". $statusMessage."</div><br />";
    	}
    	?>
    	
        <div class="loginFieldRow">
    		<span class="loginLabel"><label for="password">New Password:</label></span>
    		<span class="loginControl"><input type="password" size="20" id="password" name="password" class="customTextBox"/></span>
        </div>
    	<br/>
    	<div class="loginFieldRow">
    		<span class="loginLabel"><label for="password">Confirm New Password:</label></span>
    		<span class="loginControl"><input type="password" size="20" id="confirmPassword" name="confirmPassword" class="customTextBox"/></span>
        </div>
    	<br/>
    	<div class="reportSelectorRow">
    	<br />
    		<input type="submit" value="Change Password" class="btn" />
    		<br />
    	</div>
    	 
    
    <?php
    
    echo form_close(); 

	} else {
	    //Show error - activation ID does not match
	    echo "<div class='validationError'>Unable to validate password reset request.</div>";
	}
?>

 </body>
</html>
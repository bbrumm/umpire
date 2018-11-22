<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Umpire Reporting - Update Profile</title>
 </head>
 <body>
	<h2>Update Profile</h2>
	<?php 
		//TODO update form name and function name
	echo form_open('UpdateProfile/updateEmail');
    	if (isset($userAddedMessage)) {
    	    if($messageIsSuccess) {
    	        $messageClass = "successMessage";
    	    } else {
    	        $messageClass = "validationError";
    	    }
    	    echo "<div class='". $messageClass ."'>". $userAddedMessage."</div><br />";
    	}
    	?>
    <div class="loginFieldRow">
		<span class="loginLabel"><label for="username">Username</label></span>
		<span class="loginControl">
        <?php 
        echo $username;
        echo "<input type='hidden' name='username' id='username' value='". $username ."' />";
        ?>
		</span>
	</div>
    <br/>
    <div class="loginFieldRow">
		<span class="loginLabel"><label for="email_address">Email Address:</label></span>
		<span class="loginControl"><input type="text" size="255" id="email_address" name="email_address" class="customTextBox"
		<?php 
		echo "value='". $email_address ."'";
		?>
		/></span>
	</div>
    <br/>
    <div class="reportSelectorRow">
    	<br />
    		<input type="submit" value="Update Email Address" class="btn" />
    		<br />
    	</div>
    	 <br />
    <?php 
    
    
    echo form_close(); 
    
    echo form_open('ResetPasswordEntry/submitNewPassword');
    echo "<input type='hidden' name='username' id='username' value='". $username ."' />";
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

?>

 </body>
</html>
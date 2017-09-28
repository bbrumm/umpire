<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Umpire Reporting - Password Reset</title>
 </head>
 <body>
	<h2>Password Reset</h2>
	<?php echo form_open('ForgotPassword/submitChangePasswordForm'); ?>
	
	<div class="loginFieldRow">
		<span class="loginLabel"><label for="username">Username:</label></span>
		<span class="loginControl"><input type="text" size="20" id="username" name="username" class="customTextBox"/></span>
	</div>
    <br/>
	<div class="loginFieldRow">
		<span class="loginLabel"><label for="username">Email Address:</label></span>
		<span class="loginControl"><input type="text" size="320" id="emailAddress" name="emailAddress" class="customTextBox"/></span>
		<br />
		<i>This will be used to send a confirmation email.</i>
	</div>
    <br/>
	<div class="loginFieldRow">
		<span class="loginLabel"><label for="password">New Password:</label></span>
		<span class="loginControl"><input type="password" size="20" id="password" name="password" class="customTextBox"/></span>
    </div>
	<br/>
	<div class="loginFieldRow">
		<span class="loginLabel"><label for="password">Confirm New Password:</label></span>
		<span class="loginControl"><input type="password" size="20" id="password" name="password" class="customTextBox"/></span>
    </div>
	<br/>
	<div class="reportSelectorRow">
	<br />
		<input type="submit" value="Change Password" class="btn" />
		<br /><br />
		<div>Once you click "Change Password", you will receive an email to confirm your password reset.<br />
		Click on the link in the email to complete the password reset.</div>
	</div>

<?php echo form_close(); ?>

 </body>
</html>
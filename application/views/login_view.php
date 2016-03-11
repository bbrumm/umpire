<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Umpire Reporting - Login</title>
 </head>
 <body>
	<h2>Login</h2>
	<?php
	echo "<div class='validationError'>". validation_errors() ."</div>"; 
	?>
	<?php echo form_open('VerifyLogin'); ?>
	
	<div class="loginFieldRow">
		<span class="loginLabel"><label for="username">Username:</label></span>
		<span class="loginControl"><input type="text" size="20" id="username" name="username" class="customTextBox"/></span>
	</div>
    <br/>
	<div class="loginFieldRow">
		<span class="loginLabel"><label for="password">Password:</label></span>
		<span class="loginControl"><input type="password" size="20" id="passowrd" name="password" class="customTextBox"/></span>
    </div>
	<br/>
	<div class="reportSelectorRow">
		<input type="submit" value="Login" class="btn" />
	</div>

<?php echo form_close(); ?>

 </body>
</html>
<?php
echo "<h2>Import File</h2>";

	echo "<div class='validationError'>". validation_errors() ."</div>"; 
	?>
	<?php echo form_open('FileImport'); ?>
	
	<div class="loginFieldRow">
		<span class="loginLabel"><label for="filename">Filename:</label><span>
		<span class="loginControl"><input type="text" size="20" id="filename" name="filename" class="customTextBox"/><span>
	</div>
    <br/>
	<div class="reportSelectorRow">
		<input type="submit" value="Import File" class="btn">
	</div>

   </form>
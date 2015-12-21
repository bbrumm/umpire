<?php
echo "<h2>Import File</h2>";

	//echo "<div class='validationError'>". validation_errors() ."</div>"; 
	?>
	
	<?php 
	echo "<div class='validationError'>". $error ."</div>";
	
	?>

	<?php echo form_open_multipart('FileImport/do_upload');?>

	
	<script type="text/javascript">
 function getFile(){
   document.getElementById("upfile").click();
 }
 function sub(obj){
    var file = obj.value;
    var fileName = file.split("\\");
    document.getElementById("fileNameLabel").innerHTML = fileName[fileName.length-1];
    document.getElementById("yourBtn").innerHTML = fileName[fileName.length-1];
    document.myForm.submit();
    event.preventDefault();
  }
</script>

	<div class="loginFieldRow">
		<span class="loginLabel"><label for="filename">A Filename:</label></span>
		<span class="fileNameLabel"><label id="fileNameLabel">None selected.</label></span>
		<!--<span class="loginControl"><input id = "fileNameLabel" type="label" size="20" name="filename" class="loginLabel"/><span>-->
		<div style='height: 0px;width: 0px; overflow:hidden;'>
		 <input id="upfile" type="file" value="upload" onchange="sub(this)"/>
		</div>
	</div>
    <br/>
	<div class="reportSelectorRow">
		<input type="button" id="fileInput" onclick="getFile()" value="Browse" class="btn">
		<input type="submit" id="submit" value="Import File" class="btn">
	</div>

   </form>
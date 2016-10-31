<html>
<head>
<title>Upload Form</title>
</head>

<script type="text/javascript">
 function getFile(){
   document.getElementById("upfile").click();
 }
 function sub(obj){
    var file = obj.value;
    var fileName = file.split("\\");
    document.getElementById("fileName").innerHTML = fileName[fileName.length-1];
    document.getElementById("yourBtn").innerHTML = fileName[fileName.length-1];
    document.myForm.submit();
    event.preventDefault();
  }
</script>
<body>

<?php
echo "<div class='validationError'>". $error ."</div>";

echo form_open_multipart('FileImport/do_upload');?>
	<div class="loginFieldRow">
		<span class="loginLabel"><label id="fileNameText">Filename:</label></span>
		<span class="fileNameLabel"><label id="fileName">None selected.</label></span>
		<!--<span class="loginControl"><input id = "fileNameLabel" type="label" size="20" name="filename" class="loginLabel"/><span>-->
		<div style='height: 0px;width: 0px; overflow:hidden;'>
		 <input id="upfile" name="userfile" type="file" value="upload" onchange="sub(this)"/>
		</div>
	</div>
    <br/>
	<div class="reportSelectorRow">
		<input type="button" id="fileInput" onclick="getFile()" value="Browse" class="btn">
		<input type="submit" id="submit" value="Import File" class="btn">
	</div>

</form>

  
   <?php echo form_open_multipart('FileImport/testProc');?>
   
   <input type="submit" id="submit" value="Test Proc" class="btn">
   
   </form>
   

</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Umpire Report</title>
<?php
$this->load->helper('url');       
$data['css'] = $this->config->item('css'); 
echo "<link rel='stylesheet' type='text/css' media='all' href='". asset_url() . $data['css'] ."' />";
?>	
	</head>
<body>
<div class="mainBanner">Umpire Reporting</div>
<?php
if($this->session->userdata('logged_in')) {
?>
	<div class="menuBar">
		<a href='home'><div class="menuBarLink">Home Page</div></a>
		<?php
		echo "<a href='home/logout'><div class='menuBarLink'>Logout</div></a>";
}

if ($this->uri->segment(1) == 'report') {
	//Show extra items if we are on the report page

	//echo "<a href='createpdf/pdf' target = '_blank' onclick='form.submit();'><div class='menuBarLink'>Create PDF</div></a>";
	echo "<a href='javascript:{}' onclick='document.getElementById(\"reportPostValues\").submit(); return false;'>";
	echo "<div class='menuBarLink'>Create PDF</div></a>";
	
}


	?>
</div>
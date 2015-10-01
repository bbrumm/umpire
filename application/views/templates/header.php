<html>
	<head>
		<title>Umpire Report</title>
<?php
$this->load->helper('url');       
$data['css'] = $this->config->item('css'); 
echo "<link rel='stylesheet' type='text/css' href='". asset_url() . $data['css'] ."'>";
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
	?>
</div>
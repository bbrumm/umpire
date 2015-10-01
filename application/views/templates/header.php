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

<div class="menuBar">
	<div class="menuLink"><a href='home'>Home Page</a></div>
	<?php
	echo "<div class='menuLink'><a href='home/logout'>Logout</a></div>";
	?>
</div>
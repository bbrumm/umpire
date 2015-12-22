<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Umpire Report</title>
<?php
$this->load->helper('url');       
$data['css'] = $this->config->item('css');
$data['js_fixed'] = $this->config->item('js_fixed');
echo "<script src='http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js'></script>";
echo "<script language='JavaScript' type='text/javascript' src='". asset_url() . $data['js_fixed'] ."'></script>";

echo "<link rel='stylesheet' type='text/css' media='all' href='". asset_url() . $data['css'] ."' />";
	


?>

<script type="text/javascript">

        $(document).ready(function() {
            ActivateFloatingHeaders("table.tableWithFloatingHeader");
        });
    </script>
    
    
	</head>
<body>
<?php 
if (isset($PDFLayout)) {
    if ($PDFLayout == TRUE) {
        $showHeader = FALSE;
    } else {
        $showHeader = TRUE;
    }
} else {
    $showHeader = TRUE;
}

if ($showHeader) {
?>
    <div class="mainBanner">Umpire Reporting</div>
    <?php
    if($this->session->userdata('logged_in')) {
    ?>
    	<div class="menuBar">
    		
    		<?php
    		$session_data = $this->session->userdata('logged_in');
    		$username = $session_data['username'];
    		$menuHome = "<div class='menuBarLink'>Home Page</div>";
    		$menuImportFile = "<div class='menuBarLink'>Import File</div>";
    		$menuLogout = "<div class='menuBarLink'>Logout</div>";
    		
    		
    		echo anchor("home", $menuHome);
    		echo anchor("ImportFileSelector", $menuImportFile);
    		echo anchor("home/logout", $menuLogout);
    		

    }
    
    if ($this->uri->segment(1) == 'report') {
    	//Show extra items if we are on the report page
    
    	//echo "<a href='createpdf/pdf' target = '_blank' onclick='form.submit();'><div class='menuBarLink'>Create PDF</div></a>";
    	echo "<a href='javascript:{}' onclick='document.getElementById(\"reportPostValues\").submit(); return false;'>";
    	echo "<div class='menuBarLink'>Create PDF</div></a>";
    }
    
    if($this->session->userdata('logged_in')) {
        $usernameLabel = "<div class='usernameLabel'>Welcome, ". $username ."</div>";
        echo $usernameLabel;
    }
    ?>
	</div>
<?php 
}
?>
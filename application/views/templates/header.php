<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Umpire Report</title>
<?php
include_once("analyticstracking.php");
$this->load->helper('url');   
$this->load->model('User');
$this->load->model('useradmin/User_permission_loader_model');
$this->load->model('Database_store_user');
$data['css'] = $this->config->item('css');
$data['js_fixed'] = $this->config->item('js_fixed');
//$data['reportSelection'] = $this->config->item('reportSelection');
$data['reportSelectionNew'] = $this->config->item('reportSelectionNew');
$data['reportSelectionAcc'] = $this->config->item('reportSelectionAcc');
$data['useradmin'] = $this->config->item('useradmin');
$userPermissionLoader = new User_permission_loader_model();


if (isset($PDFLayout)) {
    if ($PDFLayout == TRUE) {
        $showHeader = FALSE;
    } else {
        $showHeader = TRUE;
    }
} else {
    $showHeader = TRUE;
}

//echo "<script src='http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js'></script>";
//Localhost version of the JQuery script
echo "<script src='". asset_url() . "jquery.min.js'></script>\n";

if ($showHeader) {
    echo "<script language='JavaScript' type='text/javascript' src='". asset_url() . $data['js_fixed'] ."'></script>\n";
}
//echo "<script language='JavaScript' type='text/javascript' src='". asset_url() . $data['reportSelection'] ."'></script>";
echo "<script language='JavaScript' type='text/javascript' src='". asset_url() . $data['reportSelectionNew'] ."'></script>\n";
//echo "<script language='JavaScript' type='text/javascript' src='". asset_url() . $data['reportSelectionAcc'] ."'></script>";
/*
echo "ASSET URL (". asset_url() .")<BR />";
echo "BASE URL (". base_url() .")<BR />";
echo "SITE URL (". site_url() .")<BR />";
*/
echo "<script language='JavaScript' type='text/javascript' src='". asset_url() . $data['useradmin'] ."'></script>\n";
echo "<link rel='stylesheet' type='text/css' media='all' href='". asset_url() . $data['css'] ."' />\n";

if ($showHeader) {

?>

<script type="text/javascript">

        $(document).ready(function() {
            ActivateFloatingHeaders("table.tableWithFloatingHeader");
        });
    </script>
<?php 
}
?>

 <style>
    #progress {
      width: 500px;
      border: 1px solid #aaa;
      height: 20px;
    }
    #progress .bar {
      background-color: #ccc;
      height: 20px;
    }
  </style>

    
	</head>
<body>
<?php 

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
    		//Get user object, including their role and permissions
            //$currentUser = User::createUserFromUsername($session_data['username']);
            $pDataStore = new Database_store_user();

            $currentUser = $userPermissionLoader->getUserFromUsername($pDataStore, $username);
    		//$currentUser = new User();
    		//$currentUser->getUserFromUsername($session_data['username']);

    		//$userFirstName = $currentUser->getUsername();
    		//echo "First Name: " . $currentUser->getFirstName();
    		$menuHome = "<div class='menuBarLink'>Home Page</div>";
    		$menuImportFile = "<div class='menuBarLink'>Import File</div>";
    		$menuUserAdmin = "<div class='menuBarLink'>User Admin</div>";
    		$menuUmpireAdmin = "<div class='menuBarLink'>Umpire Admin</div>";
    		$menuDataTest = "<div class='menuBarLink'>Data Test</div>";
    		$menuUpdateProfile = "<div class='menuBarLink'>Update Profile</div>";
    		$menuLogout = "<div class='menuBarLink'>Logout</div>";
    		/*
    		echo "<pre>";
    		print_r($currentUser);
    		echo "</pre>";
    		*/
    		echo anchor("home", $menuHome);
    		if($userPermissionLoader->userHasImportFilePermission($currentUser)) {
    		  echo anchor("ImportFileSelector", $menuImportFile);
    		}
    		
    		if($userPermissionLoader->userCanSeeUserAdminPage($currentUser)) {
    		    echo anchor("UserAdmin", $menuUserAdmin);
    		    echo anchor("UmpireAdmin/loadPage", $menuUmpireAdmin);
    		}
    		
    		echo anchor("UpdateProfile", $menuUpdateProfile);
    		echo anchor("home/logout", $menuLogout);

    }
    
    if ($this->uri->segment(1) == 'report') {
    	//Show extra items if we are on the report page
    
    	//echo "<a href='createpdf/pdf' target = '_blank' onclick='form.submit();'><div class='menuBarLink'>Create PDF</div></a>";
        if ($userPermissionLoader->userCanCreatePDF($currentUser)) {
            echo "<a href='javascript:{}' onclick='document.getElementById(\"reportPostValues\").submit(); return false;'>";
        	echo "<div class='menuBarLink'>Create PDF</div></a>";
        }
    }
    
    if($this->session->userdata('logged_in')) {
        $usernameLabel = "<div class='usernameLabel'>Welcome, ". $currentUser->getFirstName() ."</div>";
        echo $usernameLabel;
    }
    ?>
	</div>
<?php 
}
?>
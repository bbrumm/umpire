<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class UserAdmin extends CI_Controller
{

    function __construct() {
        parent::__construct();
        
        $this->load->model('Report_instance');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Useradminmodel');
        $this->load->library('Debug_library');
    }

    function index() {
        $this->loadPage(NULL);
    }
    
    public function addNewUser() {
        $this->debug_library->debugOutput("POST from AddNewUser", $_POST);
        $data = "";
        $userAdminModel = new Useradminmodel();
        $userAddSuccess = $userAdminModel->addNewUser($_POST);
        
        $this->debug_library->debugOutput("UserAddSuccess", $userAddSuccess);
        
        if ($userAddSuccess) {
            $userAddedMessage = "User ". $_POST['username'] ." successfully added.";
            $this->debug_library->debugOutput("userAddedMessage", $userAddedMessage);
            $this->loadPage($userAddedMessage);
        }
    }
    
    public function loadPage($pUserAddedMessage = "") {
        $userAdmin = new Useradminmodel();
        $userArray = $userAdmin->getAllUsers();
        $roleArray = $userAdmin->getRoleArray();
        $subRoleArray = $userAdmin->getSubRoleArray();
        $reportSelectionArray = $userAdmin->getReportArray();
        $regionSelectionArray = $userAdmin->getRegionArray();
        $umpireDisciplineSelectionArray = $userAdmin->getUmpireDisciplineArray();
        $ageGroupSelectionArray = $userAdmin->getAgeGroupArray();
        $leagueSelectionArray = $userAdmin->getLeagueArray();
        
        $this->load->view('templates/header');
        
        $data['userAddedMessage'] = $pUserAddedMessage;
        
        $data['userArray'] = $userArray;
        $data['roleArray'] = $roleArray;
        $data['subRoleArray'] = $subRoleArray;
        $data['reportSelectionArray'] = $reportSelectionArray;
        $data['regionSelectionArray'] = $regionSelectionArray;
        $data['umpireDisciplineSelectionArray'] = $umpireDisciplineSelectionArray;
        $data['ageGroupSelectionArray'] = $ageGroupSelectionArray;
        $data['leagueSelectionArray'] = $leagueSelectionArray;
        $this->load->view('useradmin', $data);
        $this->load->view('templates/footer');
    }
    
    public function saveUserPrivileges() {
        //TODO: Add logic here for saving user privileges
    }
    
}
?>
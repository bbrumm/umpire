<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
/*
* @property Object session
*/
class UserAdmin extends CI_Controller
{

    function __construct() {
        parent::__construct();
        
        $this->load->model('Report_instance');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Useradminmodel');
        $this->load->model('Database_store_user_admin');
        $this->load->model('Database_store_reference');
        $this->load->library('Debug_library');
        $this->load->library('Array_library');
    }

    function index() {
        $this->loadPage(NULL);
    }
    
    public function addNewUser() {
        $dataStore = new Database_store_user_admin();
        $userAdminModel = new Useradminmodel();
        $userAddSuccess = $userAdminModel->addNewUser($dataStore, $_POST);
        
        if ($userAddSuccess) {
            $userAddedMessage = "User ". $_POST['username'] ." successfully added.";
            $this->loadPage($userAddedMessage);
        }
    }
    
    public function loadPage($pUserAddedMessage = "") {
        
        if($this->session->userdata('logged_in')) {
            $dataStore = new Database_store_user_admin();
            $dataStoreReference = new Database_store_reference();
            $userAdmin = new Useradminmodel();
            $userArray = $userAdmin->getAllUsers($dataStore);
            $roleArray = $userAdmin->getRoleArray($dataStoreReference);
            //$subRoleArray = $userAdmin->getSubRoleArray();
            
            $permissionSelectionArray = $userAdmin->getPermissionSelectionArray($dataStoreReference);
            
            //TODO: Remove these once the permission selection array is working
            $reportSelectionArray = $userAdmin->getReportArray($dataStoreReference);
            $regionSelectionArray = $userAdmin->getRegionArray($dataStoreReference);
            $umpireDisciplineSelectionArray = $userAdmin->getUmpireDisciplineArray($dataStoreReference);
            $ageGroupSelectionArray = $userAdmin->getAgeGroupArray($dataStoreReference);
            $leagueSelectionArray = $userAdmin->getLeagueArray($dataStoreReference);
            
            $this->load->view('templates/header');
            
            $data = array();
            $data['userAddedMessage'] = $pUserAddedMessage;
            $data['userArray'] = $userArray;
            $data['roleArray'] = $roleArray;
            //$data['subRoleArray'] = $subRoleArray;
            $data['permissionSelectionArray'] = $permissionSelectionArray;
            //TODO: Remove these once the permission selection array is working
            $data['reportSelectionArray'] = $reportSelectionArray;
            $data['regionSelectionArray'] = $regionSelectionArray;
            $data['umpireDisciplineSelectionArray'] = $umpireDisciplineSelectionArray;
            $data['ageGroupSelectionArray'] = $ageGroupSelectionArray;
            $data['leagueSelectionArray'] = $leagueSelectionArray;
            
            $this->load->view('useradmin', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect('login', 'refresh');
        }
    }
    
    public function saveUserPrivileges() {
        $dataStore = new Database_store_user_permission();

        $userAdmin = new Useradminmodel();
        $privilegesSaved = $userAdmin->saveUserPrivileges($dataStore, $_POST);
        if ($privilegesSaved) {
            $userAddedMessage = "User privileges updated.";
            $this->loadPage($userAddedMessage);
        }

    }
}

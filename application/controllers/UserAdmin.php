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
        $this->load->model('User_data_loader');
        $this->load->model('data_store/Database_store_user_admin');
        $this->load->model('data_store/Database_store_reference');
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
        if($this->isUserLoggedIn()) {
            $data = $this->populateDataArrayForView($pUserAddedMessage);
            $this->load->view('templates/header');
            $this->load->view('useradmin', $data);
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            $this->redirectUserToLoginPage();
        }
    }
    
    private function isUserLoggedIn() {
        return $this->session->userdata('logged_in');
    }
    
    private function redirectUserToLoginPage() {
        redirect('login', 'refresh');
    }
    
    private function populateDataArrayForView($pUserAddedMessage) {
        $dataStore = new Database_store_user_admin();
        $dataStoreReference = new Database_store_reference();
        $userAdmin = new Useradminmodel();
        $userDataLoader = new User_data_loader();
        $userArray = $userDataLoader->getAllUsers($dataStore);
        $roleArray = $userDataLoader->getRoleArray($dataStoreReference);

        $permissionSelectionArray = $userDataLoader->getPermissionSelectionArray($dataStoreReference);

        //TODO: Remove these once the permission selection array is working
        $reportSelectionArray = $userDataLoader->getReportArray($dataStoreReference);
        $regionSelectionArray = $userDataLoader->getRegionArray($dataStoreReference);
        $umpireDisciplineSelectionArray = $userDataLoader->getUmpireDisciplineArray($dataStoreReference);
        $ageGroupSelectionArray = $userDataLoader->getAgeGroupArray($dataStoreReference);
        $leagueSelectionArray = $userDataLoader->getLeagueArray($dataStoreReference);

        $data = array();
        $data['userAddedMessage'] = $pUserAddedMessage;
        $data['userArray'] = $userArray;
        $data['roleArray'] = $roleArray;
        $data['permissionSelectionArray'] = $permissionSelectionArray;
        //TODO: Remove these once the permission selection array is working
        $data['reportSelectionArray'] = $reportSelectionArray;
        $data['regionSelectionArray'] = $regionSelectionArray;
        $data['umpireDisciplineSelectionArray'] = $umpireDisciplineSelectionArray;
        $data['ageGroupSelectionArray'] = $ageGroupSelectionArray;
        $data['leagueSelectionArray'] = $leagueSelectionArray;
        
        return $data;
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

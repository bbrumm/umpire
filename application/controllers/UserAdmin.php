<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

class UserAdmin extends CI_Controller
{

    function __construct() {
        parent::__construct();
        
        $this->load->model('Report_instance');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Useradminmodel');
        $this->load->model('Database_store_user');
        $this->load->library('Debug_library');
        $this->load->library('Array_library');
    }

    function index() {
        $this->loadPage(NULL);
    }
    
    public function addNewUser() {
        //$this->debug_library->debugOutput("POST from AddNewUser", $_POST);
        $dataStore = new Database_store_user_admin();
        $data = "";
        $userAdminModel = new Useradminmodel();
        $userAddSuccess = $userAdminModel->addNewUser($dataStore, $_POST);
        
        //$this->debug_library->debugOutput("UserAddSuccess", $userAddSuccess);
        
        if ($userAddSuccess) {
            $userAddedMessage = "User ". $_POST['username'] ." successfully added.";
            //$this->debug_library->debugOutput("userAddedMessage", $userAddedMessage);
            $this->loadPage($userAddedMessage);
        }
    }
    
    public function loadPage($pUserAddedMessage = "") {
        
        if($this->session->userdata('logged_in')) {
            $dataStore = new Database_store_user_admin();
            $userAdmin = new Useradminmodel();
            $userArray = $userAdmin->getAllUsers($dataStore);
            $roleArray = $userAdmin->getRoleArray($dataStore);
            //$subRoleArray = $userAdmin->getSubRoleArray();
            
            $permissionSelectionArray = $userAdmin->getPermissionSelectionArray($dataStore);
            
            //TODO: Remove these once the permission selection array is working
            $reportSelectionArray = $userAdmin->getReportArray($dataStore);
            $regionSelectionArray = $userAdmin->getRegionArray($dataStore);
            $umpireDisciplineSelectionArray = $userAdmin->getUmpireDisciplineArray($dataStore);
            $ageGroupSelectionArray = $userAdmin->getAgeGroupArray($dataStore);
            $leagueSelectionArray = $userAdmin->getLeagueArray($dataStore);
            
            $this->load->view('templates/header');
            
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
        $dataStore = new Database_store_user();

        $userAdmin = new Useradminmodel();
        $privilegesSaved = $userAdmin->saveUserPrivileges($dataStore, $_POST);
        if ($privilegesSaved) {
            $userAddedMessage = "User privileges updated.";
            $this->loadPage($userAddedMessage);
        }

    }
    

    /*
    private function arrayDiffKey($A, $B) {
        $intersect = array_intersect_key($A, $B);
        return array_merge(array_diff_key($A, $intersect), array_diff_key($B, $intersect));
    }
    */
    
}
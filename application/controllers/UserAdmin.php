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
        $userAdmin = new Useradminmodel();
        $arrayLibrary = new Array_library();
        
        //$this->debug_library->debugOutput($this->testArrayCheck());
        //$this->debug_library->debugOutput("POST from saveUserPrivileges:", $_POST);
        
        /*
         * Array structure:
 [userPrivilege] => Array
        (
            [bbeveridge] => Array
                (
                    [8] => on
                    [9] => on
                    [10] => on
                    [11] => on
                    [12] => on

The [#] represents the permission_selection.id value. This can be used to insert/delete from the user_permission_selection table.
         */
        
        
        /*        
         * Check which permissions are selected from the form (post is included)
         * Check which permissions exist in the database but not sent from the form
         * Insert these if they don't exist into user_permission_selection
         * Delete these from user_permission_selection
         * 
         * Repeat these steps using the role-level permissions
         * 
         * Better to load both sets of data into two arrays, with the same structure, that can then be compared easily
         */
        $dataStore = new Database_store_user();

        $userPermissionsFromDB = $userAdmin->getAllUserPermissionsFromDB($dataStore);
        $userPermissionsFromForm = $_POST['userPrivilege'];
        
        $permissionsInDBNotForm = $arrayLibrary->findRecursiveArrayDiff($userPermissionsFromDB, $userPermissionsFromForm);
        $permissionsInFormNotDB = $arrayLibrary->findRecursiveArrayDiff($userPermissionsFromForm, $userPermissionsFromDB);
        
        //Remove privileges from users that were changed on the form
        $userAdmin->removePrivileges($dataStore, $permissionsInDBNotForm);
        
        //Add privileges for users that were added on the form
        $userAdmin->addPrivileges($dataStore, $permissionsInFormNotDB);
        
        $userRolesFromDB = $userAdmin->getAllUserRolesFromDB($dataStore);
        $userRolesFromForm = $_POST['userRole'];
        
        $userRoleDifferences = $this->arrayDiff($userRolesFromDB, $userRolesFromForm);
        
        //Update user roles
        $userAdmin->updateUserRoles($dataStore, $userRoleDifferences);
        
        //TODO: Update active/not active status
        $userActiveFromDB = $userAdmin->getAllUserActiveFromDB($dataStore);
        $userActiveFromForm = $userAdmin->translateUserFormActive($_POST);
        
        //$userRoleDifferences = array_diff($userRolesFromDB, $userRolesFromForm);
        $userActiveDifferences = $this->arrayDiff($userActiveFromDB, $userActiveFromForm);
        
        //Update user roles
        $userAdmin->updateUserActive($dataStore, $userActiveDifferences);
        $userAddedMessage = "User privileges updated.";
        $this->loadPage($userAddedMessage);
    }
    
    
    private function arrayDiff($A, $B) {
        $intersect = array_intersect($A, $B);
        return array_merge(array_diff_assoc($A, $intersect), array_diff_assoc($B, $intersect));
    }
    
    /*
    private function arrayDiffKey($A, $B) {
        $intersect = array_intersect_key($A, $B);
        return array_merge(array_diff_key($A, $intersect), array_diff_key($B, $intersect));
    }
    */
    
}
?>
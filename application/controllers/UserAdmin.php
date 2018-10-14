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
        $this->load->library('Array_library');
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
            //$this->debug_library->debugOutput("userAddedMessage", $userAddedMessage);
            $this->loadPage($userAddedMessage);
        }
    }
    
    public function loadPage($pUserAddedMessage = "") {
        
        if($this->session->userdata('logged_in')) {
            $userAdmin = new Useradminmodel();
            $userArray = $userAdmin->getAllUsers();
            $roleArray = $userAdmin->getRoleArray();
            //$subRoleArray = $userAdmin->getSubRoleArray();
            
            $permissionSelectionArray = $userAdmin->getPermissionSelectionArray();
            
            //TODO: Remove these once the permission selection array is working
            $reportSelectionArray = $userAdmin->getReportArray();
            $regionSelectionArray = $userAdmin->getRegionArray();
            $umpireDisciplineSelectionArray = $userAdmin->getUmpireDisciplineArray();
            $ageGroupSelectionArray = $userAdmin->getAgeGroupArray();
            $leagueSelectionArray = $userAdmin->getLeagueArray();
            
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
        
        $userPermissionsFromDB = $userAdmin->getAllUserPermissionsFromDB();
        $userPermissionsFromForm = $_POST['userPrivilege'];
        
        $permissionsInDBNotForm = $arrayLibrary->findRecursiveArrayDiff($userPermissionsFromDB, $userPermissionsFromForm);
        $permissionsInFormNotDB = $arrayLibrary->findRecursiveArrayDiff($userPermissionsFromForm, $userPermissionsFromDB);
        
        //$this->debug_library->debugOutput("saveUserPrivileges DB not Form:", $permissionsInDBNotForm);
        //$this->debug_library->debugOutput("saveUserPrivileges Form not DB:", $permissionsInFormNotDB);
        
        //Remove privileges from users that were changed on the form
        $userAdmin->removePrivileges($permissionsInDBNotForm);
        
        //Add privileges for users that were added on the form
        $userAdmin->addPrivileges($permissionsInFormNotDB);
        
        $userRolesFromDB = $userAdmin->getAllUserRolesFromDB();
        $userRolesFromForm = $_POST['userRole'];
        
        //$this->debug_library->debugOutput("saveUserPrivileges Roles DB:", $userRolesFromDB);
        //$this->debug_library->debugOutput("saveUserPrivileges Roles Form:", $userRolesFromForm);
        
        //$userRoleDifferences = array_diff($userRolesFromDB, $userRolesFromForm);
        $userRoleDifferences = $this->arrayDiff($userRolesFromDB, $userRolesFromForm);
        
        //$this->debug_library->debugOutput("saveUserPrivileges Role Differences:", $userRoleDifferences);
        
        //Update user roles
        $userAdmin->updateUserRoles($userRoleDifferences);
        
        //TODO: Update active/not active status
        $userActiveFromDB = $userAdmin->getAllUserActiveFromDB();
        $userActiveFromForm= $userAdmin->translateUserFormActive($_POST);
        
        //$this->debug_library->debugOutput("saveUserPrivileges Active DB:", $userActiveFromDB);
        //$this->debug_library->debugOutput("saveUserPrivileges Active Form:", $userActiveFromForm);
        
        //$userRoleDifferences = array_diff($userRolesFromDB, $userRolesFromForm);
        $userActiveDifferences = $this->arrayDiff($userActiveFromDB, $userActiveFromForm);
        
        //$this->debug_library->debugOutput("saveUserPrivileges Active Differences:", $userActiveDifferences);
        
        //Update user roles
        $userAdmin->updateUserActive($userActiveDifferences);
        
        
        
        
        $userAddedMessage = "User privileges updated.";
        //$this->debug_library->debugOutput("userAddedMessage", $userAddedMessage);
        $this->loadPage($userAddedMessage);
    }
    
    
    private function arrayDiff($A, $B) {
        $intersect = array_intersect($A, $B);
        return array_merge(array_diff_assoc($A, $intersect), array_diff_assoc($B, $intersect));
    }
    
    
    private function arrayDiffKey($A, $B) {
        $intersect = array_intersect_key($A, $B);
        return array_merge(array_diff_key($A, $intersect), array_diff_key($B, $intersect));
    }
    
    
    
    //TODO Remove this function
    private function testArrayCheck() {
        $userAdmin = new Useradminmodel();
        
        $array1['apple'][1] = 'on';
        $array1['apple'][4] = 'on';
        $array1['banana'][1] = 'on';
        $array1['banana'][3] = 'on';
        $array1['carrot'][2] = 'on';
        $array1['carrot'][5] = 'on';
        $array1['carrot'][6] = 'on';
        
        
        $array2['apple'][1] = 'on';
        $array2['banana'][2] = 'on';
        $array2['banana'][3] = 'on';
        $array2['carrot'][1] = 'on';
        $array2['carrot'][5] = 'on';
        $array2['carrot'][8] = 'on';
        
        $arrayDifferences = "";
        foreach ($array1 as $key=>$valueArray) {
            //Check if the username exists in the second array, otherwise we'll get an Undefined Index error.
            if(array_key_exists($key, $array2)) {
                //$arrayDifferences[$key] = array_diff($array1[$key], $array2[$key]);
                $arrayDifferences[$key] = array_diff_key($array1[$key], $array2[$key]);
                
                $this->debug_library->debugOutput("array1 $key:", $array1[$key]);
                $this->debug_library->debugOutput("array2 $key:", $array2[$key]);
                
                $this->debug_library->debugOutput("findRecursiveArrayDiff:", $arrayDifferences[$key]);
            }
        }
        
        return $arrayDifferences;
        
        
        
    }
    
    
    
}
?>
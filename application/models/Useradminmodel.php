<?php

class Useradminmodel extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model("User");
        $this->load->library('Debug_library');
        $this->load->model("useradmin/User_permission_loader_model");
        $this->load->model("data_store/Database_store_matches");
    }
    

    
    public function addNewUser(IData_store_user_admin $pDataStore, $pSubmittedData) {
        if (strlen($pSubmittedData['password']) > 0) {
            $newUser = User::createUserFromUserSubmittedData($pSubmittedData);
            $countOfMatchingUsers = $pDataStore->getCountOfMatchingUsers($newUser);
            if ($countOfMatchingUsers > 0) {
                throw new Exception("Username already exists.");
            }
        } else {
            throw new InvalidArgumentException("Password cannot be empty.");
        }
        return $pDataStore->insertNewUser($newUser);
    }

    public function translateUserFormActive($postArray) {
        $translatedArray = [];
        foreach ($postArray['userRole'] as $userName=>$userRole) {
            if(isset($postArray['userActive'][$userName])) {
                $translatedArray[$userName] = '1';
            } else {
                $translatedArray[$userName] = '0';
            }
        }
        return $translatedArray;
    }
    
    public function removePrivileges(IData_store_user_permission $pDataStore, $permissionArray) {
        foreach ($permissionArray as $username=>$userPermissionArray) {
            //Remove permission and log removal
            foreach($userPermissionArray as $permission_selection_id=>$setValue) {
                $pDataStore->removeUserPrivilege($username, $permission_selection_id);
            }
        }
    }
    
    public function addPrivileges(IData_store_user_permission $pDataStore, $permissionArray) {
        foreach ($permissionArray as $username=>$userPermissionArray) {
            //Add permission and log removal
            foreach($userPermissionArray as $permission_selection_id=>$setValue) {
                $pDataStore->addUserPrivilege($username, $permission_selection_id);
            }
        }
    }

    public function addDefaultPrivileges(IData_store_user_admin $pDataStore, $pSubmittedData) {
        $username = $pSubmittedData['username'];
        $pDataStore->addDefaultUserPrivileges($username);
    }
    
    public function updateUserRoles(IData_store_user_permission $pDataStore, $userRoleArray) {
        foreach ($userRoleArray as $username=>$newRoleID) {
            $pDataStore->updateUserRole($username, $newRoleID);
        }
    }
    
    public function updateUserActive(IData_store_user_permission $pDataStore, $userActiveArray) {
        foreach ($userActiveArray as $username=>$setValue) {
            $pDataStore->updateSingleUserActive($username, $setValue);
        }
    }


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

    public function saveUserPrivileges(IData_store_user_permission $pDataStore, $postData) {
        $this->updateChangedPrivileges($pDataStore, $postData);
        $this->updateChangedRoles($pDataStore, $postData);
        $this->updateChangedUserActive($pDataStore, $postData);
        return true;
    }
    
    private function updateChangedPrivileges(IData_store_user_permission $pDataStore, $postData) {
        $arrayLibrary = new Array_library();
        $userDataLoader = new User_data_loader();

        $userPermissionsFromDB = $userDataLoader->getAllUserPermissionsFromDB($pDataStore);
        $userPermissionsFromForm = $postData['userPrivilege'];

        $permissionsInDBNotForm = $arrayLibrary->findRecursiveArrayDiff($userPermissionsFromDB, $userPermissionsFromForm);
        $permissionsInFormNotDB = $arrayLibrary->findRecursiveArrayDiff($userPermissionsFromForm, $userPermissionsFromDB);

        $this->removePrivileges($pDataStore, $permissionsInDBNotForm);
        $this->addPrivileges($pDataStore, $permissionsInFormNotDB);
    }
    
    private function updateChangedRoles(IData_store_user_permission $pDataStore, $postData) {
        $userDataLoader = new User_data_loader();
        $userRolesFromDB = $userDataLoader->getAllUserRolesFromDB($pDataStore);
        $userRolesFromForm = $postData['userRole'];
        $userRoleDifferences = $this->arrayDiff($userRolesFromDB, $userRolesFromForm);

        $this->updateUserRoles($pDataStore, $userRoleDifferences);
    }
    
    private function updateChangedUserActive(IData_store_user_permission $pDataStore, $postData) {
        $userDataLoader = new User_data_loader();
        //TODO: Update active/not active status
        $userActiveFromDB = $userDataLoader->getAllUserActiveFromDB($pDataStore);
        $userActiveFromForm = $this->translateUserFormActive($postData);

        $userActiveDifferences = $this->arrayDiff($userActiveFromDB, $userActiveFromForm);
        $this->updateUserActive($pDataStore, $userActiveDifferences);
    }

    private function arrayDiff($A, $B) {
        $intersect = array_intersect($A, $B);
        return array_merge(array_diff_assoc($A, $intersect), array_diff_assoc($B, $intersect));
    }

}

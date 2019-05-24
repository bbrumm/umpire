<?php

class Useradminmodel extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model("User");
        $this->load->library('Debug_library');
        $this->load->model("useradmin/User_permission_loader_model");
        $this->load->model("Database_store_matches");
    }
    
    public function getAllUsers(IData_store_user_admin $pDataStore) {
        $userArray = $pDataStore->getAllUsers();
        if (empty($userArray)) {
            throw new Exception("No users were found in the database. Please contact support.");
        }
        return $userArray;
    }

    public function getRoleArray(IData_store_reference $pDataStore) {
        $roleArray = $pDataStore->getRoleArray();
        if (empty($roleArray)) {
            throw new Exception("No roles were found in the database. Please contact support.");
        }
        return $roleArray;
    }
    
    public function getReportArray(IData_store_reference $pDataStore) {
        $reportArray = $pDataStore->getReportArray();
        if (empty($reportArray)) {
            throw new Exception("No reports were found in the database. Please contact support.");
        }
        return $reportArray;
    }
    
    public function getRegionArray(IData_store_reference $pDataStore) {
        $regionArray = $pDataStore->getRegionArray();
        if (empty($regionArray)) {
            throw new Exception("No regions were found in the database. Please contact support.");
        }
        return $regionArray;
    }
    
    public function getUmpireDisciplineArray(IData_store_reference $pDataStore) {
        $umpireDisciplineArray = $pDataStore->getUmpireDisciplineArray();
        if (empty($umpireDisciplineArray)) {
            throw new Exception("No umpire disciplines were found in the database. Please contact support.");
        }
        return $umpireDisciplineArray;
    }
    
    public function getAgeGroupArray(IData_store_reference $pDataStore) {
        $ageGroupArray = $pDataStore->getAgeGroupArray();
        if (empty($ageGroupArray)) {
            throw new Exception("No age groups were found in the database. Please contact support.");
        }
        return $ageGroupArray;
    }
    
    public function getLeagueArray(IData_store_reference $pDataStore) {
        $leagueArray = $pDataStore->getLeagueArray();
        if (empty($leagueArray)) {
            throw new Exception("No leagues were found in the database. Please contact support.");
        }
        return $leagueArray;
    }
    
    public function getPermissionSelectionArray(IData_store_reference $pDataStore) {
        $permissionSelectionArray = $pDataStore->getPermissionSelectionArray();
        if (empty($permissionSelectionArray)) {
            throw new Exception("No permission selections were found in the database. Please contact support.");
        }
        return $permissionSelectionArray;
    }
    
    public function addNewUser(IData_store_user_admin $pDataStore, $pSubmittedData) {
        if (strlen($pSubmittedData['password']) > 0) {
            $newUser = User::createUserFromNameAndPW(
                $pSubmittedData['username'], $pSubmittedData['firstname'], $pSubmittedData['lastname'], MD5($pSubmittedData['password']));
            $countOfMatchingUsers = $pDataStore->getCountOfMatchingUsers($newUser);
            if ($countOfMatchingUsers > 0) {
                throw new Exception("Username already exists.");
            }
        } else {
            throw new InvalidArgumentException("Password cannot be empty.");
        }
        return $pDataStore->insertNewUser($newUser);
    }


    /*
     This should return data in this format:
     Array (
         [username] => Array
           (
             [permission_selection.id] => on
             [permission_selection.id] => on
             [permission_selection.id] => on
     */
    public function getAllUserPermissionsFromDB(IData_store_user_permission $pDataStore) {
        return $pDataStore->getAllUserPermissionsFromDB();
    }


    /*
     This should return data in this format:
        Array (
            [bbeveridge] => 2
            [jhillgrove] => 2
            [gmanager] => 2
    */
    public function getAllUserRolesFromDB(IData_store_user_permission $pDataStore) {
        return $pDataStore->getAllUserRolesFromDB();
    }


    /*
     This should return data in this format:
         Array (
             [username] => on
             [username] => on
             [username] => on
     */
    public function getAllUserActiveFromDB(IData_store_user_permission $pDataStore) {
        return $pDataStore->getAllUserActiveFromDB();
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


        /*
         * Check which permissions are selected from the form (post is included)
         * Check which permissions exist in the database but not sent from the form
         * Insert these if they don't exist into user_permission_selection
         * Delete these from user_permission_selection
         *
         * Repeat these steps using the role-level permissions
         *
         * Better to load both sets of data into two arrays, with the same structure, that can then be compared easily
         *
         *
         */
    public function saveUserPrivileges(IData_store_user_permission $pDataStore, $postData) {

        $arrayLibrary = new Array_library();

        $userPermissionsFromDB = $this->getAllUserPermissionsFromDB($pDataStore);
        $userPermissionsFromForm = $postData['userPrivilege'];

        $permissionsInDBNotForm = $arrayLibrary->findRecursiveArrayDiff($userPermissionsFromDB, $userPermissionsFromForm);
        $permissionsInFormNotDB = $arrayLibrary->findRecursiveArrayDiff($userPermissionsFromForm, $userPermissionsFromDB);

        //Remove privileges from users that were changed on the form
        $this->removePrivileges($pDataStore, $permissionsInDBNotForm);

        //Add privileges for users that were added on the form
        $this->addPrivileges($pDataStore, $permissionsInFormNotDB);

        $userRolesFromDB = $this->getAllUserRolesFromDB($pDataStore);
        $userRolesFromForm = $postData['userRole'];

        $userRoleDifferences = $this->arrayDiff($userRolesFromDB, $userRolesFromForm);

        //Update user roles
        $this->updateUserRoles($pDataStore, $userRoleDifferences);

        //TODO: Update active/not active status
        $userActiveFromDB = $this->getAllUserActiveFromDB($pDataStore);
        $userActiveFromForm = $this->translateUserFormActive($postData);

        //$userRoleDifferences = array_diff($userRolesFromDB, $userRolesFromForm);
        $userActiveDifferences = $this->arrayDiff($userActiveFromDB, $userActiveFromForm);

        //Update user roles
        $this->updateUserActive($pDataStore, $userActiveDifferences);

        return true;
    }

    private function arrayDiff($A, $B) {
        $intersect = array_intersect($A, $B);
        return array_merge(array_diff_assoc($A, $intersect), array_diff_assoc($B, $intersect));
    }

}

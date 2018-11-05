<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Useradminmodel extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model("User");
        $this->load->library('Debug_library');
        $this->load->model("useradmin/User_permission_loader_model");
        $this->load->model("Database_store_matches");
    }
    
    public function getAllUsers(IData_store_user $pDataStore) {
        $userArray = $pDataStore->getAllUsers();
        if (empty($userArray)) {
            throw new Exception("No users were found in the database. Please contact support.");
        }
        return $userArray;
    }

    public function getRoleArray(IData_store_user $pDataStore) {
        $roleArray = $pDataStore->getRoleArray();
        if (empty($roleArray)) {
            throw new Exception("No roles were found in the database. Please contact support.");
        }
        return $roleArray;
    }
    
    public function getReportArray(IData_store_user $pDataStore) {
        $reportArray = $pDataStore->getReportArray();
        if (empty($reportArray)) {
            throw new Exception("No reports were found in the database. Please contact support.");
        }
        return $reportArray;
    }
    
    public function getRegionArray(IData_store_user $pDataStore) {
        $regionArray = $pDataStore->getRegionArray();
        if (empty($regionArray)) {
            throw new Exception("No regions were found in the database. Please contact support.");
        }
        return $regionArray;
    }
    
    public function getUmpireDisciplineArray(IData_store_user $pDataStore) {
        $umpireDisciplineArray = $pDataStore->getUmpireDisciplineArray();
        if (empty($umpireDisciplineArray)) {
            throw new Exception("No umpire disciplines were found in the database. Please contact support.");
        }
        return $umpireDisciplineArray;
    }
    
    public function getAgeGroupArray(IData_store_user $pDataStore) {
        $ageGroupArray = $pDataStore->getAgeGroupArray();
        if (empty($ageGroupArray)) {
            throw new Exception("No age groups were found in the database. Please contact support.");
        }
        return $ageGroupArray;
    }
    
    public function getLeagueArray(IData_store_user $pDataStore) {
        $leagueArray = $pDataStore->getLeagueArray();
        if (empty($leagueArray)) {
            throw new Exception("No leagues were found in the database. Please contact support.");
        }
        return $leagueArray;
    }
    
    public function getPermissionSelectionArray(IData_store_user $pDataStore) {
        $permissionSelectionArray = $pDataStore->getPermissionSelectionArray();
        if (empty($permissionSelectionArray)) {
            throw new Exception("No permission selections were found in the database. Please contact support.");
        }
        return $permissionSelectionArray;
    }
    
    public function addNewUser(IData_store_user $pDataStore, $pSubmittedData) {
        $newUser = User::createUserFromNameAndPW(
                $pSubmittedData['username'], $pSubmittedData['firstname'], $pSubmittedData['lastname'], MD5($pSubmittedData['password']));
        return $pDataStore->insertUserIntoDatabase($newUser);
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
    public function getAllUserPermissionsFromDB(IData_store_user $pDataStore) {
        return $pDataStore->getAllUserPermissionsFromDB();
    }


    /*
     This should return data in this format:
        Array (
            [bbeveridge] => 2
            [jhillgrove] => 2
            [gmanager] => 2
    */
    public function getAllUserRolesFromDB(IData_store_user $pDataStore) {
        return $pDataStore->getAllUserRolesFromDB();
    }


    /*
     This should return data in this format:
         Array (
             [username] => on
             [username] => on
             [username] => on
     */
    public function getAllUserActiveFromDB(IData_store_user $pDataStore) {
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
    


    
    public function removePrivileges($permissionArray) {
        foreach ($permissionArray as $username=>$userPermissionArray) {
            //Remove permission and log removal
            foreach($userPermissionArray as $permission_selection_id=>$setValue) {
                $this->removeUserPrivilege($username, $permission_selection_id);
            }
        }
    }
    
    public function addPrivileges($permissionArray) {
        foreach ($permissionArray as $username=>$userPermissionArray) {
            //Remove permission and log removal
            foreach($userPermissionArray as $permission_selection_id=>$setValue) {
                $this->addUserPrivilege($username, $permission_selection_id);
            }
        }
    }
    
    public function updateUserRoles($userRoleArray) {
        foreach ($userRoleArray as $username=>$newRoleID) {
            $this->updateUserRole($username, $newRoleID);
        }
    }
    
    public function updateUserActive($userActiveArray) {
        foreach ($userActiveArray as $username=>$setValue) {
            $this->updateSingleUserActive($username, $setValue);
        }
    }
    
    private function updateUserRole($username, $newRoleID) {
        $queryString = "UPDATE umpire_users
            SET role_id = ?
            WHERE user_name = ?";
        
        $query = $this->db->query($queryString, array(
            $newRoleID, $username
        ));
        //TODO: Replace magic number with global constant that represents UPDATE
        $this->logRoleChange($username, $newRoleID);
    }
    
    private function updateSingleUserActive($username, $setValue) {
        $queryString = "UPDATE umpire_users
            SET active = ?
            WHERE user_name = ?";
        
        $query = $this->db->query($queryString, array(
            $setValue, $username
        ));
        //TODO: Replace magic number with global constant that represents UPDATE
        $this->logActiveChange($username, $setValue);
    }
    
    private function removeUserPrivilege($username, $permission_selection_id) {
        $queryString = "DELETE FROM user_permission_selection 
            WHERE user_id IN (
            SELECT id
            FROM umpire_users u
            WHERE user_name = ?
            ) AND permission_selection_id = ?;";
        
        $query = $this->db->query($queryString, array(
            $username, $permission_selection_id
        ));
        //TODO: Replace magic number 3 with global constant that represents DELETE
        $this->logPrivilegeChange($username, $permission_selection_id, 3);
        
    }
    
    private function logPrivilegeChange($username, $permission_selection_id, $operation_ref) {
        $session_data = $this->session->userdata('logged_in');
        $currentUsername = $session_data['username'];
        
        $queryString = "INSERT INTO log_privilege_changes
            (username_changed, privilege_changed, privilege_action, username_changed_by, changed_datetime)
            VALUES (?, ?, ?, ?, NOW());";
        
        $query = $this->db->query($queryString, array(
            $username, $permission_selection_id, $operation_ref, $currentUsername
        ));
    }
    
    private function addUserPrivilege($username, $permission_selection_id) {
        $queryString = "INSERT INTO user_permission_selection
            (user_id, permission_selection_id)
            SELECT id, ?
            FROM umpire_users u
            WHERE user_name = ?;";
        
        $query = $this->db->query($queryString, array(
            $permission_selection_id, $username
        ));
        //TODO: Replace magic number 1 with global constant that represents INSERT
        $this->logPrivilegeChange($username, $permission_selection_id, 1);
        
    }
    
    private function logRoleChange($username, $newRoleID) {
        $session_data = $this->session->userdata('logged_in');
        $currentUsername = $session_data['username'];
        
        $queryString = "INSERT INTO log_role_changes
            (username_changed, role_changed, role_action, username_changed_by, changed_datetime)
            VALUES (?, ?, ?, ?, NOW());";
        
        $query = $this->db->query($queryString, array(
            $username, $newRoleID, 2, $currentUsername
        ));
    }
    
    private function logActiveChange($username, $newActiveValue) {
        $session_data = $this->session->userdata('logged_in');
        $currentUsername = $session_data['username'];
        
        $queryString = "INSERT INTO log_active_changes
            (username_changed, new_active, role_action, username_changed_by, changed_datetime)
            VALUES (?, ?, ?, ?, NOW());";
        
        $query = $this->db->query($queryString, array(
            $username, $newActiveValue, 2, $currentUsername
        ));
    }
}

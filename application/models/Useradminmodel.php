<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Useradminmodel extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model("User");
        $this->load->library('Debug_library');
        $this->load->model("useradmin/User_permission_loader_model");
        $this->load->model("Database_store");
    }
    
    public function getAllUsers() {
        $userPermissionLoader = User_permission_loader_model();
        $dbStore = new Database_store();
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, r.role_name, u.active 
            FROM umpire_users u  
            INNER JOIN role r ON u.role_id = r.id  
            WHERE u.user_name NOT IN ('bbrumm');";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        $userArray = '';
        $arrayCount = count($queryResultArray);

        for($i=0; $i<$arrayCount; $i++) {
            $newUser = User::createUserFromNameAndRole(
                $queryResultArray[$i]['id'], $queryResultArray[$i]['user_name'], 
                $queryResultArray[$i]['first_name'], $queryResultArray[$i]['last_name'], 
                $queryResultArray[$i]['role_name'], $queryResultArray[$i]['active'], NULL);
            $userPermissionLoader->setPermissionArray($dbStore, $newUser);
            $userArray[] = $newUser;
        }
        return $userArray;
    }
    
    private function getArrayFromQuery($queryString) {
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
    }
    
    public function getRoleArray() {
        $queryString = "SELECT id, role_name, display_order FROM role WHERE role_name != 'Owner' ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getReportArray() {
        $queryString = "SELECT report_table_id, report_title FROM report_table;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getRegionArray() {
        $queryString = "SELECT id, region_name FROM region;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getUmpireDisciplineArray() {
        $queryString = "SELECT id, umpire_type_name FROM umpire_type;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getAgeGroupArray() {
        $queryString = "SELECT id, age_group FROM age_group ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getLeagueArray() {
        $queryString = "SELECT id, short_league_name FROM short_league_name ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getPermissionSelectionArray() {
        $queryString = "SELECT id, permission_id, category, selection_name ". 
        " FROM permission_selection ORDER BY category, display_order;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function addNewUser($pSubmittedData) {
        $newUser = User::createUserFromNameAndPW(
                $pSubmittedData['username'], $pSubmittedData['firstname'], $pSubmittedData['lastname'], MD5($pSubmittedData['password']));
        return $this->insertUserIntoDatabase($newUser);
    }
    
    private function insertUserIntoDatabase(User $pUser) {
        //TODO: Replace the default role with a user selection, once it is built into the UI.
        $queryString = "INSERT INTO umpire_users
            (first_name, last_name, user_name, user_email, user_password, role_id)
            VALUES (?, ?, ?, 'None', ?, 6);";
        
        $query = $this->db->query($queryString, array(
            $pUser->getFirstName(), $pUser->getLastName(), $pUser->getUsername(), $pUser->getPassword()
        ));
        
        if ($this->db->affected_rows() == 1) {
            return true;
        } else {
            throw new exception("There was an error when inserting the user. Please contact support.");
        }
        
    }
    
    public function getAllUserPermissionsFromDB() {
        /*
         This should return data in this format:
         Array (
             [username] => Array
               (
                 [permission_selection.id] => on
                 [permission_selection.id] => on
                 [permission_selection.id] => on
         */
        
        $queryString = "SELECT u.user_name, ps.id
            FROM umpire_users u
            INNER JOIN user_permission_selection ups ON u.id = ups.user_id
            INNER JOIN permission_selection ps ON ps.id = ups.permission_selection_id
            INNER JOIN permission p ON p.id = ps.permission_id
            WHERE p.id IN (6, 7)";
        
        $resultArray = $this->getArrayFromQuery($queryString);
        
        //Translate the data into the format mentioned above
        return $this->translatePermissionArray($resultArray);
        
        
    }
    
    public function getAllUserRolesFromDB() {
        /*
         This should return data in this format:
            Array (
                [bbeveridge] => 2
                [jhillgrove] => 2
                [gmanager] => 2
        */
        
        $queryString = "SELECT user_name, role_id
            FROM umpire_users
            WHERE user_name NOT IN ('bbrumm');";
        
        $resultArray = $this->getArrayFromQuery($queryString);
        
        //Translate the data into the format mentioned above
        return $this->translateRoleArray($resultArray);
        
        
    }
    
    public function getAllUserActiveFromDB() {
        /*
         This should return data in this format:
             Array (
                 [username] => on
                 [username] => on
                 [username] => on
         */
        
        $queryString = "SELECT user_name, active
            FROM umpire_users
            WHERE user_name NOT IN ('bbrumm');";
        
        $resultArray = $this->getArrayFromQuery($queryString);
        
        //Translate the data into the format mentioned above
        return $this->translateActiveArray($resultArray);
        
        
    }
    
    public function translateUserFormActive($postArray) {
        $translatedArray = "";
        foreach ($postArray['userRole'] as $userName=>$userRole) {
            if(isset($postArray['userActive'][$userName])) {
                $translatedArray[$userName] = '1';
            } else {
                $translatedArray[$userName] = '0';
            }
        }
        return $translatedArray;
    }
    
    private function translatePermissionArray($resultArray) {
        $translatedArray = "";
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['user_name']][$rowItem['id']] = 'on';
        }
        return $translatedArray;
        
    }
    
    private function translateRoleArray($resultArray) {
        $translatedArray = "";
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['user_name']] = $rowItem['role_id'];
        }
        return $translatedArray;
        
    }
    
    private function translateActiveArray($resultArray) {
        $translatedArray = "";
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['user_name']] = $rowItem['active'];
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

<?php
require_once 'IData_store_user_admin.php';
/*
* @property Object db
* @property Object session
*/
class Database_store_user_admin extends CI_Model implements IData_store_user_admin
{

    const ROLE_REGULAR_USER = 4;

    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->model('Database_store_user');
    }
    
    private function runQuery($queryString, $arrayValues = null) {
        return $this->db->query($queryString, $arrayValues);
        $this->db->close();
    }

    public function getAllUsers() {
        $userPermissionLoader = new User_permission_loader_model();
        $dbStore = new Database_store_user();
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, r.role_name, u.active 
            FROM umpire_users u  
            INNER JOIN role r ON u.role_id = r.id  
            WHERE u.user_name NOT IN ('bbrumm');";

        //Run query and store result in array
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        $userArray = array();
        $arrayCount = count($queryResultArray);

        for($i=0; $i<$arrayCount; $i++) {
            $newUser = User::createUserFromNameAndRole(
                $queryResultArray[$i]['id'], $queryResultArray[$i]['user_name'],
                $queryResultArray[$i]['first_name'], $queryResultArray[$i]['last_name'],
                $queryResultArray[$i]['role_name'], $queryResultArray[$i]['active'], NULL);
            $userPermissionLoader->setPermissionArrayForUser($dbStore, $newUser);
            $userArray[] = $newUser;
        }
        return $userArray;
    }

    private function getArrayFromQuery($queryString) {
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
    }

    public function getRoleArray() {
        $queryString = "SELECT id, role_name, display_order FROM role WHERE role_name != 'Owner' ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }

    public function getReportArray() {
        $queryString = "SELECT report_id, report_name FROM report;";
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

    public function insertNewUser(User $pUser) {
        //TODO: Replace the default role with a user selection, once it is built into the UI.
        $queryString = "INSERT INTO umpire_users
        (first_name, last_name, user_name, user_email, user_password, role_id, active)
        VALUES (?, ?, ?, 'None', ?, 4, 1);";

        $this->runQuery($queryString, array(
            $pUser->getFirstName(), $pUser->getLastName(), $pUser->getUsername(), $pUser->getPassword()
        ));

        if ($this->db->affected_rows() == 1) {
            //One user was added. Add their default privileges
            $this->addDefaultUserPrivileges($pUser->getUsername());
            return true;
        } else {
            throw new exception("There was an error when inserting the user. Please contact support.");
        }
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
    public function getAllUserPermissionsFromDB() {
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

    private function translatePermissionArray($resultArray) {
        $translatedArray = array();
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['user_name']][$rowItem['id']] = 'on';
        }
        return $translatedArray;

    }

    public function getAllUserRolesFromDB() {
        $queryString = "SELECT user_name, role_id
            FROM umpire_users
            WHERE user_name NOT IN ('bbrumm');";
        $resultArray = $this->getArrayFromQuery($queryString);

        //Translate the data into the format mentioned above
        return $this->translateRoleArray($resultArray);
    }

    private function translateRoleArray($resultArray) {
        $translatedArray = array();
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['user_name']] = $rowItem['role_id'];
        }
        return $translatedArray;

    }

    public function getAllUserActiveFromDB() {
        $queryString = "SELECT user_name, active
            FROM umpire_users
            WHERE user_name NOT IN ('bbrumm');";

        $resultArray = $this->getArrayFromQuery($queryString);

        //Translate the data into the format mentioned above
        return $this->translateActiveArray($resultArray);
    }

    private function translateActiveArray($resultArray) {
        $translatedArray = array();
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['user_name']] = $rowItem['active'];
        }
        return $translatedArray;

    }

    public function removeUserPrivilege($username, $permission_selection_id) {
        $queryString = "DELETE FROM user_permission_selection 
            WHERE user_id IN (
            SELECT id
            FROM umpire_users u
            WHERE user_name = ?
            ) AND permission_selection_id = ?;";

        $this->runQuery($queryString, array(
            $username, $permission_selection_id
        ));
        //TODO: Replace magic number 3 with global constant that represents DELETE
        $this->logPrivilegeChange($username, $permission_selection_id, 3);

    }

    public function getUserPrivileges() {


    }

    public function addUserPrivilege($username, $permission_selection_id) {
        $queryString = "INSERT INTO user_permission_selection
            (user_id, permission_selection_id)
            SELECT id, ?
            FROM umpire_users u
            WHERE user_name = ?;";

        $this->runQuery($queryString, array(
            $permission_selection_id, $username
        ));
        //TODO: Replace magic number 1 with global constant that represents INSERT
        $this->logPrivilegeChange($username, $permission_selection_id, 1);

    }

    public function addDefaultUserPrivileges($username) {
        //Get ID for username
        $userID = $this->getUserIDFromUsername($username);

        $queryString = "INSERT INTO user_permission_selection
            (user_id, permission_selection_id)
            VALUES
            ($userID, 2),
            ($userID, 6),
            ($userID, 7),
            ($userID, 8),
            ($userID, 9),
            ($userID, 10),
            ($userID, 11),
            ($userID, 12),
            ($userID, 13),
            ($userID, 14),
            ($userID, 15),
            ($userID, 16),
            ($userID, 17),
            ($userID, 18),
            ($userID, 19),
            ($userID, 20),
            ($userID, 21),
            ($userID, 22),
            ($userID, 23),
            ($userID, 24),
            ($userID, 25),
            ($userID, 26),
            ($userID, 27),
            ($userID, 28),
            ($userID, 29),
            ($userID, 30),
            ($userID, 32),
            ($userID, 33),
            ($userID, 34);";

        $this->runQuery($queryString);
    }

    public function getUserIDFromUsername($username) {
        $queryString = "SELECT id FROM umpire_users WHERE user_name = ?";
        $query = $this->runQuery($queryString, array(
            $username
        ));
        $queryResultArray = $query->result_array();
        return $queryResultArray[0]['id'];
    }

    public function updateUserRole($username, $newRoleID) {
        $queryString = "UPDATE umpire_users
            SET role_id = ?
            WHERE user_name = ?";

        $this->runQuery($queryString, array(
            $newRoleID, $username
        ));
        //TODO: Replace magic number with global constant that represents UPDATE
        $this->logRoleChange($username, $newRoleID);
    }

    public function updateSingleUserActive($username, $setValue) {
        $queryString = "UPDATE umpire_users
            SET active = ?
            WHERE user_name = ?";

        $this->runQuery($queryString, array(
            $setValue, $username
        ));
        //TODO: Replace magic number with global constant that represents UPDATE
        $this->logActiveChange($username, $setValue);
    }

    private function logPrivilegeChange($username, $permission_selection_id, $operation_ref) {
        $session_data = $this->session->userdata('logged_in');
        $currentUsername = $session_data['username'];

        $queryString = "INSERT INTO log_privilege_changes
            (username_changed, privilege_changed, privilege_action, username_changed_by, changed_datetime)
            VALUES (?, ?, ?, ?, NOW());";

        $this->runQuery($queryString, array(
            $username, $permission_selection_id, $operation_ref, $currentUsername
        ));
    }



    private function logRoleChange($username, $newRoleID) {
        $session_data = $this->session->userdata('logged_in');
        $currentUsername = $session_data['username'];

        $queryString = "INSERT INTO log_role_changes
            (username_changed, role_changed, role_action, username_changed_by, changed_datetime)
            VALUES (?, ?, ?, ?, NOW());";

        $this->runQuery($queryString, array(
            $username, $newRoleID, 2, $currentUsername
        ));
    }

    private function logActiveChange($username, $newActiveValue) {
        $session_data = $this->session->userdata('logged_in');
        $currentUsername = $session_data['username'];

        $queryString = "INSERT INTO log_active_changes
            (username_changed, new_active, role_action, username_changed_by, changed_datetime)
            VALUES (?, ?, ?, ?, NOW());";

        $this->runQuery($queryString, array(
            $username, $newActiveValue, 2, $currentUsername
        ));
    }

    public function getCountOfMatchingUsers(User $pUser) {
        $queryString = "SELECT COUNT(*) AS usercount
            FROM umpire_users u
            WHERE u.user_name = '". $pUser->getUsername() ."';";

        //Run query and store result in array
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray[0]['usercount'];
    }
}

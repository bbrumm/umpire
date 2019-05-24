<?php
require_once 'IData_store_user_admin.php';
/*
* @property Object db
* @property Object session
*/
class Database_store_user_permission extends CI_Model implements IData_store_user_permission
{

    private function runQuery($queryString, $arrayValues = null) {
        return $this->db->query($queryString, $arrayValues);
        //$this->db->close();
    }

    private function getArrayFromQuery($queryString) {
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
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

    public function getUserPrivileges() { }

    public function addUserPrivilege($username, $permission_selection_id) {
        $queryString = "INSERT INTO user_permission_selection
            (user_id, permission_selection_id)
            SELECT id, ?
            FROM umpire_users u
            WHERE user_name = ?;";

        $this->runQuery($queryString, array(
            $permission_selection_id, $username
        ));
        if ($this->db->affected_rows() == 1) {

            //TODO: Replace magic number 1 with global constant that represents INSERT
            $this->logPrivilegeChange($username, $permission_selection_id, 1);
        } else {
            throw new Exception("There was an issue inserting into the user_permission_selection table. Please contact support.");
        }

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

}
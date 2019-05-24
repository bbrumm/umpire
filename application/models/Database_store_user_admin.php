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
        //$this->db->close();
    }

    private function getArrayFromQuery($queryString) {
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
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

    public function insertNewUser(User $pUser) {
        //TODO: Replace the default role with a user selection, once it is built into the UI.
        $queryString = "INSERT INTO umpire_users
        (first_name, last_name, user_name, user_email, user_password, role_id, active)
        VALUES (?, ?, ?, 'None', ?, ". self::ROLE_REGULAR_USER . ", 1);";

        $this->runQuery($queryString, array(
            $pUser->getFirstName(), $pUser->getLastName(), $pUser->getUsername(), $pUser->getPassword()
        ));

        //One user was added. Add their default privileges
        $this->addDefaultUserPrivileges($pUser->getUsername());
        return true;        
    }

    public function addDefaultUserPrivileges($username) {
        //Get ID for username
        $userID = $this->getUserIDFromUsername($username);

        $queryString = "INSERT INTO user_permission_selection
            (user_id, permission_selection_id)
            VALUES
            ($userID, 2), ($userID, 6), ($userID, 7), ($userID, 8),
($userID, 9), ($userID, 10), ($userID, 11), ($userID, 12),
($userID, 13), ($userID, 14), ($userID, 15), ($userID, 16), 
($userID, 17), ($userID, 18), ($userID, 19), ($userID, 20),
($userID, 21), ($userID, 22), ($userID, 23), ($userID, 24),
($userID, 25), ($userID, 26), ($userID, 27), ($userID, 28),
($userID, 29), ($userID, 30), ($userID, 32), ($userID, 33),
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

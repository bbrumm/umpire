<?php
require_once 'IData_store_user.php';
class Database_store_user extends CI_Model implements IData_store_user
{

    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');


    }
    public function findMatchingUserFromUsernameAndPassword($pUsername, $pPassword) {
        $this->db->select('id, user_name, user_password');
        $this->db->from('umpire_users');
        $this->db->where('user_name', $pUsername);
        $this->db->where('user_password', MD5($pPassword));
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }

    }

    public function checkUserActive($pUsername) {
        $this->db->select('id');
        $this->db->from('umpire_users');
        $this->db->where('user_name', $pUsername);
        $this->db->where('active', '1');

        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    //TODO refactor this so that permissions are set for the user, because they should be whenever createUserFromNameAndRole
    public function getUserFromUsername($pUsername) {
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, u.user_email, r.role_name
            FROM umpire_users u 
            INNER JOIN role r ON u.role_id = r.id
            WHERE u.user_name = '$pUsername' 
            LIMIT 1;";

        $query = $this->db->query($queryString);

        return $query->num_rows();

        if ($query->num_rows() == 1) {
            $row = $query->row();
            $user = User::createUserFromNameAndRole($row->ID, $row->user_name,
                $row->first_name, $row->last_name, $row->role_name, 1, $row->user_email);
            return $user;
        } else {
            return false;
        }
    }


    public function findPermissionsForUser(User $pUser) {
        $queryString = "SELECT ps.id, ps.permission_id, p.permission_name, ps.selection_name 
            FROM permission_selection ps 
            INNER JOIN permission p ON ps.permission_id = p.id 
            WHERE (ps.id IN ( 
            	SELECT ups.permission_selection_id 
            	FROM user_permission_selection ups 
            	WHERE user_id = " . $this->getId() . " 
            ) OR ps.id IN ( 
            	SELECT rps.permission_selection_id 
            	FROM role_permission_selection rps  
            	INNER JOIN umpire_users u ON rps.role_id = u.role_id 
            	WHERE u.id = " . $this->getId() . "
                AND u.role_id != 4));";

        $query = $this->db->query($queryString);
        $row = $query->result_array();

        if (isset($row)) {
            $user = User::createUserFromNameAndRole($row->ID, $row->user_name,
                $row->first_name, $row->last_name, $row->role_name, 1, $row->user_email);

            return $user;
        } else {
            return null;
        }


        //return count($resultArray);
    }





    public function checkUserExistsForReset(User $pUser) {
        $this->db->select('id');
        $this->db->where('user_name', $pUser->getUsername());
        $this->db->where('user_email', $pUser->getEmailAddress());
        $query = $this->db->get('umpire_users');

        return ($query->num_rows() > 0);

    }

    public function logPasswordResetRequest($pRequestData) {
        $data = array(
            'request_datetime' => $pRequestData['request_datetime'],
            'activation_id' => $pRequestData['activation_id'],
            'ip_address' => $pRequestData['client_ip'],
            'user_name' => $pRequestData['username_entered'],
            'email_address' => $pRequestData['email_address_entered']
        );

        $queryStatus = $this->db->insert('password_reset_request', $data);

        return ($queryStatus == 1);
    }



    public function storeActivationID($pActivationID, $pUser) {
        $this->db->where('user_name', $pUser->getUsername());
        $this->db->where('user_email', $pUser->getEmailAddress());
        $this->db->update('umpire_users', array('activation_id'=>$pActivationID));
    }


    public function createUserFromActivationID($pActivationID) {
        $this->db->select('user_name');
        $this->db->where('activation_id', $pActivationID);
        $query = $this->db->get('umpire_users');

        $resultArray = $query->result();
        $user = new User();
        $user->setUsername($resultArray[0]->user_name);

        return $user;
    }




    public function updatePassword(User $pUser) {
        $this->db->where('user_name', $pUser->getUsername());
        $this->db->update('umpire_users', array('user_password'=>$pUser->getPassword()));
    }

    public function findOldUserPassword(User $pUser) {
        $this->db->select('user_password');
        $this->db->where('user_name', $this->getUsername());
        $query = $this->db->get('umpire_users');

        $resultArray = $query->result();

        return $resultArray[0]->user_password;
    }

    public function logPasswordReset($pData) {
        $queryStatus = $this->db->insert('password_reset_log', $pData);
    }




    public function updateEmailAddress(User $pUser) {
        $this->db->where('user_name', $pUser->getUsername());
        $this->db->update('umpire_users', array('user_email'=>$pUser->getEmailAddress()));
    }

    public function findUserFromUsernameAndPassword($username, $password) {
        $this->db->where('user_name', $username);
        $this->db->where('user_password', $password);

        // Run the query
        $query = $this->db->get('umpire_users');

        $resultArray = $query->result();

        $user = new User();
        $user->setId($resultArray[0]['id']);
        $user->setUsername($resultArray[0]['username']);
        $user->setEmailAddress($resultArray[0]['email_address']);

    }

    public function getAllUsers() {
        $userPermissionLoader = new User_permission_loader_model();
        $dbStore = new Database_store_user();
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
            $userPermissionLoader->setPermissionArrayForUser($dbStore, $newUser);
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

    public function insertNewUser(User $pUser) {
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
        $translatedArray = "";
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
        $translatedArray = "";
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['user_name']] = $rowItem['role_id'];
        }
        return $translatedArray;

    }

    public function getAllUserActiveFromDB(IData_store_user $pDataStore) {
        $queryString = "SELECT user_name, active
            FROM umpire_users
            WHERE user_name NOT IN ('bbrumm');";

        $resultArray = $this->getArrayFromQuery($queryString);

        //Translate the data into the format mentioned above
        return $this->translateActiveArray($resultArray);
    }

    private function translateActiveArray($resultArray) {
        $translatedArray = "";
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['user_name']] = $rowItem['active'];
        }
        return $translatedArray;

    }


}
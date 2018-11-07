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



}
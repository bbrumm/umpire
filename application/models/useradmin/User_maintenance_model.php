<?php
/*
* @property Object security
* @property Object session
*/
class User_maintenance_model extends CI_Model {

    public function __construct() {
        $this->load->model('User');
    }


    public function checkUserExistsForReset(IData_store_user $pDataStore, User $pUser) {
        return ($pDataStore->checkUserExistsForReset($pUser) > 0);
    }

    public function logPasswordResetRequest(IData_store_user $pDataStore, $pRequestData) {
        $data = array(
            'request_datetime' => $pRequestData['request_datetime'],
            'activation_id' => $pRequestData['activation_id'],
            'ip_address' => $pRequestData['client_ip'],
            'user_name' => $pRequestData['username_entered'],
            'email_address' => $pRequestData['email_address_entered']
        );

        $queryStatus = $pDataStore->logPasswordResetRequest($data);
        return ($queryStatus == 1);
    }


    public function storeActivationID(IData_store_user $pDataStore, User $pUser, $pActivationID) {
        return $pDataStore->storeActivationID($pUser, $pActivationID);
    }


    public function createUserFromActivationID(IData_store_user $pDataStore, $pActivationID) {
        $returnedUser = $pDataStore->createUserFromActivationID($pActivationID);

        if (isset($returnedUser)) {
            //$pUser->setUsername($returnedUsername);
            return $returnedUser;
        } else {
            return false;
        }

    }


    public function updatePassword(IData_store_user $pDataStore, User $pUser) {
        $this->logPasswordReset($pDataStore, $pUser);

        return $pDataStore->updatePassword($pUser);
    }

    private function logPasswordReset(IData_store_user $pDataStore, User $pUser) {

        $oldPassword = $pDataStore->findOldUserPassword($pUser);

        $data = array(
            'user_name' => $pUser->getUsername(),
            'new_password' => $pUser->getPassword(),
            'old_password' => $oldPassword,
            'reset_datetime' => date('Y-m-d H:i:s', time())
        );

        $pDataStore->logPasswordReset($data);
    }

    public function validatePassword($pNewPassword, $pConfirmPassword) {
        return ($pNewPassword == $pConfirmPassword && strlen($pNewPassword) >= 6);
    }


    public function validate(IData_store_user $pDataStore, $pUsername, $pPassword){
        $username = $this->security->xss_clean($pUsername);
        $password = $this->security->xss_clean($pPassword);
        $user = $pDataStore->findMatchingUserFromUsernameAndPassword($username, $password);
        if(isset($user)) {
            // If there is a user, then create session data
            $data = array(
                'userid' => $user->getID(),
                'user_name' => $user->getUsername(),
                'user_email' => $user->getEmailAddress(),
                'validated' => true
            );
            $this->session->set_userdata($data);
            return true;
        }
        // If the previous process did not validate
        // then return false.
        return false;
    }

    public function updateEmailAddress(IData_store_user $pDataStore, User $pUser) {
        return $pDataStore->updateEmailAddress($pUser);
        //$this->db->where('user_name', $pUser->getUsername());
        //$this->db->update('umpire_users', array('user_email'=>$pUser->getEmailAddress()));
    }


}

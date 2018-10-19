<?php

class User_maintenance_model extends CI_Model {

    public function __construct() {
        $this->load->model('User');
    }


    public function checkUserExistsForReset(IData_store $pDataStore, User $pUser) {
        return ($pDataStore->checkUserExistsForReset($pUser) > 0);
    }

    public function logPasswordResetRequest(IData_store $pDataStore, $pRequestData) {
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


    public function storeActivationID(IData_store $pDataStore, User $pUser, $pActivationID) {
        $pDataStore->storeActivationID($pUser, $pActivationID);
    }


    public function createUserFromActivationID(IData_store $pDataStore, User $pUser) {
        $returnedUsername = $pDataStore->getUserNameFromActivationID($pUser);

        if (isset($returnedUsername)) {
            $pUser->setUsername($returnedUsername);
            return $pUser;
        } else {
            return false;
        }

    }


    public function updatePassword(IData_store $pDataStore, User $pUser) {
        $this->logPasswordReset($pDataStore, $pUser);

        $pDataStore->updatePassword($pUser);
    }

    private function logPasswordReset(IData_store $pDataStore, User $pUser) {

        $oldPassword = $pDataStore->findOldUserPassword($pUser);

        $data = array(
            'user_name' => $pUser->getUsername(),
            'new_password' => $pUser->getPassword(),
            'old_password' => $oldPassword,
            'reset_datetime' => date('Y-m-d H:i:s', time())
        );

        $queryStatus = $pDataStore->logPasswordReset($data);
    }

    public function validatePassword($pNewPassword, $pConfirmPassword) {
        return ($pNewPassword == $pConfirmPassword && strlen($pNewPassword) >= 6);
    }


    public function validate(IData_store $pDataStore, $pUsername, $pPassword){
        $username = $this->security->xss_clean($pUsername);
        $password = $this->security->xss_clean($pPassword);
        $user = $pDataStore->findUserFromUsernameAndPassword($username, $password);
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

    public function updateEmailAddress(IData_store $pDataStore, User $pUser) {
        $pDataStore->updateEmailAddress($pUser);
        //$this->db->where('user_name', $pUser->getUsername());
        //$this->db->update('umpire_users', array('user_email'=>$pUser->getEmailAddress()));
    }


}

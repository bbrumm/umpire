<?php
/*
* @property Object security
*/
class User_authentication_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function login(IData_store_user $pDataStore, $pUsername, $pPassword) {
        return $pDataStore->findMatchingUserFromUsernameAndPassword($pUsername, $pPassword);
    }

    public function checkUserActive(IData_store_user $pDataStore, $pUsername) {
        return $pDataStore->checkUserActive($pUsername);
    }

    //TODO: Get this working after the tests are all passing.
    //Code was copied from VerifyLogin controller
    /*
    public function isFormInputValid() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');

        if($this->form_validation->run() == FALSE) {
            return true;
        } else {
            return false;
        }
    }
    */

    public function updatePassword(IData_store_user $pDataStore, $pUsername, $pPassword, $pConfirmPassword) {
        //TODO: Refactor this with the ResetPasswordEntry controller as it's very similar code
        //$userName = $_POST['username'];
        $userMaintenance = new User_maintenance_model();
        //$dbStore = new Database_store_user();

        $newPassword= $this->security->xss_clean($pPassword);
        $confirmNewPassword= $this->security->xss_clean($pConfirmPassword);

        $umpireUser = new User();

        $validPassword = $userMaintenance->validatePassword($newPassword, $confirmNewPassword);

        if ($validPassword) {

            $umpireUser->setUsername($pUsername);
            $umpireUser->setPassword(MD5($newPassword));
            $userMaintenance->updatePassword($pDataStore, $umpireUser);
            //$statusMessage = "Password reset successfully.";
            return true;
        } else {
            return false;
            /*
            $statusMessage = "Passwords do not match or are less than 6 characters. ".
                "Please ensure that both passwords you have entered are the same, and they are at least 6 characters long.";
            return $statusMessage;
            */
        }
    }
    
    //TODO: use this function instead of the above function, if it's only called in one place
    //TODO: Refactor this with the ResetPasswordEntry controller as it's very similar code
    public function updatePasswordFromPost(IData_store_user $pDataStore) {
        $userMaintenance = new User_maintenance_model();
        $newPassword = $this->security->xss_clean($_POST['password']);
        $confirmNewPassword = $this->security->xss_clean($_POST['confirmPassword']);

        $umpireUser = new User();
        $validPassword = $userMaintenance->validatePassword($newPassword, $confirmNewPassword);
        if ($validPassword) {
            $umpireUser->setUsername($_POST['username']);
            $umpireUser->setPassword(MD5($newPassword));
            $userMaintenance->updatePassword($pDataStore, $umpireUser);
            return true;
        } else {
            return false;
        }
    }



}

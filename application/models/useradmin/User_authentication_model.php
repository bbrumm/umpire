<?php

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



}

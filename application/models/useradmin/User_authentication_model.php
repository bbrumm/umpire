<?php

class User_authentication_model extends CI_Model {


    public function login(IData_store $pDataStore, $pUsername, $pPassword) {
        return $pDataStore->findMatchingUserFromUsernameAndPassword($pUsername, $pPassword);
    }

    public function checkUserActive(IData_store $pDataStore, $pUsername) {
        return $pDataStore->checkUserActive($pUsername);
    }



}

<?php
require_once 'IData_store_user.php';

class Array_store_user_empty extends CI_Model implements IData_store_user {

    public function findMatchingUserFromUsernameAndPassword($pUsername, $pPassword) {

    }

    public function checkUserActive($pUsername) {

    }

    public function getUserFromUsername($pUsername) {

    }

    public function findPermissionsForUser(User $pUser) {

    }

    public function checkUserExistsForReset(User $pUser) {

    }

    public function logPasswordResetRequest($pRequestData) {

    }

    public function storeActivationID($pActivationID, $pUser) {

    }
/*
    public function createUserFromActivationID($pActivationID) {

    }
*/
    public function updatePassword(User $pUser) {

    }

    public function logPasswordReset($pData) {

    }

    public function updateEmailAddress(User $pUser) {

    }

    /*
    public function findUserFromUsernameAndPassword($username, $password) {

    }
*/

    public function findOldUserPassword(User $pUser) {

    }


}
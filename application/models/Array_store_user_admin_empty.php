<?php
require_once 'IData_store_user_admin.php';

class Array_store_user_admin_empty extends CI_Model implements IData_store_user_admin {

    //User admin
    public function getAllUsers() {
        $testData = [];
        return $testData;
    }
    public function insertNewUser(User $pUser) {}

    public function addDefaultUserPrivileges($username) {}

    public function getCountOfMatchingUsers(User $pUser) {}
}
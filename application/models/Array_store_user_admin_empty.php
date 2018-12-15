<?php
require_once 'IData_store_user_admin.php';

class Array_store_user_admin_empty extends CI_Model implements IData_store_user_admin
{

//User admin
    public function getAllUsers() {
        $testData = [];
        return $testData;
    }

    public function getRoleArray() {
        $testData = [];
        return $testData;
    }

    public function getReportArray() {
        $testData = [];
        return $testData;
    }

    public function getRegionArray() {
        $testData = [];
        return $testData;
    }

    public function getUmpireDisciplineArray() {
        $testData = [];
        return $testData;
    }

    public function getAgeGroupArray() {
        $testData = [];
        return $testData;
    }

    public function getLeagueArray() {
        $testData = [];
        return $testData;
    }

    public function getPermissionSelectionArray() {
        $testData = [];
        return $testData;
    }

    public function insertNewUser(User $pUser) {

    }

    public function getAllUserPermissionsFromDB() {

    }

    public function getAllUserRolesFromDB() {

    }

    public function getAllUserActiveFromDB() {

    }

    public function removeUserPrivilege($username, $permission_selection_id) {

    }

    public function getUserPrivileges() {

    }

    public function addUserPrivilege($username, $permission_selection_id) {

    }

    public function updateUserRole($username, $newRoleID) {

    }

    public function updateSingleUserActive($username, $setValue) {

    }

    public function getCountOfMatchingUsers(User $pUser) {

    }
}
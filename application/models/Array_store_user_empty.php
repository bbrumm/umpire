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

    public function createUserFromActivationID($pActivationID) {

    }

    public function updatePassword(User $pUser) {

    }

    public function logPasswordReset($pData) {

    }

    public function updateEmailAddress(User $pUser) {

    }

    public function findUserFromUsernameAndPassword($username, $password) {

    }

    public function findOldUserPassword(User $pUser) {

    }

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


}
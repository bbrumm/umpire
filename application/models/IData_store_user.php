<?php
//This interface is used to allow testing of the validation separate from database testing
//More info: https://stackoverflow.com/questions/19937667/how-can-i-unit-test-a-method-with-database-access
interface IData_store_user
{
//User
    public function findMatchingUserFromUsernameAndPassword($pUsername, $pPassword);

    public function checkUserActive($pUsername);

    public function getUserFromUsername($pUsername);

    public function findPermissionsForUser(User $pUser);

    public function checkUserExistsForReset(User $pUser);

    public function logPasswordResetRequest($pRequestData);

    public function storeActivationID($pActivationID, $pUser);

    public function createUserFromActivationID($pActivationID);

    public function updatePassword(User $pUser);

    public function logPasswordReset($pData);

    public function updateEmailAddress(User $pUser);

    public function findUserFromUsernameAndPassword($username, $password);

    public function findOldUserPassword(User $pUser);

    public function getAllUsers();

    public function getRoleArray();

    public function getReportArray();

    public function getRegionArray();

    public function getUmpireDisciplineArray();

    public function getAgeGroupArray();

    public function getLeagueArray();

    public function getPermissionSelectionArray();

    public function insertNewUser(User $pUser);

    public function getAllUserPermissionsFromDB();

    public function getAllUserRolesFromDB();


}
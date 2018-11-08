<?php
//This interface is uusersed to allow testing of the validation separate from database testing
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


}
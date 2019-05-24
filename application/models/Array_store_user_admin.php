<?php
require_once 'IData_store_user_admin.php';

class Array_store_user_admin extends CI_Model implements IData_store_user_admin {

    public function getAllUsers() {
        $userPermissionLoader = new User_permission_loader_model();
        $arrayStore = new Array_store_user();
        $userArray = [];
        $testData = array(
            array("id" => 1, "username" => "username1", "firstname"=>"john", "lastname"=>"smith", "role_name"=>"super", "active"=>1, "email_address"=>null),
            array("id" => 2, "username" => "username2", "firstname"=>"sue", "lastname"=>"jones", "role_name"=>"regular", "active"=>1, "email_address"=>null),
            array("id" => 4, "username" => "username4", "firstname"=>"mark", "lastname"=>"brown", "role_name"=>"regular", "active"=>1, "email_address"=>null),
            array("id" => 8, "username" => "username8", "firstname"=>"jane", "lastname"=>"pick", "role_name"=>"admin", "active"=>1, "email_address"=>null)
        );

        foreach ($testData as $key => $subArray) {
            $newUser = User::createUserFromNameAndRole(
                $subArray["id"], $subArray["username"], $subArray["firstname"], $subArray["lastname"], $subArray["role_name"], $subArray["active"], $subArray["email_address"]);
            $userPermissionLoader->setPermissionArrayForUser($arrayStore, $newUser);
            $userArray[] = $newUser;
        }
        return $userArray;

    }

    public function insertNewUser(User $pUser) {
        $user1 = User::createUserFromNameAndPW("john", "john", "smith", "mypass");
        $user2 = User::createUserFromNameAndPW("paul", "paul", "mcc", "apass");
        $existingUsers = array ($user1, $user2);
        $newUsername = $pUser->getUsername();
        $newPassword = $pUser->getPassword();
        if ($this->isUserDetailsValid($newUsername, $newPassword)) {
            $existingUsers[] = $pUser;
        }
        $lastIndex = count($existingUsers) - 1;
        if ($this->isNewUserInsertedCorrectly($existingUsers, $lastIndex, $pUser)) {
            return true;
        } else {
            throw new Exception("There was an error when inserting the user. Please contact support.");
        }

    }
    
    private function isUserDetailsValid($pUsername, $pPassword) {
        return (!empty($pUsername) && !empty($pPassword));
    }
            
    private function isNewUserInsertedCorrectly($existingUsers, $lastIndex, $pUser) {
        return ($existingUsers[$lastIndex]->getUsername() == $pUser->getUsername()
            && count($existingUsers) == 3);
    }

    public function addDefaultUserPrivileges($username) { }

    public function getCountOfMatchingUsers(User $pUser) { }
}

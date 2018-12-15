<?php
require_once 'IData_store_user_admin.php';

class Array_store_user_admin extends CI_Model implements IData_store_user_admin
{
//User admin
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

    public function getRoleArray() {
        $testData = array(
            array("id"=>1, "role_name"=>"Administrator", "display_order"=>1),
            array("id"=>2, "role_name"=>"Super User", "display_order"=>2),
            array("id"=>3, "role_name"=>"Regular User", "display_order"=>3)
        );

        return $testData;
    }

    public function getReportArray() {
        $testData = array (
            array("report_table_id"=>1, "report_title"=>"Report 1"),
            array("report_table_id"=>2, "report_title"=>"Report 2"),
            array("report_table_id"=>3, "report_title"=>"Report 3")
        );

        return $testData;
    }

    public function getRegionArray() {
        $testData = array (
            array("id"=>1, "region"=>"Geelong"),
            array("id"=>2, "region"=>"Colac")
        );

        return $testData;
    }

    public function getUmpireDisciplineArray() {
        $testData = array (
            array("id"=>1, "umpire_type_name"=>"Field"),
            array("id"=>2, "umpire_type_name"=>"Boundary"),
            array("id"=>3, "umpire_type_name"=>"Goal")
        );

        return $testData;
    }

    public function getAgeGroupArray() {
        $testData = array (
            array("id"=>1, "umpire_type_name"=>"Under 18"),
            array("id"=>2, "umpire_type_name"=>"Under 16"),
            array("id"=>3, "umpire_type_name"=>"Senior")
        );

        return $testData;
    }

    public function getLeagueArray() {
        $testData = array (
            array("id"=>1, "short_league_name"=>"BFL"),
            array("id"=>2, "short_league_name"=>"GFL"),
            array("id"=>3, "short_league_name"=>"GDFL")
        );

        return $testData;
    }

    public function getPermissionSelectionArray() {
        $testData = array (
            array("id"=>1, "permission_id"=>1, "category" => "something", "selection_name" => "yes"),
            array("id"=>2, "permission_id"=>1, "category" => "else", "selection_name" => "two"),
            array("id"=>3, "permission_id"=>5, "category" => "more", "selection_name" => "blah")
        );

        return $testData;
    }

    public function insertNewUser(User $pUser) {
        $user1 = User::createUserFromNameAndPW("john", "john", "smith", "mypass");
        $user2 = User::createUserFromNameAndPW("paul", "paul", "mcc", "apass");
        $existingUsers = array ($user1, $user2);
        $newUsername = $pUser->getUsername();
        $newPassword = $pUser->getPassword();
        if (!empty($newUsername) && !empty($newPassword)) {
            $existingUsers[] = $pUser;
        }
        $lastIndex = count($existingUsers) - 1;

        if ($existingUsers[$lastIndex]->getUsername() == $pUser->getUsername()
            && count($existingUsers) == 3) {
            return true;
        } else {
            throw new Exception("There was an error when inserting the user. Please contact support.");
        }

    }

    public function getAllUserPermissionsFromDB() {
        $testData = array (
            "jsmith" => array (1=>"on", 4=>"on"),
            "bbrumm" => array (3=>"on"),
            "abc" => array (1=>"on", 9=>"on")
        );

        return $testData;
    }

    public function getAllUserRolesFromDB() {
        $testData = array (
            "jsmith" => 2,
            "bbrumm" => 1,
            "abc" => 4
        );

        return $testData;
    }

    public function getAllUserActiveFromDB() {
        $testData = array (
            "jsmith22" => "on",
            "bbrumm1" => "on",
            "abc9" => "on"
        );

        return $testData;
    }

    private $testUserPrivilegeData = array (
        array ("username"=>"john", "permission_selection_id"=>2),
        array ("username"=>"john", "permission_selection_id"=>5),
        array ("username"=>"ringo", "permission_selection_id"=>6),
        array ("username"=>"george", "permission_selection_id"=>1),
        array ("username"=>"george", "permission_selection_id"=>2)
    );

    public function getUserPrivileges() {
        return $this->testUserPrivilegeData;
    }

    private function setUserPrivileges($pUserPrivileges) {
        $this->testUserPrivilegeData = $pUserPrivileges;
    }


    public function removeUserPrivilege($username, $permission_selection_id) {
        $testData = $this->getUserPrivileges();

        foreach($testData as $key=>$value) {
            if ($value["username"] == $username && $value["permission_selection_id"] == $permission_selection_id) {
                unset($testData[$key]);
            }
        }

        $this->setUserPrivileges($testData);
    }



    public function addUserPrivilege($username, $permission_selection_id) {
        $testData = $this->getUserPrivileges();
        $usernameFound = false;
        foreach ($testData as $key => $value) {
            if($value["username"] == $username) {
                $usernameFound = true;
                break;
            }
        }
        if ($usernameFound) {
            $testData[] = array(
                "username" => $username,
                "permission_selection_id" => $permission_selection_id
            );
            $this->setUserPrivileges($testData);
        }
    }

    private $testUserRoleData = array (
        array ("username"=>"john", "role_id"=>2),
        array ("username"=>"ringo", "role_id"=>2),
        array ("username"=>"paul", "role_id"=>3),
        array ("username"=>"george", "role_id"=>4)
    );

    public function getUserRoles() {
        return $this->testUserRoleData;
    }

    private function setUserRoles($pUserRoles) {
        $this->testUserRoleData = $pUserRoles;
    }

    public function updateUserRole($username, $newRoleID) {
        $testData = $this->getUserRoles();
        //print_r($testData);
        foreach ($testData as $key => $value) {
            if ($value["username"] == $username) {
                $testData[$key]["role_id"] = $newRoleID;
            }
        }
        //print_r($testData);
        $this->setUserRoles($testData);
    }

    private $testUserActiveData = array (
        array ("username"=>"john", "active"=>1),
        array ("username"=>"ringo", "active"=>0),
        array ("username"=>"paul", "active"=>0),
        array ("username"=>"george", "active"=>1)
    );

    public function getUserActiveData() {
        return $this->testUserActiveData;
    }

    private function setUserActiveData($pUserActiveData) {
        $this->testUserActiveData = $pUserActiveData;
    }

    public function updateSingleUserActive($username, $setValue) {
        $testData = $this->getUserActiveData();
        //print_r($testData);
        foreach ($testData as $key => $value) {
            if ($value["username"] == $username && ($setValue === 0 || $setValue === 1)) {
                $testData[$key]["active"] = $setValue;
            }
        }
        //print_r($testData);
        $this->setUserActiveData($testData);
    }

    public function getCountOfMatchingUsers(User $pUser) {

    }


}
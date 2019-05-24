<?php
require_once 'IData_store_user_permission.php';

class Array_store_user_permission extends CI_Model implements IData_store_user_permission {

    public function getAllUserPermissionsFromDB() {
        /*$testData = array (
            "jsmith" => array (1=>"on", 4=>"on"),
            "bbrumm" => array (3=>"on"),
            "abc" => array (1=>"on", 9=>"on")
        );*/

        //Translate the data into the format mentioned above
        return $this->translatePermissionArray($this->testUserPrivilegeData);

    }

    private function translatePermissionArray($resultArray) {
        $translatedArray = [];
        foreach ($resultArray as $rowItem) {
            $translatedArray[$rowItem['username']][$rowItem['permission_selection_id']] = 'on';
        }
        return $translatedArray;

    }

    public function getAllUserRolesFromDB() {
        $testData = array (
            "jsmith" => 2,
            "bbrumm" => 1,
            "abc" => 4
        );

        return $testData;
    }

    private $testUserPrivilegeData = array (
        array ("username"=>"jsmith", "permission_selection_id"=>1),
        array ("username"=>"jsmith", "permission_selection_id"=>4),
        array ("username"=>"bbrumm", "permission_selection_id"=>3),
        array ("username"=>"abc", "permission_selection_id"=>1),
        array ("username"=>"abc", "permission_selection_id"=>9)
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
        if (! $this->isPrivilegeExistsInUserPrivileges($username, $permission_selection_id)
            && $this->isUsernameFoundInUserPrivileges($username) ) {
            $this->addNewUserPrivilege($username, $permission_selection_id);
        }
    }

    private function  addNewUserPrivilege( $username, $permission_selection_id ) {
        $privilegeList = $this->getUserPrivileges();
        $privilegeList[] = array(
            "username" => $username,
            "permission_selection_id" => $permission_selection_id
        );
        $this->setUserPrivileges($privilegeList);
    }

    private function isUsernameFoundInUserPrivileges($username) {
        foreach($this->getUserPrivileges() as $key => $value) {
            if($value["username"] == $username) {
                return true;
            }
        }
        return false;
    }

    private function isPrivilegeExistsInUserPrivileges($username, $permission_selection_id) {
        foreach($this->getUserPrivileges() as $key => $value) {
            if($value["username"] == $username &&  $value["permission_selection_id"] == $permission_selection_id ) {
                return true;
            }
        }
        return false;
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

    public function getAllUserActiveFromDB() {
        $testData = array (
            "jsmith22" => "on",
            "bbrumm1" => "on",
            "abc9" => "on"
        );

        return $testData;
    }

    private $testUserActiveData = array (
        array ("username"=>"jsmith", "active"=>1),
        array ("username"=>"bbrumm", "active"=>1),
        array ("username"=>"abc", "active"=>0)
    );

    public function getUserActiveData() {
        return $this->testUserActiveData;
    }

    private function setUserActiveData($pUserActiveData) {
        $this->testUserActiveData = $pUserActiveData;
    }

    public function updateSingleUserActive($username, $setValue) {
        $testData = $this->getUserActiveData();
        foreach ($testData as $key => $value) {
            if ($this->isUserActiveValid($value, $username, $setValue)) {
                $testData[$key]["active"] = $setValue;
            }
        }
        $this->setUserActiveData($testData);
    }

    private function isUserActiveValid($pValue, $username, $setValue) {
        if ($pValue["username"] != $username) {
            return false;
        }
        if ($setValue === 0 || $setValue === 1) {
            return true;
        }
        return false;
    }


}
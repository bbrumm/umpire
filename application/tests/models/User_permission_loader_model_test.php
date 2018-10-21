<?php

class User_permission_loader_model_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('useradmin/User_permission_loader_model');
        $this->obj = new User_permission_loader_model();
    }

    public function test_getUserFromUsername() {
        $arrayStore = new Array_store;
        $username = "john";
        $firstname = "john";
        $lastname = "smith";
        $actual = $this->obj->getUserFromUsername($arrayStore, $username);
        $expected = User::createUserFromNameAndPW($username, $firstname, $lastname, null);
        $this->assertEquals($expected->getUsername(), $actual->getUsername());
        $this->assertEquals($expected->getFirstName(), $actual->getFirstName());
        $this->assertEquals($expected->getLastName(), $actual->getLastName());
    }

    public function test_getUserFromUsername_UserNotFound() {
        $arrayStore = new Array_store;
        $username = "john20";
        $actual = $this->obj->getUserFromUsername($arrayStore, $username);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }

    public function test_getUserFromUsername_TwoUsers() {
        $arrayStore = new Array_store;
        $username = "asd";
        $firstname = "sam";
        $lastname = "smith";
        $actual = $this->obj->getUserFromUsername($arrayStore, $username);
        $expected = User::createUserFromNameAndPW($username, $firstname, $lastname, null);
        $this->assertEquals($expected->getUsername(), $actual->getUsername());
        $this->assertEquals($expected->getFirstName(), $actual->getFirstName());
        $this->assertEquals($expected->getLastName(), $actual->getLastName());
    }

    public function test_UserHasSpecificPermission() {
        $arrayStore = new Array_store;
        $username = "john";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $permissionName = "IMPORT_FILES";
        $selectionName = "All";
        $actual = $this->obj->userHasSpecificPermission($user, $permissionName, $selectionName);
        $expected = true;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserHasSpecificPermission_SelectionNotInArray() {
        $arrayStore = new Array_store;
        $username = "john";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $permissionName = "IMPORT_FILES";
        $selectionName = "missing val";
        $actual = $this->obj->userHasSpecificPermission($user, $permissionName, $selectionName);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserHasSpecificPermission_PermissionNotInArray() {
        $arrayStore = new Array_store;
        $username = "john";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $permissionName = "MORE";
        $selectionName = "All";
        $actual = $this->obj->userHasSpecificPermission($user, $permissionName, $selectionName);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserHasSpecificPermission_Empty() {
        $arrayStore = new Array_store;
        $username = "john";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $permissionName = "";
        $selectionName = "";
        $actual = $this->obj->userHasSpecificPermission($user, $permissionName, $selectionName);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserHasSpecificPermission_Null() {
        $arrayStore = new Array_store;
        $username = "john";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $permissionName = null;
        $selectionName = null;
        $actual = $this->obj->userHasSpecificPermission($user, $permissionName, $selectionName);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserHasImportFilePermission() {
        $arrayStore = new Array_store;
        $username = "paul";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $actual = $this->obj->userHasImportFilePermission($user);
        $expected = true;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserHasImportFilePermission_No() {
        $arrayStore = new Array_store;
        $username = "george";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $actual = $this->obj->userHasImportFilePermission($user);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserCanSeeAdminPage() {
        $arrayStore = new Array_store;
        $username = "john";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $actual = $this->obj->userCanSeeUserAdminPage($user);
        $expected = true;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserCanSeeAdminPage_No() {
        $arrayStore = new Array_store;
        $username = "george";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $actual = $this->obj->userCanSeeUserAdminPage($user);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserCanSeeDataTestPage() {
        $arrayStore = new Array_store;
        $username = "john";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $actual = $this->obj->userCanSeeDataTestPage($user);
        $expected = true;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserCanSeeDataTest_No() {
        $arrayStore = new Array_store;
        $username = "george";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $actual = $this->obj->userCanSeeDataTestPage($user);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserCanCreatePDF() {
        $arrayStore = new Array_store;
        $username = "john";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $actual = $this->obj->userCanCreatePDF($user);
        $expected = true;
        $this->assertEquals($expected, $actual);
    }

    public function test_UserCanCreatePDF_No() {
        $arrayStore = new Array_store;
        $username = "george";
        $user = $this->obj->getUserFromUsername($arrayStore, $username);
        $actual = $this->obj->userCanCreatePDF($user);
        $expected = false;
        $this->assertEquals($expected, $actual);
    }


}
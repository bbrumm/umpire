<?php

class User_authentication_model_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('useradmin/User_authentication_model');
    }

    public function test_Login_ValidOneResult() {
        $arrayStore = new Array_store();
        $username = "john";
        $password = "mypass";
        $expectedID = 1;
        $this->obj = new User_authentication_model();

        $queryResult = $this->obj->login($arrayStore, $username, $password);
        $actualUsername = $queryResult['username'];
        $actualID = $queryResult['id'];

        $this->assertEquals($username, $actualUsername);
        $this->assertEquals($expectedID, $actualID);

    }

    public function test_Login_ValidTwoResults() {
        $arrayStore = new Array_store();
        $username = "ringo";
        $password = "otherthing";
        $expectedID = 3;
        $this->obj = new User_authentication_model();

        $queryResult = $this->obj->login($arrayStore, $username, $password);
        $actualUsername = $queryResult['username'];
        $actualID = $queryResult['id'];

        $this->assertEquals($username, $actualUsername);
        $this->assertEquals($expectedID, $actualID);

    }

    public function test_Login_InvalidPassword() {
        $arrayStore = new Array_store();
        $username = "ringo";
        $password = "otherthingwrong";
        $this->obj = new User_authentication_model();

        $queryResult = $this->obj->login($arrayStore, $username, $password);
        $this->assertEquals(false, $queryResult);
    }


    public function test_Login_NoPassword() {
        $arrayStore = new Array_store();
        $username = "paul";
        $password = null;
        $expectedID = 2;
        $this->obj = new User_authentication_model();

        $queryResult = $this->obj->login($arrayStore, $username, $password);
        $actualUsername = $queryResult['username'];
        $actualID = $queryResult['id'];

        $this->assertEquals($username, $actualUsername);
        $this->assertEquals($expectedID, $actualID);

    }

    public function test_Login_UsernameButNoPassword() {
        $arrayStore = new Array_store();
        $username = "paul";
        $password = "something";
        $this->obj = new User_authentication_model();

        $queryResult = $this->obj->login($arrayStore, $username, $password);
        $this->assertEquals(false, $queryResult);
    }


    public function test_Login_NoUsernameAndNoPassword() {
        $arrayStore = new Array_store();
        $username = "susan";
        $password = "onemoretest";
        $this->obj = new User_authentication_model();

        $queryResult = $this->obj->login($arrayStore, $username, $password);
        $this->assertEquals(false, $queryResult);
    }


    public function test_CheckUserActive_OneActive() {
        $arrayStore = new Array_store();
        $username = "john";
        $expected = true;
        $this->obj = new User_authentication_model();
        $actual = $this->obj->checkUserActive($arrayStore, $username);
        $this->assertEquals($expected, $actual);
    }

    public function test_CheckUserActive_OneInactive() {
        $arrayStore = new Array_store();
        $username = "paul";
        $expected = false;
        $this->obj = new User_authentication_model();
        $actual = $this->obj->checkUserActive($arrayStore, $username);
        $this->assertEquals($expected, $actual);
    }

    public function test_CheckUserActive_TwoActive() {
        $arrayStore = new Array_store();
        $username = "george";
        $expected = false;
        $this->obj = new User_authentication_model();
        $actual = $this->obj->checkUserActive($arrayStore, $username);
        $this->assertEquals($expected, $actual);
    }

    public function test_CheckUserActive_TwoButOneActive() {
        $arrayStore = new Array_store();
        $username = "ringo";
        $expected = true;
        $this->obj = new User_authentication_model();
        $actual = $this->obj->checkUserActive($arrayStore, $username);
        $this->assertEquals($expected, $actual);
    }

    public function test_CheckUserActive_NoUsername() {
        $arrayStore = new Array_store();
        $username = "susan";
        $expected = false;
        $this->obj = new User_authentication_model();
        $actual = $this->obj->checkUserActive($arrayStore, $username);
        $this->assertEquals($expected, $actual);
    }


}
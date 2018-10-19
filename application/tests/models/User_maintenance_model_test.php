<?php

class User_maintenance_model_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('useradmin/User_maintenance_model');
        $this->obj = $this->CI->User_maintenance_model;
    }

    public function testValidatePassword() {
        $expected = true;
        $firstPassword = "somepassword";
        $secondPassword = "somepassword";
        $actual = $this->obj->validatePassword($firstPassword, $secondPassword);
        $this->assertEquals($expected, $actual);
    }

    public function testValidatePasswordNoMatch() {
        $expected = false;
        $firstPassword = "somepassword";
        $secondPassword = "someotherpassword";
        $actual = $this->obj->validatePassword($firstPassword, $secondPassword);
        $this->assertEquals($expected, $actual);
    }

    public function testValidatePasswordNoMatchAndShort() {
        $expected = false;
        $firstPassword = "some";
        $secondPassword = "pass";
        $actual = $this->obj->validatePassword($firstPassword, $secondPassword);
        $this->assertEquals($expected, $actual);
    }

    public function testValidatePasswordShortMatch() {
        $expected = false;
        $firstPassword = "some";
        $secondPassword = "some";
        $actual = $this->obj->validatePassword($firstPassword, $secondPassword);
        $this->assertEquals($expected, $actual);
    }
    
    public function test_CheckUserExistsForReset() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndPW("test", "john", "smith", "mypass");
        $expected = true;
        $actual = $this->obj->checkUserExistsForReset($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }
    
    public function test_CheckUserExistsForReset_NoMatch() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = createUserFromNameAndEmail("abcdef", "john", "smith", "test@abc.com");
        $expected = false;
        $actual = $this->obj->checkUserExistsForReset($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_CheckUserExistsForReset_TwoRecordsMatch() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = createUserFromNameAndEmail("john", "john", "smith", "test@abc.com");
        $expected = false;
        $actual = $this->obj->checkUserExistsForReset($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_CheckUserExistsForReset_UserMatchEmailNoMatch() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = createUserFromNameAndEmail("abcdef", "john", "smith", "testwrong@abc.com");
        $expected = false;
        $actual = $this->obj->checkUserExistsForReset($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_CheckUserExistsForReset_UserNoMatchEmailMatch() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = createUserFromNameAndEmail("abcdefwrong", "john", "smith", "test@abc.com");
        $expected = false;
        $actual = $this->obj->checkUserExistsForReset($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_LogPasswordResetRequest() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $requestData = array(
        'request_datetime'=>'19/10/18 06:30 AM',
        'activation_id'=>'123123123',
        'ip_address'=>'123.14.5.67',
        'user_name'=>'john',
        'email_address'=>'test@abc123.com'
        );
        $actual = $this->obj->logPasswordResetRequest($arrayStore, $requestData);
        $expected = true;
        $this->assertEquals($expected, $actual);


    }


    public function test_LogPasswordResetRequest_MissingUsername() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $requestData = array(
        'request_datetime'=>'19/10/18 06:30 AM',
        'activation_id'=>'123123123',
        'ip_address'=>'123.14.5.67',
        'user_name'=>'unknownperson',
        'email_address'=>'test@abc123.com'
        );
        $actual = $this->obj->logPasswordResetRequest($arrayStore, $requestData);
        $expected = false;
        $this->assertEquals($expected, $actual);


    }


    public function test_StoreActivationID() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $activationID = "123456";
        $user = User::createUserFromNameAndEmail("abcdef", "john", "smith", "test@abc.com");
        $actual = $this->obj->storeActivationID($arrayStore, $user, $activationID);
        $expected = true;
        $this->assertEquals($expected, $actual);
    }

    public function test_StoreActivationID_UserNotFound() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $activationID = "123456";
        $user = User::createUserFromNameAndPW("abcdefnotfound", "john", "smith", "mypass");
        $actual = $this->obj->storeActivationID($arrayStore, $user, $activationID);
        $expected = true;
        $this->assertEquals($expected, $actual);
    }

    public function test_CreateUserFromActivationID() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $activationID = "123456";
        $user = User::createUserFromNameAndPW("abcdef", "john", "smith", "mypass");
        $user->setActivationID($activationID);
        $actual = $this->obj->createUserFromActivationID($arrayStore, $user);
        $expected = "abcdef";
        $this->assertEquals($expected->getUsername, $actual->getUsername());
    }

    public function test_CreateUserFromActivationID_NotFound() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $activationID = "987";
        $user = User::createUserFromNameAndPW("abcdef", "john", "smith", "mypass");
        $user->setActivationID($activationID);
        $actual = $this->obj->createUserFromActivationID($arrayStore, $user);
        $expected = false;
        $this->assertEquals($expected->getUsername, $actual->getUsername());
    }


    public function test_CreateUserFromActivationID_TwoUsersFound() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $activationID = "111";
        $user = User::createUserFromNameAndPW("john", "john", "smith", "mypass");
        $user->setActivationID($activationID);
        $actual = $this->obj->createUserFromActivationID($arrayStore, $user);
        $expected = false;
        $this->assertEquals($expected->getUsername, $actual->getUsername());
    }


    public function test_UpdatePassword() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndPW("abcdef", "john", "smith", "newpass");
        $expected = true;
        $actual = $this->obj->updatePassword($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }


    public function test_UpdatePassword_SamePW() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndPW("abcdef", "john", "smith", "mypass");
        $expected = true;
        $actual = $this->obj->updatePassword($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }


    public function test_UpdatePassword_Empty() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndPW("abcdef", "john", "smith", "");
        $expected = false;
        $actual = $this->obj->updatePassword($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_UpdatePassword_Null() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndPW("abcdef", "john", "smith", null);
        $expected = false;
        $actual = $this->obj->updatePassword($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }



    public function test_UpdatePassword_UserNotFound() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndPW("abcdefnotfound", "john", "smith", "newpass");
        $expected = false;
        $actual = $this->obj->updatePassword($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_UpdatePassword_TwoUsersFound() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndPW("john", "john", "smith", "newpass");
        $expected = false;
        $actual = $this->obj->updatePassword($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }




    public function test_Validate_UserExists() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = "test";
        $password = "mypass";
        $expected = true;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }

    public function test_Validate_UserExistsButWrongPW() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = "test";
        $password = "wrongpass";
        $expected = false;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }

    public function test_Validate_MissingUsername() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = "somethingnew";
        $password = "mypass";
        $expected = false;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }

    public function test_Validate_EmptyValues() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = "";
        $password = "";
        $expected = false;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }

    public function test_Validate_EmptyPassword() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = "abcdef";
        $password = "";
        $expected = false;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }


    public function test_Validate_NullValues() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = null;
        $password = null;
        $expected = false;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }


    public function test_Validate_TwoResults() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = "john";
        $password = "mypass";
        $expected = false;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }



    public function test_Validate_SQLInjection() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = "1 OR 1=1";
        $password = "1 OR 1=1";
        $expected = false;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }

    public function test_Validate_SQLInjectionCreate() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $username = "; CREATE TABLE inj (id NUMBER);";
        $password = "mypass";
        $expected = false;
        $actual = $this->obj->validate($arrayStore, $username, $password);
        $this->assertEquals($expected, $actual);
    }

    public function test_UpdateEmailAddress() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndEmail("test", "john", "smith", "test@email.com");
        $expected = true;
        $actual = $this->obj->updateEmailAddress($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_UpdateEmailAddress_EmailEmpty() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndEmail("test", "john", "smith", "");
        $expected = true;
        $actual = $this->obj->updateEmailAddress($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_UpdateEmailAddress_EmailNull() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndEmail("test", "john", "smith", null);
        $expected = true;
        $actual = $this->obj->updateEmailAddress($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_UpdateEmailAddress_UserNotFound() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndEmail("abcnotfound", "john", "smith", "test@abc.com");
        $expected = false;
        $actual = $this->obj->updateEmailAddress($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }

    public function test_UpdateEmailAddress_TwoUsersFound() {
        $arrayStore = new Array_store;
        $this->obj = new User_maintenance_model();
        $user = User::createUserFromNameAndEmail("john", "john", "smith", null);
        $expected = true;
        $actual = $this->obj->updateEmailAddress($arrayStore, $user);
        $this->assertEquals($expected, $actual);
    }



}

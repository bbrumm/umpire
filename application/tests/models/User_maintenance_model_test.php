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
        $user = createUserFromNameAndPW("test", "john", "smith", "mypass");
        $expected = true;
        $actual = $this->obj->checkUserExistsForReset($arrayStore, $user);
        $this->assertEquals($expected, $actual);

}


}

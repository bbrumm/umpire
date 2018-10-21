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
        $username = "john12";
        $firstname = "john";
        $lastname = "smith";
        $actual = $this->obj->getUserFromUsername($arrayStore, $username);
        $expected = User::createUserFromNameAndPW($username, $firstname, $lastname, null);
        $this->assertEquals($expected->getUsername(), $actual->getUsername());
        $this->assertEquals($expected->getFirstName(), $actual->getFirstName());
        $this->assertEquals($expected->getLastName(), $actual->getLastName());
    }
}
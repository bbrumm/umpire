<?php
class User_role_permission_test extends TestCase {
public function setUp() {
$this->resetInstance();
$this->CI->load->model('useradmin/User_role_permission');
$this->obj = $this->CI->User_role_permission;
}

public function test_ID() {
$expected = 15;
$this->obj->setID($expected);
$this->assertEquals($expected, $this->obj->getID());
}

public function test_PermissionId() {
$expected = 4;
$this->obj->setPermissionId($expected);
$this->assertEquals($expected, $this->obj->getPermissionId());
}


public function test_PermissionName() {
$expected = "some name";
$this->obj->setPermissionName($expected);
$this->assertEquals($expected, $this->obj->getPermissionName());
}


public function test_SelectionName() {
$expected = "the name here";
$this->obj->setSelectionName($expected);
$this->assertEquals($expected, $this->obj->getSelectionName());
}


}

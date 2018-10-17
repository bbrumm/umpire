<?php

class User_role_permission_test extends TestCase
{
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

    public function test_CreateFromRow() {
        $expectedID = 3;
        $expectedPermissionID = 5;
        $expectedPermissionName = "TEST";
        $expectedSelectionName = "SOMETHING";

        $row = array (
            'id'=>$expectedID,
            'permission_id'=>$expectedPermissionID,
            'permission_name'=>$expectedPermissionName,
            'selection_name'=>$expectedSelectionName
        );
        $userRolePermission = User_role_permission::createFromRow($row);
        $this->assertEquals($expectedID, $userRolePermission->getId());
        $this->assertEquals($expectedPermissionID, $userRolePermission->getPermissionId());
        $this->assertEquals($expectedPermissionName, $userRolePermission->getPermissionName());
        $this->assertEquals($expectedSelectionName, $userRolePermission->getSelectionName());
    }

    public function test_CreateFromRowNullData() {
        $this->expectException(InvalidArgumentException::class);
        $expectedID = null;
        $expectedPermissionID = null;
        $expectedPermissionName = null;
        $expectedSelectionName = null;

        $row = array (
            'id'=>$expectedID,
            'permission_id'=>$expectedPermissionID,
            'permission_name'=>$expectedPermissionName,
            'selection_name'=>$expectedSelectionName
        );
        $userRolePermission = User_role_permission::createFromRow($row);
    }

}

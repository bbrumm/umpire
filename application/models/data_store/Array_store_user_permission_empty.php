<?php
require_once 'IData_store_user_permission.php';

class Array_store_user_permission_empty extends CI_Model implements IData_store_user_permission {

    public function getAllUserPermissionsFromDB() {}

    public function getAllUserRolesFromDB() {}

    public function removeUserPrivilege($username, $permission_selection_id) {}

    public function getUserPrivileges() {}

    public function addUserPrivilege($username, $permission_selection_id) {}

    public function updateUserRole($username, $newRoleID) {}

    public function getAllUserActiveFromDB() {}

    public function updateSingleUserActive($username, $setValue) {}


}

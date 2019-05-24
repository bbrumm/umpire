<?php

interface IData_store_user_permission {


    public function getAllUserPermissionsFromDB();

    public function getAllUserRolesFromDB();

    public function removeUserPrivilege($username, $permission_selection_id);

    public function getUserPrivileges();

    public function addUserPrivilege($username, $permission_selection_id);

    public function updateUserRole($username, $newRoleID);

    public function updateSingleUserActive($username, $setValue);

    public function getAllUserActiveFromDB();


}
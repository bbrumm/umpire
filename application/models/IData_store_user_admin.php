<?php

interface IData_store_user_admin {

    public function getAllUsers();

    public function getRoleArray();

    public function getReportArray();

    public function getRegionArray();

    public function getUmpireDisciplineArray();

    public function getAgeGroupArray();

    public function getLeagueArray();

    public function getPermissionSelectionArray();

    public function insertNewUser(User $pUser);

    public function getAllUserPermissionsFromDB();

    public function getAllUserRolesFromDB();

    public function removeUserPrivilege($username, $permission_selection_id);

    public function getUserPrivileges();

    public function addUserPrivilege($username, $permission_selection_id);

    public function updateUserRole($username, $newRoleID);

    public function updateSingleUserActive($username, $setValue);


}
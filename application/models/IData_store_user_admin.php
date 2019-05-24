<?php

interface IData_store_user_admin {

    public function getAllUsers();

    public function insertNewUser(User $pUser);

    public function addDefaultUserPrivileges($username);

    public function getCountOfMatchingUsers(User $pUser);

}
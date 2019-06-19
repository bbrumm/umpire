<?php

class User_data_loader extends CI_Model
{
    function __construct() {
        parent::__construct();
        $this->load->model("User");
        $this->load->model("useradmin/User_permission_loader_model");
        $this->load->model("Database_store_matches");
    }

    public function getAllUsers(IData_store_user_admin $pDataStore) {
        $userArray = $pDataStore->getAllUsers();
        if (empty($userArray)) {
            throw new Exception("No users were found in the database. Please contact support.");
        }
        return $userArray;
    }

    public function getRoleArray(IData_store_reference $pDataStore) {
        $roleArray = $pDataStore->getRoleArray();
        if (empty($roleArray)) {
            throw new Exception("No roles were found in the database. Please contact support.");
        }
        return $roleArray;
    }

    public function getReportArray(IData_store_reference $pDataStore) {
        $reportArray = $pDataStore->getReportArray();
        if (empty($reportArray)) {
            throw new Exception("No reports were found in the database. Please contact support.");
        }
        return $reportArray;
    }

    public function getRegionArray(IData_store_reference $pDataStore) {
        $regionArray = $pDataStore->getRegionArray();
        if (empty($regionArray)) {
            throw new Exception("No regions were found in the database. Please contact support.");
        }
        return $regionArray;
    }

    public function getUmpireDisciplineArray(IData_store_reference $pDataStore) {
        $umpireDisciplineArray = $pDataStore->getUmpireDisciplineArray();
        if (empty($umpireDisciplineArray)) {
            throw new Exception("No umpire disciplines were found in the database. Please contact support.");
        }
        return $umpireDisciplineArray;
    }

    public function getAgeGroupArray(IData_store_reference $pDataStore) {
        $ageGroupArray = $pDataStore->getAgeGroupArray();
        if (empty($ageGroupArray)) {
            throw new Exception("No age groups were found in the database. Please contact support.");
        }
        return $ageGroupArray;
    }

    public function getLeagueArray(IData_store_reference $pDataStore) {
        $leagueArray = $pDataStore->getLeagueArray();
        if (empty($leagueArray)) {
            throw new Exception("No leagues were found in the database. Please contact support.");
        }
        return $leagueArray;
    }

    public function getPermissionSelectionArray(IData_store_reference $pDataStore) {
        $permissionSelectionArray = $pDataStore->getPermissionSelectionArray();
        if (empty($permissionSelectionArray)) {
            throw new Exception("No permission selections were found in the database. Please contact support.");
        }
        return $permissionSelectionArray;
    }

    /*
     This should return data in this format:
     Array (
         [username] => Array
           (
             [permission_selection.id] => on
             [permission_selection.id] => on
             [permission_selection.id] => on
     */
    public function getAllUserPermissionsFromDB(IData_store_user_permission $pDataStore) {
        return $pDataStore->getAllUserPermissionsFromDB();
    }


    /*
     This should return data in this format:
        Array (
            [bbeveridge] => 2
            [jhillgrove] => 2
            [gmanager] => 2
    */
    public function getAllUserRolesFromDB(IData_store_user_permission $pDataStore) {
        return $pDataStore->getAllUserRolesFromDB();
    }


    /*
     This should return data in this format:
         Array (
             [username] => on
             [username] => on
             [username] => on
     */
    public function getAllUserActiveFromDB(IData_store_user_permission $pDataStore) {
        return $pDataStore->getAllUserActiveFromDB();
    }

}
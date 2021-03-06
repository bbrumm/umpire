<?php

class User_permission_loader_model extends CI_Model {

    public function getUserFromUsername(IData_store_user $pDataStore, $pUsername) {
        $user = $pDataStore->getUserFromUsername($pUsername);
        if (isset($user)) {
            $this->setPermissionArrayForUser($pDataStore, $user);
            return $user;
        } else {
            throw new Exception("User not found for provided username.");
        }
    }

    public function setPermissionArrayForUser(IData_store_user $pDataStore, User $pUser) {
        $userPermissionArray = $pDataStore->findPermissionsForUser($pUser);
        $countNumberOfPermissions = count($userPermissionArray);
        $permissionArray = array();
        if ($countNumberOfPermissions > 0) {
            for($i=0; $i<$countNumberOfPermissions; $i++) {
                $userRolePermission = User_role_permission::createFromRow($userPermissionArray[$i]);
                $permissionArray[] = $userRolePermission;
            }
            $pUser->setPermissionArray($permissionArray);
        }

    }

    private function findPermissionInArray(User $pUser, $permissionName, $selectionName) {
        $permissionArray = $pUser->getPermissionArray();

        if (is_null($permissionArray)) {
            throw new Exception("Default user permissions for this user do not exist. Please contact support.");
        }

        $permissionFound = false;
        $permissionArrayCount = count($permissionArray);


        for($i=0; $i<$permissionArrayCount; $i++) {
            $userRolePermission = $permissionArray[$i];
            if ($this->permissionAndSelectionNamesMatchProvided($userRolePermission, $permissionName, $selectionName)) {
                //Permission found.
                $permissionFound = true;
                break;
            }
        }
        return $permissionFound;
    }
    
    private function permissionAndSelectionNamesMatchProvided($userRolePermission, $permissionName, $selectionName) {
        return ($userRolePermission->getPermissionName() == $permissionName &&
                $userRolePermission->getSelectionName() == $selectionName);
    }

    public function userHasImportFilePermission(User $pUser) {
        return $this->findPermissionInArray($pUser, 'IMPORT_FILES', 'All');
    }

    public function userCanSeeUserAdminPage(User $pUser) {
        return $this->findPermissionInArray($pUser, 'VIEW_USER_ADMIN', 'All');
    }

    public function userCanSeeDataTestPage(User $pUser) {
        return $this->findPermissionInArray($pUser, 'VIEW_DATA_TEST', 'All');
    }

    public function userCanCreatePDF(User $pUser) {
        return $this->findPermissionInArray($pUser, 'CREATE_PDF', 'All');
    }

    public function userHasSpecificPermission(User $pUser, $permissionName, $selectionName) {
        return $this->findPermissionInArray($pUser, $permissionName, $selectionName);
    }


}

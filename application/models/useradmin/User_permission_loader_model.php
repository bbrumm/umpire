<?php

class User_permission_loader_model extends CI_Model {

    public function getUserFromUsername(IData_store $pDataStore, $pUsername) {
        $user = $pDataStore->getUserFromUsername($pUsername);
        if (isset($user)) {
            $this->setPermissionArrayForUser($pDataStore, $user);
            return $user;
        } else {
            return false;
        }
    }

    public function setPermissionArrayForUser(IData_store $pDataStore, User $pUser) {

        $userPermissionArray = $pDataStore->findPermissionsForUser($pUser);
        $countNumberOfPermissions = count($userPermissionArray);

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
        $permissionFound = false;
        for($i=0; $i<count($permissionArray); $i++) {
            $userRolePermission = new User_role_permission();
            $userRolePermission = $permissionArray[$i];
            if ($userRolePermission->getPermissionName() == $permissionName &&
                $userRolePermission->getSelectionName() == $selectionName) {
                //Permission found.
                $permissionFound = true;
                break;
            }
        }
        return $permissionFound;
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

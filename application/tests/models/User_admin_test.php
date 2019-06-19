<?php

class User_admin_model_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Useradminmodel');
        $this->obj = $this->CI->Useradminmodel;
    }

    public function test_addNewUser() {
        $arrayStore = new Array_store_user_admin();
        $userData = array (
            "username" => "test1",
            "firstname" => "jacob",
            "lastname" => "small",
            "password" => "abc"
        );

        $actualResult = $this->obj->addNewUser($arrayStore, $userData);
        $expectedResult = true;
        $this->assertEquals($expectedResult, $actualResult);
    }


    public function test_addNewUser_EmptyUsername() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin();
        $userData = array (
            "username" => "",
            "firstname" => "jacob",
            "lastname" => "small",
            "password" => "abc"
        );

        $actualResult = $this->obj->addNewUser($arrayStore, $userData);
    }


    public function test_addNewUser_EmptyNames() {
        $arrayStore = new Array_store_user_admin();
        $userData = array (
            "username" => "test1",
            "firstname" => "",
            "lastname" => "",
            "password" => "abc"
            );

        $actualResult = $this->obj->addNewUser($arrayStore, $userData);
        $expectedResult = true;
        $this->assertEquals($expectedResult, $actualResult);
    }


    public function test_addNewUser_EmptyPassword() {
        $this->expectException(InvalidArgumentException::class);
        $arrayStore = new Array_store_user_admin();
        $userData = array (
            "username" => "test1",
            "firstname"=> "jacob",
            "lastname" => "small",
            "password" => ""
        );

        $actualResult = $this->obj->addNewUser($arrayStore, $userData);
    }



    public function test_TranslateUserFormActive() {
        $inputPostArray = array (
            "userRole" => array (
                "jsmith" => "admin",
                "pmac" => "regular",
                "sjon" => "regular"
            ),
            "userActive" => array (
                "pmac" => "checked",
                "sjon" => "checked"
            )
        );

        $actualArray = $this->obj->translateUserFormActive($inputPostArray);
        $expectedArray = array (
            "jsmith"=>0,
            "pmac"=>1,
            "sjon"=>1
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(1, $actualArray["pmac"]);
        $this->assertEquals(1, $actualArray["sjon"]);
        //$this->assertEmpty($actualArray["jsmith"]);
        $this->assertEquals(0, $actualArray["jsmith"]);
    }


    public function test_RemovePrivileges() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $deletedUsername = "jsmith";
        $deletedPermissionID = 4;

        $permissionsToDeleteArray = array (
            $deletedUsername=>array ($deletedPermissionID=>0)
        );

        $this->obj->removePrivileges($arrayStore, $permissionsToDeleteArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"jsmith", "permission_selection_id"=>1),
            array ("username"=>"bbrumm", "permission_selection_id"=>3),
            array ("username"=>"abc", "permission_selection_id"=>1),
            array ("username"=>"abc", "permission_selection_id"=>9)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);
        $deletedRecord[] = array (
            "username"=>$deletedUsername,
            "permission_selection_id"=>$deletedPermissionID
        );

        //print_r($actualArray);
        //print_r($deletedRecord);
        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $deletedRecord);

        //print_r($matchingRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));

    }

    public function test_RemovePrivileges_UsernameNotFound() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $deletedUsername = "mark";
        $deletedPermissionID = 2;

        $permissionsToDeleteArray = array (
            $deletedUsername=>array ($deletedPermissionID=>0)
        );

        $this->obj->removePrivileges($arrayStore, $permissionsToDeleteArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"jsmith", "permission_selection_id"=>1),
            array ("username"=>"jsmith", "permission_selection_id"=>4),
            array ("username"=>"bbrumm", "permission_selection_id"=>3),
            array ("username"=>"abc", "permission_selection_id"=>1),
            array ("username"=>"abc", "permission_selection_id"=>9)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);
        $deletedRecord[] = array (
            "username"=>$deletedUsername,
            "permission_selection_id"=>$deletedPermissionID
        );

        //print_r($actualArray);
        //print_r($deletedRecord);
        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $deletedRecord);

        //print_r($matchingRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));

    }

    public function test_RemovePrivileges_PermissionNotFound() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $deletedUsername = "bbrumm";
        $deletedPermissionID = 4;

        $permissionsToDeleteArray = array (
            $deletedUsername=>array ($deletedPermissionID=>0)
        );

        $this->obj->removePrivileges($arrayStore, $permissionsToDeleteArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"jsmith", "permission_selection_id"=>1),
            array ("username"=>"jsmith", "permission_selection_id"=>4),
            array ("username"=>"bbrumm", "permission_selection_id"=>3),
            array ("username"=>"abc", "permission_selection_id"=>1),
            array ("username"=>"abc", "permission_selection_id"=>9)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);
        $deletedRecord[] = array (
            "username"=>$deletedUsername,
            "permission_selection_id"=>$deletedPermissionID
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $deletedRecord);
        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));

    }

    public function test_RemovePrivileges_DeleteTwo() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $deletedUsername = "jsmith";
        $deletedPermissionID = 1;
        $deletedUsername2 = "abc";
        $deletedPermissionID2 = 9;

        $permissionsToDeleteArray = array (
            $deletedUsername=>array ($deletedPermissionID=>0),
            $deletedUsername2=>array ($deletedPermissionID2=>0)
        );

        $this->obj->removePrivileges($arrayStore, $permissionsToDeleteArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"jsmith", "permission_selection_id"=>4),
            array ("username"=>"bbrumm", "permission_selection_id"=>3),
            array ("username"=>"abc", "permission_selection_id"=>1)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);
        $deletedRecord[] = array (
            "username"=>$deletedUsername,
            "permission_selection_id"=>$deletedPermissionID
        );
        $deletedRecord[] = array (
            "username"=>$deletedUsername2,
            "permission_selection_id"=>$deletedPermissionID2
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $deletedRecord);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));

    }



    public function test_AddPrivileges() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $addedUsername = "jsmith";
        $addedPermissionID = 7;

        $permissionsToAddArray = array (
            $addedUsername=>array ($addedPermissionID=>1)
        );

        $this->obj->addPrivileges($arrayStore, $permissionsToAddArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"jsmith", "permission_selection_id"=>1),
            array ("username"=>"jsmith", "permission_selection_id"=>4),
            array ("username"=>"bbrumm", "permission_selection_id"=>3),
            array ("username"=>"abc", "permission_selection_id"=>1),
            array ("username"=>"abc", "permission_selection_id"=>9),
            array ("username"=>"jsmith", "permission_selection_id"=>7)
        );

        $addedRecords = array(
            array ("username"=>$addedUsername, "permission_selection_id"=>$addedPermissionID)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $addedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(1, count($matchingRecords));

    }

    public function test_AddPrivileges_UsernameNotFound() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $addedUsername = "peter";
        $addedPermissionID = 7;

        $permissionsToAddArray = array (
            $addedUsername=>array ($addedPermissionID=>1)
        );

        $this->obj->addPrivileges($arrayStore, $permissionsToAddArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"jsmith", "permission_selection_id"=>1),
            array ("username"=>"jsmith", "permission_selection_id"=>4),
            array ("username"=>"bbrumm", "permission_selection_id"=>3),
            array ("username"=>"abc", "permission_selection_id"=>1),
            array ("username"=>"abc", "permission_selection_id"=>9)
        );

        $addedRecords = array(
            array ("username"=>$addedUsername, "permission_selection_id"=>$addedPermissionID)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $addedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));

    }

    public function test_AddPrivileges_PermissionExists() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $addedUsername = "abc";
        $addedPermissionID = 1;

        $permissionsToAddArray = array (
            $addedUsername=>array ($addedPermissionID=>1)
        );

        $this->obj->addPrivileges($arrayStore, $permissionsToAddArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"jsmith", "permission_selection_id"=>1),
            array ("username"=>"jsmith", "permission_selection_id"=>4),
            array ("username"=>"bbrumm", "permission_selection_id"=>3),
            array ("username"=>"abc", "permission_selection_id"=>1),
            array ("username"=>"abc", "permission_selection_id"=>9)
        );

        $addedRecords = array(
            array ("username"=>$addedUsername, "permission_selection_id"=>$addedPermissionID)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $addedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(1, count($matchingRecords));

    }


    public function test_UpdateUserRoles() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $updatedUsername = "john";
        $updatedRoleID = 1;

        $rolesToUpdateArray = array (
            $updatedUsername=>$updatedRoleID
        );

        $this->obj->updateUserRoles($arrayStore, $rolesToUpdateArray);
        $actualArray = $arrayStore->getUserRoles();
        $expectedArray = array (
            array ("username"=>"john", "role_id"=>1),
            array ("username"=>"ringo", "role_id"=>2),
            array ("username"=>"paul", "role_id"=>3),
            array ("username"=>"george", "role_id"=>4)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $updatedRecords = array (
            array ("username"=>$updatedUsername, "role_id"=>$updatedRoleID)
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $updatedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(1, count($matchingRecords));
    }

    public function test_UpdateUserRoles_UsernameMissing() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $updatedUsername = "mark";
        $updatedRoleID = 1;

        $rolesToUpdateArray = array (
            $updatedUsername=>$updatedRoleID
        );

        $this->obj->updateUserRoles($arrayStore, $rolesToUpdateArray);
        $actualArray = $arrayStore->getUserRoles();
        $expectedArray = array (
            array ("username"=>"john", "role_id"=>1),
            array ("username"=>"ringo", "role_id"=>2),
            array ("username"=>"paul", "role_id"=>3),
            array ("username"=>"george", "role_id"=>4)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $updatedRecords = array (
            array ("username"=>$updatedUsername, "role_id"=>$updatedRoleID)
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $updatedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));
    }

    public function test_UpdateUserRoles_UsernameHasRole() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $updatedUsername = "ringo";
        $updatedRoleID = 2;

        $rolesToUpdateArray = array (
            $updatedUsername=>$updatedRoleID
        );

        $this->obj->updateUserRoles($arrayStore, $rolesToUpdateArray);
        $actualArray = $arrayStore->getUserRoles();
        $expectedArray = array (
            array ("username"=>"john", "role_id"=>1),
            array ("username"=>"ringo", "role_id"=>2),
            array ("username"=>"paul", "role_id"=>3),
            array ("username"=>"george", "role_id"=>4)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $updatedRecords = array (
            array ("username"=>$updatedUsername, "role_id"=>$updatedRoleID)
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $updatedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(1, count($matchingRecords));
    }



    public function test_UpdateUserActive() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $updatedUsername = "abc";
        $updatedActive = 1;

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"jsmith", "active"=>1),
            array ("username"=>"bbrumm", "active"=>1),
            array ("username"=>"abc", "active"=>1)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $updatedRecords = array (
            array ("username"=>$updatedUsername, "active"=>$updatedActive)
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $updatedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(1, count($matchingRecords));


    }

    public function test_UpdateUserActive_UsernameMissing() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $updatedUsername = "alex";
        $updatedActive = 1;

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"jsmith", "active"=>1),
            array ("username"=>"bbrumm", "active"=>1),
            array ("username"=>"abc", "active"=>0)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $updatedRecords = array (
            array ("username"=>$updatedUsername, "active"=>$updatedActive)
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $updatedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));


    }

    public function test_UpdateUserActive_SetInactive() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $updatedUsername = "jsmith";
        $updatedActive = 0;

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"jsmith", "active"=>0),
            array ("username"=>"bbrumm", "active"=>1),
            array ("username"=>"abc", "active"=>0)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $updatedRecords = array (
            array ("username"=>$updatedUsername, "active"=>$updatedActive)
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $updatedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(1, count($matchingRecords));


    }

    public function test_UpdateUserActive_SetInvalid() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $updatedUsername = "jsmith";
        $updatedActive = "abc";

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"jsmith", "active"=>1),
            array ("username"=>"bbrumm", "active"=>1),
            array ("username"=>"abc", "active"=>0)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $updatedRecords = array (
            array ("username"=>$updatedUsername, "active"=>$updatedActive)
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $updatedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));


    }

    public function test_UpdateUserActive_SetNull() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $updatedUsername = "bbrumm";
        $updatedActive = null;

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"jsmith", "active"=>1),
            array ("username"=>"bbrumm", "active"=>1),
            array ("username"=>"abc", "active"=>0)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $updatedRecords = array (
            array ("username"=>$updatedUsername, "active"=>$updatedActive)
        );

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $updatedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(0, count($matchingRecords));


    }

    public function test_SavePrivileges_OneUser() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $postDataArray = array (
            'userPrivilege' => array (
                "jsmith" => array (1=>"on", 4=>"on", 7=>"on"),
                "bbrumm" => array (3=>"on"),
                "abc" => array (1=>"on", 9=>"on")
            ),
            'userRole' => array (
                'jsmith'=>2,
                'bbrumm'=>1,
                'abc'=>4,
            ),
            'userActive' => array (
                'jsmith'=>1,
                'bbrumm'=>1,
                'abc'=>0,
            )
        );

        $this->obj->saveUserPrivileges($arrayStore, $postDataArray);

        $actualPrivilegeArray = $arrayStore->getAllUserPermissionsFromDB();
        $expectedPrivilegeArray = array (
            "jsmith" => array (1=>"on", 4=>"on", 7=>"on"),
            "bbrumm" => array (3=>"on"),
            "abc" => array (1=>"on", 9=>"on")
        );


        $expectedPrivilegeCount = count($expectedPrivilegeArray);
        $actualPrivilegeCount = count($actualPrivilegeArray);
        $countOfDifferentPrivileges = $this->countDifferentRecords($expectedPrivilegeArray, $actualPrivilegeArray);
        $expectedCountOfDifferences = 0;

        $this->assertEquals($expectedPrivilegeCount, $actualPrivilegeCount);
        $this->assertEquals($expectedCountOfDifferences, $countOfDifferentPrivileges);

        $actualRoleArray = $arrayStore->getAllUserRolesFromDB();
        $expectedRoleArray = array (
            'jsmith'=>2,
            'bbrumm'=>1,
            'abc'=>4,
        );

        $actualRoleCount = count($actualRoleArray);
        $expectedRoleCount = count($expectedRoleArray);
        $countOfDifferentRoles = count(array_diff($actualRoleArray, $expectedRoleArray));
        $expectedCountOfDifferentRoles = 0;

        $this->assertEquals($expectedRoleCount, $actualRoleCount);
        $this->assertEquals($expectedCountOfDifferentRoles, $countOfDifferentRoles);

        //Test Active value changes
        $actualActiveArray = $arrayStore->getUserActiveData();
        $expectedActiveArray = array (
            array('username'=>'jsmith', 'active'=>1),
            array('username'=>'bbrumm', 'active'=>1),
            array('username'=>'abc', 'active'=>0)
        );

        $actualActiveCount = count($actualActiveArray);
        $expectedActiveCount = count($expectedActiveArray);
        $arrayDiff = $arrayLibrary->findRecursiveArrayDiff($actualActiveArray, $expectedActiveArray);
        $countOfDifferentActive = $arrayLibrary->sumArrayValues($arrayDiff);
        $expectedCountOfDifferentActive = 0;

        $this->assertEquals($expectedActiveCount, $actualActiveCount);
        $this->assertEquals($expectedCountOfDifferentActive, $countOfDifferentActive);


    }

    private function countDifferentRecords($expectedArray, $actualArray) {
        $arrayLibrary = new Array_library();
        $differentRecords = $arrayLibrary->findRecursiveArrayDiff($expectedArray, $actualArray);
        $countDifferences = 0;
        foreach ($differentRecords as $key=>$value) {
            $countDifferences = $countDifferences + count($value);
        }

        return $countDifferences;

    }

    public function test_SavePrivileges_NoChanges() {
        $arrayStore = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $postDataArray = array (
            'userPrivilege' => array (
                "jsmith" => array (1=>"on", 4=>"on"),
                "bbrumm" => array (3=>"on"),
                "abc" => array (1=>"on", 9=>"on")
            ),
            'userRole' => array (
                'jsmith'=>2,
                'bbrumm'=>1,
                'abc'=>4,
            ),
            'userActive' => array (
                'jsmith'=>1,
                'bbrumm'=>1,
                'abc'=>1,
            )
        );

        $this->obj->saveUserPrivileges($arrayStore, $postDataArray);

        $actualPrivilegeArray = $arrayStore->getAllUserPermissionsFromDB();
        $expectedPrivilegeArray = array (
            "jsmith" => array (1=>"on", 4=>"on"),
            "bbrumm" => array (3=>"on"),
            "abc" => array (1=>"on", 9=>"on")
        );


        $expectedPrivilegeCount = count($expectedPrivilegeArray);
        $actualPrivilegeCount = count($actualPrivilegeArray);
        $countOfDifferentPrivileges = $this->countDifferentRecords($expectedPrivilegeArray, $actualPrivilegeArray);
        $expectedCountOfDifferences = 0;

        $this->assertEquals($expectedPrivilegeCount, $actualPrivilegeCount);
        $this->assertEquals($expectedCountOfDifferences, $countOfDifferentPrivileges);

        $actualRoleArray = $arrayStore->getAllUserRolesFromDB();
        $expectedRoleArray = array (
            'jsmith'=>2,
            'bbrumm'=>1,
            'abc'=>4,
        );

        $actualRoleCount = count($actualRoleArray);
        $expectedRoleCount = count($expectedRoleArray);
        $countOfDifferentRoles = count(array_diff($actualRoleArray, $expectedRoleArray));
        $expectedCountOfDifferentRoles = 0;

        $this->assertEquals($expectedRoleCount, $actualRoleCount);
        $this->assertEquals($expectedCountOfDifferentRoles, $countOfDifferentRoles);

        //Test Active value changes
        $actualActiveArray = $arrayStore->getUserActiveData();
        $expectedActiveArray = array (
            array('username'=>'jsmith', 'active'=>1),
            array('username'=>'bbrumm', 'active'=>1),
            array('username'=>'abc', 'active'=>1)
        );

        $actualActiveCount = count($actualActiveArray);
        $expectedActiveCount = count($expectedActiveArray);
        $arrayDiff = $arrayLibrary->findRecursiveArrayDiff($actualActiveArray, $expectedActiveArray);
        $countOfDifferentActive = $arrayLibrary->sumArrayValues($arrayDiff);
        $expectedCountOfDifferentActive = 0;

        $this->assertEquals($expectedActiveCount, $actualActiveCount);
        $this->assertEquals($expectedCountOfDifferentActive, $countOfDifferentActive);
    }

    public function test_SavePrivileges_TwoUsers() {
        $arrayStore = new Array_store_user_admin();
        $arrayStorePermission = new Array_store_user_permission();
        $arrayLibrary = new Array_library();
        $postDataArray = array (
            'userPrivilege' => array (
                "jsmith" => array (1=>"on", 4=>"on", 6=>"on"),
                "bbrumm" => array (3=>"on"),
                "abc" => array (1=>"on", 9=>"on")
            ),
            'userRole' => array (
                'jsmith'=>2,
                'bbrumm'=>1,
                'abc'=>4,
            ),
            'userActive' => array (
                'jsmith'=>0,
                'bbrumm'=>0,
                'abc'=>1,
            )
        );

        $this->obj->saveUserPrivileges($arrayStorePermission, $postDataArray);

        $actualPrivilegeArray = $arrayStorePermission->getAllUserPermissionsFromDB();
        $expectedPrivilegeArray = array (
            "jsmith" => array (1=>"on", 4=>"on", 6=>"on"),
            "bbrumm" => array (3=>"on"),
            "abc" => array (1=>"on", 9=>"on")
        );


        $expectedPrivilegeCount = count($expectedPrivilegeArray);
        $actualPrivilegeCount = count($actualPrivilegeArray);
        $countOfDifferentPrivileges = $this->countDifferentRecords($expectedPrivilegeArray, $actualPrivilegeArray);
        $expectedCountOfDifferences = 0;

        $this->assertEquals($expectedPrivilegeCount, $actualPrivilegeCount);
        $this->assertEquals($expectedCountOfDifferences, $countOfDifferentPrivileges);

        $actualRoleArray = $arrayStorePermission->getAllUserRolesFromDB();
        $expectedRoleArray = array (
            'jsmith'=>2,
            'bbrumm'=>1,
            'abc'=>4,
        );

        $actualRoleCount = count($actualRoleArray);
        $expectedRoleCount = count($expectedRoleArray);
        $countOfDifferentRoles = count(array_diff($actualRoleArray, $expectedRoleArray));
        $expectedCountOfDifferentRoles = 0;

        $this->assertEquals($expectedRoleCount, $actualRoleCount);
        $this->assertEquals($expectedCountOfDifferentRoles, $countOfDifferentRoles);

        //Test Active value changes
        $actualActiveArray = $arrayStorePermission->getUserActiveData();
        $expectedActiveArray = array (
            array('username'=>'jsmith', 'active'=>0),
            array('username'=>'bbrumm', 'active'=>0),
            array('username'=>'abc', 'active'=>1)
        );

        $actualActiveCount = count($actualActiveArray);
        $expectedActiveCount = count($expectedActiveArray);
        $arrayDiff = $arrayLibrary->findRecursiveArrayDiff($actualActiveArray, $expectedActiveArray);
        $countOfDifferentActive = $arrayLibrary->sumArrayValues($arrayDiff);
        $expectedCountOfDifferentActive = 0;

        $this->assertEquals($expectedActiveCount, $actualActiveCount);
        $this->assertEquals($expectedCountOfDifferentActive, $countOfDifferentActive);
    }

}

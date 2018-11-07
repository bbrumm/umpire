<?php

class User_admin_model_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Useradminmodel');
        $this->obj = $this->CI->Useradminmodel;
    }

    public function test_GetAllUsers() {

        $arrayStore = new Array_store_user_admin();

        $actualUserArray = $this->obj->getAllUsers($arrayStore);
        $user1 = User::createUserFromNameAndRole(1, 'username1', 'john', 'smith', 'super', 1, NULL);
        $user2 = User::createUserFromNameAndRole(2, 'username2', 'sue', 'jones', 'regular', 1, NULL);
        $user3 = User::createUserFromNameAndRole(4, 'username4', 'mark', 'brown', 'regular', 1, NULL);
        $user4 = User::createUserFromNameAndRole(8, 'username8', 'jane', 'pick', 'admin', 1, NULL);

        $expectedUserArray = array ($user1, $user2, $user3, $user4);
        $expectedCount = count($expectedUserArray);
        $actualCount = count($actualUserArray);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals($expectedUserArray[0]->getUsername(), $actualUserArray[0]->getUsername());
        $this->assertEquals($expectedUserArray[1]->getFirstName(), $actualUserArray[1]->getFirstName());
        $this->assertEquals($expectedUserArray[2]->getLastName(), $actualUserArray[2]->getLastName());

    }

    public function test_GetAllUsersArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin_empty();
        $actualArray= $this->obj->getAllUsers($arrayStore);
    }




    public function test_GetRoleArray() {
        $arrayStore = new Array_store_user_admin();
        $actualRoleArray = $this->obj->getRoleArray($arrayStore);
        $expectedRoleArray = array (
            array("id"=>1, "role_name"=>"Administrator", "display_order"=>1),
            array("id"=>2, "role_name"=>"Super User", "display_order"=>2),
            array("id"=>3, "role_name"=>"Regular User", "display_order"=>3)
        );

        $this->assertEquals(count($expectedRoleArray ), count($actualRoleArray ));
        $this->assertEquals($expectedRoleArray[0]["role_name"], $actualRoleArray [0]["role_name"]);
        $this->assertEquals($expectedRoleArray[1]["id"], $actualRoleArray [1]["id"]);
        $this->assertEquals($expectedRoleArray[2]["display_order"], $actualRoleArray [2]["display_order"]);
    }


    public function test_GetRoleArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin_empty();
        $actualArray= $this->obj->getRoleArray($arrayStore);

    }



    public function test_GetReportArray() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getReportArray($arrayStore);
        $expectedArray = array (
            array("report_table_id"=>1, "report_title"=>"Report 1"),
            array("report_table_id"=>2, "report_title"=>"Report 2"),
            array("report_table_id"=>3, "report_title"=>"Report 3")
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray[0]["report_table_id"], $actualArray[0]["report_table_id"]);
        $this->assertEquals($expectedArray[1]["report_title"], $actualArray[1]["report_title"]);

    }

    public function test_GetReportArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin_empty(); //TODO create new array_store with empty data
        $actualArray= $this->obj->getReportArray($arrayStore);

    }


    public function test_GetRegionArray() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getRegionArray($arrayStore);
        $expectedArray = array (
            array("id"=>1, "region"=>"Geelong"),
            array("id"=>2, "region"=>"Colac")
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray[0]["id"], $actualArray[0]["id"]);


    }

    public function test_GetRegionArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin_empty(); //TODO create new array_store with empty data
        $actualArray= $this->obj->getRegionArray($arrayStore);
    }

    public function test_GetUmpireDisciplineArray() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getUmpireDisciplineArray($arrayStore);
        $expectedArray = array (
            array("id"=>1, "umpire_type_name"=>"Field"),
            array("id"=>2, "umpire_type_name"=>"Boundary"),
            array("id"=>3, "umpire_type_name"=>"Goal")
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray[0]["id"], $actualArray[0]["id"]);


    }

    public function test_GetUmpireDisciplineArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin_empty();
        $actualArray= $this->obj->getUmpireDisciplineArray($arrayStore);
    }


    public function test_GetAgeGroupArray() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getAgeGroupArray($arrayStore);
        $expectedArray = array (
            array("id"=>1, "umpire_type_name"=>"Under 18"),
            array("id"=>2, "umpire_type_name"=>"Under 16"),
            array("id"=>3, "umpire_type_name"=>"Senior")
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray[0]["id"], $actualArray[0]["id"]);


    }

    public function test_GetAgeGroupArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin_empty();
        $actualArray= $this->obj->getAgeGroupArray($arrayStore);
    }

    public function test_GetLeagueArray() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getLeagueArray($arrayStore);
        $expectedArray = array (
            array("id"=>1, "short_league_name"=>"BFL"),
            array("id"=>2, "short_league_name"=>"GFL"),
            array("id"=>3, "short_league_name"=>"GDFL")
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray[0]["id"], $actualArray[0]["id"]);


    }

    public function test_GetLeagueArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin_empty();
        $actualArray= $this->obj->getLeagueArray($arrayStore);
    }




    public function test_GetPermissionArray() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getPermissionSelectionArray($arrayStore);
        $expectedArray = array (
            array("id"=>1, "permission_id"=>1, "category" => "something", "selection_name" => "yes"),
            array("id"=>2, "permission_id"=>1, "category" => "else", "selection_name" => "two"),
            array("id"=>3, "permission_id"=>5, "category" => "more", "selection_name" => "blah")
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray[0]["id"], $actualArray[0]["id"]);

    }

    public function test_GetPermissionArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_user_admin_empty();
        $actualArray= $this->obj->getPermissionSelectionArray($arrayStore);
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


    public function test_GetAllUserPermissionsFromDB() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getAllUserPermissionsFromDB($arrayStore);
        $expectedArray = array (
            "jsmith" => array (1=>"on", 4=>"on"),
            "bbrumm" => array (3=>"on"),
            "abc" => array (1=>"on", 9=>"on")
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray["jsmith"][1], $actualArray["jsmith"][1]);

    }


    public function test_GetAllUserRolesFromDB() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getAllUserRolesFromDB($arrayStore);
        $expectedArray = array (
            "jsmith" => 2,
            "bbrumm" => 1,
            "abc" => 4
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray["jsmith"], $actualArray["jsmith"]);

    }




    public function test_GetAllUserActiveFromDB() {
        $arrayStore = new Array_store_user_admin();
        $actualArray= $this->obj->getAllUserActiveFromDB($arrayStore);
        $expectedArray = array (
            "jsmith22" => "on",
            "bbrumm1" => "on",
            "abc9" => "on"
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray["jsmith22"], $actualArray["jsmith22"]);

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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $deletedUsername = "john";
        $deletedPermissionID = 2;

        $permissionsToDeleteArray = array (
            $deletedUsername=>array ($deletedPermissionID=>0)
        );

        $this->obj->removePrivileges($arrayStore, $permissionsToDeleteArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"john", "permission_selection_id"=>2),
            array ("username"=>"ringo", "permission_selection_id"=>6),
            array ("username"=>"george", "permission_selection_id"=>1),
            array ("username"=>"george", "permission_selection_id"=>2)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $deletedUsername = "mark";
        $deletedPermissionID = 2;

        $permissionsToDeleteArray = array (
            $deletedUsername=>array ($deletedPermissionID=>0)
        );

        $this->obj->removePrivileges($arrayStore, $permissionsToDeleteArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"john", "permission_selection_id"=>2),
            array ("username"=>"john", "permission_selection_id"=>5),
            array ("username"=>"ringo", "permission_selection_id"=>6),
            array ("username"=>"george", "permission_selection_id"=>1),
            array ("username"=>"george", "permission_selection_id"=>2)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $deletedUsername = "george";
        $deletedPermissionID = 4;

        $permissionsToDeleteArray = array (
            $deletedUsername=>array ($deletedPermissionID=>0)
        );

        $this->obj->removePrivileges($arrayStore, $permissionsToDeleteArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"john", "permission_selection_id"=>2),
            array ("username"=>"john", "permission_selection_id"=>5),
            array ("username"=>"ringo", "permission_selection_id"=>6),
            array ("username"=>"george", "permission_selection_id"=>1),
            array ("username"=>"george", "permission_selection_id"=>2)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $deletedUsername = "george";
        $deletedPermissionID = 1;
        $deletedUsername2 = "john";
        $deletedPermissionID2 = 5;

        $permissionsToDeleteArray = array (
            $deletedUsername=>array ($deletedPermissionID=>0),
            $deletedUsername2=>array ($deletedPermissionID2=>0)
        );

        $this->obj->removePrivileges($arrayStore, $permissionsToDeleteArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"john", "permission_selection_id"=>2),
            array ("username"=>"ringo", "permission_selection_id"=>6),
            array ("username"=>"george", "permission_selection_id"=>2)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $addedUsername = "john";
        $addedPermissionID = 7;

        $permissionsToAddArray = array (
            $addedUsername=>array ($addedPermissionID=>1)
        );

        $this->obj->addPrivileges($arrayStore, $permissionsToAddArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"john", "permission_selection_id"=>2),
            array ("username"=>"john", "permission_selection_id"=>5),
            array ("username"=>"ringo", "permission_selection_id"=>6),
            array ("username"=>"george", "permission_selection_id"=>1),
            array ("username"=>"george", "permission_selection_id"=>2),
            array ("username"=>"john", "permission_selection_id"=>7),
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $addedUsername = "peter";
        $addedPermissionID = 7;

        $permissionsToAddArray = array (
            $addedUsername=>array ($addedPermissionID=>1)
        );

        $this->obj->addPrivileges($arrayStore, $permissionsToAddArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"john", "permission_selection_id"=>2),
            array ("username"=>"john", "permission_selection_id"=>5),
            array ("username"=>"ringo", "permission_selection_id"=>6),
            array ("username"=>"george", "permission_selection_id"=>1),
            array ("username"=>"george", "permission_selection_id"=>2)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $addedUsername = "john";
        $addedPermissionID = 2;

        $permissionsToAddArray = array (
            $addedUsername=>array ($addedPermissionID=>1)
        );

        $this->obj->addPrivileges($arrayStore, $permissionsToAddArray);
        $actualArray = $arrayStore->getUserPrivileges();
        $expectedArray = array (
            array ("username"=>"john", "permission_selection_id"=>2),
            array ("username"=>"john", "permission_selection_id"=>5),
            array ("username"=>"ringo", "permission_selection_id"=>6),
            array ("username"=>"george", "permission_selection_id"=>1),
            array ("username"=>"george", "permission_selection_id"=>2),
            array ("username"=>"john", "permission_selection_id"=>2)
        );

        $addedRecords = array(
            array ("username"=>$addedUsername, "permission_selection_id"=>$addedPermissionID)
        );

        $expectedCount = count($expectedArray);
        $actualCount = count($actualArray);

        $matchingRecords = $arrayLibrary->findMultiArrayDiff($actualArray, $addedRecords);

        $this->assertEquals($expectedCount, $actualCount);
        $this->assertEquals(2, count($matchingRecords));

    }


    public function test_UpdateUserRoles() {
        $arrayStore = new Array_store_user_admin();
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
        $arrayStore = new Array_store_user_admin();
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
        $arrayStore = new Array_store_user_admin();
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $updatedUsername = "ringo";
        $updatedActive = 1;

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"john", "active"=>1),
            array ("username"=>"ringo", "active"=>0),
            array ("username"=>"paul", "active"=>0),
            array ("username"=>"george", "active"=>1)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $updatedUsername = "alex";
        $updatedActive = 1;

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"john", "active"=>1),
            array ("username"=>"ringo", "active"=>0),
            array ("username"=>"paul", "active"=>0),
            array ("username"=>"george", "active"=>1)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $updatedUsername = "john";
        $updatedActive = 0;

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"john", "active"=>0),
            array ("username"=>"ringo", "active"=>0),
            array ("username"=>"paul", "active"=>0),
            array ("username"=>"george", "active"=>1)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $updatedUsername = "john";
        $updatedActive = "abc";

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"john", "active"=>1),
            array ("username"=>"ringo", "active"=>0),
            array ("username"=>"paul", "active"=>0),
            array ("username"=>"george", "active"=>1)
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
        $arrayStore = new Array_store_user_admin();
        $arrayLibrary = new Array_library();
        $updatedUsername = "john";
        $updatedActive = null;

        $usersToUpdateArray = array (
            $updatedUsername=>$updatedActive
        );

        $this->obj->updateUserActive($arrayStore, $usersToUpdateArray);
        $actualArray = $arrayStore->getUserActiveData();
        $expectedArray = array (
            array ("username"=>"john", "active"=>1),
            array ("username"=>"ringo", "active"=>0),
            array ("username"=>"paul", "active"=>0),
            array ("username"=>"george", "active"=>1)
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

}

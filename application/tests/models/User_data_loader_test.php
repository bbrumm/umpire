<?php

class User_data_loader_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('User_data_loader');
        $this->obj = $this->CI->User_data_loader;
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
        $arrayStore = new Array_store_reference();
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
        $arrayStore = new Array_store_reference_empty();
        $actualArray= $this->obj->getRoleArray($arrayStore);

    }



    public function test_GetReportArray() {
        $arrayStore = new Array_store_reference();
        $actualArray= $this->obj->getReportArray($arrayStore);
        $expectedArray = array (
            array("report_id"=>1, "report_title"=>"01 - Umpires and Clubs"),
            array("report_id"=>2, "report_title"=>"02 - Umpire Names by League"),
            array("report_id"=>3, "report_title"=>"03 - Summary")
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray[0]["report_id"], $actualArray[0]["report_id"]);
        $this->assertEquals($expectedArray[1]["report_title"], $actualArray[1]["report_title"]);

    }

    public function test_GetReportArrayEmpty() {
        $this->expectException(Exception::class);
        $arrayStore = new Array_store_reference_empty(); //TODO create new array_store with empty data
        $actualArray= $this->obj->getReportArray($arrayStore);

    }


    public function test_GetRegionArray() {
        $arrayStore = new Array_store_reference();
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
        $arrayStore = new Array_store_reference_empty(); //TODO create new array_store with empty data
        $actualArray= $this->obj->getRegionArray($arrayStore);
    }

    public function test_GetUmpireDisciplineArray() {
        $arrayStore = new Array_store_reference();
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
        $arrayStore = new Array_store_reference_empty();
        $actualArray= $this->obj->getUmpireDisciplineArray($arrayStore);
    }


    public function test_GetAgeGroupArray() {
        $arrayStore = new Array_store_reference();
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
        $arrayStore = new Array_store_reference_empty();
        $actualArray= $this->obj->getAgeGroupArray($arrayStore);
    }

    public function test_GetLeagueArray() {
        $arrayStore = new Array_store_reference();
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
        $arrayStore = new Array_store_reference_empty();
        $actualArray= $this->obj->getLeagueArray($arrayStore);
    }




    public function test_GetPermissionArray() {
        $arrayStore = new Array_store_reference();
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
        $arrayStore = new Array_store_reference_empty();
        $actualArray= $this->obj->getPermissionSelectionArray($arrayStore);
    }



    public function test_GetAllUserPermissionsFromDB() {
        $arrayStore = new Array_store_user_permission();
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
        $arrayStore = new Array_store_user_permission();
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
        $arrayStore = new Array_store_user_permission();
        $actualArray= $this->obj->getAllUserActiveFromDB($arrayStore);
        $expectedArray = array (
            "jsmith22" => "on",
            "bbrumm1" => "on",
            "abc9" => "on"
        );

        $this->assertEquals(count($expectedArray ), count($actualArray));
        $this->assertEquals($expectedArray["jsmith22"], $actualArray["jsmith22"]);

    }


}
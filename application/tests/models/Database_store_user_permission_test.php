<?php
class Database_store_user_permission_test extends TestCase
{

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->database();
        $this->CI->load->model('data_store/Database_store_user_permission');
        $this->obj = $this->CI->Database_store_user_permission;
    }

    private function setSessionData() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
    }

    public function test_AddUserPrivilege_Valid() {
        $this->setSessionData();
        //Input
        $sampleUsername = 'testuser_addpriv';
        $sampleEmail = 'test@test.com';
        $sampleUserID = 102;
        $permissionSelectionID = 1;

        //Delete test data
        $queryString = "DELETE FROM umpire_users WHERE user_name = '". $sampleUsername  ."'";
        $this->CI->db->query($queryString);

        $queryString = "DELETE FROM user_permission_selection WHERE user_id = ". $sampleUserID;
        $this->CI->db->query($queryString);

        //Insert sample data
        $queryString = "INSERT INTO umpire_users(id, user_name, user_email, activation_id, user_password)
    VALUES (". $sampleUserID .", '". $sampleUsername  ."', '". $sampleEmail ."', NULL, 'abc')";
        $this->CI->db->query($queryString);



        //Check log before
        $queryStringLogLookup = "SELECT COUNT(*) AS rc FROM log_privilege_changes 
    WHERE username_changed = '". $sampleUsername ."' 
    AND privilege_changed = ". $permissionSelectionID . "
    AND privilege_action = 1";
        $query = $this->CI->db->query($queryStringLogLookup);
        $queryResult = $query->result_array();
        $logCountBefore = $queryResult[0]['rc'];

        //Run
        $this->obj->addUserPrivilege($sampleUsername, $permissionSelectionID);

        //Select
        $queryString = "SELECT COUNT(*) AS rc FROM user_permission_selection 
      WHERE user_id = ". $sampleUserID ." AND permission_selection_id = ". $permissionSelectionID;
        $query = $this->CI->db->query($queryString);
        $queryResult = $query->result_array();
        $actualCount = $queryResult[0]['rc'];
        $expectedCount = 1;

        //Assert
        $this->assertEquals($expectedCount, $actualCount);

        //Check log after
        $query = $this->CI->db->query($queryStringLogLookup);
        $queryResult = $query->result_array();
        $actualLogCount = $queryResult[0]['rc'];
        $expectedLogCount = $logCountBefore + 1;

        //Assert
        $this->assertEquals($expectedLogCount, $actualLogCount);

        //Delete test data
        $queryString = "DELETE FROM umpire_users WHERE user_name = '". $sampleUsername  ."'";
        $this->CI->db->query($queryString);

        $queryString = "DELETE FROM user_permission_selection WHERE user_id = ". $sampleUserID;
        $this->CI->db->query($queryString);


    }

    public function test_RemoveUserPrivilege_Valid() {
        $this->setSessionData();
        $permissionSelectionID = 10001;
        $sampleUserID = 25; //username of bbtest2
        $sampleUsername = "bbtest2";
        //Delete test data
        $queryString = "DELETE FROM user_permission_selection WHERE user_id = ". $sampleUserID ." AND id = ". $permissionSelectionID;
        $this->CI->db->query($queryString);

        //Insert sample data
        $queryString = "INSERT INTO user_permission_selection (id, user_id, permission_selection_id)
        VALUES (". $permissionSelectionID.", ". $sampleUserID .", 999)";
        $this->CI->db->query($queryString);

        //Check log before
        $queryStringLogLookup = "SELECT COUNT(*) AS rc FROM log_privilege_changes 
        WHERE username_changed = '". $sampleUsername ."' 
        AND privilege_changed = ". $permissionSelectionID . "
        AND privilege_action = 1";
        $query = $this->CI->db->query($queryStringLogLookup);
        $queryResult = $query->result_array();
        $logCountBefore = $queryResult[0]['rc'];


        //Run
        $this->obj->removeUserPrivilege($sampleUsername, $permissionSelectionID);

        //Select
        $queryString = "SELECT COUNT(*) AS rc FROM user_permission_selection 
            WHERE user_id = ". $sampleUserID ." AND permission_selection_id = ". $permissionSelectionID;
        $query = $this->CI->db->query($queryString);
        $queryResult = $query->result_array();
        $actualCount = $queryResult[0]['rc'];
        $expectedCount = 0;

        //Assert
        $this->assertEquals($expectedCount, $actualCount);

        //Check log after
        $query = $this->CI->db->query($queryStringLogLookup);
        $queryResult = $query->result_array();
        $actualLogCount = $queryResult[0]['rc'];
        $expectedLogCount = $logCountBefore + 1;

        //Assert
        $this->assertEquals($expectedCount, $actualCount);

        //Delete test data
        $queryString = "DELETE FROM user_permission_selection WHERE user_id = ". $sampleUserID ." AND permission_selection_id = ". $permissionSelectionID;
        $this->CI->db->query($queryString);

    }

    public function test_AddUserPrivilege_Exception() {
        $this->expectException(Exception::class);
        //Username that does not exist
        $sampleUsername = "some username that does not exist goes here";
        $this->obj->addUserPrivilege($sampleUsername, 998);
    }
}
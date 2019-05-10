<?php
class Database_store_user_admin_test extends TestCase {

  public function setUp() {
    $this->resetInstance();
    $this->CI->load->database();
    $this->CI->load->model('Database_store_user_admin');
    $this->obj = $this->CI->Database_store_user_admin;
  }
  
  public function test_AddUserPrivilege_Valid() {
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
    $this->assertEquals($expectedCount, $actualCount);
    
    //Delete test data
    $queryString = "DELETE FROM umpire_users WHERE user_name = '". $sampleUsername  ."'";
    $this->CI->db->query($queryString);

      $queryString = "DELETE FROM user_permission_selection WHERE user_id = ". $sampleUserID;
      $this->CI->db->query($queryString);
  
  
  }
  
  public function test_InsertNewUser_Exception() {
    $this->expectException(Exception::class);
    //Long username
    $sampleUsername = "bbrumm some long name some long name some long name some long name ".
      "some long name some long name some long name some long name some long name ".
      "some long name some long name some long name some long name some long name ".
      "some long name some long name some long name some long name some long name ".
      "some long name some long name some long name some long name some long name";
    $user = User::createUserFromNameAndPW($sampleUsername, 'FN', 'LN', 'abc');

    $this->obj->insertNewUser($user);

  }
  
  public function test_RemoveUserPrivilege_Valid() {
    $permissionSelectionID = 10001;
    $sampleUserID = 25; //username of bbtest2
    $sampleUsername = "bbtest2";

    //Insert sample data
    $queryString = "INSERT INTO user_permission_selection (id, user_id, permission_selection_id)
    VALUES (". $permissionSelectionID.", ". $sampleUserID .", 31)";
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
    $expectedCount = 1;
    
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

  
  
  
 }

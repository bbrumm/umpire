<?php
class Database_store_user_admin_test extends TestCase {

  public function setUp() {
    $this->resetInstance();
    $this->CI->load->database();
    $this->CI->load->model('Database_store_user_admin');
    $this->obj = $this->CI->Database_store_user_admin;
  }
  
  public function test_AddUserPrvilege_Valid() {
    //Input
    $sampleUsername = 'bbrumm_test';
    $sampleUserID = 100
    $permissionSelectionID = 1; 
    
    //Insert sample data
    $queryString = "INSERT INTO umpire_users(id, user_name, user_email, activation_id)
    VALUES (". $sampleUserID .", '". $sampleUsername  ."', NULL, NULL)";
    
    
    
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
    $actualCount = $queryResult[0]['rc'];
    $expectedCount = logCountBefore + 1;
   
    //Assert
    $this->assertEquals($expectedCount, $actualCount);
    
    //Delete test data
    $queryString = "DELETE FROM umpire_users WHERE user_name = '". $sampleUsername  ."'";
    $this->CI->db->query($queryString);
  
  
  }
  
  
 }
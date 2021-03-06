<?php
class Database_store_user_test extends TestCase {

  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('data_store/Database_store_user');
    $this->CI->load->model('User');
    $this->CI->load->database('default', true);
    $this->obj = $this->CI->Database_store_user;
  }

  public function test_GetUserFromUsername_Valid() {
    $inputUsername= 'bbrumm';
    $expectedUser = $this->obj->getUserFromUsername($inputUsername);
    $this->assertInstanceOf(User::class, $expectedUser);
  }

  public function test_GetUserFromUsername_UsernameDoesNotExist() {
    $this->expectException(Exception::class);
    $inputUsername= 'something that does not exist';
    $expectedUser = $this->obj->getUserFromUsername($inputUsername);
  }

  public function test_CheckUserExistsForReset_Valid() {
    $inputUsername= 'bbrumm';
    $inputEmail = 'brummthecar@gmail.com';
    $newUser = User::createUserFromNameAndEmail($inputUsername, 'Ben', 'Brumm', $inputEmail);
    $expected = true;
    $actual = $this->obj->checkUserExistsForReset($newUser);
    $this->assertEquals($expected, $actual);
  }
  
  public function test_LogPasswordResetRequest_Valid() {
    //Input
    $requestData = array(
    'request_datetime'=>'2019-01-01 01:23:45',
    'activation_id'=>'ABC123 Sample',
    'ip_address'=>'192093',
    'user_name'=>'test_username',
    'email_address'=>'something@test.com',
    );
    $expected = true;

    $actual = $this->obj->logPasswordResetRequest($requestData);

    $this->assertEquals($expected, $actual);

  }
  
  public function test_LogPasswordResetRequest_NullDatetime() {
    $this->expectException(Exception::class);
    
    //Input
    $requestData = array(
    'request_datetime'=>null,
    'activation_id'=>'ABC123 Sample',
    'ip_address'=>'192093',
    'user_name'=>'test_username',
    'email_address'=>'something@test.com',
    );

    $dbStoreUser = new Database_store_user();
    $actual = $dbStoreUser->logPasswordResetRequest($requestData);
  }

  public function test_LogPasswordResetRequest_NullActivationID() {
    //Input
    $requestData = array(
    'request_datetime'=>'2019-01-01 01:23:45',
    'activation_id'=>null,
    'ip_address'=>'192093',
    'user_name'=>'test_username',
    'email_address'=>'something@test.com',
    );
    $expected = true;

    $dbStoreUser = new Database_store_user();
    $actual = $dbStoreUser->logPasswordResetRequest($requestData);

    $this->assertEquals($expected, $actual);

  }

  public function test_LogPasswordResetRequest_NullIP() {
    //Input
    $requestData = array(
    'request_datetime'=>'2019-01-01 01:23:45',
    'activation_id'=>'ABC123 Sample',
    'ip_address'=>null,
    'user_name'=>'test_username',
    'email_address'=>'something@test.com',
    );
    $expected = true;

    $dbStoreUser = new Database_store_user();
    $actual = $dbStoreUser->logPasswordResetRequest($requestData);

    $this->assertEquals($expected, $actual);

  }

  public function test_LogPasswordResetRequest_NullUsername() {
    $this->expectException(Exception::class);
    
    //Input
    $requestData = array(
    'request_datetime'=>'2019-01-01 01:23:45',
    'activation_id'=>'ABC123 Sample',
    'ip_address'=>'192093',
    'user_name'=>null,
    'email_address'=>'something@test.com',
    );

    $dbStoreUser = new Database_store_user();
    $actual = $dbStoreUser->logPasswordResetRequest($requestData);
  }


  public function test_LogPasswordResetRequest_NullEmail() {
    $this->expectException(Exception::class);
    
    //Input
    $requestData = array(
    'request_datetime'=>'2019-01-01 01:23:45',
    'activation_id'=>'ABC123 Sample',
    'ip_address'=>'192093',
    'user_name'=>'test_username',
    'email_address'=>null
    );

    $dbStoreUser = new Database_store_user();
    $actual = $dbStoreUser->logPasswordResetRequest($requestData);

  }
  
  public function testStoreActivationID_Valid() {
    $sampleUsername = "test_storeactid";
    $sampleEmail = "test@test.com";
    $sampleActivationID = "123456";
    $newActivationID = "987654";

      //Delete test data
      $queryString = "DELETE FROM umpire_users WHERE user_name = '". $sampleUsername  ."'";
      $this->CI->db->query($queryString);

    //Insert sample data
    $queryString = "INSERT INTO umpire_users(id, user_name, user_email, activation_id, user_password)
    VALUES (101, '". $sampleUsername  ."', '". $sampleEmail  ."', '". $sampleActivationID  ."', 'abc123')";

    $this->CI->db->query($queryString);

    //Input
    $user = User::createUserFromNameAndEmail($sampleUsername, 'Test', 'Test', $sampleEmail);

    //Store Activation ID
    $this->obj->storeActivationID($user, $newActivationID);

    //Get Activation ID
    $queryString = "SELECT activation_id FROM umpire_users WHERE user_name = '". $sampleUsername ."'";
    $query = $this->CI->db->query($queryString);
    $queryResult = $query->result_array();
    $actualActivationID = $queryResult[0]['activation_id'];
    $expectedActivationID = $newActivationID;

    //Assert
    $this->assertEquals($expectedActivationID, $actualActivationID);


    //Delete test data
    $queryString = "DELETE FROM umpire_users WHERE user_name = '". $sampleUsername  ."'";
    $this->CI->db->query($queryString);


  }

  public function test_CreateUserFromActivationID_NoUsers() {
    $this->expectException(Exception::class);
    //Input
    $activationID = "JKBIRF29370234n";
    $user = $this->obj->createUserFromActivationID($activationID);
   }
  
  
  
  
}

<?php
class Database_store_user_test extends TestCase {

  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('Database_store_user');
    $this->CI->load->model('User');
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
    $expected = 1;

    $actual = $this->obj->logPasswordResetRequest($requestData);

    $this->assertEquals($expected, $actual);

  }
  
  
}

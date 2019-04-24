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
    $newUser = User::createUserFromNameAndEmail($inputUsername, $inputEmail);
    $expected = true
    $actual = $this->obj->checkUserExistsForReset($newUser);
    $this->assertEquals($expected, $actual);
  }
  
  
}

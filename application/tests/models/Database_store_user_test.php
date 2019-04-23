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

  public function test_LoadAllGroupingStructures_ReportDoesNotExist() {
    $this->expectException(Exception::class);
    $inputUsername= 'something that does not exist';
    $expectedUser = $this->obj->getUserFromUsername($inputUsername);
  }

}

<?php
class Database_store_user_admin_test extends TestCase {

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->database();
        $this->CI->load->model('Database_store_user_admin');
        $this->obj = $this->CI->Database_store_user_admin;
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


  
 }

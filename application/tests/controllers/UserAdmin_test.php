<?php

class UserAdmin_test extends TestCase
{

    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);

    }

    public function test_LoadPage() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['UserAdmin', 'index']);
        $expectedHeader = "<h2>User Administration</h2>";
        $this->assertContains($expectedHeader, $output);
    }

    public function test_LoadPage_NotLoggedIn() {
        $sessionArray['logged_in'] = false;
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['UserAdmin', 'index']);
        $this->assertRedirect('login');

    }

    public function test_LoadPage_WithMessage() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $message = "This is a test message.";

        $output = $this->request('POST', ['UserAdmin', 'loadPage', $message]);
        $expectedHeader = "<h2>User Administration</h2>";
        $this->assertContains($expectedHeader, $output);

        $this->assertContains($message, $output);
    }

    public function test_AddNewUser_Valid() {
        //Delete any users with this name first
        $newUserName = 'bbrummtest_newvalid';
        $queryString = "DELETE FROM umpire_users WHERE user_name = '". $newUserName ."'";
        $query = $this->dbLocal->query($queryString);


        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $postArray = array(
            'username'=>$newUserName,
            'password'=>'1234',
            'firstname'=>'bb1F',
            'lastname'=>'bb1L'
        );

        $output = $this->request('POST', ['UserAdmin', 'addNewUser'], $postArray);
        $expectedHeader = "<h2>User Administration</h2>";
        $this->assertContains($expectedHeader, $output);
        $message = "User bbrummtest_newvalid successfully added.";

        $this->assertContains($message, $output);

    }


    public function test_AddNewUser_AlreadyExists() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Username already exists.");
        //Add a user with this name first
        $newUserName = 'bbrummtest_newvalid';
        $newFirstName = 'bb1F';
        $newLastName = 'bb1L';
        $queryString = "INSERT INTO umpire_users (user_name, first_name, last_name)
        VALUES ('".$newUserName."', '". $newFirstName ."', '". $newLastName ."');";
        $query = $this->dbLocal->query($queryString);


        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $postArray = array(
            'username'=>$newUserName,
            'password'=>'1234',
            'firstname'=>$newFirstName,
            'lastname'=>$newLastName
        );

        //Load page
        try {
            $output = $this->request('POST', ['UserAdmin', 'addNewUser'], $postArray);
        } finally {
            $output = ob_get_clean();
        }

    }

    public function test_SaveUserPrivileges() {

        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $postDataArray = array (
            'userPrivilege' => array (
                'bbrummtest' => array (1=>"on")
            ),
            'userRole' => array (
                'bbrummtest'=>2
            ),
            'userActive' => array (
                'bbrummtest'=>1
            )
        );

        //Load page
        $output = $this->request('POST', ['UserAdmin', 'saveUserPrivileges'], $postDataArray);
        $expectedHeader = "<h2>User Administration</h2>";
        $this->assertContains($expectedHeader, $output);
        $message = "User privileges updated.";

        $this->assertContains($message, $output);

    }
}

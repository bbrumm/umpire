<?php

class UpdateProfile_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }

    public function test_DataValid() {
        //$sessionArray['logged_in'] = true;
        //$sessionArray['username'] = 'bbrummtest';
        $sessionArray['logged_in'] = array('username'=>'bbrummtest');

        $this->CI->session->set_userdata($sessionArray);
        $output = $this->request('POST', ['UpdateProfile', 'index']);
        $expected = "<h2>Update Profile</h2>";

        $this->assertContains($expected, $output);
        //$this->assertRedirect('home');

    }

    public function test_UpdateEmail_Valid() {
        $postArray = array(
            'username'=>'bbrummtest',
            'email_address'=>'bbrummtest@gmail.com'
        );
        $sessionArray['logged_in'] = array('username'=>'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['UpdateProfile', 'updateEmail'], $postArray);
        $expected = "Email address updated successfully.";
        $this->assertContains($expected, $output);

        //Reset email address
        $queryString = "UPDATE umpire_users SET user_email = 'brummthecar@gmail.com' WHERE user_name = 'bbrummtest'";
        $query = $this->dbLocal->query($queryString);

    }

    public function test_UpdateEmail_NotValid() {
        $postArray = array(
            'username'=>'bbrummtest',
            'email_address'=>'bbrummtestsomething'
        );
        $sessionArray['logged_in'] = array('username'=>'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['UpdateProfile', 'updateEmail'], $postArray);
        $expected = "Email address updated successfully.";
        $this->assertContains($expected, $output);

        //Reset email address
        $queryString = "UPDATE umpire_users SET user_email = 'brummthecar@gmail.com' WHERE user_name = 'bbrummtest'";
        $query = $this->dbLocal->query($queryString);

    }

    public function test_UpdateEmail_Empty() {
        $postArray = array(
            'username'=>'bbrummtest',
            'email_address'=>''
        );
        $sessionArray['logged_in'] = array('username'=>'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['UpdateProfile', 'updateEmail'], $postArray);
        $expected = "Email address updated successfully.";
        $this->assertContains($expected, $output);

        //Reset email address
        $queryString = "UPDATE umpire_users SET user_email = 'brummthecar@gmail.com' WHERE user_name = 'bbrummtest'";
        $query = $this->dbLocal->query($queryString);

    }


}
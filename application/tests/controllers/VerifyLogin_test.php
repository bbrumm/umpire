<?php

class VerifyLogin_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->controller('VerifyLogin');
        //$this->obj = new VerifyLogin();
        $_POST = array();



    }

    public function testIndex_CorrectDetails() {
        //Set POST array
        $postArray = array(
            'username'=>'bbrummtest',
            'password'=>'test'
        );
        $output = $this->request('POST', ['VerifyLogin', 'index'], $postArray);

        //$this->assertContains($expected, $output);
        $this->assertRedirect('home');
    }

    public function testIndex_IncorrectPW() {
        //Set POST array
        $postArray = array(
            'username'=>'bbrummtest',
            'password'=>'123'
        );
        $output = $this->request('POST', ['VerifyLogin', 'index'], $postArray);
        $expected = "<p>Invalid username or password.</p>";

        $this->assertContains($expected, $output);
    }

    public function testIndex_IncorrectUsername() {
        //Set POST array
        $postArray = array(
            'username'=>'abc',
            'password'=>'123'
        );
        $output = $this->request('POST', ['VerifyLogin', 'index'], $postArray);
        $expected = "User is not active. Please contact support or the administrator.";

        $this->assertContains($expected, $output);
    }

    public function testIndex_MissingUsername() {
        //Set POST array
        $postArray = array(
            'username'=>null,
            'password'=>'123'
        );
        $output = $this->request('POST', ['VerifyLogin', 'index'], $postArray);
        $expected = "User is not active. Please contact support or the administrator.";

        $this->assertContains($expected, $output);
    }

    public function testIndex_MissingPassword() {
        //Set POST array
        $postArray = array(
            'username'=>'abc',
            'password'=>null
        );
        $output = $this->request('POST', ['VerifyLogin', 'index'], $postArray);
        $expected = "User is not active. Please contact support or the administrator.";

        $this->assertContains($expected, $output);
    }


}
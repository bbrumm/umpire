<?php

class ForgotPassword_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->controller('ResetPasswordEntry');
        //$this->obj = new ResetPasswordEntry();
        $_POST = array();
    }

    public function test_DataValid() {
        $output = $this->request('POST', ['ForgotPassword', 'index']);
        $expected = "<h2>Password Reset</h2>";

        $this->assertContains($expected, $output);
        //$this->assertRedirect('home');

    }

    public function test_SubmitChangePasswordForm_Valid() {
        $postArray = array(
            'username'=>'bbrummtest',
            'emailAddress'=>'brummthecar@gmail.com'
        );

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', false], $postArray);
        $expected = "<h2>Almost Done!</h2>";

        $this->assertContains($expected, $output);
        //$this->assertRedirect('home');
    }


}
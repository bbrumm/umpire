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

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', true], $postArray);
        $expected = "<h2>Almost Done!</h2>";

        $this->assertContains($expected, $output);
    }

    public function test_SubmitChangePasswordForm_DoesNotMatch() {
        $postArray = array(
            'username'=>'bbrummtest',
            'emailAddress'=>'brummthecar2@gmail.com'
        );

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', true], $postArray);
        $expected = "Username or email address not found. Please try again or contact support.";

        $this->assertContains($expected, $output);
    }

    public function test_SubmitChangePasswordForm_UsernameNotFound() {
        $postArray = array(
            'username'=>'bbrummtest1234',
            'emailAddress'=>'brummthecar@gmail.com'
        );

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', false], $postArray);
        $expected = "Username or email address not found. Please try again or contact support.";

        $this->assertContains($expected, $output);
    }

    public function test_SubmitChangePasswordForm_UsernameNull() {
        $postArray = array(
            'username'=>null,
            'emailAddress'=>'brummthecar@gmail.com'
        );

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', false], $postArray);
        $expected = "Username or email address not found. Please try again or contact support.";

        $this->assertContains($expected, $output);
    }

    public function test_SubmitChangePasswordForm_EmailNull() {
        $postArray = array(
            'username'=>'bbrummtest',
            'emailAddress'=>null
        );

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', false], $postArray);
        $expected = "Username or email address not found. Please try again or contact support.";

        $this->assertContains($expected, $output);
    }

    public function test_SubmitChangePasswordForm_UsernameEmpty() {
        $postArray = array(
            'username'=>'',
            'emailAddress'=>'brummthecar@gmail.com'
        );

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', false], $postArray);
        $expected = "Username or email address not found. Please try again or contact support.";

        $this->assertContains($expected, $output);
    }

    public function test_SubmitChangePasswordForm_EmailEmpty() {
        $postArray = array(
            'username'=>'bbrummtest',
            'emailAddress'=>''
        );

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', false], $postArray);
        $expected = "Username or email address not found. Please try again or contact support.";

        $this->assertContains($expected, $output);
    }

    public function test_SubmitChangePasswordForm_UsernameInjection() {
        $postArray = array(
            'username'=>"' OR 1=1;'",
            'emailAddress'=>"' OR 1=1;'"
        );

        $output = $this->request('POST', ['ForgotPassword', 'submitChangePasswordForm', false], $postArray);
        $expected = "Username or email address not found. Please try again or contact support.";

        $this->assertContains($expected, $output);
    }


}
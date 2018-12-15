<?php

class Login_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);

    }

    public function test_LoadPage() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['Login', 'index']);
        $expectedHeader = "<h2>Login</h2>";
        $this->assertContains($expectedHeader, $output);
    }
}

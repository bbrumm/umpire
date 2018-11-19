<?php

class ResetPasswordEntry_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->helper('string');
        //$this->CI->load->controller('ResetPasswordEntry');
        //$this->obj = new ResetPasswordEntry();
        $_POST = array();
    }

    public function test_Dummy() {
        $this->assertEquals(1, 1);
    }

    /*
     * Complete this test once the ForgotPassword tests are done
    public function test_LoadPage() {
        $activationId = random_string('alnum',15);
        //$this->obj->load($activationId);
        $output = $this->request('POST', ['ResetPasswordEntry', 'load', $activationId]);
        $expected = "<h2>Password Reset</h2>";

        $this->assertContains($expected, $output);

    }
    */

}
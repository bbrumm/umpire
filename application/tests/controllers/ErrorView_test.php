<?php
class ErrorView_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();

    }

    public function testFileLoadCLI() {
        $exception = new CI_Exceptions();
        $heading = "Test heading 123";
        $message = "This message";
        $output = $exception->show_error($heading, $message, 'error_general');

        $expectedOutput = "ERROR: " . $heading;
        $this->assertContains($expectedOutput, $output);
    }

    public function testFileLoad() {
        $exception = new CI_Exceptions();
        $heading = "Test heading 123";
        $message = "This message";
        $output = $this->CI->load->view('errors/html/error_general', '', true);

        $expectedOutput = "<title>Error</title>";
        $this->assertContains($expectedOutput, $output);
    }

}
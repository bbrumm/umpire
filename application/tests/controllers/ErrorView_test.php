<?php
class ErrorView_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();

    }

    public function testErrorGeneralCLI() {
        $exception = new CI_Exceptions();
        $heading = "Test heading 123";
        $message = "This message";
        $output = $exception->show_error($heading, $message, 'error_general');

        $expectedOutput = "ERROR: " . $heading;
        $this->assertContains($expectedOutput, $output);
    }

    public function testErrorGeneralHTML() {
        $output = $this->CI->load->view('errors/html/error_general', '', true);

        $expectedOutput = "<title>Error</title>";
        $this->assertContains($expectedOutput, $output);
    }

    public function testError404CLI() {
        $data['heading'] = "Test heading 123";
        $data['message'] = "This message";
        $output = $this->CI->load->view('errors/cli/error_404', $data, true);

        $expectedOutput = "ERROR: " . $data['heading'];
        $this->assertContains($expectedOutput, $output);
    }

    public function testError404HTML() {
        $output = $this->CI->load->view('errors/html/error_404', '', true);

        $expectedOutput = "<title>404 Page Not Found</title>";
        $this->assertContains($expectedOutput, $output);
    }

    public function testErrorDBCLI() {
        $data['heading'] = "Test heading 123";
        $data['message'] = "This message";
        $output = $this->CI->load->view('errors/cli/error_db', $data, true);

        $expectedOutput = "Database error: " . $data['heading'];
        $this->assertContains($expectedOutput, $output);
    }

    public function testErrorDBHTML() {
        $output = $this->CI->load->view('errors/html/error_db', '', true);

        $expectedOutput = "<title>Database Error</title>";
        $this->assertContains($expectedOutput, $output);
    }

    public function testErrorExceptionCLI() {
        $data['heading'] = "Test heading 123";
        $data['message'] = "This message";
        $data['exception'] = new Exception();
        $output = $this->CI->load->view('errors/cli/error_exception', $data, true);

        $expectedOutput = "An uncaught Exception was encountered";
        $this->assertContains($expectedOutput, $output);
    }

    public function testErrorExceptionHTML() {
        $data['exception'] = new Exception();
        $output = $this->CI->load->view('errors/html/error_exception', $data, true);

        $expectedOutput = "An uncaught Exception was encountered";
        $this->assertContains($expectedOutput, $output);
    }

    public function testErrorPHPHTML() {
        $data['severity'] = 1;
        $data['message'] = "testmesssage";
        $data['filepath'] = "abc";
        $data['line'] = 2;
        $output = $this->CI->load->view('errors/html/error_php', $data, true);

        $expectedOutput = "<h4>A PHP Error was encountered</h4>";
        $this->assertContains($expectedOutput, $output);
    }

}
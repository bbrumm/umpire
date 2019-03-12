<?php
class Header_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }

    //bbrummtest is an Admin user so should see everything
    public function test_HomePage() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $linkName = "Home Page";

        $output = $this->request('POST', ['Home', 'index']);
        $expectedHeader = "<div class='menuBarLink'>". $linkName ."</div>";
        $this->assertContains($expectedHeader, $output);
    }

    public function test_ImportFile() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $linkName = "Import File";

        $output = $this->request('POST', ['Home', 'index']);
        $expectedHeader = "<div class='menuBarLink'>". $linkName ."</div>";
        $this->assertContains($expectedHeader, $output);
    }

    public function test_UserAdmin() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $linkName = "User Admin";

        $output = $this->request('POST', ['Home', 'index']);
        $expectedHeader = "<div class='menuBarLink'>". $linkName ."</div>";
        $this->assertContains($expectedHeader, $output);
    }

    public function test_UmpireAdmin() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $linkName = "Umpire Admin";

        $output = $this->request('POST', ['Home', 'index']);
        $expectedHeader = "<div class='menuBarLink'>". $linkName ."</div>";
        $this->assertContains($expectedHeader, $output);
    }

    //Create PDF link is tested in the Report_test file, because that's where the DW is checked and imported

    public function test_CreatePDFNotOnHomePage() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $linkName = "Create PDF";

        $output = $this->request('POST', ['Home', 'index']);
        $expectedHeader = "<div class='menuBarLink'>". $linkName ."</div>";
        $this->assertNotContains($expectedHeader, $output);
    }

    //TODO: Run tests for non-Admin users


}
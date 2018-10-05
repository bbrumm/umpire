<?php
class Dummy_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        //$this->CI->load->model('Report_instance');
        //$this->userReport = $this->CI->Report_instance;
        //$this->CI->load->model('Requested_report_model');
        
    }

    public function dummyAssert() {
    	$this->assertEquals("yes", "yes");
    }
    
?>
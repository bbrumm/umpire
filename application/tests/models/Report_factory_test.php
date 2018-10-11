<?php
class Report_factory_test extends TestCase {
  public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('separate_reports/Report_factory');
        $this->CI->load->model('separate_reports/Report1');
        $this->CI->load->model('separate_reports/Report2');
        $this->CI->load->model('separate_reports/Report3');
        $this->CI->load->model('separate_reports/Report4');
        $this->CI->load->model('separate_reports/Report5');
        $this->CI->load->model('separate_reports/Report6');
        $this->CI->load->model('separate_reports/Report7');
        $this->CI->load->model('separate_reports/Report8');
	      $this->obj = $this->CI->Report_factory;
    }

  public function test_Report1() {
	  $expected = $this->obj->createReport(1);
	  $this->assertInstanceOf('Report1', $this->obj);
  }
}

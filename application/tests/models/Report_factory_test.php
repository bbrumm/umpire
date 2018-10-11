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
	  $this->assertInstanceOf('Report1', $expected);
  }
	
  public function test_Report1() {
    $expected = $this->obj->createReport(1);
    $this->assertInstanceOf('Report1', $expected);
  }
	
  public function test_Report2() {
    $expected = $this->obj->createReport(2);
    $this->assertInstanceOf('Report2', $expected);
  }
	
  public function test_Report3() {
    $expected = $this->obj->createReport(3);
    $this->assertInstanceOf('Report3', $expected);
  }
	
  public function test_Report4() {
    $expected = $this->obj->createReport(4);
    $this->assertInstanceOf('Report4', $expected);
  }
	
  public function test_Report5() {
    $expected = $this->obj->createReport(5);
    $this->assertInstanceOf('Report5', $expected);
  }
	
  public function test_Report6() {
    $expected = $this->obj->createReport(6);
    $this->assertInstanceOf('Report6', $expected);
  }
	
  public function test_Report7() {
    $expected = $this->obj->createReport(7);
    $this->assertInstanceOf('Report7', $expected);
  }
	
  public function test_Report8() {
    $expected = $this->obj->createReport(8);
    $this->assertInstanceOf('Report8', $expected);
  }


}

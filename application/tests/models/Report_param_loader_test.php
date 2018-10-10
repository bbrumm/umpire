<?php
class Report_param_loader_test extends TestCase {
  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('report_param/Report_param_loader');
    $this->CI->load->model('report_param/Report_parameter');
    $this->obj = $this->CI->Report_param_loader;
  }
  
  public function testDummy() {
    $this->assertEquals(1, 1);

}

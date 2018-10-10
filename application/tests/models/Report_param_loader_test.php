<?php
class Report_param_loader_test extends TestCase {
  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('report_param/Report_param_loader');
    $this->obj = $this->CI->Report_param_loader;
  }

  public function test_LookupParameterValue_EmptyArray() {
    $expected = "";
    $inputArray = "";
    $actual = $this->obj->lookupParameterValue($inputArray, "value to search");
    $this->assertEquals($expected, $actual);
  }
}

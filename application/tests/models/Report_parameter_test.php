<?php

class Report_parameter_test extends TestCase {

  public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('report_param/Report_parameter');
        $this->obj = $this->CI->Report_parameter;
    }
    
    public function test_sample() {
      $actual = 2;
      $expected = 2;
      $this->assertEquals($expected, $actual);
    }
}

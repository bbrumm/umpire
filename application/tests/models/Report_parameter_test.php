<?php

class Report_parameter_test extends TestCase {

  public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('report_param/Report_parameter');
        $this->obj = $this->CI->Report_parameter;
    }
    /*
    public function test_ReportID() {
      $expected = 1;
      //$reportParameter = new ReportParameter();
      //$reportParameter->setReportID($expected);
      //$actual = $reportParameter->getReportID();
      $this->obj->setReportID($expected);
      $actual = $this->obj->getReportID();
      $this->assertEquals($expected, $actual);
    }
  */
  public function test_ReportTitle() {
      $expected = "Test Title";
      //$reportParameter = new ReportParameter();
      //$reportParameter->setReportID($expected);
      //$actual = $reportParameter->getReportID();
      $this->obj->setReportTitle($expected);
      $actual = $this->obj->getReportTitle();
      $this->assertEquals($expected, $actual);
    }
}

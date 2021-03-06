<?php

class Report_parameter_test extends TestCase {

  public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('report_param/Report_parameter');
    }
    /*
    public function test_ReportID() {
      $expected = 1;
      $this->obj->setReportID($expected);
      $actual = $this->obj->getReportID();
      $this->assertEquals($expected, $actual);
    }
  */


    public function test_ReportTitle() {
      $expected = "Test Title";
      $this->obj = Report_parameter::createNewReportParameter($expected, "", "", "", "", "", "", "");
      $actual = $this->obj->getReportTitle();
      $this->assertEquals($expected, $actual);
    }

  public function test_ValueFieldID() {
      $expected = 1;
      $this->obj = Report_parameter::createNewReportParameter("", $expected, "", "", "", "", "", "");
      $actual = $this->obj->getValueFieldID();
      $this->assertEquals($expected, $actual);
    }

public function test_NoValueDisplay() {
      $expected = 1;
      $this->obj = Report_parameter::createNewReportParameter("", "", $expected, "", "", "", "", "");
      $actual = $this->obj->getNoValueDisplay();
      $this->assertEquals($expected, $actual);
    }

public function test_FirstColumnFormat() {
      $expected = 1;
      $this->obj = Report_parameter::createNewReportParameter("", "", "", $expected, "", "", "", "");
      $actual = $this->obj->getFirstColumnFormat();
      $this->assertEquals($expected, $actual);
    }

public function test_ColourCells() {
      $expected = 1;
      $this->obj = Report_parameter::createNewReportParameter("", "", "", "", $expected, "", "", "");
      $actual = $this->obj->getColourCells();
      $this->assertEquals($expected, $actual);
    }

public function test_PDFOrientation() {
      $expected = 1;
      $this->obj = Report_parameter::createNewReportParameter("", "", "", "", "", $expected, "", "");
      $actual = $this->obj->getPDFOrientation();
      $this->assertEquals($expected, $actual);
    }

public function test_PDFPaperSize() {
      $expected = 1;
      $this->obj = Report_parameter::createNewReportParameter("", "", "", "", "", "", $expected, "");
      $actual = $this->obj->getPDFPaperSize();
      $this->assertEquals($expected, $actual);
    }

public function test_PDFResolution() {
      $expected = 1;
      $this->obj = Report_parameter::createNewReportParameter("", "", "", "", "", "", "", $expected);
      $actual = $this->obj->getPDFResolution();
      $this->assertEquals($expected, $actual);
    }

}

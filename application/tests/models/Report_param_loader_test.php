<?php
class Report_param_loader_test extends TestCase {
  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('report_param/Report_param_loader');
    $this->CI->load->model('report_param/Report_parameter');
    $this->obj = $this->CI->Report_param_loader;
  }

  public function test_LookupParameterValue_EmptyArray() {
    $expected = "";
    $inputArray = "";
    $actual = $this->obj->lookupParameterValue($inputArray, "value to search");
    $this->assertEquals($expected, $actual);
  }

  
  
public function test_LookupParameterValue_EmptyParamName() {
  $expected = "";
  $inputParamName = "";
  $reportParameter1 = new Report_parameter();
  $reportParameter1->setName("The first param");
  $reportParameter1->setValue("Green");
  $reportParameter2 = new Report_parameter();
  $reportParameter2->setName("Second param");
  $reportParameter2->setValue("Yellow");
  $reportParamArray = array[$reportParameter1, $reportParameter2];
  $actual = $this->obj->lookupParametervalue($reportParamArray, inputParamName );
  $this->assertEquals($expected, $actual);
}

public function test_LookupParameterValue_ParamNameNotFound() {
  $expected = "";
  $inputParamName = "Mystery";
  $reportParameter1 = new Report_parameter();
  $reportParameter1->setName("The first param");
  $reportParameter1->setValue("Green");
  $reportParameter2 = new Report_parameter();
  $reportParameter2->setName("Second param");
  $reportParameter2->setValue("Yellow");
  $reportParamArray = array[$reportParameter1, $reportParameter2];
  $actual = $this->obj->lookupParametervalue($reportParamArray, inputParamName );
  $this->assertEquals($expected, $actual);
}


public function test_LookupParameterValue_ParamNameNull() {
  $expected = "";
  $inputParamName = null;
  $reportParameter1 = new Report_parameter();
  $reportParameter1->setName("The first param");
  $reportParameter1->setValue("Green");
  $reportParameter2 = new Report_parameter();
  $reportParameter2->setName("Second param");
  $reportParameter2->setValue("Yellow");
  $reportParamArray = array[$reportParameter1, $reportParameter2];
  $actual = $this->obj->lookupParametervalue($reportParamArray, inputParamName );
  $this->assertEquals($expected, $actual);
}

public function test_LookupParameterValue_ParamNameNotFound() {
  $expected = "";
  $inputParamName = "Mystery";
  $reportParamArray = null;
  $actual = $this->obj->lookupParametervalue($reportParamArray, inputParamName );
  $this->assertEquals($expected, $actual);
}

public function test_LookupParameterValue_ParamValueNull() {
  $expected = "";
  $inputParamName = "The first param";
  $reportParameter1 = new Report_parameter();
  $reportParameter1->setName("The first param");
  $reportParameter1->setValue(null);
  $reportParameter2 = new Report_parameter();
  $reportParameter2->setName("Second param");
  $reportParameter2->setValue("Yellow");
  $reportParamArray = array[$reportParameter1, $reportParameter2];
  $actual = $this->obj->lookupParametervalue($reportParamArray, inputParamName );
  $this->assertEquals($expected, $actual);
}

public function test_LookupParameterValue_ParamValueEmptyString() {
  $expected = "";
  $inputParamName = "The first param";
  $reportParameter1 = new Report_parameter();
  $reportParameter1->setName("The first param");
  $reportParameter1->setValue("");
  $reportParameter2 = new Report_parameter();
  $reportParameter2->setName("Second param");
  $reportParameter2->setValue("Yellow");
  $reportParamArray = array[$reportParameter1, $reportParameter2];
  $actual = $this->obj->lookupParametervalue($reportParamArray, inputParamName );
  $this->assertEquals($expected, $actual);
}


public function test_LookupParameterValue_ParamValueOK() {
  $expected = "";
  $inputParamName = "The first param";
  $reportParameter1 = new Report_parameter();
  $reportParameter1->setName("The first param");
  $reportParameter1->setValue("Green");
  $reportParameter2 = new Report_parameter();
  $reportParameter2->setName("Second param");
  $reportParameter2->setValue("Yellow");
  $reportParamArray = array[$reportParameter1, $reportParameter2];
  $actual = $this->obj->lookupParametervalue($reportParamArray, inputParamName );
  $this->assertEquals($expected, $actual);
}


public function test_LookupParameterValue_ParamValueTwice() {
  $expected = "";
  $inputParamName = "Second";
  $reportParameter1 = new Report_parameter();
  $reportParameter1->setName("Second param");
  $reportParameter1->setValue("Green");
  $reportParameter2 = new Report_parameter();
  $reportParameter2->setName("Second param");
  $reportParameter2->setValue("Yellow");
  $reportParamArray = array[$reportParameter1, $reportParameter2];
  $actual = $this->obj->lookupParametervalue($reportParamArray, inputParamName );
  $this->assertEquals($expected, $actual);
}





}

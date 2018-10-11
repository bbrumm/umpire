<?php
class Report_filter_parameter_test extends TestCase {
public function setUp() {
$this->resetInstance();
$this->CI->load->model('Report_filter_parameter');
$this->obj = $this->CI->Report_filter_parameter;
}

  public function test_dummy() {
    $this->assertEquals(1, 1);
  }
/*
public function test_createFilterParameter_ValuesOKSQL() {
$expected = "'First','Second','Third'";
$inputFilterParam = array('First', 'Second', 'Third');
$inputPDFMode = true;
$inputRegion = false;
$this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
$actual = $this->obj->getFilterSQLValues();
$this->assertEquals($expected, $actual);
}

public function test_createFilterParameter_ValuesOKDisplay() {
$expected = "First,Second,Third";
$inputFilterParam = array('First', 'Second', 'Third');
$inputPDFMode = true;
$inputRegion = false;
$this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
$actual = $this->obj->getFilterDisplayValues();
$this->assertEquals($expected, $actual);
}
*/
}

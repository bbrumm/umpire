<?php
class Report_cell_Test extends TestCase {

  public function setUp() {
    $this->CI->load->model('Report_cell');
    $this->obj = $this->CI->Report_cell;
  }

  public function test_SetCellValue_String() {
    $inputValue = "This is a test value";
    $this->obj->setCellValue($inputValue);

    $actualValue = $this->obj->getCellValue();
    $this->assertEquals($inputValue, $actualValue);
  }
}

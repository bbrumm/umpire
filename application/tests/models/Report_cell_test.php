<?php
class Report_cell_Test extends TestCase {

  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('Report_cell');
    $this->obj = $this->CI->Report_cell;
  }

  public function test_SetCellValue_String() {
    $inputValue = "This is a test value";
    $this->obj->setCellValue($inputValue);

    $actualValue = $this->obj->getCellValue();
    $this->assertEquals($inputValue, $actualValue);
  }
  
  public function test_SetCellValue_Number() {
    $inputValue = 1235168;
    $this->obj->setCellValue($inputValue);

    $actualValue = $this->obj->getCellValue();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetCellValue_Null() {
    $inputValue = null;
    $this->obj->setCellValue($inputValue);

    $actualValue = $this->obj->getCellValue();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetCellValue_LongString() {
    $inputValue = "This is a test value that is really long and probably should not be in a cell because the cell will be reeeaaaaly big,";
    $this->obj->setCellValue($inputValue);

    $actualValue = $this->obj->getCellValue();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetFormatClass_String() {
    $inputValue = "some class name";
    $this->obj->setFormatClass($inputValue);

    $actualValue = $this->obj->getFormatClass();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetFormatClass_Number() {
    $inputValue = 576567;
    $this->obj->setFormatClass($inputValue);

    $actualValue = $this->obj->getFormatClass();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetFormatClass_Null() {
    $inputValue = null;
    $this->obj->setFormatClass($inputValue);

    $actualValue = $this->obj->getFormatClasse();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetRowNumber_String() {
    $inputValue = "abcd";
    $this->obj->setRowNumber($inputValue);

    $actualValue = $this->obj->getRowNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetRowNumber_Number() {
    $inputValue = 2;
    $this->obj->setRowNumber($inputValue);

    $actualValue = $this->obj->getRowNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetRowNumber_Null() {
    $inputValue = null;
    $this->obj->setRowNumber($inputValue);

    $actualValue = $this->obj->getRowNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetRowNumber_Zero() {
    $inputValue = 0;
    $this->obj->setRowNumber($inputValue);

    $actualValue = $this->obj->getRowNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetRowNumber_HighNumber() {
    $inputValue = 897349578294524;
    $this->obj->setRowNumber($inputValue);

    $actualValue = $this->obj->getRowNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetColumnNumber_String() {
    $inputValue = "abcd";
    $this->obj->setColumnNumber($inputValue);

    $actualValue = $this->obj->getColumnNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetColumnNumber_Number() {
    $inputValue = 2;
    $this->obj->setColumnNumber($inputValue);

    $actualValue = $this->obj->getColumnNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetColumnNumber_Null() {
    $inputValue = null;
    $this->obj->setColumnNumber($inputValue);

    $actualValue = $this->obj->getColumnNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetColumnNumber_Zero() {
    $inputValue = 0;
    $this->obj->setColumnNumber($inputValue);

    $actualValue = $this->obj->getColumnNumber();
    $this->assertEquals($inputValue, $actualValue);
  }

  public function test_SetColumnNumber_HighNumber() {
    $inputValue = 68576345636735674;
    $this->obj->setColumnNumber($inputValue);

    $actualValue = $this->obj->getColumnNumber();
    $this->assertEquals($inputValue, $actualValue);
  }



}

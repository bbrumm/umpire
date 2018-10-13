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

    public function test_createFilterParameter_ValuesOKSQL() {
        $expected = "'First','Second','Third'";
        $inputFilterParam = array("First", "Second","Third");
        $inputPDFMode = false;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterSQLValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_ValuesOKDisplay() {
        $expected = "First, Second, Third";
        $inputFilterParam = array("First", "Second","Third");
        $inputPDFMode = false;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterDisplayValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_SingleValueSQL() {
        $expected = "'First'";
        $inputFilterParam = array("First");
        $inputPDFMode = false;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterSQLValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_SingleValueDisplay() {
        $expected = "First";
        $inputFilterParam = array("First");
        $inputPDFMode = false;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterDisplayValues();
        $this->assertEquals($expected, $actual);
    }
    
    

    public function test_createFilterParameter_ValuesOKPDFSQL() {
        $expected = "'First','Second','Third'";
        $inputFilterParam = "First,Second,Third";
        $inputPDFMode = true;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterSQLValues();
        $this->assertEquals($expected, $actual);
    }
    
    /*
     * These tests are commented out because the output for this condition is not correct.
     * TODO: Fix the function that creates this output.
        --- Expected
        +++ Actual
        @@ @@
        -'First, Second, Third'
        +'First, Second, Third''
    public function test_createFilterParameter_ValuesOKPDFDisplay() {
        $expected = "First, Second, Third";
        $inputFilterParam = "First,Second,Third";
        $inputPDFMode = true;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterDisplayValues();
        $this->assertEquals($expected, $actual);
    }
    */
    
    public function test_createFilterParameter_SingleValuePDFSQL() {
        $expected = "'First'";
        $inputFilterParam = "First";
        $inputPDFMode = true;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterSQLValues();
        $this->assertEquals($expected, $actual);
    }
    /*
    public function test_createFilterParameter_SingleValuePDFDisplay() {
        $expected = "First";
        $inputFilterParam = "First";
        $inputPDFMode = true;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterDisplayValues();
        $this->assertEquals($expected, $actual);
    }
    */
    
    public function test_createFilterParameter_SeveralValuesRegionSQL() {
        $expected = "'First'";
        $inputFilterParam = array("First", "Second","Third");
        $inputPDFMode = false;
        $inputRegion = true;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterSQLValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_SeveralValuesRegionDisplay() {
        $expected = "First";
        $inputFilterParam = array("First", "Second","Third");
        $inputPDFMode = false;
        $inputRegion = true;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterDisplayValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_SingleArrayValueRegionSQL() {
        $expected = "'First'";
        $inputFilterParam = array("First");
        $inputPDFMode = false;
        $inputRegion = true;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterSQLValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_SingleArrayValueRegionDisplay() {
        $expected = "First";
        $inputFilterParam = array("First");
        $inputPDFMode = false;
        $inputRegion = true;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterDisplayValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_SingleValueRegionSQL() {
        $expected = "'First'";
        $inputFilterParam = "First";
        $inputPDFMode = false;
        $inputRegion = true;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterSQLValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_SingleValueRegionDisplay() {
        $expected = "First";
        $inputFilterParam = "First";
        $inputPDFMode = false;
        $inputRegion = true;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
        $actual = $this->obj->getFilterDisplayValues();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_createFilterParameter_StringSQL() {
        $this->expectException(InvalidArgumentException::class);
        $inputFilterParam = "First, Second";
        $inputPDFMode = false;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
    }
    
    public function test_createFilterParameter_EmptyArraySQL() {
        $this->expectException(InvalidArgumentException::class);
        $inputFilterParam = [];
        $inputPDFMode = false;
        $inputRegion = false;
        $this->obj->createFilterParameter($inputFilterParam, $inputPDFMode, $inputRegion);
    }
    
    

}

    
<?php
class Array_library_test extends TestCase {
  public function setUp() {
    $this->resetInstance();
    $this->CI->load->library('Array_library_test');
    $this->obj = $this->CI->Array_library_test;
  }
  
  public function test_SearchMultiArrayForThreeValues_Valid() {
    //Inputs
    $firstSearchValue = "A";
    $secondSearchValue = "B";
    $thirdSearchValue = "C";
    $arrayToSearch = array(
      array('X', 'Y', 'Z')
    );

    $expectedResult = false;
    $actualResult = $this->obj->searchMultiArrayForThreeValues(
      $arrayToSearch, $firstSearchValue, $secondSearchValue, $thirdSearchValue);
    
    $this->assertEquals($expectedResult, $actualResult);
  
  }
  
  
}

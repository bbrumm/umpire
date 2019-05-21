<?php
class Array_library_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->CI->load->library('Array_library');
        $this->CI->load->model('Umpire');
        $this->obj = new Array_library();
    }

    public function test_FindArrayDBObjectDiff_NoDiff() {
        //Set up arrays that match
        $firstArray = array(
            (object)array('name'=>'john'),
            (object)array('name'=>'paul'),
            (object)array('name'=>'ringo')
        );

        $secondArray = array(
            (object)array('name'=>'john'),
            (object)array('name'=>'paul'),
            (object)array('name'=>'ringo')
        );

        //Determine differences using function
        $arrayDifferences = $this->obj->findArrayDBObjectDiff($firstArray, $secondArray, 'name');
        $this->assertEmpty($arrayDifferences);
    }

    public function test_FindArrayDBObjectDiff_FirstHasExtraElement() {
        //Set up arrays that match
        $firstArray = array(
            (object)array('name'=>'john'),
            (object)array('name'=>'paul'),
            (object)array('name'=>'ringo')
        );

        $secondArray = array(
            (object)array('name'=>'john'),
            (object)array('name'=>'paul')
        );

        //Determine differences using function
        $arrayDifferences = $this->obj->findArrayDBObjectDiff($firstArray, $secondArray, 'name');
        $expectedResult = 'ringo';
        $actualResult = $arrayDifferences[0];
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_FindArrayDBObjectDiff_SecondHasExtraElement() {
        //Set up arrays that match
        $firstArray = array(
            (object)array('name'=>'john'),
            (object)array('name'=>'paul')
        );

        $secondArray = array(
            (object)array('name'=>'john'),
            (object)array('name'=>'paul'),
            (object)array('name'=>'ringo')
        );

        //Determine differences using function
        $arrayDifferences = $this->obj->findArrayDBObjectDiff($firstArray, $secondArray, 'name');
        $expectedResult = 'ringo';
        $actualResult = $arrayDifferences[0];
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_FindArrayDBObjectDiff_FirstHasMoreElements() {
        //Set up arrays that match
        $firstArray = array(
            (object)array('name'=>'john'),
            (object)array('name'=>'paul'),
            (object)array('name'=>'ringo'),
            (object)array('name'=>'george'),
            (object)array('name'=>'matt')
        );

        $secondArray = array(
            (object)array('name'=>'john'),
            (object)array('name'=>'paul')
        );

        //Determine differences using function
        $arrayDifferences = $this->obj->findArrayDBObjectDiff($firstArray, $secondArray, 'name');
        $expectedResult = 'ringo';
        $actualResult = $arrayDifferences[0];
        $this->assertEquals($expectedResult, $actualResult);
        $expectedSize = 3;
        $actualSize = count($arrayDifferences);
        $this->assertEquals($expectedSize, $actualSize);
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

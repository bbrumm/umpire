<?php
class Report_param_loader_test extends TestCase {
  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('report_param/Report_param_loader');
    $this->CI->load->model('report_param/Report_parameter');
    $this->CI->load->model('report_param/Report_grouping_structure');
    $this->obj = $this->CI->Report_param_loader;
  }
 
  
  public function test_GetReportParameter() {
      $inputValue = Report_parameter::createNewReportParameter("title", 2, 0, "text", 1, "portrait", "a4", "200");
      $this->obj->setReportParameter($inputValue);
      $outputValue = $this->obj->getReportParameter();
      $this->assertEquals("title", $outputValue->getReportTitle());
  }
  
  public function test_GetReportGroupingStructureArray() {
      $firstInputValue = Report_grouping_structure::createNewReportGroupingStructure(1, "type one", "name", 1, "merge", "group", 100);
      $secondValue = Report_grouping_structure::createNewReportGroupingStructure(2, "type two", "name", 1, "merge", "group", 100);
      $groupingStructureArray = array($firstInputValue, $secondValue);
      
      $this->obj->setReportGroupingStructureArray($groupingStructureArray);
      $outputValue = $this->obj->getReportGroupingStructureArray();
      $this->assertEquals("type one", $outputValue[0]->getGroupingType());
      $this->assertEquals("type two", $outputValue[1]->getGroupingType());
  }
  
  public function test_loadAllParametersTest() {
      $requestedReportModel = Requested_report_model::createRequestedReportFromValues(1, 2016, "N", "A", "W", "L", true);
      $arrayStore = new Array_store_matches();
      
      $this->obj->loadAllReportParametersForReport($requestedReportModel, $arrayStore);
      
      $expected = "Random Title";
      $actual = $this->obj->getReportParameter()->getReportTitle();
      $this->assertEquals($expected, $actual);
  }
  
  public function test_loadAllParametersTestV2() {
      $requestedReportModel = Requested_report_model::createRequestedReportFromValues(2, 2016, "N", "A", "W", "L", true);
      $arrayStore = new Array_store_matches();
      
      $this->obj->loadAllReportParametersForReport($requestedReportModel, $arrayStore);
      
      $expected = "Another Title";
      $actual = $this->obj->getReportParameter()->getReportTitle();
      $this->assertEquals($expected, $actual);
  }
  
  public function test_loadAllParametersTestNotFound() {
      $this->expectException(Exception::class);
      $requestedReportModel = Requested_report_model::createRequestedReportFromValues(15, 2016, "N", "A", "W", "L", true);
      $arrayStore = new Array_store_matches();
      
      $this->obj->loadAllReportParametersForReport($requestedReportModel, $arrayStore);
  }
  
  public function test_loadAllGroupingStructuresForReport() {
      $requestedReportModel = Requested_report_model::createRequestedReportFromValues(1, 2016, "N", "A", "W", "L", true);
      $arrayStore = new Array_store_matches();
      
      $this->obj->loadAllGroupingStructuresForReport($requestedReportModel, $arrayStore);
      
      $expected = 3;
      $actual = count($this->obj->getReportGroupingStructureArray());
      $this->assertEquals($expected, $actual);
  }
  
  public function test_loadAllGroupingStructuresForReportNotFound() {
      $this->expectException(Exception::class);
      $requestedReportModel = Requested_report_model::createRequestedReportFromValues(18, 2016, "N", "A", "W", "L", true);
      $arrayStore = new Array_store_matches();
      
      $this->obj->loadAllGroupingStructuresForReport($requestedReportModel, $arrayStore);
  }
}

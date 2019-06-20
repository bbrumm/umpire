<?php
class Database_store_report_param_test extends TestCase {

  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('data_store/Database_store_report_param');
    $this->obj = $this->CI->Database_store_report_param;
  }

  public function test_LoadAllGroupingStructures_Report1() {
    $reportNumber = 1;
    $reportGroupingStructure = $this->obj->loadAllGroupingStructures($reportNumber);

    $this->assertNotEmpty($reportGroupingStructure);
  }

  public function test_LoadAllGroupingStructures_ReportDoesNotExist() {
    $this->expectException(Exception::class);
    $reportNumber = 20;
    $reportGroupingStructure = $this->obj->loadAllGroupingStructures($reportNumber);

  }

}

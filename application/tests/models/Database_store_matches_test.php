<?php
class Database_store_matches_test extends TestCase {

  public function setUp() {
    $this->resetInstance();
    $this->CI->load->model('Database_store_matches');
    $this->obj = $this->CI->Database_store_matches;
  }

  public function test_LoadAllGroupingStructures_Report1() {
    $reportNumber = 1;
    $reportGroupingStructure = $this->obj->loadAllGroupingStructures($inputValue);

    $this->assertNotEmpty($reportGroupingStructure);
  }

  public function test_LoadAllGroupingStructures_ReportDoesNotExist() {
    $this->expectException(Exception::class);
    $reportNumber = 20;
    $reportGroupingStructure = $this->obj->loadAllGroupingStructures($inputValue);

  }

}

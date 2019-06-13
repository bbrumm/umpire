<?php
class Report_table_refresher_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('report_refresher/Report_table_refresher');
        $this->obj = $this->CI->Report_table_refresher;
    }
    
    public function test_DisableKeys_Exception() {
        $this->expectException(Exception::class);
        $this->obj->setTableName(null);
        $this->obj->disableKeys();
    }
    
     public function test_DisableKeysForSpecificTable_Exception() {
        $this->expectException(Exception::class);
        $this->obj->disableKeysForSpecificTable(null);
    }
    
    public function test_EnableKeys_Exception() {
        $this->expectException(Exception::class);
        $this->obj->setTableName(null);
        $this->obj->enableKeys();
    }
    
    public function test_EnableKeysForSpecificTable_Exception() {
        $this->expectException(Exception::class);
        $this->obj->enableKeysForSpecificTable(null);
    }
    
    public function test_TableInsertOperation_Exception() {
        $this->expectException(Exception::class);
        $this->obj->setTableName(null);
        $this->obj->logTableInsertOperation();
    }
    
    public function test_TableUpdateOperation_Exception() {
        $this->expectException(Exception::class);
        $this->obj->setTableName(null);
        $this->obj->logTableUpdateOperation();
    }
    
    public function test_TableDeleteOperation_Exception() {
        $this->expectException(Exception::class);
        $this->obj->setTableName(null);
        $this->obj->logTableDeleteOperation();
    }
    
    
}

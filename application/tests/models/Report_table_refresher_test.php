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
    
    
}

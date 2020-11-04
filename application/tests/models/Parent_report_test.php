<?php
class Parent_report_test extends TestCase {

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('separate_reports/Parent_report');
        $this->obj = $this->CI->Parent_report;
    }

    public function test_ParentReportDummy() {
        $this->assertEquals(1, 1);
    }


}


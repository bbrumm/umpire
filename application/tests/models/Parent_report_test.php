<?php
class Parent_report_test extends TestCase {

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('separate_reports/Parent_report');
        $this->obj = $this->CI->Parent_report;
    }


    public function test_IsFieldMatchingColumn_Exception() {
        $this->expectException(Exception::class);
        //Setup data
        $pReportColumnFields = array(1, 2, 3, 4);
        $pColumnItem = "column item name";
        $pColumnHeadingSet = "column heading set";

        $this->obj->isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);


    }

}


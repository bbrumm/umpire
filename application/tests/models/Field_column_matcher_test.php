<?php
class Field_column_matcher_test extends TestCase {

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('separate_reports/Field_column_matcher');
        $this->obj = $this->CI->Field_column_matcher;
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


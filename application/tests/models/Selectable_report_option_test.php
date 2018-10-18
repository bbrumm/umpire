<?php

class Selectable_report_option_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Selectable_report_option');
        $this->obj = new Selectable_report_option();
    }

    public function test_OptionName() {
        $expected = 1;
        $this->obj->setOptionName($expected);
        $this->assertEquals($expected, $this->obj->getOptionName());
    }

    public function test_OptionValue() {
        $expected = 1;
        $this->obj->setOptionValue($expected);
        $this->assertEquals($expected, $this->obj->getOptionValue());
    }

    public function test_OptionDisplayOrder() {
        $expected = 1;
        $this->obj->setOptionDisplayOrder($expected);
        $this->assertEquals($expected, $this->obj->getOptionDisplayOrder());
    }

}
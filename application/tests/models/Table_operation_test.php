<?php
class Table_operation_test extends TestCase
{
    public function setUp()
    {
        $this->resetInstance();
        //$this->CI->load->model('separate_reports/Report1');
        $this->CI->load->model('Table_operation');
        $this->obj = $this->CI->Table_operation;
    }

    public function test_ImportedFileID() {
        $expected = 1;
        $this->obj->setImportedFileID($expected);
        $this->assertEquals($expected, $this->obj->getImportedFileID());
    }

    public function test_ProcessedTableID() {
        $expected = 1;
        $this->obj->setProcessedTableID($expected);
        $this->assertEquals($expected, $this->obj->getProcessedTableID());
    }

    public function test_OperationID() {
        $expected = 1;
        $this->obj->setOperationID($expected);
        $this->assertEquals($expected, $this->obj->getOperationID());
    }

    public function test_OperationDateTime() {
        $expected = 1;
        $this->obj->setOperationDateTime($expected);
        $this->assertEquals($expected, $this->obj->getOperationDateTime());
    }

    public function test_RowCount() {
        $expected = 1;
        $this->obj->setRowCount($expected);
        $this->assertEquals($expected, $this->obj->getRowCount());
    }



}
<?php
class Report_display_options_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Report_display_options');
        $this->CI->load->model('Report_instance');
        $this->obj = $this->CI->Report_display_options;
    }
    
    public function test_ColumnGroup() {
        $expected = 1;
        $this->obj->setColumnGroup($expected);
        $this->assertEquals($expected, $this->obj->getColumnGroup());
    }
    
    public function test_RowGroup() {
        $expected = 2;
        $this->obj->setRowGroup($expected);
        $this->assertEquals($expected, $this->obj->getRowGroup());
    }
    
    public function test_FieldToDisplay() {
        $expected = 3;
        $this->obj->setFieldToDisplay($expected);
        $this->assertEquals($expected, $this->obj->getFieldToDisplay());
    }
    
    public function test_NoDataValue() {
        $expected = 4;
        $this->obj->setNoDataValue($expected);
        $this->assertEquals($expected, $this->obj->getNoDataValue());
    }
    
    public function test_MergeColumnGroup() {
        $expected = 5;
        $this->obj->setMergeColumnGroup($expected);
        $this->assertEquals($expected, $this->obj->getMergeColumnGroup());
    }
    
    public function test_ColourCells() {
        $expected = 6;
        $this->obj->setColourCells($expected);
        $this->assertEquals($expected, $this->obj->getColourCells());
    }
    
    public function test_ColumnHeadingLabel() {
        $expected = 7;
        $this->obj->setColumnHeadingLabel($expected);
        $this->assertEquals($expected, $this->obj->getColumnHeadingLabel());
    }
    
    public function test_FirstColumnFormat() {
        $expected = 8;
        $this->obj->setFirstColumnFormat($expected);
        $this->assertEquals($expected, $this->obj->getFirstColumnFormat());
    }
    
    public function test_ColumnHeadingSizeText() {
        $expected = 9;
        $this->obj->setColumnHeadingSizeText($expected);
        $this->assertEquals($expected, $this->obj->getColumnHeadingSizeText());
    }
    
    public function test_PDFResolution() {
        $expected = 10;
        $this->obj->setPDFResolution($expected);
        $this->assertEquals($expected, $this->obj->getPDFResolution());
    }
    
    public function test_PDFPaperSize() {
        $expected = 11;
        $this->obj->setPDFPaperSize($expected);
        $this->assertEquals($expected, $this->obj->getPDFPaperSize());
    }
    
    public function test_PDFOrientation() {
        $expected = 12;
        $this->obj->setPDFOrientation($expected);
        $this->assertEquals($expected, $this->obj->getPDFOrientation());
    }
    
    public function test_LastGameDate() {
        $expected = 13;
        $this->obj->setLastGameDate($expected);
        $this->assertEquals($expected, $this->obj->getLastGameDate());
    }
    
    public function test_CreateObject() {
        $reportInstance = new Report_instance();
        $this->assertInstanceOf('Report_instance', $reportInstance);
        
        
    }
    
    //This is commented out as the underlying object needs to be refactored
    /*
    public function test_CreateReportDisplayOptions() {
        $reportInstance = new Report_instance();
        
        $inputNoDataValue = 0;
        $inputFirstColumnFormat = "text";
        $inputColourCells = 1;
        $inputPDFResolution = 200;
        $inputPDFPaperSize = "a4";
        $inputPDFOrientation = "portrait";
        
        $reportDisplayOptions = Report_display_options::createReportDisplayOptions($reportInstance);
        $this->assertInstanceOf('Report_display_options', $reportDisplayOptions);
    }
    */

}

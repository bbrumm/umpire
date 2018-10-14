<?php
class Report_selection_parameter_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Report_selection_parameter');
    }
    
    public function test_createReportSelectionParameterID() {
        $inputParameterID = 1;
        $inputParameterName = "the name";
        $inputDisplayOrder = 3;
        $inputAllowMultipleSelections = 0;
        
        $this->obj = Report_selection_parameter::createReportSelectionParameter(
            $inputParameterID, $inputParameterName, $inputDisplayOrder, $inputAllowMultipleSelections);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getParameterID());
    }
    
    public function test_createReportSelectionParameterName() {
        $inputParameterID = 1;
        $inputParameterName = "the name";
        $inputDisplayOrder = 3;
        $inputAllowMultipleSelections = 0;
        
        $this->obj = Report_selection_parameter::createReportSelectionParameter(
            $inputParameterID, $inputParameterName, $inputDisplayOrder, $inputAllowMultipleSelections);
        $expected = "the name";
        $this->assertEquals($expected, $this->obj->getParameterName());
    }
    
    public function test_createReportSelectionParameterDisplayOrder() {
        $inputParameterID = 1;
        $inputParameterName = "the name";
        $inputDisplayOrder = 3;
        $inputAllowMultipleSelections = 0;
        
        $this->obj = Report_selection_parameter::createReportSelectionParameter(
            $inputParameterID, $inputParameterName, $inputDisplayOrder, $inputAllowMultipleSelections);
        $expected = 3;
        $this->assertEquals($expected, $this->obj->getParameterDisplayOrder());
    }
    
    public function test_createReportSelectionParameterAllowMultiple() {
        $inputParameterID = 1;
        $inputParameterName = "the name";
        $inputDisplayOrder = 3;
        $inputAllowMultipleSelections = 0;
        
        $this->obj = Report_selection_parameter::createReportSelectionParameter(
            $inputParameterID, $inputParameterName, $inputDisplayOrder, $inputAllowMultipleSelections);
        $expected = 0;
        $this->assertEquals($expected, $this->obj->getAllowMultipleSelections());
    }
    
    public function test_createReportSelectionParameterIDNull() {
        $inputParameterID = null;
        $inputParameterName = "the name";
        $inputDisplayOrder = 3;
        $inputAllowMultipleSelections = 0;
        
        $this->obj = Report_selection_parameter::createReportSelectionParameter(
            $inputParameterID, $inputParameterName, $inputDisplayOrder, $inputAllowMultipleSelections);
        $expected = null;
        $this->assertEquals($expected, $this->obj->getParameterID());
    }
    
    public function test_createReportSelectionParameterIDEmpty() {
        $inputParameterID = "";
        $inputParameterName = "the name";
        $inputDisplayOrder = 3;
        $inputAllowMultipleSelections = 0;
        
        $this->obj = Report_selection_parameter::createReportSelectionParameter(
            $inputParameterID, $inputParameterName, $inputDisplayOrder, $inputAllowMultipleSelections);
        $expected = "";
        $this->assertEquals($expected, $this->obj->getParameterID());
    }
}
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

    public function test_initialiseSelectableReportOptions_OneValue() {
        $arrayStore = new Array_store();

        $inputParameterID = 1;
        $inputParameterName = "the name";
        $inputDisplayOrder = 3;
        $inputAllowMultipleSelections = 0;

        $this->obj = Report_selection_parameter::createReportSelectionParameter(
            $inputParameterID, $inputParameterName, $inputDisplayOrder, $inputAllowMultipleSelections);
        $this->obj->initialiseSelectableReportOptions($arrayStore);

        $selectableReportOption1 = new Selectable_report_option();
        $selectableReportOption1->setOptionName("Some option name");
        $selectableReportOption1->setOptionDisplayOrder(1);

        $expected = array(
            $selectableReportOption1
        );

        $actual = $this->obj->getSelectableReportOptions();
        $this->assertEquals(count($expected), count($actual));
        $this->assertEquals($expected[0]->getOptionName(), $actual[0]->getOptionName());
        $this->assertEquals($expected[0]->getOptionDisplayOrder(), $actual[0]->getOptionDisplayOrder());

    }

    public function test_initialiseSelectableReportOptions_TwoValues() {
        $arrayStore = new Array_store();

        $inputParameterID = 4;
        $inputParameterName = "the name";
        $inputDisplayOrder = 3;
        $inputAllowMultipleSelections = 0;

        $this->obj = Report_selection_parameter::createReportSelectionParameter(
            $inputParameterID, $inputParameterName, $inputDisplayOrder, $inputAllowMultipleSelections);
        $this->obj->initialiseSelectableReportOptions($arrayStore);

        $selectableReportOption1 = new Selectable_report_option();
        $selectableReportOption1->setOptionName("Some option name");
        $selectableReportOption1->setOptionDisplayOrder(1);

        $selectableReportOption2 = new Selectable_report_option();
        $selectableReportOption2->setOptionName("Another option name");
        $selectableReportOption2->setOptionDisplayOrder(2);

        $expected = array(
            $selectableReportOption1,
            $selectableReportOption2
        );

        $actual = $this->obj->getSelectableReportOptions();

        $this->assertEquals(count($expected), count($actual));
        $this->assertEquals($expected[0]->getOptionName(), $actual[0]->getOptionName());
        $this->assertEquals($expected[0]->getOptionDisplayOrder(), $actual[0]->getOptionDisplayOrder());
        $this->assertEquals($expected[1]->getOptionName(), $actual[1]->getOptionName());
        $this->assertEquals($expected[1]->getOptionDisplayOrder(), $actual[1]->getOptionDisplayOrder());


    }
}
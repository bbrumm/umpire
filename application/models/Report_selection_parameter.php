<?php
/*
 * This class defines a ReportSelectionParameter, which is a collection of SelectableReportOptions on the report selection page.
 * It matches to a single drop-down on the page.
 *  
 */

class Report_selection_parameter extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Selectable_report_option');
        $this->load->library('Debug_library');
        $this->load->model('Database_store');
    }

    public static function createReportSelectionParameter($pParameterID, $pParameterName, 
        $pParameterDisplayOrder, $pAllowMultipleSelections) {

        $obj = new Report_selection_parameter();
        $obj->setParameterID($pParameterID);
        //$obj->setSelectableReportOptions($obj->loadSelectableReportOptions());
        $obj->setParameterName($pParameterName);
        $obj->setParameterDisplayOrder($pParameterDisplayOrder);
        $obj->setAllowMultipleSelections($pAllowMultipleSelections);

        return $obj;
    }
    
    private $parameterID;
    private $selectableReportOptions;
    private $parameterName;
    private $parameterDisplayOrder;
    private $allowMultipleSelections;
    

    //Get Functions
    public function getParameterID() {
        return $this->parameterID;
    }
    
    public function getSelectableReportOptions() {
        return $this->selectableReportOptions;
    }
    
    public function getParameterName() {
        return $this->parameterName;
    }
    
    public function getParameterDisplayOrder() {
        return $this->parameterDisplayOrder;
    }
    
    public function getAllowMultipleSelections() {
        return $this->allowMultipleSelections;
    }
    

    //Set Functions
    private function setParameterID($pValue) {
        $this->parameterID = $pValue;
    }
    
    private function setSelectableReportOptions($pValue) {
        $this->selectableReportOptions = $pValue;
    }
    
    private function setParameterName($pValue) {
        $this->parameterName = $pValue;
    }
    
    private function setParameterDisplayOrder($pValue) {
        $this->parameterDisplayOrder = $pValue;
    }
    
    private function setAllowMultipleSelections($pValue) {
        $this->allowMultipleSelections = $pValue;
    }
    
    public function initialiseSelectableReportOptions(IData_store $pDataStore) {
        $this->loadSelectableReportOptions($pDataStore);
        //$this->debug_library->debugOutput("initialiseSelectableReportOptions : ", $this->getSelectableReportOptions());
    }
    
    private function loadSelectableReportOptions(IData_store $pDataStore) {
        $parameterID = $this->getParameterID();
        $this->setSelectableReportOptions($pDataStore->loadSelectableReportOptions($parameterID));
    }
}
?>

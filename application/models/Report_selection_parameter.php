<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');
//require_once(__ROOT__.'/config/constants.php');
/*
 * This class defines a ReportSelectionParameter, which is a collection of SelectableReportOptions on the report selection page.
 * It matches to a single drop-down on the page.
 *  
 */

//class SelectableReportOption extends MY_Model
class Report_selection_parameter extends CI_Model
{
    /* Code .. */

    function __construct()
    {
        parent::__construct();
        $this->load->model('Selectable_report_option');
        //$this->load->config('constants');
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
    public function setParameterID($pValue) {
        $this->parameterID = $pValue;
    }
    
    private function setSelectableReportOptions($pValue) {
        $this->selectableReportOptions = $pValue;
    }
    
    public function setParameterName($pValue) {
        $this->parameterName = $pValue;
    }
    
    public function setParameterDisplayOrder($pValue) {
        $this->parameterDisplayOrder = $pValue;
    }
    
    public function setAllowMultipleSelections($pValue) {
        $this->allowMultipleSelections = $pValue;
    }
    
    
    
    public function loadSelectableReportOptions() {
        /*$queryString = "SELECT parameter_name, parameter_display_order, allow_multiple_selections " . 
            "FROM report_selection_parameters " .
            "WHERE parameter_id = $parameterID " .
            "ORDER BY parameter_display_order;";
                */
        
        //$selectableReportOptionsForParameter[] = '';
        $parameterID = $this->getParameterID();
        
        $queryString = "SELECT parameter_value_name, parameter_display_order " .
            "FROM report_selection_parameter_values " .
            "WHERE parameter_id = $parameterID " .
            "ORDER BY parameter_display_order;";
            
        $query = $this->db->query($queryString);
        foreach ($query->result() as $row) {
            $selectableReportOption = new Selectable_report_option();
            $selectableReportOption->setOptionName($row->parameter_value_name);
            $selectableReportOption->setOptionValue($row->parameter_value_name);
            $selectableReportOption->setOptionDisplayOrder($row->parameter_display_order);
            
            $selectableReportOptionsForParameter[] = $selectableReportOption;
            //print_r($selectableReportOption);
        }
         
        $this->setSelectableReportOptions($selectableReportOptionsForParameter);
            
        
    
    
    }
}
?>
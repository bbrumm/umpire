<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');
//require_once(__ROOT__.'/config/constants.php');
/*
 * This class defines a ReportSelectionParameter, which is a collection of SelectableReportOptions on the report selection page.
 * It matches to a single drop-down on the page.
 *  
 */

//class SelectableReportOption extends MY_Model
class ReportSelectionParameter extends CI_Model
{
    /* Code .. */

    function __construct()
    {
        parent::__construct();
        $this->load->model('SelectableReportOption');
        //$this->load->config('constants');
    }
    
    private $selectableReportOptions;

    //Get Functions
    public function getSelectableReportOptions() {
        return $this->selectableReportOptions;
    }
    

    //Set Functions
    public function setSelectableReportOptions($pValue) {
        $this->selectableReportOptions = $pValue;
    }
    
    public function loadSelectableReportOptions($parameterID) {
        /*$queryString = "SELECT parameter_name, parameter_display_order, allow_multiple_selections " . 
            "FROM report_selection_parameters " .
            "WHERE parameter_id = $parameterID " .
            "ORDER BY parameter_display_order;";
                */
        
        $selectableReportOptionsForParameter[] = '';
        
        $queryString = "SELECT parameter_value_name, parameter_display_order " .
            "FROM report_selection_parameter_values " .
            "WHERE parameter_id = $parameterID " .
            "ORDER BY parameter_display_order;";
            
        $query = $this->db->query($queryString);
        foreach ($query->result() as $row) {
            $selectableReportOption = new SelectableReportOption();
            $selectableReportOption->setOptionName($row->parameter_value_name);
            $selectableReportOption->setOptionValue($row->parameter_value_name);
            $selectableReportOption->setOptionDisplayOrder($row->parameter_display_order);
            
            $selectableReportOptionsForParameter[] = $selectableReportOption;
        }
         
        return $selectableReportOptionsForParameter;
            
        
    
    
    }
}
?>
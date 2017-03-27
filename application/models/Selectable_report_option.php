<?php
/*
 * This class defines a SelectableReportOption, which is contained within a drop-down box on the report selection page. 
 * It is single selection inside one of the drop-downs
 *  
 */

class Selectable_report_option
{
    function __construct() {
        //parent::__construct();
    }
    
    private $optionName;
    private $optionValue;
    private $optionDisplayOrder;
    //TODO: Add functionality here, maybe a related object, for privileges for certain users.
    
    //Get Functions
    public function getOptionName() {
        return $this->optionName;
    }
    
    public function getOptionValue() {
        return $this->optionValue;
    }
    
    public function getOptionDisplayOrder() {
        return $this->optionDisplayOrder;
    }
    
    
    //Set Functions
    public function setOptionName($pValue) {
        $this->optionName = $pValue;
    }
    
    public function setOptionValue($pValue) {
        $this->optionValue = $pValue;
    }
    
    public function setOptionDisplayOrder($pValue) {
        $this->optionDisplayOrder = $pValue;
    }
    
    

}
?>
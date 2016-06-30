<?php
/*
ReportParameter
This class represents a parameter for a particular report. It's a join of the report_parameter and 
report_parameter_map tables.
It contains information such as the report title, the value to display, and the PDF resolution.


*/
class ReportParameter 
{
    function __construct()
    {
        //parent::__construct()
        //$this->load->model('report_param/ReportGroupingStructure');
    }

    private $reportID;
    private $reportParameterID;
    private $parameterName;
    private $parameterType;
    private $parameterValue;
    //private $reportGroupingStructure;

    //Get Functions
    public function getReportID() {
        return $this->reportID;
    }
    
    public function getReportParameterID() {
        return $this->reportParameterID;
    }

    public function getParameterName() {
        return $this->parameterName;
    }

    public function getParameterType() {
        return $this->parameterType;
    }
    
    public function getParameterValue() {
        return $this->parameterValue;
    }
    /*
    public function getReportGroupingStructure() {
        return $this->reportGroupingStructure;
    }
*/

    //Set Functions
    public function setReportID($pValue) {
        $this->reportID = $pValue;
    }
    
    public function setReportParameterID($pValue) {
        $this->reportParameterID = $pValue;
    }

    public function setParameterName($pValue) {
        $this->parameterName= $pValue;
    }

    public function setParameterType($pValue) {
        $this->parameterType = $pValue;
    }
    
    public function setParameterValue($pValue) {
        $this->parameterValue = $pValue;
    }
   /* 
    public function setReportGroupingStructure($pValue) {
        $this->reportGroupingStructure = $pValue;
    }
*/
}

?>
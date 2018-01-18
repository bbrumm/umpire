<?php
/*
 * Report_selection class
 * This represents a report that can be selected on the Report Selection page.
 * It is used to populate the drop-down of reports on the home page.
 */

class Report_selection extends CI_Model {
    
    private $reportID;
    private $reportName;
    
    public function __construct() {
        
    }
    
    public function getReportID() {
        return $this->reportID;
    }
    
    public function getReportName() {
        return $this->reportName;
    }
    
    public function setReportID($pValue) {
        if(is_numeric($pValue)) {
            $this->reportID = $pValue;
        } else {
            throw new Exception('ReportID must be numeric.');
        }
    }
    
    public function setReportName($pValue) {
        $this->reportName = $pValue;
    }
    
    
}
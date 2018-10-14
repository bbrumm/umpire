<?php
require_once 'IData_store.php';
class Array_store extends CI_Model implements IData_store {
    
    
    public function __construct() {
        
    }
    
    public function loadAllReportParameters($pReportNumber) {
        $reportParameter = Report_parameter::createNewReportParameter(
            'Random Title',
            1,
            0,
            'text',
            true,
            'portrait',
            'a4',
            200
            );
        
        return $reportParameter;
    }
    
}
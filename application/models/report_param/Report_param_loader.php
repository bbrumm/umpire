<?php
/*
 * ReportParamLoader
 * Loads all of the report parameters and the report grouping structures from the database, and
 * creates objects for the application to use.
 *  
 */


class Report_param_loader extends CI_Model {
   
    function __construct()
    {
        parent::__construct();
        $this->load->model('report_param/Report_grouping_structure');
        $this->load->model('report_param/Report_parameter');
        $this->load->model('Database_store_matches');
    }
    
    private $reportParameter;
    private $reportGroupingStructureArray;
    
    public function getReportParameter() {
        return $this->reportParameter;
    }
    
    public function setReportParameter($pValue) {
        $this->reportParameter = $pValue;
    }
    
    public function getReportGroupingStructureArray() {
        return $this->reportGroupingStructureArray;
    }
    
    public function setReportGroupingStructureArray($pValue) {
        $this->reportGroupingStructureArray = $pValue;
    }
    
    public function loadAllReportParametersForReport(Requested_report_model $pRequestedReport, IData_store_matches $pDataStore) {
        $this->setReportParameter($pDataStore->loadAllReportParameters($pRequestedReport->getReportNumber()));
    }

    public function loadAllGroupingStructuresForReport(Requested_report_model $pRequestedReport, IData_store_matches $pDataStore) {
        //Load all report grouping structures
        $this->setReportGroupingStructureArray($pDataStore->loadAllGroupingStructures($pRequestedReport->getReportNumber()));
    }
}

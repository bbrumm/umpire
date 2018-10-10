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
    
    
    public function loadAllReportParametersForReport(Requested_report_model $pRequestedReport) {
        //Load all report parameters
        $queryDataReportParameters = $this->queryReportParameters($pRequestedReport);
        $queryResultArray = $queryDataReportParameters->result_array();  
        
        $reportParameter = Report_parameter::createNewReportParameter(
            $queryResultArray[0]['report_title'],
            $queryResultArray[0]['value_field_id'].
            $queryResultArray[0]['no_value_display'].
            $queryResultArray[0]['first_column_format'],
            $queryResultArray[0]['colour_cells'],
            $queryResultArray[0]['orientation'],
            $queryResultArray[0]['paper_size'],
            $queryResultArray[0]['resolution']
        );
            
        $this->setReportParameter($reportParameter);
    }

    /**
    * @property string $config
    */
    public function loadAllGroupingStructuresForReport(Requested_report_model $pRequestedReport) {
        $debugMode = $this->config->item('debug_mode');
        
        //Load all report grouping structures
        $queryDataReportGroupingStructures = 
            $this->queryReportGroupingStructures($pRequestedReport);
        
        //Create report param and report grouping objects for this report
        foreach ($queryDataReportGroupingStructures->result() as $row) {
            $reportGroupingStructure = Report_grouping_structure::createNewReportGroupingStructure(
                $row->report_grouping_structure_id,
                $row->grouping_type,
                $row->field_name,
                $row->field_group_order,
                $row->merge_field,
                $row->group_heading,
                $row->group_size_text
            );
            
            $reportGroupingStructureArray[] = $reportGroupingStructure;
        }
         
        $this->setReportGroupingStructureArray($reportGroupingStructureArray);
        if ($debugMode) {
            echo "<pre>reportGroupingStructureArray in load function:";
            print_r($reportGroupingStructureArray);
            echo "</pre>";
        }
        
    }
    
    private function queryReportParameters(Requested_report_model $pRequestedReport) {
        $queryString = "SELECT
            t.report_name,
            t.report_title,
            t.value_field_id,
            t.no_value_display,
            t.first_column_format,
            t.colour_cells,
            p.orientation,
            p.paper_size,
            p.resolution
            FROM t_report t
            INNER JOIN t_pdf_settings p ON t.pdf_settings_id = p.pdf_settings_id
            WHERE t.report_id = ". $pRequestedReport->getReportNumber() .";";
        
        $query = $this->db->query($queryString);
        
        return $query;
    }
    
    private function queryReportGroupingStructures(Requested_report_model $pRequestedReport) {        
        $debugMode = $this->config->item('debug_mode');
        $queryString = "SELECT rgs.report_grouping_structure_id, rgs.grouping_type, " .
            "fl.field_name, rgs.field_group_order, rgs.merge_field, rgs.group_heading, rgs.group_size_text " .
            "FROM report_table rt " .
            "INNER JOIN report_grouping_structure rgs ON rt.report_name = rgs.report_id " .
            "INNER JOIN field_list fl ON rgs.field_id = fl.field_id " .
            "WHERE rt.report_name = ". $pRequestedReport->getReportNumber() ." " .
            "ORDER BY rgs.grouping_type, rgs.field_group_order;";
    
        if ($debugMode) {
            echo "queryReportGroupingStructures: $queryString <BR />";
        }
        
        $query = $this->db->query($queryString);
        
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            throw new Exception("No results found in reportGroupingStructure tables for report. Check query in ReportParamLoader/queryReportGroupingStructures");
        }

    }
        
}

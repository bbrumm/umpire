<?php
/*
 * ReportParamLoader
 * Loads all of the report parameters and the report grouping structures from the database, and
 * creates objects for the application to use.
 *  
 */


class ReportParamLoader extends CI_Model {
   
    function __construct()
    {
        parent::__construct();
        $this->load->model('report_param/ReportGroupingStructure');
        $this->load->model('report_param/ReportParameter');
    }
    
    private $reportParameterArray;
    private $reportGroupingStructureArray;
    
    public function getReportParameterArray() {
        return $this->reportParameterArray;
    }
    
    public function setReportParameterArray($pValue) {
        $this->reportParameterArray = $pValue;
    }
    
    public function getReportGroupingStructureArray() {
        return $this->reportGroupingStructureArray;
    }
    
    public function setReportGroupingStructureArray($pValue) {
        $this->reportGroupingStructureArray = $pValue;
    }
    
    
    public function loadAllReportParametersForReport($pReportID) {
        //Load all report parameters
        $queryDataReportParameters = $this->queryReportParameters($pReportID);
        
        /*
        echo "<pre>queryDataReportParameters:";
        print_r($queryDataReportParameters);
        echo "</pre>";
        */
        
        //Create report param and report grouping objects for this report
        foreach ($queryDataReportParameters->result() as $row) {
            $reportParameter = new ReportParameter();
            $reportParameter->setParameterName($row->parameter_name);
            $reportParameter->setParameterType($row->parameter_type);
            $reportParameter->setParameterValue($row->parameter_value);
            
            $reportParameterArray[] = $reportParameter;
        }
         
        //return $reportParameterArray;
        $this->setReportParameterArray($reportParameterArray);
        /*   
        echo "<pre>reportParameterArray in load function:";
        print_r($reportParameterArray);
        echo "</pre>";
        */
    }
    
    public function loadAllGroupingStructuresForReport($pReportID) {
        $debugMode = $this->config->item('debug_mode');
        
        //Load all report grouping structures
        $queryDataReportGroupingStructures = $this->queryReportGroupingStructures($pReportID);
        
        //Create report param and report grouping objects for this report
        foreach ($queryDataReportGroupingStructures->result() as $row) {
            $reportGroupingStructure = new ReportGroupingStructure();
            $reportGroupingStructure->setReportGroupingStructureID($row->report_grouping_structure_id);
            $reportGroupingStructure->setGroupingType($row->grouping_type);
            $reportGroupingStructure->setFieldName($row->field_name);
            $reportGroupingStructure->setFieldGroupOrder($row->field_group_order);
            $reportGroupingStructure->setMergeField($row->merge_field);
            $reportGroupingStructure->setGroupHeading($row->group_heading);
            $reportGroupingStructure->setGroupSizeText($row->group_size_text);
            
            $reportGroupingStructureArray[] = $reportGroupingStructure;
        }
         
        $this->setReportGroupingStructureArray($reportGroupingStructureArray);
        if ($debugMode) {
            echo "<pre>reportGroupingStructureArray in load function:";
            print_r($reportGroupingStructureArray);
            echo "</pre>";
        }
        
    }
    
    private function queryReportParameters($pReportID) {
        $queryString = "SELECT rp.report_parameter_id,rp.parameter_name, " .
            "rp.parameter_type, rpm.parameter_value " .
            "FROM report_table rt " .
            "INNER JOIN report_parameter_map rpm ON rpm.report_id = rt.report_name " .
            "INNER JOIN report_parameter rp ON rpm.report_parameter_id = rp.report_parameter_id " .
            "WHERE rt.report_name = '$pReportID' " .
            "AND rp.parameter_type = 'Text' " .
            "UNION ALL " .
            "SELECT rp.report_parameter_id,rp.parameter_name, " .
            "rp.parameter_type, fl.field_name " .
            "FROM report_table rt " .
            "INNER JOIN report_parameter_map rpm ON rpm.report_id = rt.report_name " .
            "INNER JOIN report_parameter rp ON rpm.report_parameter_id = rp.report_parameter_id " .
            "INNER JOIN field_list fl ON rpm.parameter_value = fl.field_id " .
            "WHERE rt.report_name = '$pReportID' " .
            "AND rp.parameter_type = 'Field' " .
            "ORDER BY report_parameter_id";
        
        $query = $this->db->query($queryString);
        
        return $query;
    }
    
    private function queryReportGroupingStructures($pReportID) {        
        $queryString = "SELECT rgs.report_grouping_structure_id, rgs.grouping_type, " .
            "fl.field_name, rgs.field_group_order, rgs.merge_field, rgs.group_heading, rgs.group_size_text " .
            "FROM report_table rt " .
            "INNER JOIN report_grouping_structure rgs ON rt.report_name = rgs.report_id " .
            "INNER JOIN field_list fl ON rgs.field_id = fl.field_id " .
            "WHERE rt.report_name = '$pReportID' " .
            "ORDER BY rgs.grouping_type, rgs.field_group_order;";
    
        $query = $this->db->query($queryString);
    
        return $query;
    }
       
}
?>
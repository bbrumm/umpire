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
            
            
        $reportParameter = new Report_parameter();
        
        
        $reportParameter->setReportTitle($queryResultArray[0]['report_title']);
        $reportParameter->setValueFieldID($queryResultArray[0]['value_field_id']);
        $reportParameter->setNoValueDisplay($queryResultArray[0]['no_value_display']);
        $reportParameter->setFirstColumnFormat($queryResultArray[0]['first_column_format']);
        $reportParameter->setColourCells($queryResultArray[0]['colour_cells']);
        $reportParameter->setPDFOrientation($queryResultArray[0]['orientation']);
        $reportParameter->setPDFPaperSize($queryResultArray[0]['paper_size']);
        $reportParameter->setPDFResolution($queryResultArray[0]['resolution']);
            
        /*    
        foreach ($queryDataReportParameters->result() as $row) {
            $reportParameter = new Report_parameter();
            $reportParameter->setParameterName($row->parameter_name);
            $reportParameter->setParameterType($row->parameter_type);
            $reportParameter->setParameterValue($row->parameter_value);
            
            $reportParameterArray[] = $reportParameter;
        }
        */
        $this->setReportParameter($reportParameter);
    }
    
    public function loadAllGroupingStructuresForReport(Requested_report_model $pRequestedReport) {
        $debugMode = $this->config->item('debug_mode');
        
        //Load all report grouping structures
        $queryDataReportGroupingStructures = 
            $this->queryReportGroupingStructures($pRequestedReport);
        
        //Create report param and report grouping objects for this report
        foreach ($queryDataReportGroupingStructures->result() as $row) {
            $reportGroupingStructure = new Report_grouping_structure();
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
    
    private function queryReportParameters(Requested_report_model $pRequestedReport) {
        
        /*
        $queryString = "SELECT rp.report_parameter_id,rp.parameter_name, " .
            "rp.parameter_type, rpm.parameter_value " .
            "FROM report_table rt " .
            "INNER JOIN report_parameter_map rpm ON rpm.report_id = rt.report_name " .
            "INNER JOIN report_parameter rp ON rpm.report_parameter_id = rp.report_parameter_id " .
            "WHERE rt.report_name = ". $pRequestedReport->getReportNumber() ." " .
            "AND rp.parameter_type = 'Text' " .
            "UNION ALL " .
            "SELECT rp.report_parameter_id,rp.parameter_name, " .
            "rp.parameter_type, fl.field_name " .
            "FROM report_table rt " .
            "INNER JOIN report_parameter_map rpm ON rpm.report_id = rt.report_name " .
            "INNER JOIN report_parameter rp ON rpm.report_parameter_id = rp.report_parameter_id " .
            "INNER JOIN field_list fl ON rpm.parameter_value = fl.field_id " .
            "WHERE rt.report_name = ". $pRequestedReport->getReportNumber() ." " .
            "AND rp.parameter_type = 'Field' " .
            "ORDER BY report_parameter_id";
        */
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
    
    public function lookupParameterValue($reportParameterArray, $parameterName) {
        $parameterValue = "";
         
        for($i=0; $i<count($reportParameterArray); $i++) {
            $reportParameter = new Report_parameter();
            $reportParameter = $reportParameterArray[$i];
             
            if($reportParameter->getParameterName() == $parameterName) {
                $parameterValue = $reportParameter->getParameterValue();
                break;
            }
        }
        return $parameterValue;
    }
       
}
?>
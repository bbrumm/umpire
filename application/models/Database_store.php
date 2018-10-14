<?php
require_once 'IData_store.php';
class Database_store extends CI_Model implements IData_store {
    
    
    public function __construct() {
        
    }
    
    public function loadAllReportParameters($pReportNumber) {
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
            WHERE t.report_id = ". $pReportNumber .";";
        
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        
        $reportParameter = Report_parameter::createNewReportParameter(
            $queryResultArray[0]['report_title'],
            $queryResultArray[0]['value_field_id'],
            $queryResultArray[0]['no_value_display'],
            $queryResultArray[0]['first_column_format'],
            $queryResultArray[0]['colour_cells'],
            $queryResultArray[0]['orientation'],
            $queryResultArray[0]['paper_size'],
            $queryResultArray[0]['resolution']
            );
        
        return $reportParameter;
    }
    
}
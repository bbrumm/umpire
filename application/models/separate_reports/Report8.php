<?php
require_once 'IReport.php';

class Report8 extends CI_Model implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "season_year, ".
            "full_name, ".
            "match_count ".
            "FROM dw_mv_report_08 ".
            "ORDER BY full_name, season_year;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT column_heading AS season_year ".
            "FROM report_column_display_order ".
            "WHERE report_id = 8 ".
            "ORDER BY display_order;";
        return $queryString;
    }
    
    public function transformQueryResultsIntoOutputArray($pResultArray, $columnLabelResultArray, $pReportColumnFields) {
        
    }
    
    public function formatOutputArrayForView() {
        
    }

    public function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {

    }
    
}

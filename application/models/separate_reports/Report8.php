<?php
include_once 'Report_data_query.php';

class Report8 extends Report_data_query {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT
            season_year,
            full_name,
            match_count
            FROM dw_mv_report_08
            ORDER BY full_name, season_year;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        /*$queryString = "SELECT 'Other Games' AS colheading
            UNION ALL
            (SELECT DISTINCT season_year
                FROM dw_mv_report_08
                ORDER BY season_year)
            UNION ALL
            SELECT 'Total'
            ;";
        */
        $queryString = "SELECT column_heading AS season_year
            FROM
            report_column_display_order
            WHERE report_id = 8
            ORDER BY display_order;";
        return $queryString;
    }
    
}
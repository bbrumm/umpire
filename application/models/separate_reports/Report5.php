<?php
require_once 'IReport.php';

class Report5 extends CI_Model implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT umpire_type, ".
            "age_group, ".
            "short_league_name, ".
            "match_no_ump, ".
            "total_match_count, ".
            "match_pct ".
            "FROM dw_mv_report_05 ".
            "WHERE short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "ORDER BY umpire_type, age_sort_order, league_sort_order;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT ".
            "l.short_league_name, ".
            "sub.subtotal ".
            "FROM dw_dim_league l ".
            "CROSS JOIN ( ".
                "SELECT 'Games' AS subtotal ".
                "UNION ".
                "SELECT 'Total' ".
                "UNION ".
                "SELECT 'Pct' ".
            ") AS sub ".
            "WHERE l.short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "UNION ALL ".
            "SELECT 'All', 'Total';";
        return $queryString;
    }
    
    public function transformQueryResultsIntoOutputArray() {
        
    }
    
    public function formatOutputArrayForView() {
        
    }
    
}

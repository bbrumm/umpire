<?php
require_once 'IReport.php';

class Report7 extends CI_Model implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "umpire_type, ".
            "age_group, ".
            "short_league_name, ".
            "umpire_count, ".
            "match_count ".
            "FROM dw_mv_report_07 ".
            "WHERE season_year IN (". $pReportInstance->requestedReport->getSeason() .") ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN ('Field') ".
            "ORDER BY age_sort_order, league_sort_order, umpire_type, umpire_count;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
    $queryString = "SELECT DISTINCT ".
            "short_league_name, ".
            "umpire_count ".
            "FROM ( ".
            "SELECT DISTINCT ".
            "short_league_name, ".
            "league_sort_order, ".
            "'2 Umpires' AS umpire_count ".
            "FROM dw_mv_report_07 ".
            "WHERE season_year IN (". $pReportInstance->requestedReport->getSeason() .") ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN ('Field') ".
            "UNION ALL ".
            "SELECT DISTINCT ".
            "short_league_name, ".
            "league_sort_order, ".
            "'3 Umpires' ".
            "FROM dw_mv_report_07 ".
            "WHERE season_year IN (". $pReportInstance->requestedReport->getSeason() .") ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN ('Field') ".
            ") AS sub ".
            "ORDER BY league_sort_order, umpire_count;";
        return $queryString;
    }
    
    public function transformQueryResultsIntoOutputArray() {
        
    }
    
    public function formatOutputArrayForView() {
       
    }
    
}

<?php
include_once 'Report_data_query.php';

class Report6 extends Report_data_query {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "umpire_type, ".
            "age_group, ".
            "region_name, ".
            "first_umpire, ".
            "second_umpire, ".
            "match_count ".
            "FROM dw_mv_report_06 ".
            "WHERE season_year IN (". $pReportInstance->requestedReport->getSeason() .") ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .") ".
            "AND short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "ORDER BY first_umpire, second_umpire;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
    $queryString = "SELECT DISTINCT second_umpire ".
            "FROM dw_mv_report_06 ".
            "WHERE season_year IN (". $pReportInstance->requestedReport->getSeason() .") ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .") ".
            "AND short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "ORDER BY second_umpire;";
        return $queryString;
    }
    
}
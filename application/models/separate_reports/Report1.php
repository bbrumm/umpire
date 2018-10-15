<?php
require_once 'IReport.php';

class Report1 extends CI_Model implements IReport {
    
    public function __construct() {
    }
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "last_first_name, ".
            "short_league_name, ".
            "club_name, ".
            "age_group, ".
            "SUM(match_count) AS match_count ".
            "FROM dw_mv_report_01 ".
            "WHERE age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "GROUP BY last_first_name, short_league_name, club_name ".
            "ORDER BY last_first_name, short_league_name, club_name;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT short_league_name, club_name ".
           "FROM dw_mv_report_01 ".
           "WHERE age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
           "AND short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
           "AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
           "AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .") ".
           "ORDER BY short_league_name, club_name;";
        return $queryString;
    }
    
    public function transformQueryResultsIntoOutputArray() {
        
    };
    
    public function formatOutputArrayForView() {
        
    };
    
}

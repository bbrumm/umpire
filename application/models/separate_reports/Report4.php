<?php
require_once 'IReport.php';

class Report4 extends CI_Model implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT ".
            "club_name, ".
            "age_group, ".
            "short_league_name, ".
            "umpire_type, ".
            "match_count ".
            "FROM dw_mv_report_04 ".
            "WHERE region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "ORDER BY club_name, age_sort_order, league_sort_order;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT ".
            "s.umpire_type, ".
            "s.age_group, ".
            "s.short_league_name ".
            "FROM staging_all_ump_age_league s ".
            "WHERE s.short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "ORDER BY s.umpire_type, s.age_sort_order, s.league_sort_order;";
        return $queryString;
    }
    
    public function transformQueryResultsIntoOutputArray($pResultArray, $columnLabelResultArray, $pReportColumnFields) {
        
    }
    
    public function formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells) {
        
    }

    public function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields) {

    }
    
}

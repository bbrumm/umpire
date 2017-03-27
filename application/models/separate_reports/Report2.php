<?php
include_once 'Report_data_query.php';

class Report2 extends Report_data_query {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT
            last_first_name,
            age_group,
            age_sort_order,
            short_league_name,
            two_ump_flag,
            SUM(match_count) AS match_count
            FROM dw_mv_report_02
            WHERE age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .")
            AND short_league_name IN ('2 Umpires', ". $pReportInstance->filterParameterLeague->getFilterSQLValues() .")
            AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .")
            AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .")
            AND season_year = ". $pReportInstance->requestedReport->getSeason() ."
            GROUP BY last_first_name, age_group, age_sort_order, short_league_name, two_ump_flag
            ORDER BY last_first_name, age_sort_order, short_league_name;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT age_group, short_league_name
            FROM (
                SELECT
	            age_group,
	            age_sort_order,
                league_sort_order,
	            short_league_name
                FROM dw_mv_report_02
                WHERE age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .")
	            AND short_league_name IN ('2 Umpires', ". $pReportInstance->filterParameterLeague->getFilterSQLValues() .")
	            AND region_name IN (". $pReportInstance->filterParameterRegion->getFilterSQLValues() .")
	            AND umpire_type IN (". $pReportInstance->filterParameterUmpireType->getFilterSQLValues() .")
	            AND season_year = ". $pReportInstance->requestedReport->getSeason() ."
	            UNION ALL
	            SELECT
                'Total',
                50,
                50,
                ''
            ) AS sub
            ORDER BY age_sort_order, league_sort_order;";
        return $queryString;
    }
    
}
<?php
require_once 'IReport.php';

class Report3 extends CI_Model implements IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance) {
        //This has remained as a query on staging tables instead of moving to a MV table, because of the subquery using parameters from the UI selection.
        //Creating a MV would look similar to this and probably wouldn't improve performance.
        $queryString = "SELECT ".
            "weekend_date, ".
            "CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group, ".
            "short_league_name, ".
            "GROUP_CONCAT(team_names) AS team_list, (".
                "SELECT ".
            	"COUNT(DISTINCT match_id) ".
            	"FROM staging_no_umpires s2 ".
            	"WHERE s2.age_group = s.age_group ".
            	"AND s2.umpire_type = s.umpire_type ".
                "AND s2.weekend_date = s.weekend_date ".
                "AND short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            ") AS match_count ".
            "FROM staging_no_umpires s ".
            "WHERE short_league_name IN (". $pReportInstance->filterParameterLeague->getFilterSQLValues() .") ".
            "AND season_year = ". $pReportInstance->requestedReport->getSeason() ." ".
            "AND CONCAT(age_group, ' ', umpire_type) IN ( ".
                "'Seniors Boundary', ".
            	"'Seniors Goal', ".
            	"'Reserve Goal', ".
            	"'Colts Field', ".
            	"'Under 16 Field', ".
            	"'Under 14 Field', ".
            	"'Under 12 Field' ".
            ") ".
            "GROUP BY weekend_date, age_group, umpire_type, short_league_name ".
            "ORDER BY weekend_date, age_group, umpire_type, short_league_name;";
        return $queryString;
    }
    
    public function getReportColumnQuery(Report_instance $pReportInstance) {
        $queryString = "SELECT DISTINCT ".
        	"CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group, ".
        	"short_league_name ".
        	"FROM ( ".
            	"SELECT ".
            	"s.age_group, ".
            	"s.umpire_type, ".
            	"s.short_league_name, ".
                "s.region_name, ".
            	"s.age_sort_order ".
            	"FROM staging_all_ump_age_league s ".
            	"UNION ALL ".
            	"SELECT ".
            	"s.age_group, ".
            	"s.umpire_type, ".
            	"'Total', ".
                "'Total', ".
            	"s.age_sort_order ".
            	"FROM staging_all_ump_age_league s ".
            ") sub ".
            "WHERE CONCAT(age_group, ' ', umpire_type) IN ".
            	"('Seniors Boundary' , 'Seniors Goal', 'Reserves Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field') ".
            "AND age_group IN (". $pReportInstance->filterParameterAgeGroup->getFilterSQLValues() .") ".
            "AND region_name IN ('Total', ". $pReportInstance->filterParameterRegion->getFilterSQLValues() .") ".
            "ORDER BY age_sort_order, umpire_type, short_league_name;";
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

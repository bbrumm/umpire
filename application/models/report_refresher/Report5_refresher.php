<?php
class Report5_refresher extends Report_table_refresher {

    function __construct() {
        parent::__construct();
    }

    public static function createRefresher($pImportFileID, $pSeason) {
        $reportTableRefresher = new Report5_refresher();
        $reportTableRefresher->setTableName("dw_mv_report_05");
        $reportTableRefresher->setImportFileID($pImportFileID);
        $reportTableRefresher->setSeasonYear($pSeason);

        $queryString = "INSERT INTO dw_mv_report_05 (umpire_type, age_group, age_sort_order, short_league_name, league_sort_order, region_name, match_no_ump, total_match_count, match_pct, season_year) 
        SELECT 
        ua.umpire_type,
        ua.age_group,
        ua.age_sort_order,
        ua.short_league_name,
        ua.league_sort_order,
        ua.region_name,
        IFNULL(sub_match_count.match_count, 0) AS match_no_ump,
        IFNULL(sub_total_matches.total_match_count, 0) AS total_match_count,
        IFNULL(FLOOR(sub_match_count.match_count / sub_total_matches.total_match_count * 100),0) AS match_pct,
        sub_total_matches.date_year
        FROM (
            SELECT 
        	umpire_type,
        	age_group,
        	short_league_name,
        	age_sort_order,
        	league_sort_order,
        	region_name
            FROM staging_all_ump_age_league
        ) AS ua
        LEFT JOIN (
        	SELECT 
        	a.age_group,
        	l.short_league_name,
        	a.sort_order,
        	l.league_sort_order,
        	ti.date_year,
        	COUNT(DISTINCT match_id) AS total_match_count
        	FROM dw_fact_match m
        	INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        	INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        	INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        	GROUP BY a.age_group , l.short_league_name , a.sort_order , l.league_sort_order, ti.date_year
        ) AS sub_total_matches
        ON ua.age_group = sub_total_matches.age_group
        AND ua.short_league_name = sub_total_matches.short_league_name
        LEFT JOIN (
        	SELECT 
        	umpire_type,
        	age_group,
        	short_league_name,
        	COUNT(s.match_id) AS Match_Count
        	FROM staging_no_umpires s
            WHERE s.season_year = ". $reportTableRefresher->getSeasonYear() ."
        	GROUP BY umpire_type , age_group , short_league_name
        ) AS sub_match_count
        ON ua.umpire_type = sub_match_count.umpire_type
        AND ua.age_group = sub_match_count.age_group
        AND ua.short_league_name = sub_match_count.short_league_name
        WHERE sub_total_matches.date_year = ". $reportTableRefresher->getSeasonYear() .";";
        $reportTableRefresher->setDataRefreshQuery($queryString);

        return $reportTableRefresher;
    }

}

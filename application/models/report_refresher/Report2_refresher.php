<?php
class Report2_refresher extends Report_table_refresher {

    function __construct() {
        parent::__construct();
    }

    public static function createRefresher($pImportFileID, $pSeason) {
        $reportTableRefresher = new Report2_refresher();
        $reportTableRefresher->setTableName("dw_mv_report_02");
        $reportTableRefresher->setImportFileID($pImportFileID);
        $reportTableRefresher->setSeasonYear($pSeason);

        $queryString = "INSERT INTO dw_mv_report_02 (last_first_name, short_league_name, age_group, age_sort_order, league_sort_order, two_ump_flag, region_name, umpire_type, season_year, match_count)
            SELECT
            u.last_first_name,
            l.short_league_name,
            a.age_group,
            a.sort_order,
            l.league_sort_order,
            0 AS two_ump_flag,
            l.region_name,
            u.umpire_type,
            ti.date_year,
            COUNT(DISTINCT m.match_id) AS match_count
            FROM dw_fact_match m
            INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
            WHERE ti.date_year = ". $reportTableRefresher->getSeasonYear() ."
            GROUP BY u.last_first_name, l.short_league_name, a.age_group, a.sort_order, l.league_sort_order, l.region_name, u.umpire_type, ti.date_year
            UNION ALL
            SELECT
            u.last_first_name,
            l.short_league_name,
            a.age_group,
            a.sort_order,
            l.league_sort_order,
            1 AS two_ump_flag,
            l.region_name,
            u.umpire_type,
            ti.date_year,
            COUNT(DISTINCT m.match_id) AS match_count
            FROM dw_fact_match m
            INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
            INNER JOIN (
            	SELECT
            	m2.match_id,
            	COUNT(DISTINCT u2.umpire_key) AS umpire_count
            	FROM dw_fact_match m2
            	INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
            	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
            	WHERE u2.umpire_type = 'Field'
            	AND a2.age_group = 'Seniors'
            	GROUP BY m2.match_id
            	HAVING COUNT(DISTINCT u2.umpire_key) = 2
            	) AS qryMatchesWithTwoUmpires ON m.match_id = qryMatchesWithTwoUmpires.match_id
            WHERE u.umpire_type = 'Field'
            AND a.age_group = 'Seniors'
            AND ti.date_year = ". $reportTableRefresher->getSeasonYear() ."
            GROUP BY u.last_first_name, l.short_league_name, a.age_group, a.sort_order, l.league_sort_order, l.region_name, u.umpire_type, ti.date_year;";
        $reportTableRefresher->setDataRefreshQuery($queryString);

        return $reportTableRefresher;
    }

}

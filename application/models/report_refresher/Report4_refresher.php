<?php
class Report4_refresher extends Report_table_refresher {

    function __construct() {
        parent::__construct();
    }

    public static function createRefresher($pImportFileID, $pSeason) {
        $reportTableRefresher = new Report4_refresher();
        $reportTableRefresher->setTableName("dw_mv_report_04");
        $reportTableRefresher->setImportFileID($pImportFileID);
        $reportTableRefresher->setSeasonYear($pSeason);

        $queryString = "INSERT INTO dw_mv_report_04 (club_name, age_group, short_league_name, umpire_type, age_sort_order, league_sort_order, match_count, season_year, region_name)
            SELECT
            te.club_name,
            a.age_group,
            l.short_league_name,
            'Field' AS umpire_type,
            a.sort_order,
            l.league_sort_order,
            COUNT(DISTINCT match_id) AS match_count,
            ti.date_year,
            l.region_name
            FROM dw_fact_match m
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
            WHERE m.match_id IN (
                SELECT match_id
                FROM staging_no_umpires s
                WHERE s.umpire_type = 'Field'
                AND s.short_league_name = l.short_league_name
                AND s.age_group = a.age_group
            )
            AND ti.date_year = ". $reportTableRefresher->getSeasonYear() ."
            GROUP BY te.club_name, a.age_group, l.short_league_name, a.sort_order, l.league_sort_order, ti.date_year, l.region_name
            UNION ALL
            SELECT
            te.club_name,
            a.age_group,
            l.short_league_name,
            'Goal' AS umpire_type,
            a.sort_order,
            l.league_sort_order,
            COUNT(DISTINCT match_id) AS match_count,
            ti.date_year,
            l.region_name
            FROM dw_fact_match m
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
            WHERE m.match_id IN (
                SELECT match_id
                FROM staging_no_umpires s
                WHERE s.umpire_type = 'Goal'
                AND s.short_league_name = l.short_league_name
                AND s.age_group = a.age_group
            )
            AND ti.date_year = ". $reportTableRefresher->getSeasonYear() ."
            GROUP BY te.club_name, a.age_group, l.short_league_name, a.sort_order, l.league_sort_order, ti.date_year, l.region_name
            UNION ALL
            SELECT
            te.club_name,
            a.age_group,
            l.short_league_name,
            'Boundary' AS umpire_type,
            a.sort_order,
            l.league_sort_order,
            COUNT(DISTINCT match_id) AS match_count,
            ti.date_year,
            l.region_name
            FROM dw_fact_match m
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
            WHERE m.match_id IN (
                SELECT match_id
                FROM staging_no_umpires s
                WHERE s.umpire_type = 'Boundary'
                AND s.short_league_name = l.short_league_name
                AND s.age_group = a.age_group
            )
            AND ti.date_year = ". $reportTableRefresher->getSeasonYear() ."
            GROUP BY te.club_name, a.age_group, l.short_league_name, a.sort_order, l.league_sort_order, ti.date_year, l.region_name;";
        $reportTableRefresher->setDataRefreshQuery($queryString);

        return $reportTableRefresher;
    }

}

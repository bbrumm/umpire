<?php
class Report1_refresher extends Report_table_refresher {
  
  

  public function refreshMVTable() {
        $queryString = "INSERT INTO dw_mv_report_01 (last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, season_year, match_count)
            SELECT
            u.last_first_name,
            l.short_league_name,
            te.club_name,
            a.age_group,
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
            WHERE ti.date_year = $this->pSeasonYear
            GROUP BY u.last_first_name, l.short_league_name, te.club_name, a.age_group, l.region_name, u.umpire_type, ti.date_year;";
        
        parent::setDataRefreshQuery($queryString);
        parent::refreshMVTable();
    
    }

}

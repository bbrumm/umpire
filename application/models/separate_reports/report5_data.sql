SELECT umpire_type,
age_group,
short_league_name,
match_no_ump,
total_match_count,
match_pct
FROM dw_mv_report_05
WHERE short_league_name IN (:pLeague)
AND region_name IN (:pRegion)
AND season_year = :pSeasonYear
ORDER BY umpire_type, age_sort_order, league_sort_order;
SELECT
club_name,
age_group,
short_league_name,
umpire_type,
match_count
FROM dw_mv_report_04
WHERE region_name IN (:pRegion)
AND season_year = :pSeasonYear
ORDER BY club_name, age_sort_order, league_sort_order;
SELECT DISTINCT
s.umpire_type,
s.age_group,
s.short_league_name
FROM dw_mv_report_04 s
WHERE s.region_name = :pRegion
AND season_year = :pSeasonYear
GROUP BY s.umpire_type, s.age_group, s.short_league_name
ORDER BY s.umpire_type, MIN(s.age_sort_order), MIN(s.league_sort_order);
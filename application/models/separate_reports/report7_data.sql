SELECT
umpire_type,
age_group,
short_league_name,
umpire_count,
match_count
FROM dw_mv_report_07
WHERE season_year IN (:pSeasonYear)
AND age_group IN (:pAgeGroup)
AND region_name IN (:pRegion)
AND umpire_type IN ('Field')
ORDER BY age_sort_order, league_sort_order, umpire_type, umpire_count;
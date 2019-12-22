SELECT
last_first_name,
age_group,
age_sort_order,
short_league_name,
two_ump_flag,
SUM(match_count) AS match_count
FROM dw_mv_report_02
WHERE age_group IN (:pAgeGroup)
AND short_league_name IN ('2 Umpires', :pLeague)
AND region_name IN (:pRegion)
AND umpire_type IN (:pUmpireType)
AND season_year = :pSeasonYear
AND two_ump_flag = 0
GROUP BY last_first_name, age_group, age_sort_order, short_league_name, two_ump_flag
UNION ALL
SELECT
last_first_name,
age_group,
age_sort_order,
'2 Umpires',
two_ump_flag,
SUM(match_count) AS match_count
FROM dw_mv_report_02
WHERE age_group IN (:pAgeGroup)
AND short_league_name IN ('2 Umpires', :pLeague)
AND region_name IN (:pRegion)
AND umpire_type IN (:pUmpireType)
AND season_year = :pSeasonYear
AND two_ump_flag = 1
GROUP BY last_first_name, age_group, age_sort_order, two_ump_flag
ORDER BY last_first_name, age_sort_order, short_league_name;
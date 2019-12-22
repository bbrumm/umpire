SELECT DISTINCT age_group, short_league_name 
FROM ( 
  SELECT
  age_group,
  age_sort_order,
  league_sort_order,
  short_league_name
  FROM dw_mv_report_02
  WHERE age_group IN (:pAgeGroup)
  AND short_league_name IN (:pLeague)
  AND region_name IN (:pRegion)
  AND umpire_type IN (:pUmpireType)
  AND season_year =  (:pSeasonYear)
  UNION ALL
  SELECT 'Total', 1000, 1000, ''
  UNION ALL
  SELECT 'Seniors', 1, 50, '2 Umpires'
) AS sub 
GROUP BY age_group, short_league_name 
ORDER BY MIN(age_sort_order), MIN(league_sort_order);
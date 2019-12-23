SELECT DISTINCT
short_league_name,
umpire_count
FROM (
  SELECT DISTINCT
  short_league_name,
  league_sort_order,
  '2 Umpires' AS umpire_count
  FROM dw_mv_report_07
  WHERE season_year IN (:pSeasonYear)
  AND age_group IN (:pAgeGroup)
  AND region_name IN (:pRegion)
  AND umpire_type IN ('Field')

  UNION ALL

  SELECT DISTINCT
  short_league_name,
  league_sort_order,
  '3 Umpires'
  FROM dw_mv_report_07
  WHERE season_year IN (:pSeasonYear)
  AND age_group IN (:pAgeGroup)
  AND region_name IN (:pRegion)
  AND umpire_type IN ('Field')
) AS sub
GROUP BY short_league_name, umpire_count
ORDER BY MIN(league_sort_order), umpire_count;
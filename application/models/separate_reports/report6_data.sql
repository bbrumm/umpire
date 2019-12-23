SELECT
umpire_type,
age_group,
region_name,
first_umpire,
second_umpire,
match_count
FROM dw_mv_report_06
WHERE season_year IN (:pSeasonYear)
AND age_group IN (:pAgeGroup)
AND region_name IN (:pRegion)
AND umpire_type IN (:pUmpireType)
AND short_league_name IN (:pLeague)
ORDER BY first_umpire, second_umpire;
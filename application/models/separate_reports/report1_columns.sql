SELECT DISTINCT
short_league_name, club_name
FROM dw_mv_report_01
WHERE age_group IN (:pAgeGroup)
AND short_league_name IN (:pLeague)
AND region_name IN (:pRegion)
AND umpire_type IN (:pUmpireType)
AND season_year = :pSeasonYear
ORDER BY short_league_name, club_name;
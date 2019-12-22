SELECT
last_first_name,
short_league_name,
club_name,
age_group,
SUM(match_count) AS match_count
FROM dw_mv_report_01
WHERE age_group IN (:pAgeGroup)
AND short_league_name IN (:pLeague)
AND region_name IN (:pRegion)
AND umpire_type IN (:pUmpireType)
AND season_year = :pSeasonYear
GROUP BY last_first_name, short_league_name, club_name
ORDER BY last_first_name, short_league_name, club_name;
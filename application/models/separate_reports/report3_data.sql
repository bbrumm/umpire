SELECT
weekend_date,
CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group,
short_league_name,
GROUP_CONCAT(team_names) AS team_list, (
  SELECT
  COUNT(DISTINCT match_id)
  FROM staging_no_umpires s2
  WHERE s2.age_group = s.age_group
  AND s2.umpire_type = s.umpire_type
  AND s2.weekend_date = s.weekend_date
  AND short_league_name IN (:pLeague)
  AND short_league_name IN (
     SELECT DISTINCT short_league_name
     FROM league l
            INNER JOIN region r ON l.region_id = r.id
     WHERE region_name = :pRegion
  )
) AS match_count
FROM staging_no_umpires s
WHERE short_league_name IN (:pLeague)
AND short_league_name IN (
   SELECT DISTINCT short_league_name
   FROM league l
          INNER JOIN region r ON l.region_id = r.id
   WHERE region_name = :pRegion
   )
AND season_year = :pSeasonYear
AND CONCAT(age_group, ' ', umpire_type) IN (
  'Seniors Boundary',
  'Seniors Goal',
  'Reserve Goal',
  'Colts Field',
  'Under 16 Field',
  'Under 14 Field',
  'Under 12 Field'
)
GROUP BY weekend_date, age_group, umpire_type, short_league_name
ORDER BY weekend_date, age_group, umpire_type, short_league_name;
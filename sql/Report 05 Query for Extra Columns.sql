/*
Note: This query gets the correct numbers. They are slightly different to the Report 5 Expected Output file because
that file uses the match_import table, which has some duplicates.
This query uses the normalised tables, which have had their duplicates removed.
*/

SELECT
l.short_league_name,
ag.age_group,
COUNT(m.id),
/*m.match_time,
r.round_date,
r.round_number,
ht.team_name as HomeTeam,
at.team_name as AwayTeam
*/
FROM bbrumm_umpire_data.match_played m
INNER JOIN round r ON m.round_id = r.id
INNER JOIN season s ON r.season_id = s.id
INNER JOIN league l ON r.league_id = l.id
INNER JOIN age_group_division agd ON l.age_group_division_id = agd.id
INNER JOIN age_group ag ON agd.age_group_id = ag.id
INNER JOIN team ht ON m.home_team_id = ht.id
INNER JOIN team at ON m.away_team_id = at.id

WHERE s.season_year = 2016
/*and l.short_league_name = 'GDFL'
AND ag.age_group = 'Seniors'*/
GROUP BY l.short_league_name, ag.age_group;


SELECT 
region,
age_group,
short_league_name,
COUNT(DISTINCT match_id) AS total_match_count
FROM mv_summary_staging
GROUP BY region,
age_group,
short_league_name
ORDER BY
region,
age_group,
short_league_name;


SELECT * FROM mv_summary_staging;


WHERE age_group = 'Seniors';
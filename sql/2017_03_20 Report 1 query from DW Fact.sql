/*
Report 01
*/

SELECT
u.last_first_name,
l.short_league_name,
te.club_name,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
WHERE a.age_group = 'Seniors'
AND l.short_league_name = 'BFL'
AND u.umpire_type = 'Field'
GROUP BY u.last_first_name, l.short_league_name, te.club_name
ORDER BY u.last_first_name, l.short_league_name, te.club_name
/*
Report 02
*/

SELECT
u.last_first_name,
a.age_group,
l.short_league_name,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
WHERE a.age_group IN ('Seniors', 'Reserves', 'Colts')
AND l.short_league_name IN ('BFL', 'GFL', 'GDFL', 'GJFL')
AND u.umpire_type IN ('Field', 'Boundary', 'Goal')
GROUP BY u.last_first_name, a.age_group, l.short_league_name
ORDER BY u.last_first_name, a.sort_order, l.short_league_name;





SELECT full_name,
SUM(`Seniors|BFL`) as `Seniors|BFL`,
SUM(`Seniors|2 Umpires Geelong`) as `Seniors|2 Umpires Geelong`,
SUM(`Seniors|BFL`) as Total
FROM mv_report_02
WHERE season_year = '2016'
AND age_group IN ('Seniors')
AND umpire_type_name IN ('Field')
AND short_league_name IN ('BFL')
GROUP BY full_name;

 


SELECT
u.last_first_name,
a.age_group,
a.sort_order,
l.short_league_name,
0 AS two_ump_flag,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE a.age_group IN ('Seniors', 'Reserves', 'Colts')
AND l.short_league_name IN ('BFL', 'GFL', 'GDFL', 'GJFL')
AND u.umpire_type IN ('Field', 'Boundary', 'Goal')
GROUP BY u.last_first_name, a.age_group, a.sort_order, l.short_league_name

UNION ALL

SELECT
u.last_first_name,
a.age_group,
a.sort_order,
'2 Umpires' AS short_league_name,
1 AS two_ump_flag,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN (
	SELECT
    m2.match_id,
    COUNT(DISTINCT u2.umpire_key) AS umpire_count
    FROM dw_fact_match m2 
	INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
    INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
    WHERE u2.umpire_type = 'Field'
    AND a2.age_group = 'Seniors' 
	GROUP BY m2.match_id
    HAVING COUNT(DISTINCT u2.umpire_key) = 2
) AS qryMatchesWithTwoUmpires ON m.match_id = qryMatchesWithTwoUmpires.match_id  
WHERE u.umpire_type = 'Field'
AND a.age_group = 'Seniors' 
AND l.short_league_name IN ('BFL', 'GFL', 'GDFL', 'GJFL')
GROUP BY u.last_first_name, a.age_group, a.sort_order, l.short_league_name
ORDER BY last_first_name, sort_order, short_league_name;






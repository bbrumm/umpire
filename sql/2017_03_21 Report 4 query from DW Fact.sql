INSERT INTO dw_mv_report_04 (club_name, age_group, short_league_name, umpire_type, age_sort_order, league_sort_order, match_count)
SELECT
te.club_name,
a.age_group,
l.short_league_name,
'Field' AS umpire_type,
a.sort_order,
l.league_sort_order,
COUNT(DISTINCT match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE m.match_id IN (
	SELECT match_id
	FROM staging_no_umpires s
	WHERE s.umpire_type = 'Field'
	AND s.short_league_name = l.short_league_name
	AND s.age_group = a.age_group
)
GROUP BY te.club_name, a.age_group, l.short_league_name
UNION ALL
SELECT
te.club_name,
a.age_group,
l.short_league_name,
'Goal' AS umpire_type,
a.sort_order,
l.league_sort_order,
COUNT(DISTINCT match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE m.match_id IN (
	SELECT match_id
	FROM staging_no_umpires s
	WHERE s.umpire_type = 'Goal'
	AND s.short_league_name = l.short_league_name
	AND s.age_group = a.age_group
)
GROUP BY te.club_name, a.age_group, l.short_league_name
UNION ALL
SELECT
te.club_name,
a.age_group,
l.short_league_name,
'Boundary' AS umpire_type,
a.sort_order,
l.league_sort_order,
COUNT(DISTINCT match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE m.match_id IN (
	SELECT match_id
	FROM staging_no_umpires s
	WHERE s.umpire_type = 'Boundary'
	AND s.short_league_name = l.short_league_name
	AND s.age_group = a.age_group
)
GROUP BY te.club_name, a.age_group, l.short_league_name;








SELECT 
club_name,
age_group,
short_league_name,
umpire_type,
match_count
FROM dw_mv_report_04
ORDER BY club_name, age_sort_order, league_sort_order;


/*
Column Label Query
*/

SELECT
s.umpire_type,
s.age_group,
s.short_league_name
FROM staging_all_ump_age_league s
WHERE s.short_league_name IN ('BFL', 'GFL', 'GDFL', 'GJFL')
ORDER BY s.umpire_type, s.age_sort_order, s.league_sort_order;


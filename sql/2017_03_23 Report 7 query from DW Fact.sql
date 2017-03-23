SELECT
l.short_league_name,
a.age_group,
l.region_name,
u.umpire_type,
ti.date_year,
'2 Umpires' AS no_of_umpires,
COUNT(DISTINCT sub2.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN (
	SELECT
	m2.match_id,
    u2.umpire_type,
    a2.age_group,
    l2.short_league_name,
	COUNT(DISTINCT u2.umpire_key) AS umpire_count
	FROM dw_fact_match m2
	INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
    INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	GROUP BY m2.match_id,  u2.umpire_type, a2.age_group, l2.short_league_name
	HAVING COUNT(DISTINCT u2.umpire_key) = 2
) AS sub2
ON m.match_id = sub2.match_id
AND sub2.umpire_type = u.umpire_type
AND sub2.age_group = a.age_group
GROUP BY l.short_league_name, a.age_group, l.region_name, u.umpire_type, ti.date_year

UNION ALL

SELECT
l.short_league_name,
a.age_group,
l.region_name,
u.umpire_type,
ti.date_year,
'3 Umpires' AS no_of_umpires,
COUNT(DISTINCT sub2.match_id) AS match_count_2ump
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN (
	SELECT
	m2.match_id,
    u2.umpire_type,
    a2.age_group,
    l2.short_league_name,
	COUNT(DISTINCT u2.umpire_key) AS umpire_count
	FROM dw_fact_match m2
	INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
    INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	GROUP BY m2.match_id,  u2.umpire_type, a2.age_group, l2.short_league_name
	HAVING COUNT(DISTINCT u2.umpire_key) = 3
) AS sub2
ON m.match_id = sub2.match_id
AND sub2.umpire_type = u.umpire_type
AND sub2.age_group = a.age_group
GROUP BY l.short_league_name, a.age_group, l.region_name, u.umpire_type, ti.date_year


ORDER BY 1, 2, 3, 4
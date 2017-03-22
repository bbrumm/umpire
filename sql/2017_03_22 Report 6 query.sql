SELECT *
FROM mv_report_06;


SELECT 
u.umpire_type,
a.age_group,
u.last_first_name AS first_umpire,
u2.last_first_name AS second_umpire,
l.region_name,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_fact_match m2 ON m2.match_id = m.match_id
INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	AND u.last_first_name <> u2.last_first_name
    AND u.umpire_type = u2.umpire_type
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
GROUP BY u.umpire_type, a.age_group, u.last_first_name, u2.last_first_name, l.region_name;


SELECT
umpire_type, age_group, region_name, first_umpire, second_umpire, season_year, match_count
FROM dw_mv_report_06
ORDER BY umpire_type, age_group, first_umpire, second_umpire;
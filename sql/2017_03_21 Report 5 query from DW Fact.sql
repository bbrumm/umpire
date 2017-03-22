SELECT 
	s.umpire_type,
	s.age_group,
	s.short_league_name,
	COUNT(s.match_id) AS Match_Count
FROM
	mv_summary_staging s
WHERE
	s.season_year = 2016
GROUP BY s.umpire_type, s.age_group, s.short_league_name
ORDER BY s.umpire_type, s.age_group, s.short_league_name;




SELECT
umpire_type,
age_group,
short_league_name,
COUNT(s.match_id) AS Match_Count
FROM staging_no_umpires s
GROUP BY umpire_type, age_group, short_league_name;




SELECT
ua.umpire_type_name,
ua.age_group,
sub_match_count.short_league_name,
IFNULL(sub_match_count.match_count, 0) AS match_no_ump,
IFNULL(sub_total_matches.total_match_count, 0) AS total_match_count,
IFNULL(FLOOR(sub_match_count.match_count / sub_total_matches.total_match_count * 100), 0) AS match_pct,
ua.display_order AS age_sort_order,
sub_total_matches.league_sort_order
FROM (
	SELECT 
	ut.umpire_type_name,
	ag.age_group,
	ag.display_order
	FROM
	umpire_type ut, age_group ag
    ) AS ua
LEFT JOIN (
	SELECT
	umpire_type,
	age_group,
	short_league_name,
	COUNT(s.match_id) AS Match_Count
	FROM staging_no_umpires s
	GROUP BY umpire_type, age_group, short_league_name
) AS sub_match_count
ON ua.umpire_type_name = sub_match_count.umpire_type
AND ua.age_group = sub_match_count.age_group
LEFT JOIN (
	SELECT
	a.age_group,
	l.short_league_name,
	a.sort_order,
	l.league_sort_order,
	COUNT(DISTINCT match_id) AS total_match_count
	FROM dw_fact_match m
	INNER JOIN dw_dim_league l ON m.league_key = l.league_key
	INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
    GROUP BY a.age_group, l.short_league_name, a.sort_order, l.league_sort_order
) AS sub_total_matches
ON ua.age_group = sub_total_matches.age_group
AND sub_match_count.short_league_name = sub_total_matches.short_league_name
ORDER BY ua.umpire_type_name, ua.display_order, sub_total_matches.league_sort_order;
    
/*
Column Query
*/
SELECT DISTINCT
l.short_league_name,
sub.subcolumn
FROM dw_dim_league l
CROSS JOIN (
SELECT 'Games' AS subcolumn
UNION
SELECT 'Total'
UNION
SELECT 'Pct'
) AS sub
WHERE l.short_league_name IN ('BFL', 'GFL', 'GDFL', 'CDFNL', 'GJFL');


  
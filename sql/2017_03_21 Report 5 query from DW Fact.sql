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
ua.umpire_type,
ua.age_group,
ua.short_league_name,
IFNULL(sub_match_count.match_count, 0) AS match_no_ump,
IFNULL(sub_total_matches.total_match_count, 0) AS total_match_count,
IFNULL(FLOOR(sub_match_count.match_count / sub_total_matches.total_match_count * 100), 0) AS match_pct,
ua.age_sort_order,
ua.league_sort_order
FROM (
	SELECT 
	umpire_type,
    age_group,
    short_league_name,
    age_sort_order,
    league_sort_order
    FROM staging_all_ump_age_league
    ) AS ua
    
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
AND ua.short_league_name = sub_total_matches.short_league_name
LEFT JOIN (
	SELECT
	umpire_type,
	age_group,
	short_league_name,
	COUNT(s.match_id) AS Match_Count
	FROM staging_no_umpires s
	GROUP BY umpire_type, age_group, short_league_name
) AS sub_match_count
ON ua.umpire_type = sub_match_count.umpire_type
AND ua.age_group = sub_match_count.age_group
AND ua.short_league_name = sub_match_count.short_league_name
ORDER BY ua.umpire_type, ua.age_sort_order, ua.league_sort_order
    
    
    
SELECT season_year, region, umpire_type, age_group, short_league_name, weekdate, 
GROUP_CONCAT(home, ' vs ', away) AS team_list, 
COUNT(home) AS match_count  
FROM ( 
SELECT season_year, region, umpire_type_id, umpire_type, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID,  
weekdate 
FROM mv_summary_staging 
WHERE season_year = @vSeasonYear 
) AS outer2 
GROUP BY season_year, region, umpire_type, age_group, short_league_name, weekdate ;



SELECT
ti.weekend_date,
a.age_group,
u.umpire_type,
l.short_league_name,
COUNT(DISTINCT m.match_id) AS match_count



FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE 1=1
/*AND a.age_group IN ('Seniors', 'Reserves', 'Colts')
AND l.short_league_name IN ('BFL', 'GFL', 'GDFL', 'GJFL')
AND u.umpire_type IN ('Field', 'Boundary', 'Goal')
*/
GROUP BY ti.weekend_date, a.age_group, u.umpire_type, l.short_league_name
ORDER BY ti.weekend_date, a.sort_order, u.umpire_type, l.short_league_name;


/* In progress */
SELECT 
ti.weekend_date,
a.age_group,
'Field' as missing_umpire_type,
l.short_league_name,
GROUP_CONCAT(th.team_name, ' vs ', ta.team_name) AS team_list,
COUNT(th.team_name) AS match_count
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Field'
	AND a2.age_group IN ('Senior', 'Reserves', 'Colts', 'Under 16', 'Under 14', 'Under 12')
)
AND a.age_group IN ('Senior', 'Reserves', 'Colts', 'Under 16', 'Under 14', 'Under 12')
GROUP BY ti.weekend_date, a.age_group, l.short_league_name
ORDER BY ti.weekend_date, a.age_group, l.short_league_name
;




SELECT 
weekend_date,
CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group,
short_league_name,
GROUP_CONCAT(team_names) AS team_list,
(
	SELECT
	COUNT(DISTINCT match_id)
	FROM staging_no_umpires s2
	WHERE s2.age_group = s.age_group
	AND s2.umpire_type = s.umpire_type
    AND s2.weekend_date = s.weekend_date
    /*AND s2.short_league_name IN ('BFL', 'GFL')*/
) AS match_count
FROM staging_no_umpires s
WHERE 1=1
/*AND short_league_name IN ('BFL', 'GFL')*/

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




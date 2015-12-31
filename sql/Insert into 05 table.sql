SELECT umpire_type, age_group,
SUM(`BFL`),
SUM(`GDFL`),
SUM(`GFL`),
SUM(`None`)

FROM (

	SELECT umpire_type, age_group, age_group_ID, 
	(CASE WHEN short_league_name = 'BFL' THEN match_count ELSE 0 END) as `BFL`,
	(CASE WHEN short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `GDFL`,
	(CASE WHEN short_league_name = 'GFL' THEN match_count ELSE 0 END) as `GFL`,
	(CASE WHEN short_league_name = 'None' THEN match_count ELSE 0 END) as `None`

	FROM (

			SELECT s.umpire_type, s.age_group, s.short_league_name, s.age_group_ID,
			COUNT(s.match_id) AS Match_Count
			FROM mv_summary_staging s
			GROUP BY s.age_group, s.umpire_type, s.short_league_name, s.age_group_ID
			/*
			UNION ALL
			
			SELECT 'Away' as Club_Type,  s.age_group, s.umpire_type, s.away_club,  s.short_league_name, 
			COUNT(s.age_group_ID), age_group_ID
			FROM mv_summary_staging s
			GROUP BY s.age_group, s.umpire_type, s.away_club, s.age_group_ID, s.short_league_name*/
			
		)  AS outer1

) AS outer3
GROUP BY umpire_type, age_group_id
ORDER BY umpire_type, age_group_id



/* V2 */

SELECT ua.umpire_type_name, ua.age_group,
SUM(`BFL`),
SUM(`GDFL`),
SUM(`GFL`),
SUM(`None`)
FROM (
	SELECT ut.id AS umpire_type_id, ut.umpire_type_name,
	ag.id AS age_group_id, ag.age_group
	FROM umpire_type ut, age_group ag
) AS ua
LEFT JOIN (
 	SELECT umpire_type, age_group, age_group_ID, 
	(CASE WHEN short_league_name = 'BFL' THEN match_count ELSE 0 END) as `BFL`,
	(CASE WHEN short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `GDFL`,
	(CASE WHEN short_league_name = 'GFL' THEN match_count ELSE 0 END) as `GFL`,
	(CASE WHEN short_league_name = 'None' THEN match_count ELSE 0 END) as `None`
	FROM (
			SELECT s.umpire_type, s.age_group, s.short_league_name, s.age_group_ID,
			COUNT(s.match_id) AS Match_Count
			FROM mv_summary_staging s
			GROUP BY s.age_group, s.umpire_type, s.short_league_name, s.age_group_ID
		)  AS outer1
) AS outer2 ON ua.umpire_type_name = outer2.umpire_type
AND ua.age_group = outer2.age_group
GROUP BY ua.umpire_type_id, ua.age_group_id
ORDER BY ua.umpire_type_id, ua.age_group_id


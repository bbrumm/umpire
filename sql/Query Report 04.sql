/*Updated summary query */
        SELECT 
        umpire_type_id,
            umpire_type,
            age_group,
            short_league_name,
            round_date,
            match_id,
            home,
            away,
            home_club,
            away_club,
            age_group_ID,
            ADDDATE(round_date, (5 - WEEKDAY(round_date))) AS WeekDate
    FROM
        (SELECT 
        1 AS umpire_type_id,
            'Field' AS umpire_type,
            age_group.age_group,
            league.short_league_name,
            round.round_date,
            match_played.id AS match_id,
            team_1.team_name AS home,
            team.team_name AS away,
            club_1.club_name AS home_club,
            club.club_name AS away_club,
            age_group_division.age_group_ID
    FROM
        match_played
    INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
    INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
    INNER JOIN team ON team.ID = match_played.away_team_id
    INNER JOIN club ON club.ID = team.club_id
    INNER JOIN round ON round.ID = match_played.round_id
    INNER JOIN league ON league.ID = ROUND.league_id
    INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
    INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
    INNER JOIN division ON division.ID = age_group_division.division_id
    WHERE
        match_played.id NOT IN (SELECT 
                umpire_name_type_match.match_id
            FROM
                umpire_name_type_match
            INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
            INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
            WHERE
                ut_sub.umpire_type_name = 'Field') UNION SELECT 
        3 AS umpire_type_id,
            'Goal' AS umpire_type,
            age_group.age_group,
            league.short_league_name,
            round.round_date,
            match_played.id AS match_id,
            team_1.team_name AS home,
            team.team_name AS away,
            club_1.club_name AS home_club,
            club.club_name AS away_club,
            age_group_division.age_group_ID
    FROM
        match_played
    INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
    INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
    INNER JOIN team ON team.ID = match_played.away_team_id
    INNER JOIN club ON club.ID = team.club_id
    INNER JOIN round ON round.ID = match_played.round_id
    INNER JOIN league ON league.ID = ROUND.league_id
    INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
    INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
    INNER JOIN division ON division.ID = age_group_division.division_id
    WHERE
        match_played.id NOT IN (SELECT 
                umpire_name_type_match.match_id
            FROM
                umpire_name_type_match
            INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
            INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
            WHERE
                ut_sub.umpire_type_name = 'Goal') UNION SELECT 
        2 AS umpire_type_id,
            'Boundary' AS umpire_type,
            age_group.age_group,
            league.short_league_name,
            round.round_date,
            match_played.id AS match_id,
            team_1.team_name AS home,
            team.team_name AS away,
            club_1.club_name AS home_club,
            club.club_name AS away_club,
            age_group_division.age_group_ID
    FROM
        match_played
    INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
    INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
    INNER JOIN team ON team.ID = match_played.away_team_id
    INNER JOIN club ON club.ID = team.club_id
    INNER JOIN round ON round.ID = match_played.round_id
    INNER JOIN league ON league.ID = round.league_id
    INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
    INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
    INNER JOIN division ON division.ID = age_group_division.division_id
    WHERE
        match_played.id NOT IN (SELECT 
                umpire_name_type_match.match_id
            FROM
                umpire_name_type_match
            INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
            INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
            WHERE
                ut_sub.umpire_type_name = 'Boundary')) AS outer1

				
				

/* qryNoUmpiresByClub from Access v1 */
SELECT age_group_ID, age_group, UmpireType, Club, sum(Match_Count) AS Matches
FROM (
	SELECT 'Home' as Club_Type, mvNoUmpiresForMatch.age_group, mvNoUmpiresForMatch.UmpireType, mvNoUmpiresForMatch.home_club as Club, 
	Count(mvNoUmpiresForMatch.[age_group_ID]) AS Match_Count, age_group_ID
	FROM mvNoUmpiresForMatch
	GROUP BY mvNoUmpiresForMatch.age_group, mvNoUmpiresForMatch.UmpireType, mvNoUmpiresForMatch.home_club, mvNoUmpiresForMatch.age_group_ID
	
	UNION ALL
	
	SELECT 'Away' as Club_Type,  mvNoUmpiresForMatch.age_group, mvNoUmpiresForMatch.UmpireType, mvNoUmpiresForMatch.away_club, 
	Count(mvNoUmpiresForMatch.[age_group_ID]), age_group_ID
	FROM mvNoUmpiresForMatch
	GROUP BY mvNoUmpiresForMatch.age_group, mvNoUmpiresForMatch.UmpireType, mvNoUmpiresForMatch.away_club, mvNoUmpiresForMatch.age_group_ID
)  AS outer1
GROUP BY age_group_ID, age_group, UmpireType, Club
ORDER BY age_group_ID, age_group, UmpireType, Club;



/* qryNoUmpiresByClub v2 */

SELECT age_group, umpire_type, Club, sum(Match_Count) AS Matches
FROM (
	SELECT 'Home' as Club_Type, s.age_group, s.umpire_type, s.home_club as Club, 
	COUNT(s.age_group_ID) AS Match_Count, age_group_ID
	FROM mv_summary_staging s
	GROUP BY s.age_group, s.umpire_type, s.home_club, s.age_group_ID
	
	UNION ALL
	
	SELECT 'Away' as Club_Type,  s.age_group, s.umpire_type, s.away_club, 
	COUNT(s.age_group_ID), age_group_ID
	FROM mv_summary_staging s
	GROUP BY s.age_group, s.umpire_type, s.away_club, s.age_group_ID
)  AS outer1
GROUP BY age_group, umpire_type, Club
ORDER BY age_group, umpire_type, Club;



/* qryNoUmpiresByClub v3 */
SELECT age_group, umpire_type, Club, short_league_name, SUM(Match_Count) AS Matches
FROM (
	SELECT 'Home' as Club_Type, s.age_group, s.umpire_type, s.home_club as Club, s.short_league_name, 
	COUNT(s.age_group_ID) AS Match_Count, age_group_ID
	FROM mv_summary_staging s
	GROUP BY s.age_group, s.umpire_type, s.home_club, s.age_group_ID
	
	UNION ALL
	
	SELECT 'Away' as Club_Type,  s.age_group, s.umpire_type, s.away_club,  s.short_league_name, 
	COUNT(s.age_group_ID), age_group_ID
	FROM mv_summary_staging s
	GROUP BY s.age_group, s.umpire_type, s.away_club, s.age_group_ID, s.short_league_name
)  AS outer1
GROUP BY age_group, umpire_type, Club, short_league_name
ORDER BY age_group, umpire_type, Club, short_league_name;

/*query for report 04 insert - 01 */

SELECT club,
SUM(`Boundary|Seniors|BFL`),
SUM(`Boundary|Seniors|GDFL`),
SUM(`Boundary|Seniors|GFL`),
SUM(`Boundary|Reserves|BFL`),
SUM(`Boundary|Reserves|GDFL`),
SUM(`Boundary|Reserves|GFL`),
SUM(`Boundary|Colts|None`),
SUM(`Boundary|Under 16|None`),
SUM(`Boundary|Under 14|None`),
SUM(`Boundary|Youth Girls|None`),
SUM(`Boundary|Junior Girls|None`),
SUM(`Field|Seniors|BFL`),
SUM(`Field|Seniors|GDFL`),
SUM(`Field|Seniors|GFL`),
SUM(`Field|Reserves|BFL`),
SUM(`Field|Reserves|GDFL`),
SUM(`Field|Reserves|GFL`),
SUM(`Field|Colts|None`),
SUM(`Field|Under 16|None`),
SUM(`Field|Under 14|None`),
SUM(`Field|Youth Girls|None`),
SUM(`Field|Junior Girls|None`),
SUM(`Goal|Seniors|BFL`),
SUM(`Goal|Seniors|GDFL`),
SUM(`Goal|Seniors|GFL`),
SUM(`Goal|Reserves|BFL`),
SUM(`Goal|Reserves|GDFL`),
SUM(`Goal|Reserves|GFL`),
SUM(`Goal|Colts|None`),
SUM(`Goal|Under 16|None`),
SUM(`Goal|Under 14|None`),
SUM(`Goal|Youth Girls|None`),
SUM(`Goal|Junior Girls|None`)

FROM (

	SELECT club,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|BFL`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|GDFL`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|GFL`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|BFL`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|GDFL`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|GFL`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Colts|None`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Under 16|None`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Under 14|None`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Youth Girls|None`,
	(CASE WHEN umpire_type = 'Boundary' AND age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Boundary|Junior Girls|None`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Field|Seniors|BFL`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Field|Seniors|GDFL`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Field|Seniors|GFL`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Field|Reserves|BFL`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Field|Reserves|GDFL`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Field|Reserves|GFL`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Colts|None`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Under 16|None`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Under 14|None`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Youth Girls|None`,
	(CASE WHEN umpire_type = 'Field' AND age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Field|Junior Girls|None`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Goal|Seniors|BFL`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Goal|Seniors|GDFL`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Goal|Seniors|GFL`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Goal|Reserves|BFL`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Goal|Reserves|GDFL`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Goal|Reserves|GFL`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Colts|None`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Under 16|None`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Under 14|None`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Youth Girls|None`,
	(CASE WHEN umpire_type = 'Goal' AND age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as `Goal|Junior Girls|None`
	FROM (
		SELECT age_group, umpire_type, Club, short_league_name, SUM(Match_Count) AS match_count
		FROM (
			SELECT 'Home' as Club_Type, s.age_group, s.umpire_type, s.home_club as Club, s.short_league_name, 
			COUNT(s.age_group_ID) AS Match_Count, age_group_ID
			FROM mv_summary_staging s
			GROUP BY s.age_group, s.umpire_type, s.home_club, s.age_group_ID
			
			UNION ALL
			
			SELECT 'Away' as Club_Type,  s.age_group, s.umpire_type, s.away_club,  s.short_league_name, 
			COUNT(s.age_group_ID), age_group_ID
			FROM mv_summary_staging s
			GROUP BY s.age_group, s.umpire_type, s.away_club, s.age_group_ID, s.short_league_name
		)  AS outer1
		GROUP BY age_group, umpire_type, Club, short_league_name
	) AS outer2
) AS outer3
GROUP BY club
ORDER BY club;









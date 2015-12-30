/* qryLoadNoUmpires */

SELECT umpire_type_id, UmpireType, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID, 
ADDDATE(round_date, (5-Weekday(round_date))) as WeekDate
FROM 
(
	SELECT 1 as umpire_type_id, 'Field' AS UmpireType, age_group.age_group, league.short_league_name, 
	round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
	age_group_division.age_group_ID
	FROM match_played
	INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
	INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
	INNER JOIN team ON team.ID = match_played.away_team_id
	INNER JOIN club ON club.ID = team.club_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = ROUND.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	WHERE match_played.id NOT IN (
		SELECT umpire_name_type_match.match_id
		FROM umpire_name_type_match
		INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
		INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
		WHERE ut_sub.umpire_type_name='Field'
	)
UNION
SELECT 3 as umpire_type_id, 'Goal' AS UmpireType, age_group.age_group, league.short_league_name, 
	round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
	age_group_division.age_group_ID
	FROM match_played
	INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
	INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
	INNER JOIN team ON team.ID = match_played.away_team_id
	INNER JOIN club ON club.ID = team.club_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = ROUND.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	WHERE match_played.id NOT IN (
		SELECT umpire_name_type_match.match_id
		FROM umpire_name_type_match
		INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
		INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
		WHERE ut_sub.umpire_type_name='Goal'
	)
UNION
SELECT 2 as umpire_type_id, 'Boundary' AS UmpireType, age_group.age_group, league.short_league_name, 
	round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
	age_group_division.age_group_ID
	FROM match_played
	INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
	INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
	INNER JOIN team ON team.ID = match_played.away_team_id
	INNER JOIN club ON club.ID = team.club_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	WHERE match_played.id NOT IN (
		SELECT umpire_name_type_match.match_id
		FROM umpire_name_type_match
		INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
		INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
		WHERE ut_sub.umpire_type_name='Boundary'
	)
) AS alldata


/* qryNoUmpiresWithTeamNames */

SELECT mvNoUmpiresForMatch.umpiretype, 
mvNoUmpiresForMatch.age_group, 
mvNoUmpiresForMatch.short_league_name, 
mvNoUmpiresForMatch.weekdate, 
concatado("Select home & ' vs ' & away From mvNoUmpiresForMatch As S1 Where S1.age_group = """ & age_group & """ AND s1.umpiretype = """ & umpiretype & """ AND s1.short_league_name = """ & short_league_name & """ AND s1.weekdate = cdate(""" & weekdate & """)",", ","; ") AS WHO, 
COUNT(mvNoUmpiresForMatch.home) AS match_count 
INTO mvNoUmpiresForMatchCombined
FROM mvNoUmpiresForMatch
GROUP BY mvNoUmpiresForMatch.umpiretype, mvNoUmpiresForMatch.age_group, mvNoUmpiresForMatch.short_league_name, mvNoUmpiresForMatch.weekdate, 5;


/* qryNoUmpiresWithTeamNames v2 */

SELECT umpiretype, age_group, short_league_name, weekdate,
GROUP_CONCAT(home, ' vs ', away) AS team_list,
COUNT(home) AS match_count 
FROM (
SELECT umpire_type_id, UmpireType, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID, 
ADDDATE(round_date, (5-Weekday(round_date))) as WeekDate
FROM 
(
SELECT 1 as umpire_type_id, 'Field' AS UmpireType, age_group.age_group, league.short_league_name, 
round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
age_group_division.age_group_ID
FROM match_played
INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
INNER JOIN team ON team.ID = match_played.away_team_id
INNER JOIN club ON club.ID = team.club_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = ROUND.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
WHERE match_played.id NOT IN (
SELECT umpire_name_type_match.match_id
FROM umpire_name_type_match
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
WHERE ut_sub.umpire_type_name='Field'
)
UNION
SELECT 3 as umpire_type_id, 'Goal' AS UmpireType, age_group.age_group, league.short_league_name, 
round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
age_group_division.age_group_ID
FROM match_played
INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
INNER JOIN team ON team.ID = match_played.away_team_id
INNER JOIN club ON club.ID = team.club_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = ROUND.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
WHERE match_played.id NOT IN (
SELECT umpire_name_type_match.match_id
FROM umpire_name_type_match
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
WHERE ut_sub.umpire_type_name='Goal'
)
UNION
SELECT 2 as umpire_type_id, 'Boundary' AS UmpireType, age_group.age_group, league.short_league_name, 
round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
age_group_division.age_group_ID
FROM match_played
INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
INNER JOIN team ON team.ID = match_played.away_team_id
INNER JOIN club ON club.ID = team.club_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
WHERE match_played.id NOT IN (
SELECT umpire_name_type_match.match_id
FROM umpire_name_type_match
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
WHERE ut_sub.umpire_type_name='Boundary'
)
) AS outer1
) AS outer2
GROUP BY umpiretype, age_group, short_league_name, weekdate
ORDER BY umpiretype, age_group, short_league_name, weekdate


/* qry 03 */
SELECT umpire_type, age_group, short_league_name, weekdate,
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Seniors' AND umpire_type = 'Boundary' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Seniors' AND umpire_type = 'Goal' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Reserve' AND short_league_name = 'BFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Reserve' AND short_league_name = 'GDFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Reserve' AND short_league_name = 'GFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Reserve' AND umpire_type = 'Goal' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Colts' AND short_league_name = 'GFL' AND umpire_type = 'Field' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Colts' AND umpire_type = 'Field' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Under 16' AND short_league_name = 'GFL' AND umpire_type = 'Field' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Under 16' AND umpire_type = 'Field' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Under 14' AND short_league_name = 'GFL' AND umpire_type = 'Field' THEN team_list ELSE NULL END), 
(CASE WHEN age_group = 'Under 14' AND umpire_type = 'Field' THEN match_count ELSE 0 END)
FROM (
	SELECT umpire_type, age_group, short_league_name, weekdate,
	GROUP_CONCAT(home, ' vs ', away) AS team_list,
	COUNT(home) AS match_count 
	FROM (
	SELECT umpire_type_id, umpire_type, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID, 
	ADDDATE(round_date, (5-Weekday(round_date))) as WeekDate
	FROM (
	SELECT 1 as umpire_type_id, 'Field' AS umpire_type, age_group.age_group, league.short_league_name, 
	round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
	age_group_division.age_group_ID
	FROM match_played
	INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
	INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
	INNER JOIN team ON team.ID = match_played.away_team_id
	INNER JOIN club ON club.ID = team.club_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = ROUND.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	WHERE match_played.id NOT IN (
	SELECT umpire_name_type_match.match_id
	FROM umpire_name_type_match
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
	WHERE ut_sub.umpire_type_name='Field'
	)
	UNION
	SELECT 3 as umpire_type_id, 'Goal' AS UmpireType, age_group.age_group, league.short_league_name, 
	round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
	age_group_division.age_group_ID
	FROM match_played
	INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
	INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
	INNER JOIN team ON team.ID = match_played.away_team_id
	INNER JOIN club ON club.ID = team.club_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = ROUND.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	WHERE match_played.id NOT IN (
	SELECT umpire_name_type_match.match_id
	FROM umpire_name_type_match
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
	WHERE ut_sub.umpire_type_name='Goal'
	)
	UNION
	SELECT 2 as umpire_type_id, 'Boundary' AS UmpireType, age_group.age_group, league.short_league_name, 
	round.round_date, match_played.id AS match_id, team_1.team_name AS home, team.team_name AS away, club_1.club_name AS home_club, club.club_name AS away_club, 
	age_group_division.age_group_ID
	FROM match_played
	INNER JOIN team AS team_1 ON match_played.home_team_id = team_1.ID
	INNER JOIN club AS club_1 ON club_1.ID = team_1.club_id
	INNER JOIN team ON team.ID = match_played.away_team_id
	INNER JOIN club ON club.ID = team.club_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	WHERE match_played.id NOT IN (
	SELECT umpire_name_type_match.match_id
	FROM umpire_name_type_match
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id
	WHERE ut_sub.umpire_type_name='Boundary'
	)
	) AS outer1
	) AS outer2
	GROUP BY umpiretype, age_group, short_league_name, weekdate
	ORDER BY umpiretype, age_group, short_league_name, weekdate)































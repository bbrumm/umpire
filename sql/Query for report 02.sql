/* qryUmpireNamesOnly */

SELECT 
    umpire_type.umpire_type_name,
    age_group.ID,
    age_group.age_group,
    league.short_league_name,
    last_name & ', ' & first_name AS full_name,
    COUNT(match_played.ID) AS CountOfID
FROM
    umpire
        INNER JOIN
    (umpire_type
    INNER JOIN (umpire_name_type
    INNER JOIN (((age_group
    INNER JOIN (age_group_division
    INNER JOIN league ON age_group_division.ID = league.age_group_division_id) ON age_group.ID = age_group_division.age_group_id)
    INNER JOIN round ON league.ID = round.league_id)
    INNER JOIN (match_played
    INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id) ON round.ID = match_played.round_id) ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id) ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , league.short_league_name , last_name & ', ' & first_name
ORDER BY last_name & ', ' & first_name;



/* qryMatchesWithTwoUmpires (MS Access) */

SELECT 
    match_played.ID AS match_id, COUNT(umpire.ID) AS umpire_count
FROM
    umpire
        INNER JOIN
    (umpire_type
    INNER JOIN (umpire_name_type
    INNER JOIN (((division
    INNER JOIN (age_group
    INNER JOIN (age_group_division
    INNER JOIN league ON age_group_division.ID = league.age_group_division_id) ON age_group.ID = age_group_division.age_group_id) ON division.ID = age_group_division.division_id)
    INNER JOIN round ON league.ID = round.league_id)
    INNER JOIN (match_played
    INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id) ON round.ID = match_played.round_id) ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id) ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
WHERE
    (((umpire_type.umpire_type_name) = 'Field')
        AND ((age_group.age_group) = 'Seniors'))
GROUP BY match_played.ID , umpire_type.umpire_type_name , age_group.age_group
HAVING (((COUNT(umpire.ID)) = 2))
ORDER BY COUNT(umpire.ID);



/* qryUmpireNamesTwoMatches */
SELECT 
    umpire_type.umpire_type_name,
    age_group.ID,
    age_group.age_group,
    '2 Umpires' AS dummy_league,
    last_name & ', ' & first_name AS full_name,
    COUNT(match_played.ID) AS CountOfID
FROM
    umpire
        INNER JOIN
    (umpire_type
    INNER JOIN (umpire_name_type
    INNER JOIN (((age_group
    INNER JOIN (age_group_division
    INNER JOIN league ON age_group_division.ID = league.age_group_division_id) ON age_group.ID = age_group_division.age_group_id)
    INNER JOIN round ON league.ID = round.league_id)
    INNER JOIN ((match_played
    INNER JOIN qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id)
    INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id) ON round.ID = match_played.round_id) ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id) ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , last_name & ', ' & first_name
HAVING (((umpire_type.umpire_type_name) = 'Field')
    AND ((age_group.age_group) = 'Seniors'))
ORDER BY last_name & ', ' & first_name;




/* qryMatchesWithTwoUmpires v2 */

SELECT 
    match_played.ID AS match_id,
	COUNT(umpire.ID) AS umpire_count
FROM match_played
INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
GROUP BY match_played.ID, umpire_type.umpire_type_name, age_group.age_group
HAVING COUNT(umpire.ID) = 2
ORDER BY COUNT(umpire.ID);


/* qryUmpireNamesTwoMatches v2 */
SELECT 
    umpire_type.umpire_type_name,
    age_group.ID,
    age_group.age_group,
    '2 Umpires' AS dummy_league,
    CONCAT(last_name,', ',first_name) AS full_name,
    COUNT(match_played.ID) AS CountOfID
FROM match_played
INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
INNER JOIN qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id
GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , CONCAT(last_name,', ',first_name)
HAVING umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
ORDER BY last_name & ', ' & first_name;

/* qryUmpireNamesTwoMatches v3 with match id join */
SELECT 
    umpire_type.umpire_type_name,
    age_group.ID,
    age_group.age_group,
    '2 Umpires' AS dummy_league,
    CONCAT(last_name,', ',first_name) AS full_name,
    COUNT(match_played.ID) AS CountOfID
FROM match_played
INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
INNER JOIN (
	SELECT 
		match_played.ID AS match_id,
		COUNT(umpire.ID) AS umpire_count
	FROM match_played
	INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
	GROUP BY match_played.ID, umpire_type.umpire_type_name, age_group.age_group
	HAVING COUNT(umpire.ID) = 2
) AS qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id
GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , CONCAT(last_name,', ',first_name)
HAVING umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
ORDER BY CONCAT(last_name,', ',first_name);


/* qryUmpireNamesOnly v2 */

SELECT 
    umpire_type.umpire_type_name,
    age_group.ID,
    age_group.age_group,
    league.short_league_name,
    CONCAT(last_name,', ',first_name) AS full_name,
    COUNT(match_played.ID) AS CountOfID
FROM match_played
INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , league.short_league_name , CONCAT(last_name,', ',first_name)
ORDER BY CONCAT(last_name,', ',first_name);

/* Union of qryUmpireNamesOnly and qryUmpireNamesTwoMatches */


SELECT 
    umpire_type.umpire_type_name,
    age_group.ID,
    age_group.age_group,
    league.short_league_name,
    CONCAT(last_name,', ',first_name) AS full_name,
    COUNT(match_played.ID) AS CountOfID
FROM match_played
INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , league.short_league_name , CONCAT(last_name,', ',first_name)

UNION ALL

SELECT 
    umpire_type.umpire_type_name,
    age_group.ID,
    age_group.age_group,
    '2 Umpires',
    CONCAT(last_name,', ',first_name),
    COUNT(match_played.ID)
FROM match_played
INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
INNER JOIN (
	SELECT 
		match_played.ID AS match_id,
		COUNT(umpire.ID) AS umpire_count
	FROM match_played
	INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
	GROUP BY match_played.ID, umpire_type.umpire_type_name, age_group.age_group
	HAVING COUNT(umpire.ID) = 2
) AS qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id
WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , CONCAT(last_name,', ',first_name)




/* Query split into columns for displaying */


SELECT 
full_name,
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as "Seniors|BFL",
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as "Seniors|GDFL",
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as "Seniors|GFL",
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as "Reserves|BFL",
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as "Reserves|GDFL",
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as "Reserves|GFL",
(CASE WHEN age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END) as "Colts|None",
(CASE WHEN age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END) as "Under 16|None",
(CASE WHEN age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END) as "Under 14|None",
(CASE WHEN age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as "Youth Girls|None",
(CASE WHEN age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END) as "Junior Girls|None",
(CASE WHEN age_group = 'Seniors' AND short_league_name = '2 Umpires' THEN match_count ELSE 0 END) as "Seniors|2 Umpires"
INTO mv_report_02
FROM (
	SELECT 
		umpire_type.umpire_type_name,
		age_group.ID,
		age_group.age_group,
		league.short_league_name,
		CONCAT(last_name,', ',first_name) AS full_name,
		COUNT(match_played.ID) AS match_count
	FROM match_played
	INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , league.short_league_name , CONCAT(last_name,', ',first_name)

	UNION ALL

	SELECT 
		umpire_type.umpire_type_name,
		age_group.ID,
		age_group.age_group,
		'2 Umpires',
		CONCAT(last_name,', ',first_name),
		COUNT(match_played.ID)
	FROM match_played
	INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	INNER JOIN (
		SELECT 
			match_played.ID AS match_id,
			COUNT(umpire.ID) AS umpire_count
		FROM match_played
		INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
		INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
		INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
		INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
		INNER JOIN round ON round.ID = match_played.round_id
		INNER JOIN league ON league.ID = round.league_id
		INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
		INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
		INNER JOIN division ON division.ID = age_group_division.division_id
		WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
		GROUP BY match_played.ID, umpire_type.umpire_type_name, age_group.age_group
		HAVING COUNT(umpire.ID) = 2
	) AS qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id
	WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
	GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , CONCAT(last_name,', ',first_name)
) AS mainquery
ORDER BY full_name;


/* Insert Into */

INSERT INTO mv_report_02 (full_name, umpire_type_name, `Seniors|BFL`, `Seniors|GDFL`, `Seniors|GFL`, `Reserves|BFL`, `Reserves|GDFL`, `Reserves|GFL`, `Colts|None`,
`Under 16|None`, `Under 14|None`, `Youth Girls|None`, `Junior Girls|None`, `Seniors|2 Umpires`)
SELECT 
full_name,
umpire_type_name,
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Seniors' AND short_league_name = '2 Umpires' THEN match_count ELSE 0 END)
FROM (
	SELECT 
		umpire_type.umpire_type_name,
		age_group.ID,
		age_group.age_group,
		league.short_league_name,
		CONCAT(last_name,', ',first_name) AS full_name,
		COUNT(match_played.ID) AS match_count
	FROM match_played
	INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , league.short_league_name , CONCAT(last_name,', ',first_name)

	UNION ALL

	SELECT 
		umpire_type.umpire_type_name,
		age_group.ID,
		age_group.age_group,
		'2 Umpires',
		CONCAT(last_name,', ',first_name),
		COUNT(match_played.ID)
	FROM match_played
	INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	INNER JOIN (
		SELECT 
			match_played.ID AS match_id,
			COUNT(umpire.ID) AS umpire_count
		FROM match_played
		INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
		INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
		INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
		INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
		INNER JOIN round ON round.ID = match_played.round_id
		INNER JOIN league ON league.ID = round.league_id
		INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
		INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
		INNER JOIN division ON division.ID = age_group_division.division_id
		WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
		GROUP BY match_played.ID, umpire_type.umpire_type_name, age_group.age_group
		HAVING COUNT(umpire.ID) = 2
	) AS qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id
	WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
	GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , CONCAT(last_name,', ',first_name)
) AS mainquery
ORDER BY full_name;






/* Insert Into with Quotes */

INSERT INTO mv_report_02 (full_name, umpire_type_name, `Seniors|BFL`, `Seniors|GDFL`, `Seniors|GFL`, `Reserves|BFL`, `Reserves|GDFL`, `Reserves|GFL`, `Colts|None`,
`Under 16|None`, `Under 14|None`, `Youth Girls|None`, `Junior Girls|None`, `Seniors|2 Umpires`)
SELECT 
full_name,
umpire_type_name,
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Colts' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Under 16' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Under 14' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Youth Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Junior Girls' AND short_league_name = 'None' THEN match_count ELSE 0 END),
(CASE WHEN age_group = 'Seniors' AND short_league_name = '2 Umpires' THEN match_count ELSE 0 END)
FROM (
	SELECT 
		umpire_type.umpire_type_name,
		age_group.ID,
		age_group.age_group,
		league.short_league_name,
		CONCAT(last_name,', ',first_name) AS full_name,
		COUNT(match_played.ID) AS match_count
	FROM match_played
	INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , league.short_league_name , CONCAT(last_name,', ',first_name)

	UNION ALL

	SELECT 
		umpire_type.umpire_type_name,
		age_group.ID,
		age_group.age_group,
		'2 Umpires',
		CONCAT(last_name,', ',first_name),
		COUNT(match_played.ID)
	FROM match_played
	INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
	INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN round ON round.ID = match_played.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	INNER JOIN division ON division.ID = age_group_division.division_id
	INNER JOIN (
		SELECT 
			match_played.ID AS match_id,
			COUNT(umpire.ID) AS umpire_count
		FROM match_played
		INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id
		INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
		INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
		INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id
		INNER JOIN round ON round.ID = match_played.round_id
		INNER JOIN league ON league.ID = round.league_id
		INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
		INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
		INNER JOIN division ON division.ID = age_group_division.division_id
		WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
		GROUP BY match_played.ID, umpire_type.umpire_type_name, age_group.age_group
		HAVING COUNT(umpire.ID) = 2
	) AS qryMatchesWithTwoUmpires ON match_played.ID = qryMatchesWithTwoUmpires.match_id
	WHERE umpire_type.umpire_type_name = 'Field' AND age_group.age_group = 'Seniors'
	GROUP BY umpire_type.umpire_type_name , age_group.ID , age_group.age_group , '2 Umpires' , CONCAT(last_name,', ',first_name)
) AS mainquery
ORDER BY full_name;















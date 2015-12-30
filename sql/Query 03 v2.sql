

INSERT INTO `umpire`.`mv_report_03` 
(weekdate, `No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, `No Senior Goal|BFL`,
 `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, `No Reserve Goal|BFL`, `No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, `No Colts Field|Clubs`,
 `No Colts Field|No.`, `No U16 Field|Clubs`, `No U16 Field|No.`, `No U14 Field|Clubs`, `No U14 Field|No.`) 
 

SELECT weekdate,
MAX(`No Senior Boundary|BFL`), MAX(`No Senior Boundary|GDFL`), MAX(`No Senior Boundary|GFL`), SUM(`No Senior Boundary|No.`), MAX(`No Senior Goal|BFL`),
MAX(`No Senior Goal|GDFL`), MAX(`No Senior Goal|GFL`), SUM(`No Senior Goal|No.`), MAX(`No Reserve Goal|BFL`), MAX(`No Reserve Goal|GDFL`),
MAX(`No Reserve Goal|GFL`), SUM(`No Reserve Goal|No.`), MAX(`No Colts Field|Clubs`),SUM(`No Colts Field|No.`), MAX(`No U16 Field|Clubs`),
SUM(`No U16 Field|No.`), MAX(`No U14 Field|Clubs`), SUM(`No U14 Field|No.`) 
FROM (

 
 
	SELECT 
    /*umpire_type,
    age_group,
    short_league_name,
    */
    weekdate,
    (CASE
        WHEN
            age_group = 'Seniors'
                AND short_league_name = 'BFL'
                AND umpire_type = 'Boundary'
        THEN
            team_list
        ELSE NULL
    END) as `No Senior Boundary|BFL`,
    (CASE
        WHEN
            age_group = 'Seniors'
                AND short_league_name = 'GDFL'
                AND umpire_type = 'Boundary'
        THEN
            team_list
        ELSE NULL
    END) as `No Senior Boundary|GDFL`,
    (CASE
        WHEN
            age_group = 'Seniors'
                AND short_league_name = 'GFL'
                AND umpire_type = 'Boundary'
        THEN
            team_list
        ELSE NULL
    END) as `No Senior Boundary|GFL`,
    (CASE
        WHEN
            age_group = 'Seniors'
                AND umpire_type = 'Boundary'
        THEN
            match_count
        ELSE 0
    END) as `No Senior Boundary|No.`,
    (CASE
        WHEN
            age_group = 'Seniors'
                AND short_league_name = 'BFL'
                AND umpire_type = 'Goal'
        THEN
            team_list
        ELSE NULL
    END) as `No Senior Goal|BFL`,
    (CASE
        WHEN
            age_group = 'Seniors'
                AND short_league_name = 'GDFL'
                AND umpire_type = 'Goal'
        THEN
            team_list
        ELSE NULL
    END) as `No Senior Goal|GDFL`,
    (CASE
        WHEN
            age_group = 'Seniors'
                AND short_league_name = 'GFL'
                AND umpire_type = 'Goal'
        THEN
            team_list
        ELSE NULL
    END) as `No Senior Goal|GFL`,
    (CASE
        WHEN
            age_group = 'Seniors'
                AND umpire_type = 'Goal'
        THEN
            match_count
        ELSE 0
    END) as `No Senior Goal|No.`,
    (CASE
        WHEN
            age_group = 'Reserve'
                AND short_league_name = 'BFL'
                AND umpire_type = 'Goal'
        THEN
            team_list
        ELSE NULL
    END) as `No Reserve Goal|BFL`,
    (CASE
        WHEN
            age_group = 'Reserve'
                AND short_league_name = 'GDFL'
                AND umpire_type = 'Goal'
        THEN
            team_list
        ELSE NULL
    END) as `No Reserve Goal|GDFL`,
    (CASE
        WHEN
            age_group = 'Reserve'
                AND short_league_name = 'GFL'
                AND umpire_type = 'Goal'
        THEN
            team_list
        ELSE NULL
    END) as `No Reserve Goal|GFL`,
    (CASE
        WHEN
            age_group = 'Reserve'
                AND umpire_type = 'Goal'
        THEN
            match_count
        ELSE 0
    END) as `No Reserve Goal|No.`,
    (CASE
        WHEN
            age_group = 'Colts'
                AND umpire_type = 'Field'
        THEN
            team_list
        ELSE NULL
    END) as `No Colts Field|Clubs`,
    (CASE
        WHEN
            age_group = 'Colts'
                AND umpire_type = 'Field'
        THEN
            match_count
        ELSE 0
    END) as `No Colts Field|No.`,
    (CASE
        WHEN
            age_group = 'Under 16'
                AND umpire_type = 'Field'
        THEN
            team_list
        ELSE NULL
    END) as `No U16 Field|Clubs`,
    (CASE
        WHEN
            age_group = 'Under 16'
                AND umpire_type = 'Field'
        THEN
            match_count
        ELSE 0
    END) as `No U16 Field|No.`,
    (CASE
        WHEN
            age_group = 'Under 14'
                AND umpire_type = 'Field'
        THEN
            team_list
        ELSE NULL
    END) as `No U14 Field|Clubs`,
    (CASE
        WHEN
            age_group = 'Under 14'
                AND umpire_type = 'Field'
        THEN
            match_count
        ELSE 0
    END) as `No U14 Field|No.`
FROM
    (SELECT 
        umpire_type,
            age_group,
            short_league_name,
            weekdate,
            GROUP_CONCAT(home, ' vs ', away) AS team_list,
            COUNT(home) AS match_count
    FROM
        (SELECT 
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
                ut_sub.umpire_type_name = 'Boundary')) AS outer1) AS outer2
	
    
    /*WHERE age_group = 'Under 16'*/
    
    
    GROUP BY umpire_type , age_group , short_league_name , weekdate
   
    
    
    ) AS outer3
    GROUP BY weekdate, 
		`No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, `No Senior Goal|BFL`,
	 `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, `No Reserve Goal|BFL`, `No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, `No Colts Field|Clubs`,
	 `No Colts Field|No.`, `No U16 Field|Clubs`, `No U16 Field|No.`, `No U14 Field|Clubs`, `No U14 Field|No.`
	) as maintable
GROUP BY  weekdate, `No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, 
`No Senior Goal|BFL`, `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, `No Reserve Goal|BFL`, 
`No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, `No Colts Field|Clubs`,`No Colts Field|No.`
ORDER BY weekdate
    
    
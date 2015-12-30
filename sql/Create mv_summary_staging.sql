CREATE TABLE mv_summary_staging (
umpire_type_id INT(11) DEFAULT NULL,
umpire_type VARCHAR(200) DEFAULT NULL,
age_group VARCHAR(200) DEFAULT NULL,
short_league_name VARCHAR(200) DEFAULT NULL,
round_date DATE DEFAULT NULL,
match_id INT(11) DEFAULT NULL,
home VARCHAR(200) DEFAULT NULL,
away VARCHAR(200) DEFAULT NULL,
home_club VARCHAR(200) DEFAULT NULL,
away_club VARCHAR(200) DEFAULT NULL,
age_group_ID INT(11) DEFAULT NULL,
weekdate DATE DEFAULT NULL
);


INSERT INTO mv_summary_staging (
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
weekdate
)



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

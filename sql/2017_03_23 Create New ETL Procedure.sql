DROP PROCEDURE RunETLProcess;

DELIMITER $$
CREATE PROCEDURE `RunETLProcess`(IN `pSeasonID` INT, IN `pImportedFileID` INT)
BEGIN

DECLARE vSeasonYear INT(4);

SET group_concat_max_len = 15000;

SELECT MAX(season_year)
INTO @vSeasonYear
FROM season
WHERE id = pSeasonID;

/*Delete from Umpire table*/
DELETE umpire FROM umpire
        INNER JOIN
    umpire_name_type ON umpire_name_type.umpire_id = umpire.ID
        INNER JOIN
    umpire_name_type_match ON umpire_name_type.id = umpire_name_type_match.umpire_name_type_id
        INNER JOIN
    match_played ON umpire_name_type_match.match_ID = match_played.ID
        INNER JOIN
    round ON match_played.round_id = round.ID 
WHERE
    round.season_id = pSeasonID;

/*Log Table Operation here*/
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire'), 3, ROW_COUNT());

DELETE umpire_name_type FROM umpire_name_type
        INNER JOIN
    umpire_name_type_match ON umpire_name_type.id = umpire_name_type_match.umpire_name_type_id
        INNER JOIN
    match_played ON umpire_name_type_match.match_ID = match_played.ID
        INNER JOIN
    round ON match_played.round_id = round.ID 
WHERE
    round.season_id = pSeasonID;

/*Log Table Operation here*/
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire_name_type'), 3, ROW_COUNT());

DELETE umpire_name_type_match FROM umpire_name_type_match
        INNER JOIN
    match_played ON umpire_name_type_match.match_ID = match_played.ID
        INNER JOIN
    round ON match_played.round_id = round.ID 
WHERE
    round.season_id = pSeasonID;
    
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire_name_type_match'), 3, ROW_COUNT());


DELETE match_played FROM match_played
        INNER JOIN
    round ON match_played.round_id = round.ID 
WHERE
    round.season_id = pSeasonID;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_played'), 3, ROW_COUNT());

DELETE round FROM round 
WHERE
    round.season_id = pSeasonID;
    
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'round'), 3, ROW_COUNT());


TRUNCATE match_staging;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_staging'), 3, ROW_COUNT());



/*Delete from MV tables*/

DELETE rec FROM dw_mv_report_01 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_01'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_02 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_02'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_04 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_04'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_05 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_05'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_06 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_06'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_07 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_07'), 3, ROW_COUNT());


/*Reload tables*/

ALTER TABLE round DISABLE KEYS;

INSERT INTO round ( round_number, round_date, season_id, league_id )
SELECT DISTINCT match_import.round, STR_TO_DATE(match_import.date, '%d/%m/%Y'), season.ID AS season_id, league.ID AS league_id
FROM match_import 
INNER JOIN season ON match_import.season = season.season_year
INNER JOIN competition_lookup ON (season.ID = competition_lookup.season_id) AND (match_import.competition_name = competition_lookup.competition_name)
INNER JOIN league ON league.ID = competition_lookup.league_id
ORDER BY match_import.Round, match_import.Date;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'round'), 1, ROW_COUNT());

ALTER TABLE round ENABLE KEYS;

INSERT INTO umpire (first_name, last_name) 
SELECT first_name, last_name FROM (
SELECT LEFT(UMPIRE_FULLNAME,InStr(UMPIRE_FULLNAME,' ')-1) AS FIRST_NAME, 
RIGHT(UMPIRE_FULLNAME,Length(UMPIRE_FULLNAME)-InStr(UMPIRE_FULLNAME,' ')) AS LAST_NAME 
FROM (SELECT match_import.field_umpire_1 AS UMPIRE_FULLNAME 
	FROM match_import 
	UNION 
	SELECT match_import.field_umpire_2 
	FROM match_import 
	UNION 
	SELECT match_import.field_umpire_3 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_1 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_2 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_3 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_4 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_5 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_6 
	FROM match_import 
	UNION 
	SELECT match_import.goal_umpire_1 
	FROM match_import 
	UNION 
	SELECT match_import.goal_umpire_2 
	FROM match_import 
) AS com  
WHERE UMPIRE_FULLNAME IS NOT NULL 
) AS sub  
WHERE NOT EXISTS (
SELECT 1 
FROM umpire u 
WHERE u.first_name = sub.first_name 
AND u.last_name = sub.last_name);

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire'), 1, ROW_COUNT());

ALTER TABLE umpire_name_type DISABLE KEYS;

INSERT INTO umpire_name_type (umpire_id, umpire_type_id) 
          SELECT DISTINCT umpire.ID, umpire_type.ID 
          FROM ( 
        	SELECT 
        	LEFT(UMPIRE_FULLNAME,INSTR(UMPIRE_FULLNAME,' ')-1) AS FIRST_NAME, 
        	RIGHT(UMPIRE_FULLNAME,LENGTH(UMPIRE_FULLNAME)-INSTR(UMPIRE_FULLNAME,' ')) AS LAST_NAME, 
        	com1.UMPIRE_TYPE 
        	FROM ( 
        		SELECT match_import.field_umpire_1 AS UMPIRE_FULLNAME, 'Field' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.field_umpire_2, 'Field' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.field_umpire_3, 'Field' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_1, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_2, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_3, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_4, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_5, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_6, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.goal_umpire_1, 'Goal' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.goal_umpire_2, 'Goal' as UMPIRE_TYPE 
        		FROM match_import 
        	) com1 
        	WHERE com1.UMPIRE_FULLNAME IS NOT NULL 
        )  AS com2 
        INNER JOIN umpire ON com2.first_name = umpire.first_name AND com2.last_name = umpire.last_name 
        INNER JOIN umpire_type ON com2.umpire_type = umpire_type.umpire_type_name;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire_name_type'), 1, ROW_COUNT());

ALTER TABLE umpire_name_type ENABLE KEYS;

ALTER TABLE match_staging DISABLE KEYS;

INSERT INTO match_staging 
        (appointments_id, appointments_season, appointments_round, appointments_date, appointments_compname, appointments_ground, appointments_time, 
        appointments_hometeam, appointments_awayteam, appointments_field1_first, appointments_field1_last, appointments_field2_first, appointments_field2_last, 
        appointments_field3_first, appointments_field3_last, appointments_boundary1_first, appointments_boundary1_last, appointments_boundary2_first, appointments_boundary2_last, 
        appointments_boundary3_first, appointments_boundary3_last, appointments_boundary4_first, appointments_boundary4_last,  
        appointments_boundary5_first, appointments_boundary5_last, appointments_boundary6_first, appointments_boundary6_last, appointments_goal1_first, appointments_goal1_last, 
        appointments_goal2_first, appointments_goal2_last, season_id, round_ID, round_date, round_leagueid, league_leaguename, league_sponsored_league_name, agd_agegroupid, 
        ag_agegroup, agd_divisionid, division_divisionname, ground_id, ground_mainname, home_team_id, away_team_id) 
      SELECT match_import.ID,  
        match_import.Season,   
        match_import.Round,   
        STR_TO_DATE(match_import.date, '%d/%m/%Y'),   
        match_import.competition_name,   
        match_import.ground,   
        STR_TO_DATE(CONCAT(match_import.date, ' ', match_import.time), '%d/%m/%Y %h:%i %p'),   
        match_import.home_team,   
        match_import.away_team, 
      LEFT(match_import.field_umpire_1,InStr(match_import.field_umpire_1,' ')-1) AS match_import_field1_first,   
        RIGHT(match_import.field_umpire_1,LENGTH(match_import.field_umpire_1)-InStr(match_import.field_umpire_1,' ')) AS match_import_field1_last,    
        LEFT(match_import.field_umpire_2,InStr(match_import.field_umpire_2,' ')-1) AS match_import_field2_first,    
        RIGHT(match_import.field_umpire_2,LENGTH(match_import.field_umpire_2)-InStr(match_import.field_umpire_2,' ')) AS match_import_field2_last,    
        LEFT(match_import.field_umpire_3,InStr(match_import.field_umpire_3,' ')-1) AS match_import_field3_first,    
        RIGHT(match_import.field_umpire_3,LENGTH(match_import.field_umpire_3)-InStr(match_import.field_umpire_3,' ')) AS match_import_field3_last,    
        LEFT(match_import.boundary_umpire_1,InStr(match_import.boundary_umpire_1,' ')-1) AS match_import_boundary1_first,    
        RIGHT(match_import.boundary_umpire_1,LENGTH(match_import.boundary_umpire_1)-InStr(match_import.boundary_umpire_1,' ')) AS match_import_boundary1_last,    
        LEFT(match_import.boundary_umpire_2,InStr(match_import.boundary_umpire_2,' ')-1) AS match_import_boundary2_first,    
        RIGHT(match_import.boundary_umpire_2,LENGTH(match_import.boundary_umpire_2)-InStr(match_import.boundary_umpire_2,' ')) AS match_import_boundary2_last,    
        LEFT(match_import.boundary_umpire_3,InStr(match_import.boundary_umpire_3,' ')-1) AS match_import_boundary3_first, 
        RIGHT(match_import.boundary_umpire_3,LENGTH(match_import.boundary_umpire_3)-InStr(match_import.boundary_umpire_3,' ')) AS match_import_boundary3_last,    
        LEFT(match_import.boundary_umpire_4,InStr(match_import.boundary_umpire_4,' ')-1) AS match_import_boundary4_first,    
        RIGHT(match_import.boundary_umpire_4,LENGTH(match_import.boundary_umpire_4)-InStr(match_import.boundary_umpire_4,' ')) AS match_import_boundary4_last,   
        LEFT(match_import.boundary_umpire_5,InStr(match_import.boundary_umpire_5,' ')-1) AS match_import_boundary5_first, 
        RIGHT(match_import.boundary_umpire_5,LENGTH(match_import.boundary_umpire_5)-InStr(match_import.boundary_umpire_5,' ')) AS match_import_boundary5_last, 
        LEFT(match_import.boundary_umpire_6,InStr(match_import.boundary_umpire_6,' ')-1) AS match_import_boundary6_first, 
        RIGHT(match_import.boundary_umpire_6,LENGTH(match_import.boundary_umpire_6)-InStr(match_import.boundary_umpire_6,' ')) AS match_import_boundary6_last, 
        LEFT(match_import.goal_umpire_1,InStr(match_import.goal_umpire_1,' ')-1) AS match_import_goal1_first,    
        RIGHT(match_import.goal_umpire_1,LENGTH(match_import.goal_umpire_1)-InStr(match_import.goal_umpire_1,' ')) AS match_import_goal1_last,    
        LEFT(match_import.goal_umpire_2,InStr(match_import.goal_umpire_2,' ')-1) AS match_import_goal2_first,    
        RIGHT(match_import.goal_umpire_2,LENGTH(match_import.goal_umpire_2)-InStr(match_import.goal_umpire_2,' ')) AS match_import_goal2_last,    
        season.ID AS season_id,    
        round.ID AS round_ID,    
        round.round_date AS round_date,    
        round.league_id AS round_leagueid,    
        league.league_name AS league_leaguename,    
        league.sponsored_league_name AS league_sponsored_league_name,    
        age_group_division.age_group_id AS agd_agegroupid,    
        age_group.age_group AS ag_agegroup,    
        age_group_division.division_id AS agd_divisionid,    
        division.division_name AS division_divisionname,    
        ground.id AS ground_id,    
        ground.main_name AS ground_mainname,    
        team.ID AS home_team_id,    
        team_1.ID AS away_team_id 
      FROM match_import 
        INNER JOIN round ON (STR_TO_DATE(match_import.date, '%d/%m/%Y') = round.round_date) AND (match_import.round = round.round_number) 
        INNER JOIN competition_lookup ON match_import.competition_name = competition_lookup.competition_name 
        INNER JOIN season ON (match_import.season = season.season_year) AND (season.ID = competition_lookup.season_id) AND (season.ID = round.season_id) 
        INNER JOIN ground ON match_import.Ground = ground.alternative_name 
        INNER JOIN team ON match_import.home_team = team.team_name 
        INNER JOIN team AS team_1 ON match_import.away_team = team_1.team_name 
        INNER JOIN league ON (league.ID = competition_lookup.league_id) AND (league.ID = round.league_id) 
        INNER JOIN age_group_division ON league.age_group_division_id = age_group_division.ID 
        INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
        INNER JOIN division ON division.ID = age_group_division.division_id;



/*
TODO: Add season filter here?
*/


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_staging'), 1, ROW_COUNT());

ALTER TABLE match_staging ENABLE KEYS;
        
/*
Delete duplicate records from match_staging
11*/

DELETE m1 FROM match_staging m1,
    match_staging m2 
WHERE
    m1.appointments_id > m2.appointments_id
    AND m1.ground_id = m2.ground_id
    AND m1.round_id = m2.round_id
    AND m1.appointments_time = m2.appointments_time
    AND m1.home_team_id = m2.home_team_id
    AND m1.away_team_id = m2.away_team_id;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_staging'), 3, ROW_COUNT());

ALTER TABLE match_played DISABLE KEYS;

INSERT INTO match_played (round_ID, ground_id, home_team_id, away_team_id, match_time, match_staging_id)
SELECT match_staging.round_ID, match_staging.ground_id, match_staging.home_team_id, 
match_staging.away_team_id, match_staging.appointments_time,
match_staging.match_staging_id
FROM match_staging;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_played'), 1, ROW_COUNT());

ALTER TABLE match_played ENABLE KEYS;

ALTER TABLE umpire_name_type_match DISABLE KEYS;

INSERT INTO umpire_name_type_match ( umpire_name_type_id, match_id ) 
SELECT umpire_name_type_id, match_id 
FROM (
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Field' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field2_first) AND (umpire.last_name = match_staging.appointments_field2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Field' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field3_first) AND (umpire.last_name = match_staging.appointments_field3_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Field' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary1_first) AND (umpire.last_name = match_staging.appointments_boundary1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary2_first) AND (umpire.last_name = match_staging.appointments_boundary2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary3_first) AND (umpire.last_name = match_staging.appointments_boundary3_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary4_first) AND (umpire.last_name = match_staging.appointments_boundary4_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary5_first) AND (umpire.last_name = match_staging.appointments_boundary5_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary6_first) AND (umpire.last_name = match_staging.appointments_boundary6_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal1_first) AND (umpire.last_name = match_staging.appointments_goal1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Goal' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal2_first) AND (umpire.last_name = match_staging.appointments_goal2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Goal') AS ump;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire_name_type_match'), 1, ROW_COUNT());

ALTER TABLE umpire_name_type_match ENABLE KEYS;


/*
Truncate DIM and FACT tables
*/
TRUNCATE TABLE dw_dim_age_group;
TRUNCATE TABLE dw_dim_league;
TRUNCATE TABLE dw_dim_team;
TRUNCATE TABLE dw_dim_time;
TRUNCATE TABLE dw_dim_umpire;
TRUNCATE TABLE dw_fact_match;
TRUNCATE staging_match;
TRUNCATE staging_no_umpires;

ALTER TABLE dw_dim_umpire DISABLE KEYS;

/*
Populate DimUmpire
*/
INSERT INTO dw_dim_umpire (first_name, last_name, last_first_name, umpire_type)
SELECT
u.first_name,
u.last_name,
CONCAT(u.last_name, ', ', u.first_name) AS last_first_name,
ut.umpire_type_name AS umpire_type
FROM umpire u
INNER JOIN umpire_name_type unt ON u.id = unt.umpire_id
INNER JOIN umpire_type ut ON unt.umpire_type_id = ut.ID;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_umpire'), 1, ROW_COUNT());

ALTER TABLE dw_dim_umpire ENABLE KEYS;

/*
Populate DimAgeGroup
*/

ALTER TABLE dw_dim_age_group DISABLE KEYS;

INSERT INTO dw_dim_age_group (age_group, sort_order, division)
SELECT
ag.age_group,
ag.display_order AS sort_order,
d.division_name
FROM age_group ag
INNER JOIN age_group_division agd ON ag.id = agd.age_group_id
INNER JOIN division d ON agd.division_id = d.ID
ORDER BY ag.display_order;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_age_group'), 1, ROW_COUNT());

ALTER TABLE dw_dim_age_group ENABLE KEYS;

/*
Populate DimLeague
*/

ALTER TABLE dw_dim_league DISABLE KEYS;

INSERT INTO dw_dim_league (short_league_name, full_name, region_name, competition_name, league_year, league_sort_order)
SELECT DISTINCT
l.short_league_name,
l.league_name,
r.region_name,
c.competition_name,
s.season_year,
CASE short_league_name
	WHEN 'GFL' THEN 1
	WHEN 'BFL' THEN 2
	WHEN 'GDFL' THEN 3
	WHEN 'CDFNL' THEN 4
	ELSE 10
END league_sort_order
FROM league l
INNER JOIN region r ON l.region_id = r.id
INNER JOIN competition_lookup c ON l.ID = c.league_id
INNER JOIN season s ON c.season_id = s.id;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_league'), 1, ROW_COUNT());

ALTER TABLE dw_dim_league ENABLE KEYS;



/*
Populate DimTeam
*/

ALTER TABLE dw_dim_team DISABLE KEYS;

INSERT INTO dw_dim_team (team_name, club_name)
SELECT
t.team_name,
c.club_name
FROM team t
INNER JOIN club c ON t.club_id = c.id
ORDER BY t.team_name, c.club_name;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_team'), 1, ROW_COUNT());

ALTER TABLE dw_dim_team ENABLE KEYS;


/*
Populate DimTime
*/

ALTER TABLE dw_dim_time DISABLE KEYS;

INSERT INTO dw_dim_time (round_number, match_date, date_year, date_month, date_day, date_hour, date_minute, weekend_date, weekend_year, weekend_month, weekend_day)
SELECT
DISTINCT
r.round_number,
m.match_time,
YEAR(m.match_time) AS date_year,
MONTH(m.match_time) AS date_month,
DAY(m.match_time) AS date_day,
HOUR(m.match_time) AS date_hour,
MINUTE(m.match_time) AS date_minute,
ADDDATE(r.round_date, (5-Weekday(r.round_date))) AS weekend_date,
YEAR(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_year,
MONTH(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_month,
DAY(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_day
FROM match_played m
INNER JOIN round r ON m.round_id = r.id
ORDER BY m.match_time;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_time'), 1, ROW_COUNT());

ALTER TABLE dw_dim_time ENABLE KEYS;


/*
Populate StagingMatch
*/

ALTER TABLE staging_match DISABLE KEYS;

INSERT INTO staging_match (season_id, season_year, umpire_id, umpire_first_name, umpire_last_name,
home_club, home_team, away_club, away_team, short_league_name, league_name, age_group_id, age_group_name, 
umpire_type_name, match_id, match_time, region_id, region_name, division_name, competition_name)
SELECT 
    s.id,
    s.season_year,
    u.id,
    u.first_name,
    u.last_name,
    hmc.club_name AS home_club,
    hmt.team_name AS home_team_name,
    awc.club_name AS away_club,
    awt.team_name AS away_team_name,
    l.short_league_name,
    l.league_name,
    ag.id,
    ag.age_group,
    ut.umpire_type_name,
    m.ID,
    m.match_time,
    r.id,
    r.region_name,
    d.division_name,
    cl.competition_name
FROM
match_played m
INNER JOIN    round rn ON rn.ID = m.round_id
INNER JOIN    league l ON l.ID = rn.league_id
INNER JOIN    age_group_division agd ON agd.ID = l.age_group_division_id
INNER JOIN    age_group ag ON ag.ID = agd.age_group_id
INNER JOIN    team hmt ON hmt.ID = m.home_team_id
INNER JOIN    club hmc ON hmc.ID = hmt.club_id
INNER JOIN    team awt ON awt.ID = m.away_team_id
INNER JOIN    club awc ON awc.ID = awt.club_id
INNER JOIN    division d ON agd.division_id = d.id
INNER JOIN    competition_lookup cl ON cl.league_id = l.ID
LEFT JOIN    umpire_name_type_match untm ON m.ID = untm.match_id
LEFT JOIN    umpire_name_type unt ON unt.ID = untm.umpire_name_type_id
LEFT JOIN    umpire_type ut ON ut.ID = unt.umpire_type_id
LEFT JOIN    umpire u ON u.ID = unt.umpire_id
INNER JOIN    season s ON s.id = rn.season_id
INNER JOIN    region r ON r.id = l.region_id;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'staging_match'), 1, ROW_COUNT());

ALTER TABLE staging_match ENABLE KEYS;




ALTER TABLE staging_all_ump_age_league ENABLE KEYS;

INSERT INTO staging_all_ump_age_league (age_group, umpire_type, short_league_name, region_name, age_sort_order, league_sort_order)
SELECT DISTINCT
ag.age_group,
ut.umpire_type_name,
l.short_league_name,
r.region_name,
ag.display_order,
CASE short_league_name
	WHEN 'GFL' THEN 1
	WHEN 'BFL' THEN 2
	WHEN 'GDFL' THEN 3
	WHEN 'CDFNL' THEN 4
	ELSE 10
END league_sort_order
FROM age_group ag
INNER JOIN age_group_division agd ON ag.ID = agd.age_group_id
INNER JOIN league l ON l.age_group_division_id = agd.ID
CROSS JOIN umpire_type ut
INNER JOIN region r ON l.region_id = r.id;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'staging_all_ump_age_league'), 1, ROW_COUNT());

ALTER TABLE staging_all_ump_age_league ENABLE KEYS;

/*
Populate Fact Match
*/

ALTER TABLE dw_fact_match ENABLE KEYS;

INSERT INTO dw_fact_match (match_id, umpire_key, age_group_key, league_key, time_key, home_team_key, away_team_key)
SELECT 
s.match_id,
du.umpire_key,
dag.age_group_key,
dl.league_key,
dt.time_key,
dth.team_key AS home_team_key,
dta.team_key AS away_team_key
FROM
staging_match s
LEFT JOIN dw_dim_umpire du ON (s.umpire_first_name = du.first_name
	AND s.umpire_last_name = du.last_name
	AND s.umpire_type_name = du.umpire_type
)
INNER JOIN dw_dim_age_group dag ON (
	s.age_group_name = dag.age_group
	AND s.division_name = dag.division
)
INNER JOIN dw_dim_league dl ON (
	s.short_league_name = dl.short_league_name
	AND s.league_name = dl.full_name
	AND s.region_name = dl.region_name
    AND s.competition_name = dl.competition_name
)
INNER JOIN dw_dim_team dth ON (
	s.home_team = dth.team_name
	AND s.home_club = dth.club_name
    )
INNER JOIN dw_dim_team dta ON (
	s.away_team = dta.team_name
	AND s.away_club = dta.club_name
    )
INNER JOIN dw_dim_time dt ON (
	s.match_time = dt.match_date
    AND s.season_year = dl.league_year
);

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_fact_match'), 1, ROW_COUNT());

ALTER TABLE dw_fact_match ENABLE KEYS;







ALTER TABLE staging_no_umpires DISABLE KEYS;

INSERT INTO staging_no_umpires (weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Field',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
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
)
UNION ALL

SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Boundary',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
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
	WHERE u2.umpire_type = 'Boundary'
)
UNION ALL

SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Goal',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
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
	WHERE u2.umpire_type = 'Goal'
);


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'staging_no_umpires'), 1, ROW_COUNT());

ALTER TABLE staging_no_umpires ENABLE KEYS;



ALTER TABLE dw_mv_report_01 DISABLE KEYS;

INSERT INTO dw_mv_report_01 (last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, season_year, match_count)
SELECT
u.last_first_name,
l.short_league_name,
te.club_name,
a.age_group,
l.region_name,
u.umpire_type,
ti.date_year,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE ti.date_year = @vSeasonYear
GROUP BY u.last_first_name, l.short_league_name, te.club_name, a.age_group, l.region_name, u.umpire_type, ti.date_year;
     
     
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_01'), 1, ROW_COUNT());

ALTER TABLE dw_mv_report_01 ENABLE KEYS; 
     
    
ALTER TABLE dw_mv_report_02 DISABLE KEYS; 
     
INSERT INTO dw_mv_report_02 (last_first_name, short_league_name, age_group, age_sort_order, league_sort_order, two_ump_flag, region_name, umpire_type, season_year, match_count)
SELECT
u.last_first_name,
l.short_league_name,
a.age_group,
a.sort_order,
l.league_sort_order,
0 AS two_ump_flag,
l.region_name,
u.umpire_type,
ti.date_year,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE ti.date_year = @vSeasonYear
GROUP BY u.last_first_name, l.short_league_name, a.age_group, a.sort_order, l.region_name, u.umpire_type, ti.date_year
UNION ALL
SELECT
u.last_first_name,
'2 Umpires' AS short_league_name,
a.age_group,
a.sort_order,
10 AS league_sort_order,
1 AS two_ump_flag,
l.region_name,
u.umpire_type,
ti.date_year,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN (
	SELECT
	m2.match_id,
	COUNT(DISTINCT u2.umpire_key) AS umpire_count
	FROM dw_fact_match m2
	INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Field'
	AND a2.age_group = 'Seniors'
	GROUP BY m2.match_id
	HAVING COUNT(DISTINCT u2.umpire_key) = 2
	) AS qryMatchesWithTwoUmpires ON m.match_id = qryMatchesWithTwoUmpires.match_id
WHERE u.umpire_type = 'Field'
AND a.age_group = 'Seniors'
AND ti.date_year = @vSeasonYear
GROUP BY u.last_first_name, l.short_league_name, a.age_group, a.sort_order, l.region_name, u.umpire_type, ti.date_year;
     
     
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_02'), 1, ROW_COUNT());

ALTER TABLE dw_mv_report_02 ENABLE KEYS;    

     
ALTER TABLE dw_mv_report_04 ENABLE KEYS;    
   
INSERT INTO dw_mv_report_04 (club_name, age_group, short_league_name, umpire_type, age_sort_order, league_sort_order, match_count, season_year, region_name)
SELECT
te.club_name,
a.age_group,
l.short_league_name,
'Field' AS umpire_type,
a.sort_order,
l.league_sort_order,
COUNT(DISTINCT match_id) AS match_count,
ti.date_year,
l.region_name
FROM dw_fact_match m
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE m.match_id IN (
	SELECT match_id
	FROM staging_no_umpires s
	WHERE s.umpire_type = 'Field'
	AND s.short_league_name = l.short_league_name
	AND s.age_group = a.age_group
)
AND ti.date_year = @vSeasonYear
GROUP BY te.club_name, a.age_group, l.short_league_name
UNION ALL
SELECT
te.club_name,
a.age_group,
l.short_league_name,
'Goal' AS umpire_type,
a.sort_order,
l.league_sort_order,
COUNT(DISTINCT match_id) AS match_count,
ti.date_year,
l.region_name
FROM dw_fact_match m
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE m.match_id IN (
	SELECT match_id
	FROM staging_no_umpires s
	WHERE s.umpire_type = 'Goal'
	AND s.short_league_name = l.short_league_name
	AND s.age_group = a.age_group
)
AND ti.date_year = @vSeasonYear
GROUP BY te.club_name, a.age_group, l.short_league_name
UNION ALL
SELECT
te.club_name,
a.age_group,
l.short_league_name,
'Boundary' AS umpire_type,
a.sort_order,
l.league_sort_order,
COUNT(DISTINCT match_id) AS match_count,
ti.date_year,
l.region_name
FROM dw_fact_match m
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
WHERE m.match_id IN (
	SELECT match_id
	FROM staging_no_umpires s
	WHERE s.umpire_type = 'Boundary'
	AND s.short_league_name = l.short_league_name
	AND s.age_group = a.age_group
)
AND ti.date_year = @vSeasonYear
GROUP BY te.club_name, a.age_group, l.short_league_name;
     
     
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_04'), 1, ROW_COUNT());

ALTER TABLE dw_mv_report_04 ENABLE KEYS;    


ALTER TABLE dw_mv_report_05 ENABLE KEYS; 

INSERT INTO dw_mv_report_05 (umpire_type, age_group, age_sort_order, short_league_name, league_sort_order, region_name, match_no_ump, total_match_count, match_pct, season_year) 
SELECT 
ua.umpire_type,
ua.age_group,
ua.age_sort_order,
ua.short_league_name,
ua.league_sort_order,
ua.region_name,
IFNULL(sub_match_count.match_count, 0) AS match_no_ump,
IFNULL(sub_total_matches.total_match_count, 0) AS total_match_count,
IFNULL(FLOOR(sub_match_count.match_count / sub_total_matches.total_match_count * 100),0) AS match_pct,
sub_total_matches.date_year
FROM (
    SELECT 
	umpire_type,
	age_group,
	short_league_name,
	age_sort_order,
	league_sort_order,
	region_name
    FROM staging_all_ump_age_league
) AS ua
LEFT JOIN (
	SELECT 
	a.age_group,
	l.short_league_name,
	a.sort_order,
	l.league_sort_order,
	ti.date_year,
	COUNT(DISTINCT match_id) AS total_match_count
	FROM dw_fact_match m
	INNER JOIN dw_dim_league l ON m.league_key = l.league_key
	INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
	INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
	GROUP BY a.age_group , l.short_league_name , a.sort_order , l.league_sort_order, ti.date_year
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
    WHERE s.season_year = @vSeasonYear
	GROUP BY umpire_type , age_group , short_league_name
) AS sub_match_count
ON ua.umpire_type = sub_match_count.umpire_type
AND ua.age_group = sub_match_count.age_group
AND ua.short_league_name = sub_match_count.short_league_name
WHERE sub_total_matches.date_year = @vSeasonYear;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_05'), 1, ROW_COUNT());

ALTER TABLE dw_mv_report_05 ENABLE KEYS;    


ALTER TABLE dw_mv_report_06 DISABLE KEYS;    

INSERT INTO dw_mv_report_06 (umpire_type, age_group, region_name, first_umpire, second_umpire, season_year, match_count)
SELECT 
u.umpire_type,
a.age_group,
l.region_name,
u.last_first_name AS first_umpire,
u2.last_first_name AS second_umpire,
dti.date_year,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_fact_match m2 ON m2.match_id = m.match_id
INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	AND u.last_first_name <> u2.last_first_name
    AND u.umpire_type = u2.umpire_type
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time dti ON m.time_key = dti.time_key
INNER JOIN dw_dim_time dti2 ON m2.time_key = dti2.time_key
WHERE dti.date_year = @vSeasonYear
AND dti2.date_year = @vSeasonYear
GROUP BY u.umpire_type, a.age_group, u.last_first_name, u2.last_first_name, l.region_name;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_06'), 1, ROW_COUNT());

ALTER TABLE dw_mv_report_06 ENABLE KEYS;   


ALTER TABLE dw_mv_report_07 DISABLE KEYS;  

INSERT INTO dw_mv_report_07 (umpire_type, age_group, region_name, short_league_name, season_year, age_sort_order, league_sort_order, umpire_count, match_count)
SELECT
u.umpire_type,
a.age_group,
l.region_name,
l.short_league_name,
ti.date_year,
a.sort_order,
l.league_sort_order,
CONCAT(sub.umpire_count, ' Umpires'),
COUNT(DISTINCT sub.match_id)
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN (
	SELECT
	m2.match_id,
    u2.umpire_type,
    a2.age_group,
    l2.short_league_name,
	COUNT(DISTINCT u2.umpire_key) AS umpire_count
	FROM dw_fact_match m2
	INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
    INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	GROUP BY m2.match_id,  u2.umpire_type, a2.age_group, l2.short_league_name
	HAVING COUNT(DISTINCT u2.umpire_key) IN (2, 3)
) AS sub
ON m.match_id = sub.match_id
AND u.umpire_type = sub.umpire_type
AND a.age_group = sub.age_group
WHERE ti.date_year = @vSeasonYear
GROUP BY l.short_league_name, a.age_group, l.region_name, u.umpire_type, ti.date_year, a.sort_order, sub.umpire_count, l.league_sort_order;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_07'), 1, ROW_COUNT());

ALTER TABLE dw_mv_report_07 ENABLE KEYS;  

   

/*
Insert New Competitions
These will be displayed to the user when a file is imported. The leagues need to be assigned manually by the person who imported them.
NOTE: This assumes that a competition name is unique to a season. If the same name is used in a different season, this needs to be changed
so that the subquery includes WHERE season_id = pSeasonID
*/

INSERT INTO competition_lookup (competition_name, season_id, league_id)
SELECT DISTINCT competition_name, pSeasonID, NULL
FROM match_import
WHERE competition_name NOT IN (
	SELECT competition_name
    FROM competition_lookup
);

END$$
DELIMITER ;
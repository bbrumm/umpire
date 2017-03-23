DROP PROCEDURE RunETLProcess;

DELIMITER $$
CREATE PROCEDURE `RunETLProcess`(IN `pSeasonID` INT, IN `pImportedFileID` INT)
BEGIN

DECLARE vSeasonYear INT(4);

SET group_concat_max_len = 15000;

INSERT INTO test_insert(logtime, test_value) VALUES (NOW(), "Start ETL");

SELECT MAX(season_year) INTO @vSeasonYear FROM season WHERE id = pSeasonID;

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


TRUNCATE mv_summary_staging;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_summary_staging'), 3, ROW_COUNT());

TRUNCATE mv_umpire_list;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_umpire_list'), 3, ROW_COUNT());

TRUNCATE mv_denormalised;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_denormalised'), 3, ROW_COUNT());


/*Delete from MV tables*/
/*TODO: Write queries*/

DELETE rec FROM mv_report_01 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_01'), 3, ROW_COUNT());

DELETE rec FROM mv_report_02 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_02'), 3, ROW_COUNT());

DELETE rec FROM mv_report_03 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_03'), 3, ROW_COUNT());

DELETE rec FROM mv_report_04 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_04'), 3, ROW_COUNT());

DELETE rec FROM mv_report_05 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_05'), 3, ROW_COUNT());

DELETE rec FROM mv_report_06_staging rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_06_staging'), 3, ROW_COUNT());

DELETE rec FROM mv_report_06 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_06'), 3, ROW_COUNT());

DELETE rec FROM mv_report_07 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_07'), 3, ROW_COUNT());


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

INSERT INTO umpire_name_type ( umpire_id, umpire_type_id ) 
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
CREATE TEMPORARY TABLE mv_denormalised (
season_year INT(4),
umpire_first_name VARCHAR(100),
umpire_last_name VARCHAR(100),
umpire_full_name VARCHAR(100),
club_name VARCHAR(100),
team_name VARCHAR(100),
short_league_name VARCHAR(100),
age_group_id INT(11),
age_group VARCHAR(100),
umpire_type_name VARCHAR(100),
season_id INT(11),
match_played_id INT(11)
);*/

INSERT INTO mv_denormalised (season_year, umpire_id, umpire_first_name, umpire_last_name, umpire_full_name, club_name, team_name,
short_league_name, age_group_id, age_group, umpire_type_name, season_id, match_played_id, region_id, region_name, display_order)
SELECT DISTINCT season.season_year, 
umpire.id,
umpire.first_name, 
umpire.last_name, 
CONCAT(umpire.last_name, ', ', umpire.first_name),
club.club_name, 
team.team_name, 
league.short_league_name, 
age_group.id,
age_group.age_group, 
umpire_type.umpire_type_name,
season.id,
match_played.ID,
region.id,
region.region_name,
age_group.display_order
FROM match_played 
INNER JOIN round ON round.ID = match_played.round_id 
INNER JOIN league ON league.ID = round.league_id 
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id 
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
INNER JOIN team ON team.ID = match_played.home_team_id 
INNER JOIN club ON club.ID = team.club_id 
LEFT JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id 
LEFT JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
LEFT JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
LEFT JOIN umpire ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN season ON season.id = round.season_id 
INNER JOIN region ON region.id = league.region_id
WHERE season.id = pSeasonID 

UNION ALL 

SELECT DISTINCT season.season_year, 
umpire.id,
umpire.first_name, 
umpire.last_name, 
CONCAT(umpire.last_name, ', ', umpire.first_name),
club.club_name, 
team.team_name, 
league.short_league_name, 
age_group.id,
age_group.age_group, 
umpire_type.umpire_type_name,
season.id,
match_played.ID,
region.id,
region.region_name,
age_group.display_order
FROM match_played 
INNER JOIN round ON round.ID = match_played.round_id 
INNER JOIN league ON league.ID = round.league_id 
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id 
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
INNER JOIN team ON team.ID = match_played.away_team_id 
INNER JOIN club ON club.ID = team.club_id 
LEFT JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id 
LEFT JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
LEFT JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
LEFT JOIN umpire ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN season ON season.id = round.season_id 
INNER JOIN region ON region.id = league.region_id
WHERE season.id = pSeasonID;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_denormalised'), 1, ROW_COUNT());


ALTER TABLE mv_report_01 DISABLE KEYS;

INSERT INTO mv_report_01 (season_year, full_name, club_name, short_league_name, age_group, umpire_type_name,  
        `GDFL|Anakie`, `GDFL|Bannockburn`, `GJFL|Corio`, `GDFL|East_Geelong`, `GDFL|North_Geelong`, `GJFL|Portarlington`, `GDFL|Werribee_Centrals`, 
        `GDFL|Winchelsea`, `GFL|Bell_Park`, `GDFL|Bell_Post_Hill`, `GDFL|Belmont_Lions`, `GFL|Colac`, `BFL|Geelong_Amateur`, `GDFL|Geelong_West`, 
        `GFL|Grovedale`, `GFL|Gwsp`, `GDFL|Inverleigh`, `GFL|Lara`, `GFL|Leopold`, `GFL|Newtown_&_Chilwell`, `GFL|North_Shore`, 
        `GFL|South_Barwon`, `GFL|St_Joseph's`, `GFL|St_Mary's`, `BFL|Torquay`, `GJFL|Barwon_Heads`, `GJFL|Drysdale`, `GJFL|East_Geelong`, 
        `GJFL|Geelong_West_St_Peters`, `GJFL|Grovedale`, `GJFL|Inverleigh`, `GJFL|Leopold`, `GJFL|Newcomb`, `GJFL|Newtown_&_Chilwell`, `GJFL|Ocean_Grove`, 
        `GJFL|South_Barwon`, `GJFL|St_Albans`, `GJFL|St_Joseph's`, `GJFL|St_Mary's`, `GJFL|Torquay`, `BFL|Anglesea`, `BFL|Barwon_Heads`, 
        `GDFL|Corio`, `GDFL|Thomson`, `GJFL|Anglesea`, `GJFL|Bell_Park`, `GJFL|North_Shore`, `GJFL|Belmont_Lions`, `GJFL|Colac`, 
        `GJFL|North_Geelong`, `GJFL|Ogcc`, `GJFL|Torquay_Jones`, `GJFL|Torquay_Papworth`, `GJFL|Winchelsea_/_Grovedale`, `BFL|Modewarre`, `BFL|Newcomb_Power`, 
        `BFL|Queenscliff`, `GFL|St_Albans`, `GJFL|Drysdale_Byrne`, `GJFL|Drysdale_Hall`, `GJFL|Drysdale_Hector`, `GJFL|Lara`, `GJFL|Queenscliff`, 
        `GJFL|St_Albans_Reid`, `GJFL|Torquay_Bumpstead`, `GJFL|Torquay_Pyers`, `GJFL|Modewarre`, `BFL|Ocean_Grove`, `BFL|Drysdale`, `BFL|Portarlington`, 
        `GJFL|St_Joseph's_Podbury`, `GJFL|Geelong_Amateur`, `GJFL|Winchelsea`, `GJFL|Anakie`, `GJFL|Bannockburn`, `GJFL|South_Barwon_/_Geelong_Amateur`, 
        `GJFL|St_Joseph's_Hill`, `GJFL|Torquay_Dunstan`, `GJFL|Werribee_Centrals`, `GJFL|Drysdale_Eddy`, `GJFL|Belmont_Lions_/_Newcomb`, `GJFL|Torquay_Coles`, 
        `GJFL|Gwsp_/_Bannockburn`, `GJFL|St_Albans_Allthorpe`, `GJFL|Drysdale_Bennett`, `GJFL|Torquay_Scott`, `GJFL|Torquay_Nairn`, `GJFL|Tigers_Gold`, 
        `CDFNL|Birregurra`, `CDFNL|Lorne`, `CDFNL|Colac Imperials`, `CDFNL|Irrewarra-beeac`, `CDFNL|Otway Districts`, `CDFNL|Simpson`, `CDFNL|South Colac`, `CDFNL|Western Eagles`, 
        `GJFL|Aireys Inlet`, `GJFL|Ammos Blue`, `GJFL|Ammos Green`, `GJFL|Ammos White`, `GJFL|Bannockburn / South Barwon`, `GJFL|Barwon Heads Gulls`, `GJFL|Barwon Heads Heads`, 
        `GJFL|Dragons`, `GJFL|Drysdale 1`, `GJFL|Drysdale 2`, `GJFL|Drysdale Humphrey`, `GJFL|Drysdale Mcintyre`, `GJFL|Drysdale Mckeon`, `GJFL|Drysdale Scott`, 
        `GJFL|Drysdale Smith`, `GJFL|Drysdale Taylor`, `GJFL|Drysdale Wilson`, `GJFL|Eagles Black`, `GJFL|Eagles Red`, `GJFL|East Newcomb Lions`, `GJFL|East Tigers`, 
        `GJFL|Flying Joeys`, `GJFL|Gdfl Raiders`, `GJFL|Grovedale Broad`, `GJFL|Grovedale Ford`, `GJFL|Grovedale Mcneel`, `GJFL|Grovedale Waldron`, `GJFL|Grovedale Williams`, 
        `GJFL|Grovedale Young`, `GJFL|Lara Batman`, `GJFL|Lara Flinders`, `GJFL|Lara Hume`, `GJFL|Leopold Brown`, `GJFL|Leopold Dowsett`, `GJFL|Leopold Ruggles`, 
        `GJFL|Lethbridge`, `GJFL|Newtown & Chilwell Eagles`, `GJFL|Ogcc Blue`, `GJFL|Ogcc Orange`, `GJFL|Ogcc Red`, `GJFL|Ogcc White`, `GJFL|Queenscliff Blue`, 
        `GJFL|Queenscliff Red`, `GJFL|Roosters`, `GJFL|Saints White`, `GJFL|Seagulls`, `GJFL|South Barwon Blue`, `GJFL|South Barwon Red`, `GJFL|South Barwon White`, 
        `GJFL|St Albans Butterworth`, `GJFL|St Albans Grinter`, `GJFL|St Albans Mcfarlane`, `GJFL|St Albans Osborne`, `GJFL|Surf Coast Suns`, `GJFL|Teesdale Roos`, 
        `GJFL|Tigers`, `GJFL|Torquay Boyse`, `GJFL|Torquay Browning`, `GJFL|Torquay Bruce`, `GJFL|Torquay Coleman`, `GJFL|Torquay Davey`, `GJFL|Torquay Milliken`, 
        `GJFL|Torquay Stone`, `GJFL|Torquay Watson`, `GJFL|Winchelsea / Inverleigh`, `GFL|Geelong_West_St_Peters`, `BFL|Newcomb`, `CDFNL|Alvie`, `CDFNL|Apollo Bay`, 
        `GJFL|Surf Coast`, `GJFL|East Newcomb`, `GJFL|Bell Post Hill`) 
        SELECT season_year, umpire_full_name, club_name, short_league_name, age_group, umpire_type_name,  
        (CASE WHEN club_name = 'Anakie' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Anakie', 
        (CASE WHEN club_name = 'Bannockburn' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Bannockburn', 
        (CASE WHEN club_name = 'Corio' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Corio', 
        (CASE WHEN club_name = 'East Geelong' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|East_Geelong', 
        (CASE WHEN club_name = 'North Geelong' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|North_Geelong', 
        (CASE WHEN club_name = 'Portarlington' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Portarlington', 
        (CASE WHEN club_name = 'Werribee Centrals' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Werribee_Centrals', 
        (CASE WHEN club_name = 'Winchelsea' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Winchelsea', 
        (CASE WHEN club_name = 'Bell Park' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Bell_Park', 
        (CASE WHEN club_name = 'Bell Post Hill' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Bell_Post_Hill', 
        (CASE WHEN club_name = 'Belmont Lions' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Belmont_Lions', 
        (CASE WHEN club_name = 'Colac' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Colac', 
        (CASE WHEN club_name = 'Geelong Amateur' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Geelong_Amateur', 
        (CASE WHEN club_name = 'Geelong West' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Geelong_West', 
        (CASE WHEN club_name = 'Grovedale' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Grovedale', 
        (CASE WHEN club_name = 'Gwsp' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Gwsp', 
        (CASE WHEN club_name = 'Inverleigh' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Inverleigh', 
        (CASE WHEN club_name = 'Lara' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Lara', 
        (CASE WHEN club_name = 'Leopold' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Leopold', 
        (CASE WHEN club_name = 'Newtown & Chilwell' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Newtown_&_Chilwell', 
        (CASE WHEN club_name = 'North Shore' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|North_Shore', 
        (CASE WHEN club_name = 'South Barwon' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|South_Barwon', 
        (CASE WHEN club_name = 'St Joseph''s' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|St_Joseph\'s', 
        (CASE WHEN club_name = 'St Mary''s' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|St_Mary\'s', 
        (CASE WHEN club_name = 'Torquay' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Torquay', 
      (CASE WHEN club_name = 'Barwon Heads' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Barwon_Heads', 
        (CASE WHEN club_name = 'Drysdale' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale', 
        (CASE WHEN club_name = 'East Geelong' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|East_Geelong', 
        (CASE WHEN club_name = 'Geelong West St Peters' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Geelong_West_St_Peters', 
        (CASE WHEN club_name = 'Grovedale' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Grovedale', 
        (CASE WHEN club_name = 'Inverleigh' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Inverleigh', 
        (CASE WHEN club_name = 'Leopold' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Leopold', 
        (CASE WHEN club_name = 'Newcomb' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Newcomb', 
        (CASE WHEN club_name = 'Newtown & Chilwell' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Newtown_&_Chilwell', 
        (CASE WHEN club_name = 'Ocean Grove' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ocean_Grove', 
        (CASE WHEN club_name = 'South Barwon' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|South_Barwon', 
        (CASE WHEN club_name = 'St Albans' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Albans', 
        (CASE WHEN club_name = 'St Joseph''s' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Joseph\'s', 
        (CASE WHEN club_name = 'St Mary''s' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Mary\'s', 
        (CASE WHEN club_name = 'Torquay' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay', 
        (CASE WHEN club_name = 'Anglesea' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Anglesea', 
        (CASE WHEN club_name = 'Barwon Heads' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Barwon_Heads', 
        (CASE WHEN club_name = 'Corio' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Corio', 
        (CASE WHEN club_name = 'Thomson' AND short_league_name = 'GDFL' THEN COUNT(*) ELSE 0 END) as 'GDFL|Thomson', 
        (CASE WHEN club_name = 'Anglesea' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Anglesea', 
        (CASE WHEN club_name = 'Bell Park' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Bell_Park', 
        (CASE WHEN club_name = 'North Shore' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|North_Shore', 
        (CASE WHEN club_name = 'Belmont Lions' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Belmont_Lions',
      (CASE WHEN club_name = 'Colac' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Colac', 
        (CASE WHEN club_name = 'North Geelong' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|North_Geelong', 
        (CASE WHEN club_name = 'Ogcc' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ogcc', 
        (CASE WHEN club_name = 'Torquay Jones' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Jones', 
        (CASE WHEN club_name = 'Torquay Papworth' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Papworth', 
        (CASE WHEN club_name = 'Winchelsea / Grovedale' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Winchelsea_/_Grovedale', 
        (CASE WHEN club_name = 'Modewarre' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Modewarre', 
        (CASE WHEN club_name = 'Newcomb Power' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Newcomb_Power', 
        (CASE WHEN club_name = 'Queenscliff' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Queenscliff', 
        (CASE WHEN club_name = 'St Albans' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|St_Albans', 
        (CASE WHEN club_name = 'Drysdale Byrne' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Byrne', 
        (CASE WHEN club_name = 'Drysdale Hall' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Hall', 
        (CASE WHEN club_name = 'Drysdale Hector' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Hector', 
        (CASE WHEN club_name = 'Lara' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Lara', 
        (CASE WHEN club_name = 'Queenscliff' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Queenscliff', 
        (CASE WHEN club_name = 'St Albans Reid' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Albans_Reid', 
        (CASE WHEN club_name = 'Torquay Bumpstead' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Bumpstead', 
        (CASE WHEN club_name = 'Torquay Pyers' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Pyers', 
        (CASE WHEN club_name = 'Modewarre' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Modewarre', 
        (CASE WHEN club_name = 'Ocean Grove' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Ocean_Grove', 
        (CASE WHEN club_name = 'Drysdale' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Drysdale', 
        (CASE WHEN club_name = 'Portarlington' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Portarlington', 
        (CASE WHEN club_name = 'St Joseph''s Podbury' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Joseph\'s_Podbury', 
        (CASE WHEN club_name = 'Geelong Amateur' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Geelong_Amateur', 
      (CASE WHEN club_name = 'Winchelsea' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Winchelsea', 
        (CASE WHEN club_name = 'Anakie' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Anakie', 
        (CASE WHEN club_name = 'Bannockburn' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Bannockburn', 
        (CASE WHEN club_name = 'South Barwon / Geelong Amateur' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|South_Barwon_/_Geelong_Amateur', 
        (CASE WHEN club_name = 'St Joseph''s Hill' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Joseph\'s_Hill', 
        (CASE WHEN club_name = 'Torquay Dunstan' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Dunstan', 
        (CASE WHEN club_name = 'Werribee Centrals' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Werribee_Centrals', 
        (CASE WHEN club_name = 'Drysdale Eddy' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Eddy', 
        (CASE WHEN club_name = 'Belmont Lions / Newcomb' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Belmont_Lions_/_Newcomb', 
        (CASE WHEN club_name = 'Torquay Coles' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Coles', 
        (CASE WHEN club_name = 'Gwsp / Bannockburn' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Gwsp_/_Bannockburn', 
        (CASE WHEN club_name = 'St Albans Allthorpe' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St_Albans_Allthorpe', 
        (CASE WHEN club_name = 'Drysdale Bennett' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale_Bennett', 
        (CASE WHEN club_name = 'Torquay Scott' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Scott', 
        (CASE WHEN club_name = 'Torquay Nairn' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay_Nairn', 
        (CASE WHEN club_name = 'Tigers Gold' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Tigers_Gold', 
        (CASE WHEN club_name = 'Birregurra' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Birregurra', 
        (CASE WHEN club_name = 'Lorne' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Lorne', 
        (CASE WHEN club_name = 'Colac Imperials' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Colac Imperials', 
        (CASE WHEN club_name = 'Irrewarra-beeac' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Irrewarra-beeac', 
        (CASE WHEN club_name = 'Otway Districts' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Otway Districts', 
        (CASE WHEN club_name = 'Simpson' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Simpson', 
        (CASE WHEN club_name = 'South Colac' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|South Colac', 
        (CASE WHEN club_name = 'Western Eagles' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Western Eagles', 
      (CASE WHEN club_name = 'Aireys Inlet' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Aireys Inlet',
          (CASE WHEN club_name = 'Ammos Blue' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ammos Blue', 
          (CASE WHEN club_name = 'Ammos Green' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ammos Green',
          (CASE WHEN club_name = 'Ammos White' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ammos White',
          (CASE WHEN club_name = 'Bannockburn / South Barwon' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Bannockburn / South Barwon', 
          (CASE WHEN club_name = 'Barwon Heads Gulls' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Barwon Heads Gulls', 
          (CASE WHEN club_name = 'Barwon Heads Heads' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Barwon Heads Heads',
          (CASE WHEN club_name = 'Dragons' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Dragons', 
          (CASE WHEN club_name = 'Drysdale 1' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale 1', 
          (CASE WHEN club_name = 'Drysdale 2' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale 2', 
          (CASE WHEN club_name = 'Drysdale Humphrey' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale Humphrey', 
          (CASE WHEN club_name = 'Drysdale Mcintyre' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale Mcintyre', 
          (CASE WHEN club_name = 'Drysdale Mckeon' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale Mckeon', 
          (CASE WHEN club_name = 'Drysdale Scott' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale Scott', 
          (CASE WHEN club_name = 'Drysdale Smith' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale Smith', 
          (CASE WHEN club_name = 'Drysdale Taylor' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale Taylor', 
          (CASE WHEN club_name = 'Drysdale Wilson' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Drysdale Wilson', 
          (CASE WHEN club_name = 'Eagles Black' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Eagles Black', 
          (CASE WHEN club_name = 'Eagles Red' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Eagles Red', 
          (CASE WHEN club_name = 'East Newcomb Lions' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|East Newcomb Lions', 
          (CASE WHEN club_name = 'East Tigers' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|East Tigers', 
          (CASE WHEN club_name = 'Flying Joeys' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Flying Joeys', 
          (CASE WHEN club_name = 'Gdfl Raiders' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Gdfl Raiders', 
          (CASE WHEN club_name = 'Grovedale Broad' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Grovedale Broad', 
          (CASE WHEN club_name = 'Grovedale Ford' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Grovedale Ford', 
          (CASE WHEN club_name = 'Grovedale Mcneel' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Grovedale Mcneel', 
          (CASE WHEN club_name = 'Grovedale Waldron' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Grovedale Waldron', 
          (CASE WHEN club_name = 'Grovedale Williams' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Grovedale Williams', 
          (CASE WHEN club_name = 'Grovedale Young' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Grovedale Young', 
          (CASE WHEN club_name = 'Lara Batman' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Lara Batman', 
          (CASE WHEN club_name = 'Lara Flinders' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Lara Flinders', 
          (CASE WHEN club_name = 'Lara Hume' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Lara Hume', 
          (CASE WHEN club_name = 'Leopold Brown' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Leopold Brown', 
          (CASE WHEN club_name = 'Leopold Dowsett' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Leopold Dowsett', 
          (CASE WHEN club_name = 'Leopold Ruggles' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Leopold Ruggles', 
          (CASE WHEN club_name = 'Lethbridge' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Lethbridge', 
          (CASE WHEN club_name = 'Newtown & Chilwell Eagles' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Newtown & Chilwell Eagles', 
          (CASE WHEN club_name = 'Ogcc Blue' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ogcc Blue', 
          (CASE WHEN club_name = 'Ogcc Orange' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ogcc Orange', 
          (CASE WHEN club_name = 'Ogcc Red' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ogcc Red', 
          (CASE WHEN club_name = 'Ogcc White' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Ogcc White', 
          (CASE WHEN club_name = 'Queenscliff Blue' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Queenscliff Blue', 
          (CASE WHEN club_name = 'Queenscliff Red' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Queenscliff Red', 
          (CASE WHEN club_name = 'Roosters' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Roosters', 
          (CASE WHEN club_name = 'Saints White' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Saints White', 
          (CASE WHEN club_name = 'Seagulls' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Seagulls', 
          (CASE WHEN club_name = 'South Barwon Blue' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|South Barwon Blue', 
          (CASE WHEN club_name = 'South Barwon Red' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|South Barwon Red', 
          (CASE WHEN club_name = 'South Barwon White' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|South Barwon White', 
          (CASE WHEN club_name = 'St Albans Butterworth' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St Albans Butterworth', 
          (CASE WHEN club_name = 'St Albans Grinter' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St Albans Grinter', 
          (CASE WHEN club_name = 'St Albans Mcfarlane' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St Albans Mcfarlane', 
          (CASE WHEN club_name = 'St Albans Osborne' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|St Albans Osborne', 
          (CASE WHEN club_name = 'Surf Coast Suns' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Surf Coast Suns', 
          (CASE WHEN club_name = 'Teesdale Roos' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Teesdale Roos', 
          (CASE WHEN club_name = 'Tigers' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Tigers', 
          (CASE WHEN club_name = 'Torquay Boyse' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay Boyse', 
          (CASE WHEN club_name = 'Torquay Browning' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay Browning', 
          (CASE WHEN club_name = 'Torquay Bruce' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay Bruce', 
          (CASE WHEN club_name = 'Torquay Coleman' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay Coleman', 
          (CASE WHEN club_name = 'Torquay Davey' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay Davey', 
          (CASE WHEN club_name = 'Torquay Milliken' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay Milliken', 
          (CASE WHEN club_name = 'Torquay Stone' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay Stone', 
          (CASE WHEN club_name = 'Torquay Watson' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Torquay Watson', 
          (CASE WHEN club_name = 'Winchelsea / Inverleigh' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Winchelsea / Inverleigh',
          (CASE WHEN club_name = 'Geelong West St Peters' AND short_league_name = 'GFL' THEN COUNT(*) ELSE 0 END) as 'GFL|Geelong_West_St_Peters', 
          (CASE WHEN club_name = 'Newcomb' AND short_league_name = 'BFL' THEN COUNT(*) ELSE 0 END) as 'BFL|Newcomb', 
          (CASE WHEN club_name = 'Alvie' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Alvie', 
          (CASE WHEN club_name = 'Apollo Bay' AND short_league_name = 'CDFNL' THEN COUNT(*) ELSE 0 END) as 'CDFNL|Apollo Bay', 
          (CASE WHEN club_name = 'Surf Coast' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Surf Coast', 
          (CASE WHEN club_name = 'East Newcomb' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|East Newcomb', 
          (CASE WHEN club_name = 'Bell Post Hill' AND short_league_name = 'GJFL' THEN COUNT(*) ELSE 0 END) as 'GJFL|Bell Post Hill'  

      FROM mv_denormalised AS a 
        GROUP BY season_year, umpire_full_name, club_name, short_league_name, age_group, umpire_type_name 
        ORDER BY season_year, umpire_full_name, club_name, short_league_name, age_group, umpire_type_name;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_01'), 1, ROW_COUNT());        

ALTER TABLE mv_report_01 ENABLE KEYS;

ALTER TABLE mv_report_02 DISABLE KEYS;
        
INSERT INTO mv_report_02 (season_year, full_name, umpire_type_name, short_league_name, age_group,  
            `Seniors|BFL`, `Seniors|GDFL`, `Seniors|GFL`, `Reserves|BFL`, `Reserves|GDFL`, `Reserves|GFL`, `Colts|GJFL`,  
            `Under 16|GJFL`, `Under 14|GJFL`, `Youth Girls|GJFL`, `Junior Girls|GJFL`, `Seniors|2 Umpires Colac`, `Seniors|2 Umpires Geelong`,
            `Seniors|CDFNL`, `Reserves|CDFNL`, `Under 17.5|CDFNL`, `Under 14.5|CDFNL`, `Under 12|GJFL`) 
      SELECT season_year,  
            umpire_full_name,  
            umpire_type_name, 
            short_league_name, 
            age_group, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Colts' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Under 16' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Under 14' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Youth Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Junior Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END),  
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'CDFNL' AND two_ump_flag = 1 THEN match_count ELSE 0 END), 
			(CASE WHEN age_group = 'Seniors' AND short_league_name IN ('BFL', 'GFL', 'GDFL') AND two_ump_flag = 1 THEN match_count ELSE 0 END), 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END), 
            (CASE WHEN age_group = 'Reserves' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END), 
            (CASE WHEN age_group = 'Under 17.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END), 
            (CASE WHEN age_group = 'Under 14.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END), 
            (CASE WHEN age_group = 'Under 12' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) 
            FROM (  
				SELECT   
				d1.season_year, 
				d1.umpire_type_name,  
				d1.age_group_ID,  
				d1.age_group,  
				d1.short_league_name,  
				0 AS two_ump_flag,
				d1.umpire_full_name,  
				COUNT(DISTINCT d1.match_played_ID) AS match_count  
				FROM mv_denormalised d1
				GROUP BY d1.season_year, d1.umpire_type_name , d1.age_group_ID , d1.age_group, d1.short_league_name , d1.umpire_full_name
				
				UNION ALL 
				
				SELECT   
				d1.season_year, 
				d1.umpire_type_name,  
				d1.age_group_ID,  
				d1.age_group,  
				d1.short_league_name,
				1 AS two_ump_flag,
				d1.umpire_full_name,  
				COUNT(DISTINCT d1.match_played_ID)  
				FROM mv_denormalised d1
				INNER JOIN ( 
					SELECT   
					d2.season_year, 
					d2.match_played_ID,  
					COUNT(DISTINCT d2.umpire_ID) AS umpire_count  
					FROM mv_denormalised d2
					WHERE d2.umpire_type_name = 'Field'
					AND d2.age_group = 'Seniors'  
					GROUP BY d2.season_year, d2.match_played_ID, d2.umpire_type_name, d2.age_group, d2.short_league_name
					HAVING COUNT(DISTINCT d2.umpire_ID) = 2  
				) AS qryMatchesWithTwoUmpires ON d1.match_played_ID = qryMatchesWithTwoUmpires.match_played_ID  
				WHERE d1.umpire_type_name = 'Field'
				AND d1.age_group = 'Seniors'  
				GROUP BY d1.season_year, d1.umpire_type_name , d1.age_group_ID , d1.age_group , d1.short_league_name, d1.umpire_full_name 
			) AS mainquery    
            ORDER BY season_year, umpire_full_name;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_02'), 1, ROW_COUNT());            

ALTER TABLE mv_report_02 ENABLE KEYS;
            
ALTER TABLE mv_summary_staging DISABLE KEYS;

INSERT INTO mv_summary_staging (season_year, region, umpire_type_id, umpire_type, age_group, short_league_name, 
          round_date, match_id, home, away, home_club, away_club, age_group_ID, weekdate) 
      SELECT season_year, region_name, umpire_type_id, umpire_type, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID,  
            ADDDATE(round_date, (5-Weekday(round_date))) as WeekDate 
            FROM ( 
      SELECT season_year, region.region_name, 1 as umpire_type_id, 'Field' AS umpire_type, age_group.age_group, league.short_league_name,  
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
            INNER JOIN season ON season.ID = round.season_id 
            INNER JOIN region ON league.region_id = region.id 
            WHERE match_played.id NOT IN ( 
				SELECT umpire_name_type_match.match_id 
				FROM umpire_name_type_match 
				INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
				INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id 
				WHERE ut_sub.umpire_type_name='Field' 
            ) 
            AND season.id = pSeasonID 
            UNION 
            SELECT season_year, region.region_name, 3 as umpire_type_id, 'Goal' AS umpire_type, age_group.age_group, league.short_league_name,  
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
            INNER JOIN season ON season.ID = round.season_id 
            INNER JOIN region ON league.region_id = region.id 
            WHERE match_played.id NOT IN ( 
            SELECT umpire_name_type_match.match_id 
            FROM umpire_name_type_match 
            INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
            INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id 
            WHERE ut_sub.umpire_type_name='Goal' 
            ) 
            AND season.id = pSeasonID 
      UNION 
            SELECT season_year, region.region_name, 2 as umpire_type_id, 'Boundary' AS umpire_type, age_group.age_group, league.short_league_name,  
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
            INNER JOIN season ON season.ID = round.season_id 
            INNER JOIN region ON league.region_id = region.id 
            WHERE match_played.id NOT IN ( 
            SELECT umpire_name_type_match.match_id 
            FROM umpire_name_type_match 
            INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
            INNER JOIN umpire_type ut_sub ON ut_sub.ID = umpire_name_type.umpire_type_id 
            WHERE ut_sub.umpire_type_name='Boundary' 
            ) 
            AND season.id = pSeasonID 
            ) AS outer1;
            


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_summary_staging'), 1, ROW_COUNT());
ALTER TABLE mv_summary_staging ENABLE KEYS;
            
           
ALTER TABLE mv_report_03 DISABLE KEYS;

INSERT INTO `mv_report_03` (season_year, region, weekdate, 
            `No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`, 
            `No Senior Goal|BFL`, `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, 
            `No Reserve Goal|BFL`, `No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, 
            `No Colts Field|Clubs`, `No Colts Field|No.`, `No U16 Field|Clubs`, `No U16 Field|No.`, 
            `No U14 Field|Clubs`, `No U14 Field|No.`, 
            `No Senior Boundary|CDFNL`, 
            `No Senior Goal|CDFNL`, 
            `No Reserve Goal|CDFNL`, 
            `No U12 Field|Clubs`, `No U12 Field|No.` ) 
            SELECT season_year, region, weekdate, 
            MAX(`No Senior Boundary|BFL`), MAX(`No Senior Boundary|GDFL`), MAX(`No Senior Boundary|GFL`), SUM(`No Senior Boundary|No.`), MAX(`No Senior Goal|BFL`), 
            MAX(`No Senior Goal|GDFL`), MAX(`No Senior Goal|GFL`), SUM(`No Senior Goal|No.`), MAX(`No Reserve Goal|BFL`), MAX(`No Reserve Goal|GDFL`), 
            MAX(`No Reserve Goal|GFL`), SUM(`No Reserve Goal|No.`), MAX(`No Colts Field|Clubs`),SUM(`No Colts Field|No.`), MAX(`No U16 Field|Clubs`), 
            SUM(`No U16 Field|No.`), MAX(`No U14 Field|Clubs`), SUM(`No U14 Field|No.`), 
            MAX(`No Senior Boundary|CDFNL`), MAX(`No Senior Goal|CDFNL`), MAX(`No Reserve Goal|CDFNL`), MAX(`No U12 Field|Clubs`), SUM(`No U12 Field|No.`)  
            FROM (
      SELECT season_year, region, weekdate, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END) as `No Senior Boundary|BFL`, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END) as `No Senior Boundary|GDFL`, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END) as `No Senior Boundary|GFL`, 
            (CASE WHEN age_group = 'Seniors' AND umpire_type = 'Boundary' THEN match_count ELSE 0 END) as `No Senior Boundary|No.`, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'BFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Senior Goal|BFL`, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'GDFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Senior Goal|GDFL`, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'GFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Senior Goal|GFL`, 
            (CASE WHEN age_group = 'Seniors' AND umpire_type = 'Goal' THEN match_count ELSE 0 END) as `No Senior Goal|No.`, 
            (CASE WHEN age_group = 'Reserve' AND short_league_name = 'BFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Reserve Goal|BFL`, 
            (CASE WHEN age_group = 'Reserve' AND short_league_name = 'GDFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Reserve Goal|GDFL`, 
            (CASE WHEN age_group = 'Reserve' AND short_league_name = 'GFL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Reserve Goal|GFL`, 
            (CASE WHEN age_group = 'Reserve' AND umpire_type = 'Goal' THEN match_count ELSE 0 END) as `No Reserve Goal|No.`, 
            (CASE WHEN age_group = 'Colts' AND umpire_type = 'Field' THEN team_list ELSE NULL END) as `No Colts Field|Clubs`, 
            (CASE WHEN age_group = 'Colts' AND umpire_type = 'Field' THEN match_count ELSE 0 END) as `No Colts Field|No.`, 
            (CASE WHEN age_group = 'Under 16' AND umpire_type = 'Field' THEN team_list ELSE NULL END) as `No U16 Field|Clubs`, 
            (CASE WHEN age_group = 'Under 16' AND umpire_type = 'Field' THEN match_count ELSE 0 END) as `No U16 Field|No.`, 
            (CASE WHEN age_group = 'Under 14' AND umpire_type = 'Field' THEN team_list ELSE NULL END) as `No U14 Field|Clubs`, 
            (CASE WHEN age_group = 'Under 14' AND umpire_type = 'Field' THEN match_count ELSE 0 END)  as `No U14 Field|No.`, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'CDFNL' AND umpire_type = 'Boundary' THEN team_list ELSE NULL END) as `No Senior Boundary|CDFNL`, 
            (CASE WHEN age_group = 'Seniors' AND short_league_name = 'CDFNL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Senior Goal|CDFNL`, 
            (CASE WHEN age_group = 'Reserve' AND short_league_name = 'CDFNL' AND umpire_type = 'Goal' THEN team_list ELSE NULL END) as `No Reserve Goal|CDFNL`, 
            (CASE WHEN age_group = 'Under 12' AND umpire_type = 'Field' THEN team_list ELSE NULL END) as `No U12 Field|Clubs`, 
            (CASE WHEN age_group = 'Under 12' AND umpire_type = 'Field' THEN match_count ELSE 0 END)  as `No U12 Field|No.` 
            FROM (
            
      SELECT season_year, region, umpire_type, age_group, short_league_name, weekdate, 
            GROUP_CONCAT(home, ' vs ', away) AS team_list, 
            COUNT(home) AS match_count  
            FROM ( 
				SELECT season_year, region, umpire_type_id, umpire_type, age_group, short_league_name, round_date, match_id, home, away, home_club, away_club, age_group_ID,  
				weekdate 
				FROM mv_summary_staging 
				WHERE season_year = @vSeasonYear 
            ) AS outer2 
            GROUP BY season_year, region, umpire_type, age_group, short_league_name, weekdate 
      ) AS outer3 
             
        	) as maintable 
        GROUP BY  season_year, region, weekdate, `No Senior Boundary|BFL`, `No Senior Boundary|GDFL`, `No Senior Boundary|GFL`, `No Senior Boundary|No.`,  
        `No Senior Goal|BFL`, `No Senior Goal|GDFL`, `No Senior Goal|GFL`, `No Senior Goal|No.`, `No Reserve Goal|BFL`,  
        `No Reserve Goal|GDFL`, `No Reserve Goal|GFL`, `No Reserve Goal|No.`, `No Colts Field|Clubs`,`No Colts Field|No.`,  
        `No U16 Field|Clubs`, `No U16 Field|No.`, `No U14 Field|Clubs`, `No U14 Field|No.`, 
        `No Senior Boundary|CDFNL`, `No Senior Goal|CDFNL`, `No Reserve Goal|CDFNL`, `No U12 Field|Clubs`, `No U12 Field|No.` 
        ORDER BY season_year, region, weekdate;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_03'), 1, ROW_COUNT());
ALTER TABLE mv_report_03 ENABLE KEYS;

ALTER TABLE mv_report_04 DISABLE KEYS;

INSERT INTO mv_report_04 (season_year, region, club_name, `Boundary|Seniors|BFL`, 
        `Boundary|Seniors|GDFL`, `Boundary|Seniors|GFL`, `Boundary|Reserves|BFL`, `Boundary|Reserves|GDFL`, 
        `Boundary|Reserves|GFL`, `Boundary|Colts|GJFL`, `Boundary|Under 16|GJFL`, `Boundary|Under 14|GJFL`, 
        `Boundary|Youth Girls|GJFL`, `Boundary|Junior Girls|GJFL`, `Field|Seniors|BFL`, `Field|Seniors|GDFL`, 
        `Field|Seniors|GFL`, `Field|Reserves|BFL`, `Field|Reserves|GDFL`, `Field|Reserves|GFL`, 
        `Field|Colts|GJFL`, `Field|Under 16|GJFL`, `Field|Under 14|GJFL`, `Field|Youth Girls|GJFL`, 
        `Field|Junior Girls|GJFL`, `Goal|Seniors|BFL`, `Goal|Seniors|GDFL`, `Goal|Seniors|GFL`, 
        `Goal|Reserves|BFL`, `Goal|Reserves|GDFL`, `Goal|Reserves|GFL`, `Goal|Colts|GJFL`, 
        `Goal|Under 16|GJFL`, `Goal|Under 14|GJFL`, `Goal|Youth Girls|GJFL`, `Goal|Junior Girls|GJFL`,  
        `Boundary|Seniors|CDFNL`, `Boundary|Reserves|CDFNL`, `Boundary|Under 17.5|CDFNL`, `Boundary|Under 14.5|CDFNL`,  
        `Field|Seniors|CDFNL`, `Field|Reserves|CDFNL`, `Field|Under 17.5|CDFNL`, `Field|Under 14.5|CDFNL`,  
        `Goal|Seniors|CDFNL`, `Goal|Reserves|CDFNL`, `Goal|Under 17.5|CDFNL`, `Goal|Under 14.5|CDFNL`,  
        `Boundary|Under 12|GJFL`, `Field|Under 12|GJFL`, `Goal|Under 12|GJFL`) 
       SELECT season_year, region, club, SUM(`Boundary|Seniors|BFL`), SUM(`Boundary|Seniors|GDFL`), SUM(`Boundary|Seniors|GFL`), 
        SUM(`Boundary|Reserves|BFL`), SUM(`Boundary|Reserves|GDFL`), SUM(`Boundary|Reserves|GFL`), SUM(`Boundary|Colts|GJFL`), 
        SUM(`Boundary|Under 16|GJFL`), SUM(`Boundary|Under 14|GJFL`), SUM(`Boundary|Youth Girls|GJFL`), SUM(`Boundary|Junior Girls|GJFL`), 
        SUM(`Field|Seniors|BFL`), SUM(`Field|Seniors|GDFL`), SUM(`Field|Seniors|GFL`), SUM(`Field|Reserves|BFL`), 
        SUM(`Field|Reserves|GDFL`), SUM(`Field|Reserves|GFL`), SUM(`Field|Colts|GJFL`), SUM(`Field|Under 16|GJFL`), 
        SUM(`Field|Under 14|GJFL`), SUM(`Field|Youth Girls|GJFL`), SUM(`Field|Junior Girls|GJFL`), SUM(`Goal|Seniors|BFL`), 
        SUM(`Goal|Seniors|GDFL`), SUM(`Goal|Seniors|GFL`), SUM(`Goal|Reserves|BFL`), SUM(`Goal|Reserves|GDFL`), 
        SUM(`Goal|Reserves|GFL`), SUM(`Goal|Colts|GJFL`), SUM(`Goal|Under 16|GJFL`), SUM(`Goal|Under 14|GJFL`), 
        SUM(`Goal|Youth Girls|GJFL`), SUM(`Goal|Junior Girls|GJFL`), 
        SUM(`Boundary|Seniors|CDFNL`), SUM(`Boundary|Reserves|CDFNL`), SUM(`Boundary|Under 17.5|CDFNL`), SUM(`Boundary|Under 14.5|CDFNL`), 
        SUM(`Field|Seniors|CDFNL`), SUM(`Field|Reserves|CDFNL`), SUM(`Field|Under 17.5|CDFNL`), SUM(`Field|Under 14.5|CDFNL`), 
        SUM(`Goal|Seniors|CDFNL`), SUM(`Goal|Reserves|CDFNL`), SUM(`Goal|Under 17.5|CDFNL`), SUM(`Goal|Under 14.5|CDFNL`),  
        SUM(`Boundary|Under 12|GJFL`), SUM(`Field|Under 12|GJFL`), SUM(`Goal|Under 12|GJFL`)  
        FROM ( 
      
       SELECT season_year, region, club, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|BFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|GDFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Boundary|Seniors|GFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|BFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|GDFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Boundary|Reserves|GFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Colts' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Colts|GJFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 16' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Under 16|GJFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 14' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Under 14|GJFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Youth Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Youth Girls|GJFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Junior Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Junior Girls|GJFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Field|Seniors|BFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Field|Seniors|GDFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Field|Seniors|GFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Field|Reserves|BFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Field|Reserves|GDFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Field|Reserves|GFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Colts' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Colts|GJFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Under 16' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Under 16|GJFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Under 14' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Under 14|GJFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Youth Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Youth Girls|GJFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Junior Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Junior Girls|GJFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Goal|Seniors|BFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Goal|Seniors|GDFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Goal|Seniors|GFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'BFL' THEN match_count ELSE 0 END) as `Goal|Reserves|BFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'GDFL' THEN match_count ELSE 0 END) as `Goal|Reserves|GDFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'GFL' THEN match_count ELSE 0 END) as `Goal|Reserves|GFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Colts' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Colts|GJFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 16' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Under 16|GJFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 14' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Under 14|GJFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Youth Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Youth Girls|GJFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Junior Girls' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Junior Girls|GJFL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Seniors' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Boundary|Seniors|CDFNL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Reserves' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Boundary|Reserves|CDFNL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 17.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Boundary|Under 17.5|CDFNL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 14.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Boundary|Under 14.5|CDFNL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Seniors' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Field|Seniors|CDFNL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Reserves' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Field|Reserves|CDFNL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Under 17.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Field|Under 17.5|CDFNL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Under 14.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Field|Under 14.5|CDFNL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Seniors' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Goal|Seniors|CDFNL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Reserves' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Goal|Reserves|CDFNL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 17.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Goal|Under 17.5|CDFNL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 14.5' AND short_league_name = 'CDFNL' THEN match_count ELSE 0 END) as `Goal|Under 14.5|CDFNL`, 
        (CASE WHEN umpire_type = 'Boundary' AND age_group = 'Under 12' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Boundary|Under 12|GJFL`, 
        (CASE WHEN umpire_type = 'Field' AND age_group = 'Under 12' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Field|Under 12|GJFL`, 
        (CASE WHEN umpire_type = 'Goal' AND age_group = 'Under 12' AND short_league_name = 'GJFL' THEN match_count ELSE 0 END) as `Goal|Under 12|GJFL` 
        FROM ( 
       SELECT season_year, region, age_group, umpire_type, Club, short_league_name, SUM(Match_Count) AS match_count 
            FROM ( 
            SELECT season_year, region, 'Home' as Club_Type, s.age_group, s.umpire_type, s.home_club as Club, s.short_league_name,  
            COUNT(s.age_group_ID) AS Match_Count, age_group_ID 
            FROM mv_summary_staging s 
            WHERE season_year = @vSeasonYear
            GROUP BY s.age_group, s.region, s.umpire_type, s.home_club, s.age_group_ID 
            UNION ALL 
            SELECT season_year, region, 'Away' as Club_Type,  s.age_group, s.umpire_type, s.away_club,  s.short_league_name,  
            COUNT(s.age_group_ID), age_group_ID 
            FROM mv_summary_staging s 
            WHERE season_year = @vSeasonYear
            GROUP BY season_year, s.region, s.age_group, s.umpire_type, s.away_club, s.age_group_ID, s.short_league_name 
            )  AS outer1 
            GROUP BY season_year, region, age_group, umpire_type, Club, short_league_name 
            ) AS outer2 
            ) AS outer3 
            GROUP BY season_year, region, club 
            ORDER BY season_year, region, club ;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_04'), 1, ROW_COUNT());            

ALTER TABLE mv_report_04 ENABLE KEYS;

ALTER TABLE mv_report_05 DISABLE KEYS;

INSERT INTO mv_report_05 (season_year, region, umpire_type, age_group, `BFL|Games`, `BFL|Total`, `BFL|Pct`, 
      `GDFL|Games`, `GDFL|Total`, `GDFL|Pct`, 
      `GFL|Games`, `GFL|Total`, `GFL|Pct`, 
      `GJFL|Games`, `GJFL|Total`, `GJFL|Pct`, 
      `CDFNL|Games`, `CDFNL|Total`, `CDFNL|Pct`,`Total`, display_order) 
SELECT 
    season_year,
    outer2.region,
    ua.umpire_type_name,
    ua.age_group,
    IFNULL(SUM(`BFL`), 0) AS sum_bfl,
    SUM(outer3.match_count_bfl) as match_count_bfl,
    /*IFNULL(SUM(`BFL`), 0)/SUM(outer3.match_count_bfl)*100 AS pct_bfl,*/
    CASE WHEN SUM(BFL) IS NULL OR SUM(outer3.match_count_bfl) = 0 THEN 0 ELSE SUM(BFL)/SUM(outer3.match_count_bfl)*100 END AS pct_bfl,
    IFNULL(SUM(`GDFL`), 0) AS sum_gdfl,
    SUM(outer3.match_count_gdfl) AS match_count_gdfl,
    /*IFNULL(SUM(`GDFL`), 0)/SUM(outer3.match_count_gdfl)*100 AS pct_gdfl,*/
    CASE WHEN SUM(GDFL) IS NULL OR SUM(outer3.match_count_gdfl) = 0 THEN 0 ELSE SUM(GDFL)/SUM(outer3.match_count_gdfl)*100 END AS pct_gdfl,
    IFNULL(SUM(`GFL`), 0) AS sum_gfl,
    SUM(outer3.match_count_gfl) AS match_count_gfl,
    /*IFNULL(SUM(`GFL`), 0)/SUM(outer3.match_count_gfl)*100 AS pct_gfl,*/
    CASE WHEN SUM(GFL) IS NULL OR SUM(outer3.match_count_gfl) = 0 THEN 0 ELSE SUM(GFL)/SUM(outer3.match_count_gfl)*100 END AS pct_gfl,
    IFNULL(SUM(`GJFL`), 0) AS sum_gjfl,
    SUM(outer3.match_count_gjfl) AS match_count_gjfl,
    /*IFNULL(SUM(`GJFL`), 0)/SUM(outer3.match_count_gjfl)*100 AS pct_gjfl,*/
    CASE WHEN SUM(GJFL) IS NULL OR SUM(outer3.match_count_gjfl) = 0 THEN 0 ELSE SUM(GJFL)/SUM(outer3.match_count_gjfl)*100 END AS pct_gjfl,
    IFNULL(SUM(`CDFNL`), 0) AS sum_cdfnl,
    SUM(outer3.match_count_cdfnl) AS match_count_cdfnl,
    /*IFNULL(SUM(`CDFNL`), 0)/SUM(outer3.match_count_cdfnl)*100 AS pct_cdfnl,*/
    CASE WHEN SUM(CDFNL) IS NULL OR SUM(outer3.match_count_cdfnl) = 0 THEN 0 ELSE SUM(CDFNL)/SUM(outer3.match_count_cdfnl)*100 END AS pct_cdfnl,
    CASE
        WHEN outer2.region = 'Geelong' THEN IFNULL(SUM(`BFL` + `GDFL` + `GFL` + `GJFL`), 0)
        WHEN outer2.region = 'Colac' THEN IFNULL(SUM(`CDFNL`), 0)
        ELSE 0
    END AS Total,
    ua.display_order
FROM
    (SELECT 
        ut.id AS umpire_type_id,
            ut.umpire_type_name,
            ag.id AS age_group_id,
            ag.age_group,
            ag.display_order
    FROM
        umpire_type ut, age_group ag) AS ua
        LEFT JOIN
    (SELECT 
        season_year,
            region,
            umpire_type,
            age_group,
            age_group_ID,
            short_league_name,
            (CASE
                WHEN short_league_name = 'BFL' THEN match_count
                ELSE 0
            END) AS `BFL`,
            (CASE
                WHEN short_league_name = 'GDFL' THEN match_count
                ELSE 0
            END) AS `GDFL`,
            (CASE
                WHEN short_league_name = 'GFL' THEN match_count
                ELSE 0
            END) AS `GFL`,
            (CASE
                WHEN short_league_name = 'GJFL' THEN match_count
                ELSE 0
            END) AS `GJFL`,
            (CASE
                WHEN short_league_name = 'CDFNL' THEN match_count
                ELSE 0
            END) AS `CDFNL`
	FROM
        (SELECT 
        s.season_year,
            region,
            s.umpire_type,
            s.age_group,
            s.short_league_name,
            s.age_group_ID,
            COUNT(s.match_id) AS Match_Count
		FROM
			mv_summary_staging s
		WHERE
			s.season_year = @vSeasonYear
		GROUP BY s.season_year , region , s.age_group , s.umpire_type , s.short_league_name , s.age_group_ID) 
		AS outer1) 
    AS outer2 ON ua.umpire_type_name = outer2.umpire_type
	AND ua.age_group = outer2.age_group
    LEFT JOIN
    (
		SELECT
			region_name,
			age_group,
			short_league_name,
			total_match_count,
			CASE
				WHEN short_league_name = 'BFL' THEN total_match_count
				ELSE 0
			END AS match_count_bfl,
			CASE
				WHEN short_league_name = 'GFL' THEN total_match_count
				ELSE 0
			END AS match_count_gfl,
			CASE
				WHEN short_league_name = 'GDFL' THEN total_match_count
				ELSE 0
			END AS match_count_gdfl,
			CASE
				WHEN short_league_name = 'GJFL' THEN total_match_count
				ELSE 0
			END AS match_count_gjfl,
			CASE
				WHEN short_league_name = 'CDFNL' THEN total_match_count
				ELSE 0
			END AS match_count_cdfnl
			FROM (
				SELECT
				mvd.region_name,
				mvd.age_group,
				mvd.short_league_name,
				COUNT(DISTINCT mvd.match_played_id) AS total_match_count
				FROM mv_denormalised mvd
				GROUP BY region_name, age_group, short_league_name
				ORDER BY region_name, age_group, short_league_name
			) AS total_matches_sub
            
    ) AS outer3
    ON outer2.age_group = outer3.age_group
    AND outer2.short_league_name = outer3.short_league_name
GROUP BY season_year , outer2.region , ua.umpire_type_id , ua.age_group_id, ua.display_order
ORDER BY season_year , outer2.region , ua.umpire_type_id , ua.age_group_id, ua.display_order;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_05'), 1, ROW_COUNT());

ALTER TABLE mv_report_05 ENABLE KEYS;

ALTER TABLE mv_umpire_list DISABLE KEYS;

INSERT INTO mv_umpire_list (season_year, umpire_type_name, age_group, umpire_name) 
SELECT DISTINCT 
season_year, umpire_type.umpire_type_name,  
age_group.age_group,  
CONCAT(umpire.last_name, ', ', umpire.first_name) AS umpire_name 
FROM umpire 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN umpire_name_type_match ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
INNER JOIN match_played ON match_played.ID = umpire_name_type_match.match_id 
INNER JOIN round ON round.ID = match_played.round_id 
INNER JOIN league ON league.ID = round.league_id 
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id 
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
INNER JOIN season ON season.id = round.season_id 
WHERE season.id = pSeasonID;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_umpire_list'), 1, ROW_COUNT());
ALTER TABLE mv_umpire_list ENABLE KEYS;

ALTER TABLE mv_report_06_staging DISABLE KEYS;

INSERT INTO mv_report_06_staging (season_year, region, umpire_type_name, age_group, first_umpire, second_umpire, match_ID)  
SELECT  
season.season_year, region.region_name, umpire_type1.umpire_type_name,  
age_group.age_group,  
CONCAT(umpire1.last_name, ', ', umpire1.first_name) AS first_umpire,  
CONCAT(umpire2.last_name, ', ', umpire2.first_name) AS second_umpire, 
match_played1.ID 
FROM umpire AS umpire1 
INNER JOIN umpire_name_type AS umpire_name_type1 ON umpire1.ID = umpire_name_type1.umpire_id 
INNER JOIN umpire_type AS umpire_type1 ON umpire_type1.ID = umpire_name_type1.umpire_type_id 
INNER JOIN umpire_name_type_match AS umpire_name_type_match1 ON umpire_name_type1.ID = umpire_name_type_match1.umpire_name_type_id 
INNER JOIN match_played AS match_played1 ON match_played1.ID = umpire_name_type_match1.match_id 
INNER JOIN match_played AS match_played2 ON match_played1.ID = match_played2.ID 
INNER JOIN umpire_name_type_match AS umpire_name_type_match2 ON umpire_name_type_match2.match_id = match_played2.ID 
INNER JOIN umpire_name_type AS umpire_name_type2 ON umpire_name_type_match2.umpire_name_type_id = umpire_name_type2.ID 
INNER JOIN umpire AS umpire2 ON umpire_name_type2.umpire_id = umpire2.ID 
INNER JOIN umpire_type AS umpire_type2 ON umpire_type2.ID = umpire_name_type2.umpire_type_id 
INNER JOIN round ON round.ID = match_played1.round_id AND round.ID = match_played2.round_id 
INNER JOIN league ON league.ID = round.league_id 
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id 
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
INNER JOIN season ON season.id = round.season_id 
INNER JOIN region ON league.region_id = region.id 
WHERE umpire1.first_name <> umpire2.first_name 
AND umpire1.last_name <> umpire2.last_name 
AND umpire_type1.ID = umpire_name_type2.umpire_type_id 
AND season.id = pSeasonID;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_06_staging'), 1, ROW_COUNT());

ALTER TABLE mv_report_06_staging ENABLE KEYS;

ALTER TABLE mv_report_06 DISABLE KEYS;

INSERT INTO mv_report_06 (season_year, region, umpire_type_name, age_group, first_umpire, second_umpire, match_count)  
SELECT u1.season_year, s.region, u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name, COUNT(s.match_id) 
FROM mv_umpire_list u1 
INNER JOIN mv_umpire_list u2 ON u1.umpire_type_name = u2.umpire_type_name AND u1.age_group = u2.age_group AND u1.season_year = u2.season_year
LEFT OUTER JOIN mv_report_06_staging s ON u1.umpire_name = s.first_umpire AND u2.umpire_name = s.second_umpire AND u1.season_year = s.season_year AND u1.age_group = s.age_group
WHERE u1.season_year = @vSeasonYear
AND u2.season_year = @vSeasonYear
AND s.season_year = @vSeasonYear
GROUP BY u1.season_year, s.region, u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name  
ORDER BY u1.season_year, s.region, u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_06'), 1, ROW_COUNT());

ALTER TABLE mv_report_06 ENABLE KEYS;


ALTER TABLE mv_report_07 DISABLE KEYS;


INSERT INTO mv_report_07 (season_year, umpire_type, age_group, region, short_league_name, display_order,
`GFL|2 Umpires`, `GFL|3 Umpires`, 
`BFL|2 Umpires`, `BFL|3 Umpires`, 
`GDFL|2 Umpires`, `GDFL|3 Umpires`, 
`GJFL|2 Umpires`, `GJFL|3 Umpires`, 
`CDFNL|2 Umpires`, `CDFNL|3 Umpires`)
SELECT   
d1.season_year, 
'Field' as umpire_type,
d1.age_group,  
d1.region_name,
d1.short_league_name,
d1.display_order,
(CASE WHEN short_league_name = 'GFL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GFL|2 Umpires',
(CASE WHEN short_league_name = 'GFL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GFL|3 Umpires',
(CASE WHEN short_league_name = 'BFL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'BFL|2 Umpires',
(CASE WHEN short_league_name = 'BFL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'BFL|3 Umpires',
(CASE WHEN short_league_name = 'GDFL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GDFL|2 Umpires',
(CASE WHEN short_league_name = 'GDFL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GDFL|3 Umpires',
(CASE WHEN short_league_name = 'GJFL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GJFL|2 Umpires',
(CASE WHEN short_league_name = 'GJFL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GJFL|3 Umpires',
(CASE WHEN short_league_name = 'CDFNL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'CDFNL|2 Umpires',
(CASE WHEN short_league_name = 'CDFNL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'CDFNL|3 Umpires'
FROM mv_denormalised d1
INNER JOIN ( 
	SELECT  
	d2.match_played_ID,  
	COUNT(DISTINCT d2.umpire_ID) AS umpire_count  
	FROM mv_denormalised d2
	WHERE d2.umpire_type_name = 'Field'
	GROUP BY d2.season_year, d2.match_played_ID, d2.umpire_type_name, d2.age_group, d2.short_league_name
	HAVING COUNT(DISTINCT d2.umpire_ID) IN (2, 3)
) AS sub ON d1.match_played_ID = sub.match_played_ID  
WHERE d1.umpire_type_name = 'Field'
GROUP BY d1.season_year, d1.age_group_ID , d1.age_group, d1.short_league_name, sub.umpire_count;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'mv_report_07'), 1, ROW_COUNT());        

ALTER TABLE mv_report_07 ENABLE KEYS;

/*
Insert New Competitions
These will be displayed to the user when a file is imported. The leagues need to be assigned manually.
*/

INSERT INTO competition_lookup(competition_name, season_id, league_id)
SELECT DISTINCT competition_name, pSeasonID, NULL
FROM match_import
WHERE competition_name NOT IN (
	SELECT competition_name
    FROM bbrumm_umpire_data.competition_lookup
);


INSERT INTO test_insert(logtime, test_value) VALUES (NOW(), "End ETL");

END$$
DELIMITER ;
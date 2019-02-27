<?php
/*
* @property Object db
*/
class Etl_query_builder extends CI_Model
{

    private $season;

    function __construct() {
        parent::__construct();
        $this->load->model('Season');
    }

    public function setSeason($pSeason) {
        $this->season = $pSeason;
    }

    public function getLatestSeasonQuery() {
        return "SELECT MAX(season_year) AS season_year
FROM season
WHERE id = ". $this->season->getSeasonID() . ";";
    }

    public function getDeleteUmpireNameTypeMatchQuery() {
        return "DELETE umpire_name_type_match FROM umpire_name_type_match
INNER JOIN match_played ON umpire_name_type_match.match_ID = match_played.ID
INNER JOIN round ON match_played.round_id = round.ID 
WHERE round.season_id = ". $this->season->getSeasonID() .";";
    }

    
    public function getDeleteMatchPlayedQuery() {
        return "DELETE match_played FROM match_played
        INNER JOIN round ON match_played.round_id = round.ID 
WHERE round.season_id = ". $this->season->getSeasonID() .";";
    }

    public function getDeleteRoundQuery() {
        return "DELETE round FROM round 
WHERE round.season_id = ". $this->season->getSeasonID() .";";
    }

    public function getInsertRoundQuery() {
        return "INSERT INTO round ( round_number, round_date, season_id, league_id )
SELECT DISTINCT match_import.round, STR_TO_DATE(match_import.date, '%d/%m/%Y'), season.ID AS season_id, league.ID AS league_id
FROM match_import 
INNER JOIN season ON match_import.season = season.season_year
INNER JOIN competition_lookup ON (season.ID = competition_lookup.season_id) AND (match_import.competition_name = competition_lookup.competition_name)
INNER JOIN league ON league.ID = competition_lookup.league_id
ORDER BY match_import.Round, match_import.Date;";
    }

    public function getInsertUmpireQuery() {
        return "INSERT INTO umpire (first_name, last_name) 
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
AND u.last_name = sub.last_name);";
    }

    public function getInsertUmpireNameTypeQuery() {
        return "INSERT INTO umpire_name_type (umpire_id, umpire_type_id) 
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
        INNER JOIN umpire_type ON com2.umpire_type = umpire_type.umpire_type_name
        WHERE (umpire.ID, umpire_type.ID) NOT IN (
			SELECT umpire_id, umpire_type_id
            FROM umpire_name_type
        );";
    }

    public function getInsertMatchStagingQuery() {
        return "INSERT INTO match_staging 
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
        INNER JOIN division ON division.ID = age_group_division.division_id;";
    }

    public function getInsertUmpireNameTypeMatchQuery() {
        return "INSERT INTO umpire_name_type_match ( umpire_name_type_id, match_id ) 
SELECT umpire_name_type_id, match_id 
FROM (
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Field' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field2_first) AND (umpire.last_name = match_staging.appointments_field2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Field' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field3_first) AND (umpire.last_name = match_staging.appointments_field3_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Field' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary1_first) AND (umpire.last_name = match_staging.appointments_boundary1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Boundary' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary2_first) AND (umpire.last_name = match_staging.appointments_boundary2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Boundary' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary3_first) AND (umpire.last_name = match_staging.appointments_boundary3_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Boundary' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary4_first) AND (umpire.last_name = match_staging.appointments_boundary4_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Boundary' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary5_first) AND (umpire.last_name = match_staging.appointments_boundary5_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Boundary' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary6_first) AND (umpire.last_name = match_staging.appointments_boundary6_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Boundary' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal1_first) AND (umpire.last_name = match_staging.appointments_goal1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Goal' 
AND round.season_id = ". $this->season->getSeasonID() ."
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal2_first) AND (umpire.last_name = match_staging.appointments_goal2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
INNER JOIN round ON match_played.round_id = round.ID 
WHERE umpire_type.umpire_type_name = 'Goal' 
AND round.season_id = ". $this->season->getSeasonID() ."
) AS ump;";
    }

}

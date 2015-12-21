SELECT clng(match_import.ID) AS match_import_id, 
match_import.Season AS match_import_season, 
match_import.Round AS match_import_round, 
cdate(match_import.Date) AS match_import_date, 
match_import.[Competition Name] AS match_import_compname, 
match_import.Ground AS match_import_ground, 
cdate(match_import.Time) AS match_import_time, 
match_import.[Home Team] AS match_import_hometeam, 
match_import.[Away Team] AS match_import_awayteam, 
cstr(LEFT(match_import.[Field Umpire 1],InStr(match_import.[Field Umpire 1]," ")-1)) AS match_import_field1_first, 
cstr(RIGHT(match_import.[Field Umpire 1],Len(match_import.[Field Umpire 1])-InStr(match_import.[Field Umpire 1]," "))) AS match_import_field1_last, 
cstr(LEFT(match_import.[Field Umpire 2],InStr(match_import.[Field Umpire 2]," ")-1)) AS match_import_field2_first, 
cstr(RIGHT(match_import.[Field Umpire 2],Len(match_import.[Field Umpire 2])-InStr(match_import.[Field Umpire 2]," "))) AS match_import_field2_last, 
cstr(LEFT(match_import.[Field Umpire 3],InStr(match_import.[Field Umpire 3]," ")-1)) AS match_import_field3_first, 
cstr(RIGHT(match_import.[Field Umpire 3],Len(match_import.[Field Umpire 3])-InStr(match_import.[Field Umpire 3]," "))) AS match_import_field3_last, 
cstr(LEFT(match_import.[Boundary Umpire 1],InStr(match_import.[Boundary Umpire 1]," ")-1)) AS match_import_boundary1_first, 
cstr(RIGHT(match_import.[Boundary Umpire 1],Len(match_import.[Boundary Umpire 1])-InStr(match_import.[Boundary Umpire 1]," "))) AS match_import_boundary1_last, 
cstr(LEFT(match_import.[Boundary Umpire 2],InStr(match_import.[Boundary Umpire 2]," ")-1)) AS match_import_boundary2_first, 
cstr(RIGHT(match_import.[Boundary Umpire 2],Len(match_import.[Boundary Umpire 2])-InStr(match_import.[Boundary Umpire 2]," "))) AS match_import_boundary2_last, 
cstr(LEFT(match_import.[Boundary Umpire 3],InStr(match_import.[Boundary Umpire 3]," ")-1)) AS match_import_boundary3_first, 
cstr(RIGHT(match_import.[Boundary Umpire 3],Len(match_import.[Boundary Umpire 3])-InStr(match_import.[Boundary Umpire 3]," "))) AS match_import_boundary3_last, 
cstr(LEFT(match_import.[Boundary Umpire 4],InStr(match_import.[Boundary Umpire 4]," ")-1)) AS match_import_boundary4_first, 
cstr(RIGHT(match_import.[Boundary Umpire 4],Len(match_import.[Boundary Umpire 4])-InStr(match_import.[Boundary Umpire 4]," "))) AS match_import_boundary4_last, 
cstr(LEFT(match_import.[Goal Umpire 1],InStr(match_import.[Goal Umpire 1]," ")-1)) AS match_import_goal1_first, 
cstr(RIGHT(match_import.[Goal Umpire 1],Len(match_import.[Goal Umpire 1])-InStr(match_import.[Goal Umpire 1]," "))) AS match_import_goal1_last, 
cstr(LEFT(match_import.[Goal Umpire 2],InStr(match_import.[Goal Umpire 2]," ")-1)) AS match_import_goal2_first, 
cstr(RIGHT(match_import.[Goal Umpire 2],Len(match_import.[Goal Umpire 2])-InStr(match_import.[Goal Umpire 2]," "))) AS match_import_goal2_last, 
clng(season.ID) AS season_id, 
clng(round.ID) AS round_ID, 
round.round_date AS round_date, 
clng(round.league_id) AS round_leagueid, 
league.league_name AS league_leaguename, 
league.sponsored_league_name AS league_sponsored_league_name, 
clng(age_group_division.age_group_id) AS agd_agegroupid, 
age_group.age_group AS ag_agegroup, 
clng(age_group_division.division_id) AS agd_divisionid, 
division.division_name AS division_divisionname, 
clng(ground.id) AS ground_id, 
ground.main_name AS ground_mainname, 
clng(team.ID) AS home_team_id, 
clng(team_1.ID) AS away_team_id 
INTO match_staging
FROM 
(division 
	INNER JOIN (
		age_group INNER JOIN (
			age_group_division INNER JOIN league ON age_group_division.ID = league.age_group_division_id) 
		ON age_group.ID = age_group_division.age_group_id) 
	ON division.ID = age_group_division.division_id) 
INNER JOIN (
	(
		(
			(
				(
					(
						match_import INNER JOIN ROUND ON (cdate(match_import.Date) = round.round_date) AND (match_import.Round = round.round_number))
					INNER JOIN competition_lookup ON match_import.[Competition Name] = competition_lookup.competition_name)
				INNER JOIN season ON (match_import.Season = season.season_year) AND (season.ID = competition_lookup.season_id) AND ( season.ID = round.season_id))
			INNER JOIN ground ON match_import.Ground = ground.alternative_name)
		INNER JOIN team ON match_import.[Home Team] = team.team_name) 
	INNER JOIN team AS team_1 ON match_import.[Away Team] = team_1.team_name)
ON (league.ID = competition_lookup.league_id) AND (league.ID = round.league_id)
ORDER BY match_import.Round, match_import.Date, match_import.[Competition Name];



/* ********************* */
SELECT match_import.ID AS match_import_id, 
match_import.Season AS match_import_season, 
match_import.Round AS match_import_round, 
match_import.Date AS match_import_date, 
match_import.[Competition Name] AS match_import_compname, 
match_import.Ground AS match_import_ground, 
match_import.Time AS match_import_time, 
match_import.home_team AS match_import_hometeam, 
match_import.away_team AS match_import_awayteam, 
LEFT(match_import.[Field Umpire 1],InStr(match_import.[Field Umpire 1]," ")-1) AS match_import_field1_first, 
RIGHT(match_import.[Field Umpire 1],Len(match_import.[Field Umpire 1])-InStr(match_import.[Field Umpire 1]," ")) AS match_import_field1_last, 
LEFT(match_import.[Field Umpire 2],InStr(match_import.[Field Umpire 2]," ")-1) AS match_import_field2_first, 
RIGHT(match_import.[Field Umpire 2],Len(match_import.[Field Umpire 2])-InStr(match_import.[Field Umpire 2]," ")) AS match_import_field2_last, 
LEFT(match_import.[Field Umpire 3],InStr(match_import.[Field Umpire 3]," ")-1) AS match_import_field3_first, 
RIGHT(match_import.[Field Umpire 3],Len(match_import.[Field Umpire 3])-InStr(match_import.[Field Umpire 3]," ")) AS match_import_field3_last, 
LEFT(match_import.[Boundary Umpire 1],InStr(match_import.[Boundary Umpire 1]," ")-1) AS match_import_boundary1_first, 
RIGHT(match_import.[Boundary Umpire 1],Len(match_import.[Boundary Umpire 1])-InStr(match_import.[Boundary Umpire 1]," ")) AS match_import_boundary1_last, 
LEFT(match_import.[Boundary Umpire 2],InStr(match_import.[Boundary Umpire 2]," ")-1) AS match_import_boundary2_first, 
RIGHT(match_import.[Boundary Umpire 2],Len(match_import.[Boundary Umpire 2])-InStr(match_import.[Boundary Umpire 2]," ")) AS match_import_boundary2_last, 
LEFT(match_import.[Boundary Umpire 3],InStr(match_import.[Boundary Umpire 3]," ")-1) AS match_import_boundary3_first, 
RIGHT(match_import.[Boundary Umpire 3],Len(match_import.[Boundary Umpire 3])-InStr(match_import.[Boundary Umpire 3]," ")) AS match_import_boundary3_last, 
LEFT(match_import.[Boundary Umpire 4],InStr(match_import.[Boundary Umpire 4]," ")-1) AS match_import_boundary4_first, 
RIGHT(match_import.[Boundary Umpire 4],Len(match_import.[Boundary Umpire 4])-InStr(match_import.[Boundary Umpire 4]," ")) AS match_import_boundary4_last, 
LEFT(match_import.[Goal Umpire 1],InStr(match_import.[Goal Umpire 1]," ")-1) AS match_import_goal1_first, 
RIGHT(match_import.[Goal Umpire 1],Len(match_import.[Goal Umpire 1])-InStr(match_import.[Goal Umpire 1]," ")) AS match_import_goal1_last, 
LEFT(match_import.[Goal Umpire 2],InStr(match_import.[Goal Umpire 2]," ")-1) AS match_import_goal2_first, 
RIGHT(match_import.[Goal Umpire 2],Len(match_import.[Goal Umpire 2])-InStr(match_import.[Goal Umpire 2]," ")) AS match_import_goal2_last, 
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
INNER JOIN round ON (match_import.date = round.round_date) AND (match_import.round = round.round_number)
INNER JOIN competition_lookup ON match_import.competition_name = competition_lookup.competition_name
INNER JOIN season ON (match_import.season = season.season_year) AND (season.ID = competition_lookup.season_id) AND (season.ID = round.season_id)
INNER JOIN ground ON match_import.Ground = ground.alternative_name
INNER JOIN team ON match_import.home_team = team.team_name
INNER JOIN team AS team_1 ON match_import.away_team = team_1.team_name
INNER JOIN league ON (league.ID = competition_lookup.league_id) AND (league.ID = round.league_id)
INNER JOIN age_group_division ON league.age_group_division_id = age_group_division.ID
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id


/*  ******************** */


SELECT match_import.ID AS match_import_id, 
match_import.Season AS match_import_season, 
match_import.Round AS match_import_round, 
match_import.date AS match_import_date, 
match_import.competition_name AS match_import_compname, 
match_import.Ground AS match_import_ground, 
match_import.time AS match_import_time, 
match_import.home_team AS match_import_hometeam, 
match_import.away_team AS match_import_awayteam, 
LEFT(match_import.field_umpire_1,InStr(match_import.field_umpire_1," ")-1) AS match_import_field1_first, 
RIGHT(match_import.field_umpire_1,LENGTH(match_import.field_umpire_1)-InStr(match_import.field_umpire_1," ")) AS match_import_field1_last, 
LEFT(match_import.field_umpire_2,InStr(match_import.field_umpire_2," ")-1) AS match_import_field2_first, 
RIGHT(match_import.field_umpire_2,LENGTH(match_import.field_umpire_2)-InStr(match_import.field_umpire_2," ")) AS match_import_field2_last, 
LEFT(match_import.field_umpire_3,InStr(match_import.field_umpire_3," ")-1) AS match_import_field3_first, 
RIGHT(match_import.field_umpire_3,LENGTH(match_import.field_umpire_3)-InStr(match_import.field_umpire_3," ")) AS match_import_field3_last, 
LEFT(match_import.boundary_umpire_1,InStr(match_import.boundary_umpire_1," ")-1) AS match_import_boundary1_first, 
RIGHT(match_import.boundary_umpire_1,LENGTH(match_import.boundary_umpire_1)-InStr(match_import.boundary_umpire_1," ")) AS match_import_boundary1_last, 
LEFT(match_import.boundary_umpire_2,InStr(match_import.boundary_umpire_2," ")-1) AS match_import_boundary2_first, 
RIGHT(match_import.boundary_umpire_2,LENGTH(match_import.boundary_umpire_2)-InStr(match_import.boundary_umpire_2," ")) AS match_import_boundary2_last, 
LEFT(match_import.boundary_umpire_3,InStr(match_import.boundary_umpire_3," ")-1) AS match_import_boundary3_first, 
RIGHT(match_import.boundary_umpire_3,LENGTH(match_import.boundary_umpire_3)-InStr(match_import.boundary_umpire_3," ")) AS match_import_boundary3_last, 
LEFT(match_import.boundary_umpire_4,InStr(match_import.boundary_umpire_4," ")-1) AS match_import_boundary4_first, 
RIGHT(match_import.boundary_umpire_4,LENGTH(match_import.boundary_umpire_4)-InStr(match_import.boundary_umpire_4," ")) AS match_import_boundary4_last, 
LEFT(match_import.goal_umpire_1,InStr(match_import.goal_umpire_1," ")-1) AS match_import_goal1_first, 
RIGHT(match_import.goal_umpire_1,LENGTH(match_import.goal_umpire_1)-InStr(match_import.goal_umpire_1," ")) AS match_import_goal1_last, 
LEFT(match_import.goal_umpire_2,InStr(match_import.goal_umpire_2," ")-1) AS match_import_goal2_first, 
RIGHT(match_import.goal_umpire_2,LENGTH(match_import.goal_umpire_2)-InStr(match_import.goal_umpire_2," ")) AS match_import_goal2_last, 
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
INNER JOIN round ON (match_import.date = round.round_date) AND (match_import.round = round.round_number)
INNER JOIN competition_lookup ON match_import.competition_name = competition_lookup.competition_name
INNER JOIN season ON (match_import.season = season.season_year) AND (season.ID = competition_lookup.season_id) AND (season.ID = round.season_id)
INNER JOIN ground ON match_import.Ground = ground.alternative_name
INNER JOIN team ON match_import.home_team = team.team_name
INNER JOIN team AS team_1 ON match_import.away_team = team_1.team_name
INNER JOIN league ON (league.ID = competition_lookup.league_id) AND (league.ID = round.league_id)
INNER JOIN age_group_division ON league.age_group_division_id = age_group_division.ID
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
INNER JOIN division ON division.ID = age_group_division.division_id
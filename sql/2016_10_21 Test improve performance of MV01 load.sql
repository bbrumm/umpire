/*CREATE TABLE bbrumm_umpire_data.staging_matches_home  AS 
SELECT DISTINCT season_year, umpire.first_name, umpire.last_name, match_played.ID, team.team_name, club.club_name, league.short_league_name, age_group.age_group, umpire_type.umpire_type_name 
        FROM match_played 
        INNER JOIN round ON round.ID = match_played.round_id 
        INNER JOIN league ON league.ID = round.league_id 
        INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id 
        INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
        INNER JOIN team ON team.ID = match_played.home_team_id 
        INNER JOIN club ON club.ID = team.club_id 
        INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id 
        INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
        INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
        INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id 
        INNER JOIN season ON season.id = round.season_id 
        WHERE season.season_year = '2016';
        
        CREATE TABLE bbrumm_umpire_data.staging_matches_away AS 
        SELECT DISTINCT season_year, umpire.first_name, umpire.last_name, match_played.ID, team.team_name, club.club_name, league.short_league_name, age_group.age_group, umpire_type.umpire_type_name 
        FROM match_played 
        INNER JOIN round ON round.ID = match_played.round_id 
        INNER JOIN league ON league.ID = round.league_id 
        INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id 
        INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
        INNER JOIN team ON team.ID = match_played.away_team_id 
        INNER JOIN club ON club.ID = team.club_id 
        INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id 
        INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
        INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
        INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id 
        INNER JOIN season ON season.id = round.season_id 
        WHERE season.season_year = '2016';
        */

CREATE TABLE bbrumm_umpire_data.staging_matches_homeaway AS         
SELECT DISTINCT season_year, umpire.first_name, umpire.last_name, match_played.ID, team.team_name, club.club_name, league.short_league_name, age_group.age_group, umpire_type.umpire_type_name 
        FROM match_played 
        INNER JOIN round ON round.ID = match_played.round_id 
        INNER JOIN league ON league.ID = round.league_id 
        INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id 
        INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
        INNER JOIN team ON team.ID = match_played.home_team_id 
        INNER JOIN club ON club.ID = team.club_id 
        INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id 
        INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
        INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
        INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id 
        INNER JOIN season ON season.id = round.season_id 
        WHERE season.season_year = '2016'
        UNION ALL
        SELECT DISTINCT season_year, umpire.first_name, umpire.last_name, match_played.ID, team.team_name, club.club_name, league.short_league_name, age_group.age_group, umpire_type.umpire_type_name 
        FROM match_played 
        INNER JOIN round ON round.ID = match_played.round_id 
        INNER JOIN league ON league.ID = round.league_id 
        INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id 
        INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
        INNER JOIN team ON team.ID = match_played.away_team_id 
        INNER JOIN club ON club.ID = team.club_id 
        INNER JOIN umpire_name_type_match ON match_played.ID = umpire_name_type_match.match_id 
        INNER JOIN umpire_name_type ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id 
        INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
        INNER JOIN umpire ON umpire.ID = umpire_name_type.umpire_id 
        INNER JOIN season ON season.id = round.season_id 
        WHERE season.season_year = '2016';        
        
SELECT * FROM staging_matches_home
UNION ALL
SELECT * FROM staging_matches_away;

SELECT * FROM staging_matches_homeaway;

/*
season_year, first_name, last_name, club_name, short_league_name, age_group, umpire_type_name
*/
CREATE INDEX idx_smha_firstname ON staging_matches_homeaway(season_year);

CREATE INDEX idx_smha_cn ON staging_matches_homeaway(club_name);
CREATE INDEX idx_smha_sln ON staging_matches_homeaway(short_league_name);
        
        
CREATE INDEX idx_smha_group ON staging_matches_homeaway(season_year, first_name, last_name, club_name, short_league_name, age_group, umpire_type_name);
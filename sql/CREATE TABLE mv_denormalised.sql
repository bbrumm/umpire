DROP TABLE mv_denormalised;

CREATE TABLE mv_denormalised (
season_year INT(4),
umpire_id INT(11),
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
);

INSERT INTO processed_table (TABLE_NAME) VALUES ('mv_denormalised');
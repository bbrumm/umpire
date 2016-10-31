/*
Create ETL procedure
Create mv_denormalised table
*/

insert into processed_table(table_name) value ('ground');

alter table match_played drop foreign key fk_match_ground;

alter table ground modify column id INT(11) auto_increment;

alter table match_played add constraint fk_match_ground foreign key (ground_id) references ground(id);

CREATE INDEX idx_mtstaging_1 ON match_staging(appointments_time, away_team_id, home_team_id, ground_id, round_id);
CREATE INDEX idx_mtstaging_2 ON match_staging(appointments_time);
CREATE INDEX idx_mtstaging_3 ON match_staging(away_team_id);
CREATE INDEX idx_mtstaging_4 ON match_staging(home_team_id);
CREATE INDEX idx_mtstaging_5 ON match_staging(ground_id);
CREATE INDEX idx_mtstaging_6 ON match_staging(round_id);
CREATE INDEX idx_umpire_1 ON umpire(first_name, last_name);
CREATE INDEX idx_mtstaging_7 ON match_staging(away_team_id, home_team_id, ground_id, round_id);
CREATE INDEX idx_mp_1 ON match_played(match_staging_id);
/*CREATE INDEX `fk_match_round_idx` ON `umpire`.`match_played` (`round_id` ASC);*/

CREATE INDEX idx_matchimport_date ON umpire.match_import(date);
CREATE INDEX idx_matchimport_round ON umpire.match_import(round);
CREATE INDEX idx_matchimport_competition_name ON umpire.match_import(competition_name);
CREATE INDEX idx_matchimport_season ON umpire.match_import(season);
CREATE INDEX idx_matchimport_ground ON umpire.match_import(ground);
CREATE INDEX idx_matchimport_home_team ON umpire.match_import(home_team);
CREATE INDEX idx_matchimport_away_team ON umpire.match_import(away_team);

CREATE INDEX idx_team_team_name ON umpire.team(team_name);
CREATE INDEX idx_ground_alternative_name ON umpire.ground(alternative_name);


CREATE INDEX idx_mv01_short_league_name ON umpire.mv_report_01(short_league_name);
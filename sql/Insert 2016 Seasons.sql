INSERT INTO league (id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id)
VALUES (30, 'CDFNL Seniors', 'CDFNL Seniors', 'CDFNL', 1, 2);

INSERT INTO league (id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id)
VALUES (31, 'CDFNL Reserves', 'CDFNL Reserves', 'CDFNL', 2, 2);

INSERT INTO league (id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id)
VALUES (32, 'CDFNL Under 17.5', 'CDFNL Under 17.5', 'CDFNL', 24, 2);

INSERT INTO league (id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id)
VALUES (33, 'CDFNL Under 14.5', 'CDFNL Under 14.5', 'CDFNL', 25, 2);

INSERT INTO league (id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id)
VALUES (34, 'AFL Barwon - Buckley''s Bellarine FNL Reserves', 'AFL Barwon - Buckley''s Bellarine FNL Reserves', 'GFL', 2, 2);

INSERT INTO league (id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id)
VALUES (35, 'AFL Barwon - Buckley''s Bellarine FNL Reserves', 'AFL Barwon - Buckley''s Bellarine FNL Reserves', 'BFL', 2, 2);

/*
Season
*/
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (55, 'AFL Barwon - 2016 Dow Bellarine FNL Seniors', 2, 5);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (56, '2016 CDFNL Seniors', 2, 30);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (57, 'AFL Barwon - 2016 Buckley''s Bellarine FNL Reserves', 2, 4);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (58, 'AFL Barwon - 2016 Buckley''s Bellarine FNL Reserves', 2, 6);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (59, '2016 CDFNL Reserves', 2, 31);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (60, '2016 CDFNL Under 17.5', 2, 32);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (61, '2016 CDFNL Under 14.5', 2, 33);


/*
Ground
*/

INSERT INTO ground (id, main_name, alternative_name) VALUES (50, 'Shell Road Reserve', 'Shell Road Reserve');
INSERT INTO ground (id, main_name, alternative_name) VALUES (51, 'Stribling Reserve', 'Stribling Reserve');

/*
Club
*/
INSERT INTO club (id, club_name) VALUES (124, 'Birregurra');
INSERT INTO club (id, club_name) VALUES (125, 'Lorne');

/*
Team
*/

INSERT INTO team (id, team_name, club_id) VALUES (94, 'Birregurra', 124);
INSERT INTO team (id, team_name, club_id) VALUES (95, 'Lorne', 125);




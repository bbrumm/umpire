ALTER TABLE match_import ADD COLUMN boundary_umpire_5 VARCHAR(200);
ALTER TABLE match_import ADD COLUMN boundary_umpire_6 VARCHAR(200);

ALTER TABLE match_staging ADD COLUMN appointments_boundary5_first VARCHAR(200);
ALTER TABLE match_staging ADD COLUMN appointments_boundary5_last VARCHAR(200);
ALTER TABLE match_staging ADD COLUMN appointments_boundary6_first VARCHAR(200);
ALTER TABLE match_staging ADD COLUMN appointments_boundary6_last VARCHAR(200);


INSERT INTO ground (id, main_name, alternative_name) VALUES (74, 'Bakers Oval', 'Bakers Oval');
INSERT INTO ground (id, main_name, alternative_name) VALUES (75, 'Christian College', 'Christian College');
INSERT INTO ground (id, main_name, alternative_name) VALUES (76, 'Elliminyt Recreation Reserve', 'Elliminyt Recreation Reserve');
INSERT INTO ground (id, main_name, alternative_name) VALUES (77, 'Frier Reserve', 'Frier Reserve');
INSERT INTO ground (id, main_name, alternative_name) VALUES (78, 'Inverleigh Reserve Inverleigh', 'Inverleigh Reserve Inverleigh');
INSERT INTO ground (id, main_name, alternative_name) VALUES (79, 'Inverleigh Reserve No 2', 'Inverleigh Reserve No 2');
INSERT INTO ground (id, main_name, alternative_name) VALUES (80, 'Irrewillipe Recreation Reserve', 'Irrewillipe Recreation Reserve');
INSERT INTO ground (id, main_name, alternative_name) VALUES (81, 'Jetts Oval Winter Reserve Belmont', 'Jetts Oval Winter Reserve Belmont');
INSERT INTO ground (id, main_name, alternative_name) VALUES (82, 'Little River Reserve', 'Little River Reserve');
INSERT INTO ground (id, main_name, alternative_name) VALUES (83, 'Mortimer Oval', 'Mortimer Oval');
INSERT INTO ground (id, main_name, alternative_name) VALUES (84, 'Myers Reserve Bell Post Hill', 'Myers Reserve Bell Post Hill');
INSERT INTO ground (id, main_name, alternative_name) VALUES (85, 'Polwarth Oval', 'Polwarth Oval');
INSERT INTO ground (id, main_name, alternative_name) VALUES (86, 'Richmond Cresent East Geelong', 'Richmond Cresent East Geelong');
INSERT INTO ground (id, main_name, alternative_name) VALUES (87, 'Shell Oval Corio', 'Shell Oval Corio');
INSERT INTO ground (id, main_name, alternative_name) VALUES (88, 'Village Park Oval', 'Village Park Oval');
INSERT INTO ground (id, main_name, alternative_name) VALUES (89, 'Zampatti Oval', 'Zampatti Oval');


ALTER TABLE mv_report_01 ADD COLUMN `GFL|Geelong_West_St_Peters` INT(11) DEFAULT NULL;

/*SELECT MAX(report_column_id) FROM report_column;*/
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (258, 'GFL|Geelong_West_St_Peters', 'SUM', 1, 1);


/*SELECT MAX(report_column_lookup_id) FROM report_column_lookup;*/
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (805, 'short_league_name',  'GFL', 1, 258);

/*SELECT MAX(report_column_lookup_display_id) FROM report_column_lookup_display;*/
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (566, 258, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (567, 258, 'club_name', 'Geelong_West_St_Peters');


ALTER TABLE mv_report_01 ADD COLUMN `BFL|Newcomb` INT(11) DEFAULT NULL;
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (259, 'BFL|Newcomb', 'SUM', 1, 1);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (806, 'short_league_name',  'BFL', 1, 259);
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (558, 259, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (559, 259, 'club_name', 'Newcomb');


ALTER TABLE mv_report_01 ADD COLUMN `GJFL|Surf Coast` INT(11) DEFAULT NULL;
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (260, 'GJFL|Surf Coast', 'SUM', 1, 1);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (807, 'short_league_name',  'GJFL', 1, 260);
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (560, 260, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (561, 260, 'club_name', 'Surf Coast');

ALTER TABLE mv_report_01 ADD COLUMN `GJFL|East Newcomb` INT(11) DEFAULT NULL;
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (261, 'GJFL|East Newcomb', 'SUM', 1, 1);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (808, 'short_league_name',  'GJFL', 1, 261);
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (562, 261, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (563, 261, 'club_name', 'East Newcomb');

ALTER TABLE mv_report_01 ADD COLUMN `GJFL|Bell Post Hill` INT(11) DEFAULT NULL;
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (262, 'GJFL|Bell Post Hill', 'SUM', 1, 1);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (809, 'short_league_name',  'GJFL', 1, 262);
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (564, 262, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (565, 262, 'club_name', 'Bell Post Hill');


DELETE FROM umpire_name_type_match
WHERE match_id IN (
SELECT match_ID
FROM match_played WHERE ground_id = 64
);

DELETE FROM match_played WHERE ground_id = 64;
DELETE FROM ground WHERE id = 64;

DELETE FROM umpire_name_type_match
WHERE match_id IN (
SELECT match_ID
FROM match_played WHERE ground_id = 60
);

DELETE FROM match_played WHERE ground_id = 60;

DELETE FROM ground WHERE id = 60;


INSERT INTO team(id, team_name, club_id) VALUES (169, 'Lara 3', 88);

UPDATE competition_lookup SET league_id = 12 WHERE id = 69;
UPDATE competition_lookup SET league_id = 11 WHERE id = 70;
UPDATE competition_lookup SET league_id = 25 WHERE id = 65;
UPDATE competition_lookup SET league_id = 24 WHERE id = 66;


INSERT INTO division (id, division_name) VALUES (13, 'Div 7');
INSERT INTO age_group_division (id, age_group_id, division_id) VALUES (27, 5, 13);

INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (38, 'AFL Barwon', 'AFL Barwon', 'GJFL', 12, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (39, 'AFL Barwon', 'AFL Barwon', 'GJFL', 13, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (40, 'AFL Barwon', 'AFL Barwon', 'GJFL', 14, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (41, 'AFL Barwon', 'AFL Barwon', 'GJFL', 15, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (42, 'AFL Barwon', 'AFL Barwon', 'GJFL', 16, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (43, 'AFL Barwon', 'AFL Barwon', 'GJFL', 17, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (44, 'AFL Barwon', 'AFL Barwon', 'GJFL', 27, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (45, 'AFL Barwon', 'AFL Barwon', 'GJFL', 7, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (46, 'AFL Barwon', 'AFL Barwon', 'GJFL', 8, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (47, 'AFL Barwon', 'AFL Barwon', 'GJFL', 9, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (48, 'AFL Barwon', 'AFL Barwon', 'GJFL', 10, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (49, 'AFL Barwon', 'AFL Barwon', 'GJFL', 11, 1);
INSERT INTO league(id, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES (50, 'AFL Barwon', 'AFL Barwon Corio Bay Health Group', 'GJFL', 18, 1);


INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (92,'AFL Barwon - 2016 Colts Div 1 K Rock Cup', 2, 26);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (74,'AFL Barwon - 2016 Colts Div 2 Bendigo Bank Cup', 2, 27);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (75,'AFL Barwon - 2016 Colts Div 3 Corio Bay Health Group Cup', 2, 28);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (76,'AFL Barwon - 2016 Colts Div 4 Corio Bay Health Group Cup', 2, 29);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (77,'AFL Barwon - 2016 Junior Girls Corio Bay Health Group', 2, 25);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (78,'AFL Barwon - 2016 Under 12''s Geelong Advertiser ', 2, 36);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (79,'AFL Barwon - 2016 Under 14 Div 1', 2, 38);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (80,'AFL Barwon - 2016 Under 14 Div 2', 2, 39);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (81,'AFL Barwon - 2016 Under 14 Div 3', 2, 40);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (82,'AFL Barwon - 2016 Under 14 Div 4', 2, 41);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (83,'AFL Barwon - 2016 Under 14 Div 5', 2, 42);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (84,'AFL Barwon - 2016 Under 14 Div 6', 2, 43);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (85,'AFL Barwon - 2016 Under 14 Div 7', 2, 44);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (86,'AFL Barwon - 2016 Under 16 Div 1', 2, 45);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (87,'AFL Barwon - 2016 Under 16 Div 2', 2, 46);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (88,'AFL Barwon - 2016 Under 16 Div 3', 2, 47);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (89,'AFL Barwon - 2016 Under 16 Div 4', 2, 48);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (90,'AFL Barwon - 2016 Under 16 Div 5', 2, 49);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES (91,'AFL Barwon - 2016 Youth Girls Corio Bay Health Group', 2, 50);



UPDATE competition_lookup SET league_id = 7 WHERE id = 65;
UPDATE competition_lookup SET league_id = 25 WHERE id = 66;
UPDATE competition_lookup SET league_id = 24 WHERE id = 67;

UPDATE competition_lookup SET league_id = 37 WHERE id = 69;
UPDATE competition_lookup SET league_id = 12 WHERE id = 70;
UPDATE competition_lookup SET league_id = 11 WHERE id = 71;
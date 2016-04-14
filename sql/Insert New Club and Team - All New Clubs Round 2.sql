/*
Insert New Club and Team - All New Clubs Round 2
*/

SELECT * FROM club;
SELECT MAX(id) FROM club;

INSERT INTO club (id, club_name) VALUES (126, 'Alvie');
INSERT INTO club (id, club_name) VALUES (127, 'Apollo Bay');
INSERT INTO club (id, club_name) VALUES (128, 'Colac Imperials');
INSERT INTO club (id, club_name) VALUES (129, 'Irrewarra-beeac');
INSERT INTO club (id, club_name) VALUES (130, 'Otway Districts');
INSERT INTO club (id, club_name) VALUES (131, 'Simpson');
INSERT INTO club (id, club_name) VALUES (132, 'South Colac');
INSERT INTO club (id, club_name) VALUES (133, 'Western Eagles');

SELECT MAX(id) FROM team;
INSERT INTO team (id, team_name, club_id) VALUES (96, 'Alvie', 126);
INSERT INTO team (id, team_name, club_id) VALUES (97, 'Apollo Bay', 127);
INSERT INTO team (id, team_name, club_id) VALUES (98, 'Colac Imperials', 128);
INSERT INTO team (id, team_name, club_id) VALUES (99, 'Irrewarra-beeac', 129);
INSERT INTO team (id, team_name, club_id) VALUES (100, 'Otway Districts', 130);
INSERT INTO team (id, team_name, club_id) VALUES (101, 'Simpson', 131);
INSERT INTO team (id, team_name, club_id) VALUES (102, 'South Colac', 132);
INSERT INTO team (id, team_name, club_id) VALUES (103, 'Western Eagles', 133);

/*League|Team*/
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Alvie` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Apollo Bay` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Colac Imperials` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Irrewarra-beeac` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Otway Districts` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Simpson` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|South Colac` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Western Eagles` INT(11) DEFAULT NULL;


SELECT MAX(report_column_id) FROM report_column;
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (179, 'CDFNL|Alvie', 'SUM', 1, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (180, 'CDFNL|Apollo Bay', 'SUM', 1, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (181, 'CDFNL|Colac Imperials', 'SUM', 1, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (182, 'CDFNL|Irrewarra-beeac', 'SUM', 1, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (183, 'CDFNL|Otway Districts', 'SUM', 1, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (184, 'CDFNL|Simpson', 'SUM', 1, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (185, 'CDFNL|South Colac', 'SUM', 1, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (186, 'CDFNL|Western Eagles', 'SUM', 1, 5);

SELECT MAX(report_column_lookup_id) FROM report_column_lookup;
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (698, 'short_league_name',  'CDFNL', 1, 179);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (699, 'short_league_name',  'CDFNL', 1, 180);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (700, 'short_league_name',  'CDFNL', 1, 181);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (701, 'short_league_name',  'CDFNL', 1, 182);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (702, 'short_league_name',  'CDFNL', 1, 183);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (703, 'short_league_name',  'CDFNL', 1, 184);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (704, 'short_league_name',  'CDFNL', 1, 185);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (705, 'short_league_name',  'CDFNL', 1, 186);


SELECT MAX(report_column_lookup_display_id) FROM report_column_lookup_display;
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (396, 186, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (397, 186, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (398, 186, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (399, 186, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (400, 186, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (401, 186, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (402, 186, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (403, 186, 'short_league_name', 'CDFNL');

INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (404, 186, 'club_name', 'Alvie');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (405, 186, 'club_name', 'Apollo Bay');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (406, 186, 'club_name', 'Colac Imperials');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (407, 186, 'club_name', 'Irrewarra-beeac');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (408, 186, 'club_name', 'Otway Districts');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (409, 186, 'club_name', 'Simpson');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (410, 186, 'club_name', 'South Colac');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (411, 186, 'club_name', 'Western Eagles');




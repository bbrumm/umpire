/*
Insert New Club and Team
*/

SELECT * FROM club;
SELECT MAX(id) FROM club;

INSERT INTO club (id, club_name) VALUES (133, 'Western Eagles');

SELECT MAX(id) FROM team;
INSERT INTO team (id, team_name, club_id) VALUES (103, 'Western Eagles', 133);

/*League|Team*/
ALTER TABLE mv_report_01 ADD COLUMN `CDFNL|Western Eagles` INT(11) DEFAULT NULL;

SELECT MAX(report_column_id) FROM report_column;
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (186, 'CDFNL|Western Eagles', 'SUM', 1, 5);

SELECT MAX(report_column_lookup_id) FROM report_column_lookup;
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (705, 'short_league_name',  'CDFNL', 1, 186);

SELECT MAX(report_column_lookup_display_id) FROM report_column_lookup_display;
SELECT * FROM report_column_lookup_display;

INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (410, 186, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (411, 186, 'club_name', 'Western Eagles');




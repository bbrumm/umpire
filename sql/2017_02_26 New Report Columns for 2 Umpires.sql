SELECT * FROM bbrumm_umpire_data.report_column
WHERE column_name LIKE '%2 Umpires%';

SELECT *
FROM report_column_lookup l
WHERE l.report_column_id = 92;

SELECT *
FROM report_column_lookup_display
WHERE report_column_id = 92;

UPDATE report_column
SET column_name = 'Seniors|2 Umpires Geelong'
WHERE column_name = 'Seniors|2 Umpires';




SELECT MAX(report_column_id)
FROM report_column;

SELECT MAX(report_column_lookup_id)
FROM report_column_lookup;

SELECT MAX(report_column_lookup_display_ID)
FROM report_column_lookup_display;


INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (278, 'Seniors|2 Umpires Colac', NULL, 0, 6);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (826, 'short_league_name', 'All', 2, 278);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (827, 'age_group', 'All', 2, 278);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (828, 'age_group', 'Seniors', 2, 278);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (829, 'umpire_type', 'All', 2, 278);

UPDATE report_column_lookup
SET report_column_id = 278
WHERE report_column_lookup_id = 810;

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (598, 278, 'short_league_name', '2 Umpires');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (599, 278, 'age_group', 'Seniors');



/*Update mv_report_02 table*/
ALTER TABLE mv_report_02 CHANGE COLUMN `Seniors|2 Umpires` `Seniors|2 Umpires Geelong` INT(11);

ALTER TABLE mv_report_02 ADD COLUMN `Seniors|2 Umpires Colac` INT(11);

UPDATE report_column
SET column_function = 'SUM'
WHERE report_column_id IN (92, 278);
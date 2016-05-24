SELECT * FROM report_table;

DELETE FROM report_table WHERE report_name = 6;

ALTER TABLE report_table ADD COLUMN report_title VARCHAR(100);

INSERT INTO report_table (report_table_id, report_name, table_name, report_title) VALUES (6, 6, 'mv_report_06', 'Report 6');

UPDATE report_table
SET report_title = CONCAT('Report ', report_name)
WHERE report_title IS NULL;


SELECT * FROM age_group;

ALTER TABLE age_group ADD COLUMN display_order INT(2);

UPDATE age_group SET display_order = 1 WHERE age_group = 'Seniors';
UPDATE age_group SET display_order = 2 WHERE age_group = 'Reserves';
UPDATE age_group SET display_order = 3 WHERE age_group = 'Colts';
UPDATE age_group SET display_order = 4 WHERE age_group = 'Under 17.5';
UPDATE age_group SET display_order = 5 WHERE age_group = 'Under 16';
UPDATE age_group SET display_order = 6 WHERE age_group = 'Under 14.5';
UPDATE age_group SET display_order = 7 WHERE age_group = 'Under 14';
UPDATE age_group SET display_order = 8 WHERE age_group = 'Under 12';
UPDATE age_group SET display_order = 9 WHERE age_group = 'Youth Girls';
UPDATE age_group SET display_order = 10 WHERE age_group = 'Junior Girls';


CREATE TABLE short_league_name (
id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
short_league_name VARCHAR(100),
display_order INT(2)
);

INSERT INTO short_league_name (short_league_name, display_order) VALUES ('BFL', 1);
INSERT INTO short_league_name (short_league_name, display_order) VALUES ('GFL', 2);
INSERT INTO short_league_name (short_league_name, display_order) VALUES ('GDFL', 3);
INSERT INTO short_league_name (short_league_name, display_order) VALUES ('GJFL', 4);
INSERT INTO short_league_name (short_league_name, display_order) VALUES ('CDFNL', 5);


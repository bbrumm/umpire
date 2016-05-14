DROP TABLE processed_table;
DROP TABLE operation_ref;
DROP TABLE table_operations;

CREATE TABLE processed_table (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
table_name VARCHAR(50) NOT NULL);

CREATE TABLE operation_ref (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
operation_name VARCHAR(50) NOT NULL);

ALTER TABLE imported_files ENGINE = InnoDB;

CREATE TABLE table_operations (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
imported_file_id INT(11) NOT NULL,
processed_table_id INT(11) NOT NULL,
operation_id INT(11) NOT NULL,
operation_datetime DATETIME NOT NULL,
rowcount INT(11) NULL,
FOREIGN KEY (imported_file_id) REFERENCES imported_files (imported_file_id),
FOREIGN KEY (processed_table_id) REFERENCES processed_table (id),
FOREIGN KEY (operation_id) REFERENCES operation_ref (id));


INSERT INTO processed_table (table_name) VALUES ('match_import');
INSERT INTO processed_table (table_name) VALUES ('round');
INSERT INTO processed_table (table_name) VALUES ('umpire');
INSERT INTO processed_table (table_name) VALUES ('umpire_name_type');
INSERT INTO processed_table (table_name) VALUES ('match_staging');
INSERT INTO processed_table (table_name) VALUES ('match_played');
INSERT INTO processed_table (table_name) VALUES ('umpire_name_type_match');
INSERT INTO processed_table (table_name) VALUES ('mv_report_01');
INSERT INTO processed_table (table_name) VALUES ('mv_report_02');
INSERT INTO processed_table (table_name) VALUES ('mv_summary_staging');
INSERT INTO processed_table (table_name) VALUES ('mv_report_03');
INSERT INTO processed_table (table_name) VALUES ('mv_report_04');
INSERT INTO processed_table (table_name) VALUES ('mv_report_05');
INSERT INTO processed_table (table_name) VALUES ('mv_report_06');
INSERT INTO processed_table (table_name) VALUES ('mv_report_06_staging');
INSERT INTO processed_table (table_name) VALUES ('mv_umpire_list');

INSERT INTO operation_ref (operation_name) VALUES ('INSERT');
INSERT INTO operation_ref (operation_name) VALUES ('UPDATE');
INSERT INTO operation_ref (operation_name) VALUES ('DELETE');

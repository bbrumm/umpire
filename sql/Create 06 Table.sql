DROP TABLE IF EXISTS mv_report_06;
DROP TABLE IF EXISTS mv_report_06_staging;
DROP TABLE IF EXISTS mv_umpire_list;

CREATE TABLE mv_umpire_list (
umpire_type_name VARCHAR(200) DEFAULT NULL,
age_group VARCHAR(200) DEFAULT NULL,
umpire_name VARCHAR(200) DEFAULT NULL
);

CREATE INDEX idx_mvul_ump ON umpire.mv_umpire_list(umpire_type_name);
CREATE INDEX idx_mvul_ag ON umpire.mv_umpire_list(age_group);
CREATE INDEX idx_mvul_un ON umpire.mv_umpire_list(umpire_name);

CREATE TABLE mv_report_06_staging (
umpire_type_name VARCHAR(200) DEFAULT NULL,
age_group VARCHAR(200) DEFAULT NULL,
first_umpire VARCHAR(200) DEFAULT NULL,
second_umpire VARCHAR(200) DEFAULT NULL,
match_ID  INT(11) DEFAULT NULL
);

CREATE INDEX idx_mv06s_ump ON umpire.mv_report_06_staging(umpire_type_name);
CREATE INDEX idx_mv06s_ag ON umpire.mv_report_06_staging(age_group);
CREATE INDEX idx_mv06s_fu ON umpire.mv_report_06_staging(first_umpire);
CREATE INDEX idx_mv06s_su ON umpire.mv_report_06_staging(second_umpire);



CREATE TABLE mv_report_06 (
umpire_type_name VARCHAR(200) DEFAULT NULL,
age_group VARCHAR(200) DEFAULT NULL,
first_umpire VARCHAR(200) DEFAULT NULL,
second_umpire VARCHAR(200) DEFAULT NULL,
match_count  INT(11) DEFAULT NULL
);

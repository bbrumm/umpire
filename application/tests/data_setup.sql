USE databas6;

CREATE TABLE password_reset_request (
  request_datetime datetime,
  activation_id varchar(200),
  ip_address varchar(50),
  user_name varchar(255),
  email_address varchar(255)
);

CREATE TABLE umpire_users (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_name varchar(255) NOT NULL,
  user_email varchar(255) NOT NULL,
  user_password varchar(255) NOT NULL,
  first_name varchar(100) DEFAULT NULL,
  last_name varchar(100) DEFAULT NULL,
  activation_id varchar(20) DEFAULT NULL,
  role_id int(11) DEFAULT NULL,
  active int(1) DEFAULT NULL,
  PRIMARY KEY (id)
);


INSERT INTO umpire_users (id, user_name, user_email, user_password, first_name, last_name, role_id, active)
VALUES (24, 'bbrummtest', 'brummthecar@gmail.com', MD5('test'), 'Ben', 'Brumm', 3, 1);

CREATE TABLE role (
  id int(11) NOT NULL AUTO_INCREMENT,
  role_name varchar(100) DEFAULT NULL,
  display_order int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO role (id, role_name, display_order) VALUES (1,'Owner',1);
INSERT INTO role (id, role_name, display_order) VALUES (2,'Administrator',2);
INSERT INTO role (id, role_name, display_order) VALUES (3,'Super User (Geelong)',3);
INSERT INTO role (id, role_name, display_order) VALUES (4,'Regular User',4);
INSERT INTO role (id, role_name, display_order) VALUES (5,'Super User (Colac)',4);


CREATE TABLE permission_selection (
  id int(11) NOT NULL AUTO_INCREMENT,
  permission_id int(11) DEFAULT NULL,
  category varchar(100) DEFAULT NULL,
  selection_name varchar(100) DEFAULT NULL,
  display_order int(5) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY permission_id (permission_id)
);

INSERT INTO `permission_selection` VALUES
(1,1,'General','All',10),
(2,2,'General','All',10),
(3,3,'General','All',10),
(4,4,'General','All',10),
(5,5,'General','All',10),
(6,7,'Region','Geelong',10),
(7,7,'Region','Colac',20),
(8,6,'Report','Report 1',10),
(9,6,'Report','Report 2',20),
(10,6,'Report','Report 3',30),
(11,6,'Report','Report 4',40),
(12,6,'Report','Report 5',50),
(13,6,'Report','Report 6',60),
(14,7,'Age Group','Seniors',10),
(15,7,'Age Group','Reserves',20),
(16,7,'Age Group','Colts',30),
(17,7,'Age Group','Under 17.5',40),
(18,7,'Age Group','Under 16',50),
(19,7,'Age Group','Under 14.5',60),
(20,7,'Age Group','Under 12',70),
(21,7,'Age Group','Youth Girls',80),
(22,7,'Age Group','Junior Girls',90),
(23,7,'League','BFL',10),
(24,7,'League','GFL',20),
(25,7,'League','GDFL',30),
(26,7,'League','GJFL',40),
(27,7,'League','CDFNL',50),
(28,7,'Umpire Type','Boundary',10),
(29,7,'Umpire Type','Field',20),
(30,7,'Umpire Type','Goal',30),
(31,8,'General','All',10),
(32,7,'Age Group','Under 14',100),
(33,6,'Report','Report 7',70),
(34,6,'Report','Report 8',80);

CREATE TABLE user_permission_selection (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) DEFAULT NULL,
  permission_selection_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  KEY permission_selection_id (permission_selection_id)
);

INSERT INTO user_permission_selection 
VALUES (278,2,31),(279,3,31),(280,13,2),(281,13,6),(282,13,8),(283,13,9),(284,13,10),(285,13,11),(286,13,12),(287,13,13),(288,13,14),(289,13,15),(290,13,16),(291,13,18),(292,13,20),(293,13,21),(294,13,22),(295,13,23),(296,13,24),(297,13,25),(298,13,26),(299,13,28),(300,13,29),(301,13,30),(302,13,7),(303,13,17),(304,13,19),(305,13,32),(306,13,27),(307,14,2),(308,14,6),(309,14,7),(310,14,8),(311,14,9),(312,14,10),(313,14,11),(314,14,12),(315,14,13),(316,14,14),(317,14,15),(318,14,16),(319,14,17),(320,14,18),(321,14,19),(322,14,20),(323,14,21),(324,14,22),(325,14,23),(326,14,24),(327,14,25),(328,14,26),(329,14,27),(330,14,28),(331,14,29),(332,14,30),(333,14,32),(334,17,2),(335,17,6),(336,17,7),(337,17,8),(338,17,9),(339,17,10),(340,17,11),(341,17,12),(342,17,13),(343,17,14),(344,17,15),(345,17,16),(346,17,17),(347,17,18),(348,17,19),(349,17,20),(350,17,21),(351,17,22),(352,17,23),(353,17,24),(354,17,25),(355,17,26),(356,17,27),(357,17,28),(358,17,29),(359,17,30),(360,17,32),(361,18,2),(362,18,6),(363,18,25),(364,19,29),(365,19,13),(366,18,12),(367,19,24),(368,18,11),(369,19,10),(370,18,14),(371,18,10),(372,18,9),(373,18,17),(374,18,18),(375,18,19),(376,18,8),(377,18,21),(378,20,26),(379,18,23),(380,20,21),(381,20,19),(382,18,26),(383,20,16),(384,18,28),(385,18,29),(386,18,30),(387,18,32),(388,19,2),(389,19,6),(390,19,7),(391,19,8),(392,19,34),(393,18,33),(394,19,11),(395,19,12),(396,19,23),(397,19,14),(398,19,15),(399,19,16),(400,19,17),(401,19,18),(402,19,19),(403,19,20),(404,19,21),(405,19,22),(406,18,24),(407,18,22),(408,18,20),(409,18,16),(410,19,27),(411,19,28),(412,19,9),(413,19,30),(414,19,32),(415,20,2),(416,20,6),(417,20,7),(418,20,8),(419,20,9),(420,20,10),(421,19,33),(422,20,11),(423,17,34),(424,20,12),(425,17,33),(426,20,13),(427,14,34),(428,20,14),(429,14,33),(430,20,15),(431,13,34),(432,18,27),(433,20,17),(434,20,18),(435,18,15),(436,20,20),(437,18,34),(438,20,22),(439,20,23),(440,20,24),(441,20,25),(442,18,13),(443,20,27),(444,20,28),(445,13,33),(446,20,29),(447,19,26),(448,20,30),(449,19,25),(450,20,32);


CREATE TABLE permission (
  id int(11) NOT NULL AUTO_INCREMENT,
  permission_name varchar(100) DEFAULT NULL,
  PRIMARY KEY (id)
);

INSERT INTO permission VALUES (1,'IMPORT_FILES'),(2,'CREATE_PDF'),(3,'VIEW_DATA_TEST'),(4,'ADD_NEW_USERS'),(5,'MODIFY_EXISTING_USERS'),(6,'VIEW_REPORT'),(7,'SELECT_REPORT_OPTION'),(8,'VIEW_USER_ADMIN'),(9,'VIEW_USER_ADMIN');

CREATE TABLE role_permission_selection (
  id int(11) NOT NULL AUTO_INCREMENT,
  permission_selection_id int(11) DEFAULT NULL,
  role_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY permission_selection_id (permission_selection_id)
);

INSERT INTO role_permission_selection VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,6,1),(7,7,1),(8,8,1),(9,9,1),(10,10,1),(11,11,1),(12,12,1),(13,13,1),(14,14,1),(15,15,1),(16,16,1),(17,17,1),(18,18,1),(19,19,1),(20,20,1),(21,21,1),(22,22,1),(23,23,1),(24,24,1),(25,25,1),(26,26,1),(27,27,1),(28,28,1),(29,29,1),(30,30,1),(31,1,2),(32,2,2),(33,4,2),(34,5,2),(35,6,2),(36,8,2),(37,9,2),(38,10,2),(39,11,2),(40,12,2),(41,13,2),(42,14,2),(43,15,2),(44,16,2),(46,18,2),(48,20,2),(49,21,2),(50,22,2),(51,23,2),(52,24,2),(53,25,2),(54,26,2),(56,28,2),(57,29,2),(58,30,2),(59,1,2),(60,2,2),(61,4,2),(62,5,2),(63,7,2),(64,8,2),(65,9,2),(66,10,2),(67,11,2),(68,12,2),(69,13,2),(70,14,2),(71,15,2),(73,17,2),(75,19,2),(83,27,2),(84,28,2),(85,29,2),(86,30,2),(87,2,3),(88,6,3),(89,8,3),(90,9,3),(91,10,3),(92,11,3),(93,12,3),(94,13,3),(95,14,3),(96,15,3),(97,16,3),(99,18,3),(101,20,3),(102,21,3),(103,22,3),(104,23,3),(105,24,3),(106,25,3),(107,26,3),(109,28,3),(110,29,3),(111,30,3),(112,2,3),(113,7,3),(114,8,3),(115,9,3),(116,10,3),(117,11,3),(118,12,3),(119,13,3),(120,14,3),(121,15,3),(123,17,3),(125,19,3),(133,27,3),(134,28,3),(135,29,3),(136,30,3),(137,2,4),(138,6,4),(139,8,4),(140,9,4),(141,10,4),(142,11,4),(143,12,4),(144,13,4),(145,14,4),(146,15,4),(147,16,4),(149,18,4),(151,20,4),(152,21,4),(153,22,4),(154,23,4),(155,24,4),(156,25,4),(157,26,4),(159,28,4),(160,29,4),(161,30,4),(162,2,4),(163,7,4),(164,8,4),(165,9,4),(166,10,4),(167,11,4),(168,12,4),(169,13,4),(170,14,4),(171,15,4),(173,17,4),(175,19,4),(190,32,4),(189,32,3),(188,32,2),(187,32,1),(183,27,4),(184,28,4),(185,29,4),(186,30,4);


CREATE TABLE password_reset_log (
  reset_datetime datetime DEFAULT NULL,
  user_name varchar(255) DEFAULT NULL,
  old_password varchar(255) DEFAULT NULL,
  new_password varchar(255) DEFAULT NULL
);


CREATE TABLE imported_files (
  imported_file_id int(11) NOT NULL AUTO_INCREMENT,
  filename varchar(500) NOT NULL,
  imported_datetime datetime NOT NULL,
  imported_user_id varchar(200) DEFAULT NULL,
  etl_status int(2) DEFAULT NULL,
  PRIMARY KEY (imported_file_id)
);



INSERT INTO imported_files VALUES (1,'data_setup_insert.xlsx','2018-09-19 17:37:15','bb',1);


CREATE TABLE report_selection_parameters (
  parameter_id int(11) NOT NULL,
  parameter_name varchar(100) NOT NULL,
  parameter_display_order int(3) NOT NULL,
  allow_multiple_selections tinyint(1) DEFAULT NULL,
  PRIMARY KEY (parameter_id)
);

INSERT INTO report_selection_parameters VALUES (1,'Region',1,0),(2,'League',2,1),(3,'Umpire Discipline',3,1),(4,'Age Group',4,1);


CREATE TABLE report_selection_parameter_values (
  parameter_value_id int(11) NOT NULL AUTO_INCREMENT,
  parameter_id int(11) NOT NULL,
  parameter_value_name varchar(100) NOT NULL,
  parameter_display_order int(3) NOT NULL,
  PRIMARY KEY (parameter_value_id),
  KEY fk_param_value (parameter_id)
);

INSERT INTO report_selection_parameter_values VALUES (1,1,'Geelong',1),(2,1,'Colac',2),(3,2,'BFL',1),(4,2,'GFL',2),(5,2,'GDFL',3),(6,2,'GJFL',4),(7,2,'CDFNL',5),(8,3,'Field',1),(9,3,'Boundary',2),(10,3,'Goal',3),(11,4,'Seniors',1),(12,4,'Reserves',2),(13,4,'Colts',3),(14,4,'Under 17.5',10),(15,4,'Under 16',15),(16,4,'Under 14.5',20),(17,4,'Under 14',25),(18,4,'Under 12',30),(19,4,'Youth Girls',80),(20,4,'Junior Girls',90),(21,4,'Under 19 Girls',50),(22,4,'Under 15 Girls',60),(23,4,'Under 12 Girls',70),(24,2,'Women',6),(25,4,'Under 19',6),(26,4,'Under 17',12),(27,4,'Under 15',17),(28,4,'Under 13',27),(29,4,'Under 18 Girls',53);



CREATE TABLE t_field_list (
  field_id int(11) NOT NULL AUTO_INCREMENT,
  field_name varchar(200) DEFAULT NULL,
  PRIMARY KEY (field_id)
);

INSERT INTO t_field_list VALUES (1,'match_count'),(2,'short_league_name'),(3,'club_name'),(4,'full_name'),(5,'age_group'),(6,'umpire_type_age_group'),(7,'weekend_date'),(8,'umpire_type'),(9,'umpire_name'),(10,'subtotal'),(11,'umpire_count'),(12,'first_umpire'),(13,'second_umpire'),(14,'last_first_name');


CREATE TABLE t_pdf_settings (
  pdf_settings_id int(11) NOT NULL AUTO_INCREMENT,
  resolution int(5) NOT NULL,
  paper_size varchar(5) NOT NULL,
  orientation varchar(20) NOT NULL,
  PRIMARY KEY (pdf_settings_id)
);

INSERT INTO t_pdf_settings VALUES (1,200,'a3','Landscape'),(2,200,'a3','Portrait'),(3,100,'a3','Landscape'),(4,200,'a4','Landscape');


CREATE TABLE t_report (
  report_id int(3) NOT NULL AUTO_INCREMENT,
  report_name varchar(100) NOT NULL,
  report_title varchar(200) NOT NULL,
  value_field_id int(11) DEFAULT NULL,
  no_value_display varchar(10) DEFAULT NULL,
  first_column_format varchar(50) DEFAULT NULL,
  colour_cells int(1) DEFAULT NULL,
  pdf_settings_id int(11) DEFAULT NULL,
  region_enabled int(1) DEFAULT NULL,
  league_enabled int(1) DEFAULT NULL,
  age_group_enabled int(1) DEFAULT NULL,
  umpire_type_enabled int(1) DEFAULT NULL,
  PRIMARY KEY (report_id),
  KEY value_field_id (value_field_id),
  KEY pdf_settings_id (pdf_settings_id),
  CONSTRAINT t_report_ibfk_1 FOREIGN KEY (value_field_id) REFERENCES t_field_list (field_id),
  CONSTRAINT t_report_ibfk_2 FOREIGN KEY (pdf_settings_id) REFERENCES t_pdf_settings (pdf_settings_id)
);


INSERT INTO t_report VALUES
(1,'01 - Umpires and Clubs','01 - Umpires and Clubs (%seasonYear)',1,' ','text',1,1,1,1,1,1),
(2,'02 - Umpire Names by League','02 - Umpire Names by League (%seasonYear)',1,' ','text',0,2,1,1,1,1),
(3,'03 - Summary','03 - Summary by Week (Matches Where No Umpires Are Recorded) (%seasonYear)',1,' ','date',0,1,1,0,0,0),
(4,'04 - Summary by Club','04 - Summary by Club (Matches Where No Umpires Are Recorded) (%seasonYear)',1,' ','text',0,1,1,0,0,0),
(5,'05 - Summary by League','05 - Games with Zero Umpires For Each League (%seasonYear)',1,'0','text',0,3,1,0,0,0),
(6,'06 - Pairings','06 - Umpire Pairing (%seasonYear)',1,' ','text',1,1,1,1,1,1),
(7,'07 - 2 and 3 Field Umpires','07 - Games with 2 or 3 Field Umpires (%seasonYear)',1,'','text',0,4,1,1,1,0),
(8,'08 - Umpire Games Tally','08 - Umpire Games Tally',1,NULL,'text',0,4,0,0,0,0);

CREATE TABLE season (
  ID int(11) NOT NULL AUTO_INCREMENT,
  season_year int(11) DEFAULT NULL COMMENT 'The year that this season belongs to.',
  PRIMARY KEY (ID)
);

INSERT INTO season VALUES (1,2015),(2,2016),(3,2017),(4,2018);

CREATE TABLE valid_selection_combinations (
  id int(11) DEFAULT NULL,
  pv_region_id int(11) DEFAULT NULL,
  pv_league_id int(11) DEFAULT NULL,
  pv_age_group_id int(11) DEFAULT NULL
);


INSERT INTO valid_selection_combinations VALUES (1,1,3,11),(2,1,3,12),(3,1,4,11),(4,1,4,12),(5,1,5,11),(6,1,5,12),(7,1,24,11),(8,1,24,12),(9,1,6,13),(10,1,6,15),(11,1,6,17),(12,1,6,18),(13,1,6,20),(14,1,6,19),(15,1,6,21),(16,1,6,22),(17,1,6,23),(18,2,7,11),(19,2,7,12),(20,2,7,14),(21,2,7,16),(22,1,6,25),(23,1,6,26),(24,1,6,27),(25,1,6,28),(26,1,6,29);

CREATE TABLE report_table (
  report_table_id int(11) NOT NULL,
  report_name varchar(40) DEFAULT NULL,
  table_name varchar(40) DEFAULT NULL,
  report_title varchar(100) DEFAULT NULL,
  PRIMARY KEY (report_table_id)
);

INSERT INTO report_table VALUES (1,'1','mv_report_01','Report 1'),(2,'2','mv_report_02','Report 2'),(3,'3','mv_report_03','Report 3'),(4,'4','mv_report_04','Report 4'),(5,'5','mv_report_05','Report 5'),(6,'6','mv_report_06','Report 6'),(7,'7','mv_report_07','Report 7'),(8,'8','mv_report_08','Report 8');

CREATE TABLE report_grouping_structure (
  report_grouping_structure_id int(11) NOT NULL AUTO_INCREMENT,
  grouping_type varchar(10) NOT NULL,
  report_id int(3) NOT NULL,
  field_id int(5) NOT NULL,
  field_group_order int(3) NOT NULL,
  merge_field int(1) NOT NULL DEFAULT '0',
  group_heading varchar(100) DEFAULT NULL,
  group_size_text varchar(500) DEFAULT NULL,
  PRIMARY KEY (report_grouping_structure_id),
  KEY report_id (report_id),
  KEY field_id (field_id)
);

INSERT INTO report_grouping_structure VALUES (1,'Column',1,2,1,1,NULL,NULL),(2,'Column',1,3,2,0,NULL,NULL),(3,'Row',1,14,1,0,'Name','Umpire_Name_First_Last'),(4,'Column',2,5,1,1,NULL,NULL),(5,'Column',2,2,2,0,NULL,NULL),(6,'Row',2,14,1,0,'Name',NULL),(7,'Column',3,6,1,1,NULL,NULL),(8,'Column',3,2,2,0,NULL,NULL),(9,'Row',3,7,1,0,'Week (Sat)',NULL),(10,'Column',4,8,1,1,NULL,NULL),(11,'Column',4,5,2,1,NULL,NULL),(12,'Column',4,2,3,0,NULL,NULL),(13,'Row',4,3,1,0,'Club',NULL),(14,'Column',5,2,1,1,NULL,NULL),(15,'Row',5,8,1,0,'Discipline',NULL),(16,'Row',5,5,2,0,'Age Group',NULL),(17,'Column',6,13,1,0,NULL,NULL),(18,'Row',6,12,1,0,'Umpire Name','Umpire_Name_First_Last'),(19,'Column',5,10,2,0,NULL,NULL),(20,'Column',7,2,1,1,NULL,NULL),(21,'Column',7,11,2,0,NULL,NULL),(22,'Row',7,5,1,0,'Age Group',NULL),(23,'Column',8,15,1,0,NULL,NULL),(24,'Row',8,4,1,0,NULL,NULL);



CREATE TABLE field_list (
  field_id int(11) NOT NULL AUTO_INCREMENT,
  field_name varchar(200) NOT NULL,
  PRIMARY KEY (field_id)
);


INSERT INTO field_list VALUES (1,'match_count'),(2,'short_league_name'),(3,'club_name'),(4,'full_name'),(5,'age_group'),(6,'umpire_type_age_group'),(7,'weekend_date'),(8,'umpire_type'),(9,'umpire_name'),(10,'subtotal'),(11,'umpire_count'),(12,'first_umpire'),(13,'second_umpire'),(14,'last_first_name'),(15,'season_year'),(16,'total_match_count'),(17,'column_heading');


CREATE TABLE ground (
  id int(11) NOT NULL AUTO_INCREMENT,
  main_name varchar(100) DEFAULT NULL COMMENT 'The common name for a ground.',
  alternative_name varchar(100) DEFAULT NULL COMMENT 'An alternative name for a ground, as there are multiple names for the same ground.',
  PRIMARY KEY (id),
  KEY alternative_name (alternative_name)
);



CREATE TABLE round (
  ID int(11) NOT NULL AUTO_INCREMENT,
  round_number int(11) DEFAULT NULL COMMENT 'The round number of the season.',
  round_date datetime DEFAULT NULL COMMENT 'The date this round starts on.',
  season_id int(11) DEFAULT NULL COMMENT 'The season this round belongs to.',
  league_id int(11) DEFAULT NULL COMMENT 'The league this round belonds to.',
  PRIMARY KEY (ID),
  KEY fk_round_season_idx (season_id),
  KEY fk_round_league_idx (league_id)
);



CREATE TABLE match_played (
  ID int(11) NOT NULL AUTO_INCREMENT,
  round_id int(11) DEFAULT NULL,
  ground_id int(11) DEFAULT NULL,
  match_time datetime DEFAULT NULL,
  home_team_id int(11) DEFAULT NULL,
  away_team_id int(11) DEFAULT NULL,
  match_staging_id int(11) DEFAULT NULL,
  PRIMARY KEY (ID),
  KEY fk_match_round_idx (round_id),
  KEY fk_match_ground_idx (ground_id),
  KEY fk_match_team_idx (home_team_id),
  KEY idx_mp_1 (match_staging_id)
);



INSERT INTO match_played (id, round_id, ground_id, match_time, home_team_id, away_team_id, match_staging_id)
VALUES (1,1,1,'2015-04-03 14:10:00',1,1,1);


CREATE TABLE dw_mv_report_01 (
  last_first_name varchar(200) DEFAULT NULL,
  short_league_name varchar(100) DEFAULT NULL,
  club_name varchar(100) DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  umpire_type varchar(100) DEFAULT NULL,
  match_count int(11) DEFAULT NULL,
  season_year int(4) DEFAULT NULL
);

INSERT INTO dw_mv_report_01(last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, match_count, season_year)
VALUES ('Wood, Shalia','BFL','Ocean Grove','Reserves','Geelong','Boundary',1,2016);
INSERT INTO dw_mv_report_01(last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, match_count, season_year)
VALUES ('Zarb, Jonathan', 'GFL', 'Ocean Grove', 'Seniors', 'Geelong', 'Field', 1, 2018);


CREATE TABLE dw_mv_report_02 (
  last_first_name varchar(200) DEFAULT NULL,
  short_league_name varchar(100) DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  age_sort_order int(2) DEFAULT NULL,
  league_sort_order int(2) DEFAULT NULL,
  two_ump_flag int(1) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  umpire_type varchar(100) DEFAULT NULL,
  match_count int(11) DEFAULT NULL,
  season_year int(4) DEFAULT NULL
);

INSERT INTO dw_mv_report_02 VALUES ('Mcgrath, Caleb','Women','Seniors',1,10,1,'Geelong','Field',4,2018);
INSERT INTO dw_mv_report_02 VALUES ('Mcgrath, Caleb','GFL','Seniors',1,10,1,'Geelong','Field',4,2018);

CREATE TABLE staging_no_umpires (
  weekend_date datetime DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  umpire_type varchar(100) DEFAULT NULL,
  short_league_name varchar(100) DEFAULT NULL,
  team_names varchar(400) DEFAULT NULL,
  match_id int(11) DEFAULT NULL,
  season_year int(4) DEFAULT NULL,
  KEY idx_stg_no (umpire_type,short_league_name,age_group),
  KEY idx_stg_no_mid (match_id)
);


INSERT INTO staging_no_umpires(weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
VALUES ('2018-06-16 00:00:00', 'Seniors', 'Boundary', 'Women', 'Bell Post Hill vs Drysdale', 612741, 2018);

CREATE TABLE dw_mv_report_04 (
  club_name varchar(100) DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  short_league_name varchar(100) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  umpire_type varchar(100) DEFAULT NULL,
  age_sort_order int(11) DEFAULT NULL,
  league_sort_order int(11) DEFAULT NULL,
  match_count int(11) DEFAULT NULL,
  season_year int(4) DEFAULT NULL
);

INSERT INTO dw_mv_report_04 VALUES ('Werribee Centrals','Seniors','GFL','Geelong','Field',6,10,14,2018);


CREATE TABLE dw_mv_report_05 (
  umpire_type varchar(100) DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  age_sort_order int(2) DEFAULT NULL,
  short_league_name varchar(100) DEFAULT NULL,
  league_sort_order int(2) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  match_no_ump int(11) DEFAULT NULL,
  total_match_count int(11) DEFAULT NULL,
  match_pct int(11) DEFAULT NULL,
  season_year int(4) DEFAULT NULL
);


INSERT INTO dw_mv_report_05 VALUES ('Field','Seniors',20,'GFL',4,'Geelong',90,96,93,2018);

CREATE TABLE dw_mv_report_06 (
  umpire_type varchar(100) DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  first_umpire varchar(200) DEFAULT NULL,
  second_umpire varchar(200) DEFAULT NULL,
  season_year int(4) DEFAULT NULL,
  match_count int(11) DEFAULT NULL,
  short_league_name varchar(200) DEFAULT NULL
);

INSERT INTO dw_mv_report_06 VALUES ('Field','Seniors','Geelong','Woolsey, Louise','Bell, Gwenda',2018,15,'GFL');


CREATE TABLE dw_mv_report_07 (
  umpire_type varchar(100) DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  short_league_name varchar(200) DEFAULT NULL,
  season_year int(4) DEFAULT NULL,
  age_sort_order int(2) DEFAULT NULL,
  league_sort_order int(2) DEFAULT NULL,
  umpire_count varchar(100) DEFAULT NULL,
  match_count int(11) DEFAULT NULL
);

INSERT INTO dw_mv_report_07 (umpire_type, age_group, region_name, short_league_name, season_year, age_sort_order, league_sort_order, umpire_count, match_count)
VALUES ('Field','Seniors','Geelong','GFL',2018,1,10,'2 Umpires',2);


CREATE TABLE dw_mv_report_08 (
  season_year varchar(100) DEFAULT NULL,
  full_name varchar(200) DEFAULT NULL,
  match_count int(11) DEFAULT NULL,
  total_match_count int(11) DEFAULT NULL,
  last_name varchar(100) DEFAULT NULL,
  first_name varchar(100) DEFAULT NULL,
  KEY idx_rpt8_fnln (last_name,first_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO dw_mv_report_08 VALUES ('2018','Abbott, Trevor',27,426,'Abbott','Trevor');

CREATE TABLE dw_dim_league (
  league_key int(11) NOT NULL AUTO_INCREMENT,
  short_league_name varchar(50) DEFAULT NULL,
  full_name varchar(200) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  competition_name varchar(500) DEFAULT NULL,
  league_sort_order int(11) DEFAULT NULL,
  league_year int(11) DEFAULT NULL,
  PRIMARY KEY (league_key),
  KEY idx_dl_join (short_league_name,full_name,region_name,competition_name)
);

INSERT INTO dw_dim_league VALUES (1,'GFL','AFL Barwon Blood Toyota Geelong FNL','Geelong','AFL Barwon - 2018 Blood Toyota Geelong FNL Seniors',1,2018);

CREATE TABLE report_column_display_order (
  report_id int(3) DEFAULT NULL,
  column_heading varchar(200) DEFAULT NULL,
  display_order int(5) DEFAULT '0'
);


INSERT INTO report_column_display_order VALUES (8,'Games Prior',10),(8,'2015',20),(8,'2016',30),(8,'2017',40),(8,'2018',50),(8,'Total Geelong',60),(8,'Games Other Leagues',70),(8,'Total Overall',80);


CREATE TABLE staging_all_ump_age_league (
  age_group varchar(100) DEFAULT NULL,
  umpire_type varchar(100) DEFAULT NULL,
  short_league_name varchar(100) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  age_sort_order int(11) DEFAULT NULL,
  league_sort_order int(11) DEFAULT NULL
);

INSERT INTO staging_all_ump_age_league VALUES ('Seniors','Boundary','GFL','Geelong',1,1);

CREATE TABLE umpire (
  ID int(11) NOT NULL AUTO_INCREMENT,
  first_name varchar(100) DEFAULT NULL,
  last_name varchar(100) DEFAULT NULL,
  games_prior int(5) DEFAULT '0',
  games_other_leagues int(5) DEFAULT '0',
  PRIMARY KEY (ID),
  KEY idx_umpire_1 (first_name,last_name),
  KEY idx_ump_fn (first_name),
  KEY idx_ump_ln (last_name)
);

INSERT INTO umpire VALUES (22960,'Tim','Arnott',166,0);
INSERT INTO umpire VALUES (89372,'Mitch','Gray',387,0);

CREATE TABLE umpire_games_history (
  id int(11) DEFAULT NULL,
  first_name varchar(100) DEFAULT NULL,
  last_name varchar(100) DEFAULT NULL,
  old_games_prior int(5) DEFAULT NULL,
  old_games_other_leagues int(5) DEFAULT NULL,
  new_games_prior int(5) DEFAULT NULL,
  new_games_other_leagues int(5) DEFAULT NULL,
  updated_by varchar(100) DEFAULT NULL,
  updated_date datetime DEFAULT NULL
);

CREATE TABLE dw_dim_umpire (
  umpire_key int(11) NOT NULL AUTO_INCREMENT,
  first_name varchar(100) DEFAULT NULL,
  last_name varchar(100) DEFAULT NULL,
  last_first_name varchar(200) DEFAULT NULL,
  umpire_type varchar(100) DEFAULT NULL,
  games_prior int(5) DEFAULT NULL,
  games_other_leagues int(5) DEFAULT NULL,
  PRIMARY KEY (umpire_key),
  KEY idx_du_join (first_name,last_name,umpire_type),
  KEY idx_du_fn (first_name),
  KEY idx_du_ln (last_name),
  KEY idx_du_ut (umpire_type),
  KEY idx_du_nametype (umpire_key,last_first_name,umpire_type),
  KEY idx_du_keytype (umpire_key,umpire_type)
);

CREATE TABLE umpire_type (
  ID int(11) NOT NULL AUTO_INCREMENT,
  umpire_type_name varchar(100) DEFAULT NULL,
  PRIMARY KEY (ID),
  KEY idx_ut_ut (umpire_type_name)
);


INSERT INTO umpire_type VALUES (1,'Field'),(2,'Boundary'),(3,'Goal');

CREATE TABLE umpire_name_type (
  ID int(11) NOT NULL AUTO_INCREMENT,
  umpire_id int(11) DEFAULT NULL,
  umpire_type_id int(11) DEFAULT NULL,
  PRIMARY KEY (ID),
  KEY fk_unt_umpire_idx (umpire_id),
  KEY fk_unt_ut_idx (umpire_type_id)
);

INSERT INTO umpire_name_type(umpire_id, umpire_type_id) VALUES (22960, 1);
INSERT INTO umpire_name_type(umpire_id, umpire_type_id) VALUES (89372, 1);

CREATE TABLE match_import (
  ID int(11) NOT NULL AUTO_INCREMENT,
  season int(11) DEFAULT NULL,
  round int(11) DEFAULT NULL,
  date varchar(45) DEFAULT NULL,
  competition_name varchar(200) DEFAULT NULL,
  ground varchar(200) DEFAULT NULL,
  time varchar(200) DEFAULT NULL,
  home_team varchar(200) DEFAULT NULL,
  away_team varchar(200) DEFAULT NULL,
  field_umpire_1 varchar(200) DEFAULT NULL,
  field_umpire_2 varchar(200) DEFAULT NULL,
  field_umpire_3 varchar(200) DEFAULT NULL,
  boundary_umpire_1 varchar(200) DEFAULT NULL,
  boundary_umpire_2 varchar(200) DEFAULT NULL,
  boundary_umpire_3 varchar(200) DEFAULT NULL,
  boundary_umpire_4 varchar(200) DEFAULT NULL,
  goal_umpire_1 varchar(200) DEFAULT NULL,
  goal_umpire_2 varchar(200) DEFAULT NULL,
  boundary_umpire_5 varchar(200) DEFAULT NULL,
  boundary_umpire_6 varchar(200) DEFAULT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE match_staging (
  appointments_id int(11) NOT NULL,
  appointments_season int(11) DEFAULT NULL,
  appointments_round int(11) DEFAULT NULL,
  appointments_date datetime DEFAULT NULL,
  appointments_compname varchar(100) DEFAULT NULL,
  appointments_ground varchar(100) DEFAULT NULL,
  appointments_time datetime DEFAULT NULL,
  appointments_hometeam varchar(100) DEFAULT NULL,
  appointments_awayteam varchar(100) DEFAULT NULL,
  appointments_field1_first varchar(100) DEFAULT NULL,
  appointments_field1_last varchar(100) DEFAULT NULL,
  appointments_field2_first varchar(100) DEFAULT NULL,
  appointments_field2_last varchar(100) DEFAULT NULL,
  appointments_field3_first varchar(100) DEFAULT NULL,
  appointments_field3_last varchar(100) DEFAULT NULL,
  appointments_boundary1_first varchar(100) DEFAULT NULL,
  appointments_boundary1_last varchar(100) DEFAULT NULL,
  appointments_boundary2_first varchar(100) DEFAULT NULL,
  appointments_boundary2_last varchar(100) DEFAULT NULL,
  appointments_boundary3_first varchar(100) DEFAULT NULL,
  appointments_boundary3_last varchar(100) DEFAULT NULL,
  appointments_boundary4_first varchar(100) DEFAULT NULL,
  appointments_boundary4_last varchar(100) DEFAULT NULL,
  appointments_goal1_first varchar(100) DEFAULT NULL,
  appointments_goal1_last varchar(100) DEFAULT NULL,
  appointments_goal2_first varchar(100) DEFAULT NULL,
  appointments_goal2_last varchar(100) DEFAULT NULL,
  season_id int(11) DEFAULT NULL,
  round_ID int(11) DEFAULT NULL,
  round_date datetime DEFAULT NULL,
  round_leagueid int(11) DEFAULT NULL,
  league_leaguename varchar(100) DEFAULT NULL,
  league_sponsored_league_name varchar(100) DEFAULT NULL,
  agd_agegroupid int(11) DEFAULT NULL,
  ag_agegroup varchar(100) DEFAULT NULL,
  agd_divisionid int(11) DEFAULT NULL,
  division_divisionname varchar(100) DEFAULT NULL,
  ground_id int(11) DEFAULT NULL,
  ground_mainname varchar(100) DEFAULT NULL,
  home_team_id int(11) DEFAULT NULL,
  away_team_id int(11) DEFAULT NULL,
  appointments_boundary5_first varchar(200) DEFAULT NULL,
  appointments_boundary5_last varchar(200) DEFAULT NULL,
  appointments_boundary6_first varchar(200) DEFAULT NULL,
  appointments_boundary6_last varchar(200) DEFAULT NULL,
  match_staging_id int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (match_staging_id)
);



CREATE TABLE staging_match (
  season_id int(11) DEFAULT NULL,
  season_year int(4) DEFAULT NULL,
  umpire_id int(11) DEFAULT NULL,
  umpire_first_name varchar(100) DEFAULT NULL,
  umpire_last_name varchar(100) DEFAULT NULL,
  home_club varchar(100) DEFAULT NULL,
  home_team varchar(100) DEFAULT NULL,
  away_club varchar(100) DEFAULT NULL,
  away_team varchar(100) DEFAULT NULL,
  short_league_name varchar(100) DEFAULT NULL,
  league_name varchar(100) DEFAULT NULL,
  age_group_id int(11) DEFAULT NULL,
  age_group_name varchar(100) DEFAULT NULL,
  umpire_type_name varchar(100) DEFAULT NULL,
  match_id int(11) DEFAULT NULL,
  match_time datetime DEFAULT NULL,
  region_id int(11) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  division_name varchar(100) DEFAULT NULL,
  competition_name varchar(500) DEFAULT NULL
);


CREATE TABLE staging_matches_away (
  season_year int(11) DEFAULT NULL COMMENT 'The year that this season belongs to.',
  first_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  last_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  ID int(11) NOT NULL DEFAULT '0',
  team_name varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'The team name within a club.',
  club_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  short_league_name varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT 'The shorter name of the league, used for reports',
  age_group varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  umpire_type_name varchar(100) CHARACTER SET utf8 DEFAULT NULL
);



CREATE TABLE staging_matches_home (
  season_year int(11) DEFAULT NULL COMMENT 'The year that this season belongs to.',
  first_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  last_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  ID int(11) NOT NULL DEFAULT '0',
  team_name varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'The team name within a club.',
  club_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  short_league_name varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT 'The shorter name of the league, used for reports',
  age_group varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  umpire_type_name varchar(100) CHARACTER SET utf8 DEFAULT NULL
);



CREATE TABLE staging_matches_homeaway (
  season_year int(11) DEFAULT NULL,
  first_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  last_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  ID int(11) NOT NULL DEFAULT '0',
  team_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  club_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  short_league_name varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  age_group varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  umpire_type_name varchar(100) CHARACTER SET utf8 DEFAULT NULL
);

CREATE TABLE umpire_name_type_match (
  ID int(11) NOT NULL AUTO_INCREMENT,
  umpire_name_type_id int(11) DEFAULT NULL,
  match_id int(11) DEFAULT NULL,
  PRIMARY KEY (ID)
);


/*
********
********
Create RunETL Procedure
********
********
*/


DELIMITER $$
CREATE PROCEDURE `RunETLProcess`(IN `pSeasonID` INT, IN `pImportedFileID` INT)
BEGIN



DECLARE vSeasonYear INT(4);

SET group_concat_max_len = 15000;

SELECT MAX(season_year)
INTO @vSeasonYear
FROM season
WHERE id = pSeasonID;



DELETE umpire_name_type_match FROM umpire_name_type_match
        INNER JOIN
    match_played ON umpire_name_type_match.match_ID = match_played.ID
        INNER JOIN
    round ON match_played.round_id = round.ID 
WHERE
    round.season_id = pSeasonID;
    
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire_name_type_match'), 3, ROW_COUNT());


DELETE match_played FROM match_played
        INNER JOIN
    round ON match_played.round_id = round.ID 
WHERE
    round.season_id = pSeasonID;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_played'), 3, ROW_COUNT());

DELETE round FROM round 
WHERE
    round.season_id = pSeasonID;
    
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'round'), 3, ROW_COUNT());


TRUNCATE match_staging;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_staging'), 3, ROW_COUNT());



/*Delete from MV tables*/

DELETE rec FROM dw_mv_report_01 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_01'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_02 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_02'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_04 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_04'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_05 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_05'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_06 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_06'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_07 rec WHERE rec.season_year = @vSeasonYear;
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_07'), 3, ROW_COUNT());

DELETE rec FROM dw_mv_report_08 rec WHERE rec.season_year IN(CONVERT(@vSeasonYear, CHAR), 'Games Other Leagues', 'Games Prior', 'Other Years');
CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_mv_report_08'), 3, ROW_COUNT());

/*
Delete rather than Truncate because we want this table to contain data for all years
*/
DELETE rec FROM dw_fact_match rec
INNER JOIN dw_dim_time t ON rec.time_key = t.time_key
WHERE t.date_year = @vSeasonYear;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_fact_match'), 3, ROW_COUNT());


/*Reload tables*/

ALTER TABLE round DISABLE KEYS;

INSERT INTO round ( round_number, round_date, season_id, league_id )
SELECT DISTINCT match_import.round, STR_TO_DATE(match_import.date, '%d/%m/%Y'), season.ID AS season_id, league.ID AS league_id
FROM match_import 
INNER JOIN season ON match_import.season = season.season_year
INNER JOIN competition_lookup ON (season.ID = competition_lookup.season_id) AND (match_import.competition_name = competition_lookup.competition_name)
INNER JOIN league ON league.ID = competition_lookup.league_id
ORDER BY match_import.Round, match_import.Date;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'round'), 1, ROW_COUNT());

ALTER TABLE round ENABLE KEYS;

INSERT INTO umpire (first_name, last_name) 
SELECT first_name, last_name FROM (
SELECT LEFT(UMPIRE_FULLNAME,InStr(UMPIRE_FULLNAME,' ')-1) AS FIRST_NAME, 
RIGHT(UMPIRE_FULLNAME,Length(UMPIRE_FULLNAME)-InStr(UMPIRE_FULLNAME,' ')) AS LAST_NAME 
FROM (SELECT match_import.field_umpire_1 AS UMPIRE_FULLNAME 
	FROM match_import 
	UNION 
	SELECT match_import.field_umpire_2 
	FROM match_import 
	UNION 
	SELECT match_import.field_umpire_3 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_1 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_2 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_3 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_4 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_5 
	FROM match_import 
	UNION 
	SELECT match_import.boundary_umpire_6 
	FROM match_import 
	UNION 
	SELECT match_import.goal_umpire_1 
	FROM match_import 
	UNION 
	SELECT match_import.goal_umpire_2 
	FROM match_import 
) AS com  
WHERE UMPIRE_FULLNAME IS NOT NULL 
) AS sub  
WHERE NOT EXISTS (
SELECT 1 
FROM umpire u 
WHERE u.first_name = sub.first_name 
AND u.last_name = sub.last_name);

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire'), 1, ROW_COUNT());

ALTER TABLE umpire_name_type DISABLE KEYS;

INSERT INTO umpire_name_type (umpire_id, umpire_type_id) 
          SELECT DISTINCT umpire.ID, umpire_type.ID 
          FROM ( 
        	SELECT 
        	LEFT(UMPIRE_FULLNAME,INSTR(UMPIRE_FULLNAME,' ')-1) AS FIRST_NAME, 
        	RIGHT(UMPIRE_FULLNAME,LENGTH(UMPIRE_FULLNAME)-INSTR(UMPIRE_FULLNAME,' ')) AS LAST_NAME, 
        	com1.UMPIRE_TYPE 
        	FROM ( 
        		SELECT match_import.field_umpire_1 AS UMPIRE_FULLNAME, 'Field' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.field_umpire_2, 'Field' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.field_umpire_3, 'Field' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_1, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_2, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_3, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_4, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_5, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.boundary_umpire_6, 'Boundary' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.goal_umpire_1, 'Goal' as UMPIRE_TYPE 
        		FROM match_import 
        		UNION 
        		SELECT match_import.goal_umpire_2, 'Goal' as UMPIRE_TYPE 
        		FROM match_import 
        	) com1 
        	WHERE com1.UMPIRE_FULLNAME IS NOT NULL 
        )  AS com2 
        INNER JOIN umpire ON com2.first_name = umpire.first_name AND com2.last_name = umpire.last_name 
        INNER JOIN umpire_type ON com2.umpire_type = umpire_type.umpire_type_name
        WHERE (umpire.ID, umpire_type.ID) NOT IN (
			SELECT umpire_id, umpire_type_id
            FROM umpire_name_type
        );

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire_name_type'), 1, ROW_COUNT());

ALTER TABLE umpire_name_type ENABLE KEYS;

ALTER TABLE match_staging DISABLE KEYS;

INSERT INTO match_staging 
        (appointments_id, appointments_season, appointments_round, appointments_date, appointments_compname, appointments_ground, appointments_time, 
        appointments_hometeam, appointments_awayteam, appointments_field1_first, appointments_field1_last, appointments_field2_first, appointments_field2_last, 
        appointments_field3_first, appointments_field3_last, appointments_boundary1_first, appointments_boundary1_last, appointments_boundary2_first, appointments_boundary2_last, 
        appointments_boundary3_first, appointments_boundary3_last, appointments_boundary4_first, appointments_boundary4_last,  
        appointments_boundary5_first, appointments_boundary5_last, appointments_boundary6_first, appointments_boundary6_last, appointments_goal1_first, appointments_goal1_last, 
        appointments_goal2_first, appointments_goal2_last, season_id, round_ID, round_date, round_leagueid, league_leaguename, league_sponsored_league_name, agd_agegroupid, 
        ag_agegroup, agd_divisionid, division_divisionname, ground_id, ground_mainname, home_team_id, away_team_id) 
      SELECT match_import.ID,  
        match_import.Season,   
        match_import.Round,   
        STR_TO_DATE(match_import.date, '%d/%m/%Y'),   
        match_import.competition_name,   
        match_import.ground,   
        STR_TO_DATE(CONCAT(match_import.date, ' ', match_import.time), '%d/%m/%Y %h:%i %p'),   
        match_import.home_team,   
        match_import.away_team, 
      LEFT(match_import.field_umpire_1,InStr(match_import.field_umpire_1,' ')-1) AS match_import_field1_first,   
        RIGHT(match_import.field_umpire_1,LENGTH(match_import.field_umpire_1)-InStr(match_import.field_umpire_1,' ')) AS match_import_field1_last,    
        LEFT(match_import.field_umpire_2,InStr(match_import.field_umpire_2,' ')-1) AS match_import_field2_first,    
        RIGHT(match_import.field_umpire_2,LENGTH(match_import.field_umpire_2)-InStr(match_import.field_umpire_2,' ')) AS match_import_field2_last,    
        LEFT(match_import.field_umpire_3,InStr(match_import.field_umpire_3,' ')-1) AS match_import_field3_first,    
        RIGHT(match_import.field_umpire_3,LENGTH(match_import.field_umpire_3)-InStr(match_import.field_umpire_3,' ')) AS match_import_field3_last,    
        LEFT(match_import.boundary_umpire_1,InStr(match_import.boundary_umpire_1,' ')-1) AS match_import_boundary1_first,    
        RIGHT(match_import.boundary_umpire_1,LENGTH(match_import.boundary_umpire_1)-InStr(match_import.boundary_umpire_1,' ')) AS match_import_boundary1_last,    
        LEFT(match_import.boundary_umpire_2,InStr(match_import.boundary_umpire_2,' ')-1) AS match_import_boundary2_first,    
        RIGHT(match_import.boundary_umpire_2,LENGTH(match_import.boundary_umpire_2)-InStr(match_import.boundary_umpire_2,' ')) AS match_import_boundary2_last,    
        LEFT(match_import.boundary_umpire_3,InStr(match_import.boundary_umpire_3,' ')-1) AS match_import_boundary3_first, 
        RIGHT(match_import.boundary_umpire_3,LENGTH(match_import.boundary_umpire_3)-InStr(match_import.boundary_umpire_3,' ')) AS match_import_boundary3_last,    
        LEFT(match_import.boundary_umpire_4,InStr(match_import.boundary_umpire_4,' ')-1) AS match_import_boundary4_first,    
        RIGHT(match_import.boundary_umpire_4,LENGTH(match_import.boundary_umpire_4)-InStr(match_import.boundary_umpire_4,' ')) AS match_import_boundary4_last,   
        LEFT(match_import.boundary_umpire_5,InStr(match_import.boundary_umpire_5,' ')-1) AS match_import_boundary5_first, 
        RIGHT(match_import.boundary_umpire_5,LENGTH(match_import.boundary_umpire_5)-InStr(match_import.boundary_umpire_5,' ')) AS match_import_boundary5_last, 
        LEFT(match_import.boundary_umpire_6,InStr(match_import.boundary_umpire_6,' ')-1) AS match_import_boundary6_first, 
        RIGHT(match_import.boundary_umpire_6,LENGTH(match_import.boundary_umpire_6)-InStr(match_import.boundary_umpire_6,' ')) AS match_import_boundary6_last, 
        LEFT(match_import.goal_umpire_1,InStr(match_import.goal_umpire_1,' ')-1) AS match_import_goal1_first,    
        RIGHT(match_import.goal_umpire_1,LENGTH(match_import.goal_umpire_1)-InStr(match_import.goal_umpire_1,' ')) AS match_import_goal1_last,    
        LEFT(match_import.goal_umpire_2,InStr(match_import.goal_umpire_2,' ')-1) AS match_import_goal2_first,    
        RIGHT(match_import.goal_umpire_2,LENGTH(match_import.goal_umpire_2)-InStr(match_import.goal_umpire_2,' ')) AS match_import_goal2_last,    
        season.ID AS season_id,    
        round.ID AS round_ID,    
        round.round_date AS round_date,    
        round.league_id AS round_leagueid,    
        league.league_name AS league_leaguename,    
        league.sponsored_league_name AS league_sponsored_league_name,    
        age_group_division.age_group_id AS agd_agegroupid,    
        age_group.age_group AS ag_agegroup,    
        age_group_division.division_id AS agd_divisionid,    
        division.division_name AS division_divisionname,    
        ground.id AS ground_id,    
        ground.main_name AS ground_mainname,    
        team.ID AS home_team_id,    
        team_1.ID AS away_team_id 
      FROM match_import 
        INNER JOIN round ON (STR_TO_DATE(match_import.date, '%d/%m/%Y') = round.round_date) AND (match_import.round = round.round_number) 
        INNER JOIN competition_lookup ON match_import.competition_name = competition_lookup.competition_name 
        INNER JOIN season ON (match_import.season = season.season_year) AND (season.ID = competition_lookup.season_id) AND (season.ID = round.season_id) 
        INNER JOIN ground ON match_import.Ground = ground.alternative_name 
        INNER JOIN team ON match_import.home_team = team.team_name 
        INNER JOIN team AS team_1 ON match_import.away_team = team_1.team_name 
        INNER JOIN league ON (league.ID = competition_lookup.league_id) AND (league.ID = round.league_id) 
        INNER JOIN age_group_division ON league.age_group_division_id = age_group_division.ID 
        INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id 
        INNER JOIN division ON division.ID = age_group_division.division_id;



/*
TODO: Add season filter here?
*/


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_staging'), 1, ROW_COUNT());

ALTER TABLE match_staging ENABLE KEYS;
        
/*
Delete duplicate records from match_staging
11*/

DELETE m1 FROM match_staging m1,
    match_staging m2 
WHERE
    m1.appointments_id > m2.appointments_id
    AND m1.ground_id = m2.ground_id
    AND m1.round_id = m2.round_id
    AND m1.appointments_time = m2.appointments_time
    AND m1.home_team_id = m2.home_team_id
    AND m1.away_team_id = m2.away_team_id;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_staging'), 3, ROW_COUNT());

ALTER TABLE match_played DISABLE KEYS;

INSERT INTO match_played (round_ID, ground_id, home_team_id, away_team_id, match_time, match_staging_id)
SELECT match_staging.round_ID, match_staging.ground_id, match_staging.home_team_id, 
match_staging.away_team_id, match_staging.appointments_time,
match_staging.match_staging_id
FROM match_staging;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'match_played'), 1, ROW_COUNT());

ALTER TABLE match_played ENABLE KEYS;

ALTER TABLE umpire_name_type_match DISABLE KEYS;

INSERT INTO umpire_name_type_match ( umpire_name_type_id, match_id ) 
SELECT umpire_name_type_id, match_id 
FROM (
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Field' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field2_first) AND (umpire.last_name = match_staging.appointments_field2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Field' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field3_first) AND (umpire.last_name = match_staging.appointments_field3_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Field' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary1_first) AND (umpire.last_name = match_staging.appointments_boundary1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary2_first) AND (umpire.last_name = match_staging.appointments_boundary2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary3_first) AND (umpire.last_name = match_staging.appointments_boundary3_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary4_first) AND (umpire.last_name = match_staging.appointments_boundary4_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary5_first) AND (umpire.last_name = match_staging.appointments_boundary5_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary6_first) AND (umpire.last_name = match_staging.appointments_boundary6_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Boundary' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal1_first) AND (umpire.last_name = match_staging.appointments_goal1_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Goal' 
UNION ALL 
SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
FROM match_played 
INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal2_first) AND (umpire.last_name = match_staging.appointments_goal2_last) 
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
WHERE umpire_type.umpire_type_name = 'Goal') AS ump;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'umpire_name_type_match'), 1, ROW_COUNT());

ALTER TABLE umpire_name_type_match ENABLE KEYS;


/*
Truncate DIM and FACT tables
*/
TRUNCATE TABLE dw_dim_age_group;
TRUNCATE TABLE dw_dim_league;
TRUNCATE TABLE dw_dim_team;
TRUNCATE TABLE dw_dim_time;
TRUNCATE TABLE dw_dim_umpire;



TRUNCATE staging_match;
TRUNCATE staging_no_umpires;
TRUNCATE staging_all_ump_age_league;

TRUNCATE dw_rpt06_stg2;
TRUNCATE dw_rpt06_staging;


ALTER TABLE dw_dim_umpire DISABLE KEYS;

/*
Populate DimUmpire
Uses LEFT JOIN to cater for umpires who haven't been imported (those that were pre-2015) as we want to include them in report 8
*/
INSERT INTO dw_dim_umpire (first_name, last_name, last_first_name, umpire_type, games_prior, games_other_leagues)
SELECT DISTINCT
u.first_name,
u.last_name,
CONCAT(u.last_name, ', ', u.first_name) AS last_first_name,
ut.umpire_type_name AS umpire_type,
u.games_prior,
u.games_other_leagues
FROM umpire u
LEFT JOIN umpire_name_type unt ON u.id = unt.umpire_id
LEFT JOIN umpire_type ut ON unt.umpire_type_id = ut.ID;


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_umpire'), 1, ROW_COUNT());

ALTER TABLE dw_dim_umpire ENABLE KEYS;

/*
Populate DimAgeGroup
*/

ALTER TABLE dw_dim_age_group DISABLE KEYS;

INSERT INTO dw_dim_age_group (age_group, sort_order, division)
SELECT
ag.age_group,
ag.display_order AS sort_order,
d.division_name
FROM age_group ag
INNER JOIN age_group_division agd ON ag.id = agd.age_group_id
INNER JOIN division d ON agd.division_id = d.ID
ORDER BY ag.display_order;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_age_group'), 1, ROW_COUNT());

ALTER TABLE dw_dim_age_group ENABLE KEYS;

/*
Populate DimLeague
*/

ALTER TABLE dw_dim_league DISABLE KEYS;

INSERT INTO dw_dim_league (short_league_name, full_name, region_name, competition_name, league_year, league_sort_order)
SELECT DISTINCT
l.short_league_name,
l.league_name,
r.region_name,
c.competition_name,
s.season_year,
CASE short_league_name
	WHEN 'GFL' THEN 1
	WHEN 'BFL' THEN 2
	WHEN 'GDFL' THEN 3
	WHEN 'CDFNL' THEN 4
	ELSE 10
END league_sort_order
FROM league l
INNER JOIN region r ON l.region_id = r.id
INNER JOIN competition_lookup c ON l.ID = c.league_id
INNER JOIN season s ON c.season_id = s.id;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_league'), 1, ROW_COUNT());

ALTER TABLE dw_dim_league ENABLE KEYS;



/*
Populate DimTeam
*/

ALTER TABLE dw_dim_team DISABLE KEYS;

INSERT INTO dw_dim_team (team_name, club_name)
SELECT
t.team_name,
c.club_name
FROM team t
INNER JOIN club c ON t.club_id = c.id
ORDER BY t.team_name, c.club_name;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_team'), 1, ROW_COUNT());

ALTER TABLE dw_dim_team ENABLE KEYS;


/*
Populate DimTime
*/

ALTER TABLE dw_dim_time DISABLE KEYS;

INSERT INTO dw_dim_time (match_date, date_year, date_month, date_day, date_hour, date_minute, weekend_date, weekend_year, weekend_month, weekend_day)
SELECT
DISTINCT
/*r.round_number,*/
m.match_time,
YEAR(m.match_time) AS date_year,
MONTH(m.match_time) AS date_month,
DAY(m.match_time) AS date_day,
HOUR(m.match_time) AS date_hour,
MINUTE(m.match_time) AS date_minute,
ADDDATE(r.round_date, (5-Weekday(r.round_date))) AS weekend_date,
YEAR(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_year,
MONTH(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_month,
DAY(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_day
FROM match_played m
INNER JOIN round r ON m.round_id = r.id
ORDER BY m.match_time;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_dim_time'), 1, ROW_COUNT());

ALTER TABLE dw_dim_time ENABLE KEYS;


/*
Populate StagingMatch
*/

ALTER TABLE staging_match DISABLE KEYS;

INSERT INTO staging_match (season_id, season_year, umpire_id, umpire_first_name, umpire_last_name,
home_club, home_team, away_club, away_team, short_league_name, league_name, age_group_id, age_group_name, 
umpire_type_name, match_id, match_time, region_id, region_name, division_name, competition_name)
SELECT 
    s.id,
    s.season_year,
    u.id,
    u.first_name,
    u.last_name,
    hmc.club_name AS home_club,
    hmt.team_name AS home_team_name,
    awc.club_name AS away_club,
    awt.team_name AS away_team_name,
    l.short_league_name,
    l.league_name,
    ag.id,
    ag.age_group,
    ut.umpire_type_name,
    m.ID,
    m.match_time,
    r.id,
    r.region_name,
    d.division_name,
    cl.competition_name
FROM
match_played m
INNER JOIN    round rn ON rn.ID = m.round_id
INNER JOIN    league l ON l.ID = rn.league_id
INNER JOIN    age_group_division agd ON agd.ID = l.age_group_division_id
INNER JOIN    age_group ag ON ag.ID = agd.age_group_id
INNER JOIN    team hmt ON hmt.ID = m.home_team_id
INNER JOIN    club hmc ON hmc.ID = hmt.club_id
INNER JOIN    team awt ON awt.ID = m.away_team_id
INNER JOIN    club awc ON awc.ID = awt.club_id
INNER JOIN    division d ON agd.division_id = d.id
INNER JOIN    competition_lookup cl ON cl.league_id = l.ID
LEFT JOIN    umpire_name_type_match untm ON m.ID = untm.match_id
LEFT JOIN    umpire_name_type unt ON unt.ID = untm.umpire_name_type_id
LEFT JOIN    umpire_type ut ON ut.ID = unt.umpire_type_id
LEFT JOIN    umpire u ON u.ID = unt.umpire_id
INNER JOIN    season s ON s.id = rn.season_id AND cl.season_id = s.id
INNER JOIN    region r ON r.id = l.region_id;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'staging_match'), 1, ROW_COUNT());

ALTER TABLE staging_match ENABLE KEYS;




ALTER TABLE staging_all_ump_age_league ENABLE KEYS;

INSERT INTO staging_all_ump_age_league (age_group, umpire_type, short_league_name, region_name, age_sort_order, league_sort_order)
SELECT DISTINCT
ag.age_group,
ut.umpire_type_name,
l.short_league_name,
r.region_name,
ag.display_order,
CASE short_league_name
	WHEN 'GFL' THEN 1
	WHEN 'BFL' THEN 2
	WHEN 'GDFL' THEN 3
	WHEN 'CDFNL' THEN 4
	ELSE 10
END league_sort_order
FROM age_group ag
INNER JOIN age_group_division agd ON ag.ID = agd.age_group_id
INNER JOIN league l ON l.age_group_division_id = agd.ID
CROSS JOIN umpire_type ut
INNER JOIN region r ON l.region_id = r.id;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'staging_all_ump_age_league'), 1, ROW_COUNT());

ALTER TABLE staging_all_ump_age_league ENABLE KEYS;

/*
Populate Fact Match
*/

ALTER TABLE dw_fact_match ENABLE KEYS;

INSERT INTO dw_fact_match (match_id, umpire_key, age_group_key, league_key, time_key, home_team_key, away_team_key)
SELECT 
s.match_id,
du.umpire_key,
dag.age_group_key,
dl.league_key,
dt.time_key,
dth.team_key AS home_team_key,
dta.team_key AS away_team_key
FROM
staging_match s
LEFT JOIN dw_dim_umpire du ON (s.umpire_first_name = du.first_name
	AND s.umpire_last_name = du.last_name
	AND s.umpire_type_name = du.umpire_type
)
INNER JOIN dw_dim_age_group dag ON (
	s.age_group_name = dag.age_group
	AND s.division_name = dag.division
)
INNER JOIN dw_dim_league dl ON (
	s.short_league_name = dl.short_league_name
	AND s.league_name = dl.full_name
	AND s.region_name = dl.region_name
    AND s.competition_name = dl.competition_name
)
INNER JOIN dw_dim_team dth ON (
	s.home_team = dth.team_name
	AND s.home_club = dth.club_name
    )
INNER JOIN dw_dim_team dta ON (
	s.away_team = dta.team_name
	AND s.away_club = dta.club_name
    )
INNER JOIN dw_dim_time dt ON (
	s.match_time = dt.match_date
    AND s.season_year = dl.league_year
    AND s.season_year = @vSeasonYear
);

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'dw_fact_match'), 1, ROW_COUNT());

ALTER TABLE dw_fact_match ENABLE KEYS;







ALTER TABLE staging_no_umpires DISABLE KEYS;

INSERT INTO staging_no_umpires (weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Field',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Field'
)
UNION ALL

SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Boundary',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Boundary'
)
UNION ALL

SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Goal',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Goal'
);


CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'staging_no_umpires'), 1, ROW_COUNT());

ALTER TABLE staging_no_umpires ENABLE KEYS;



/*
Insert New Competitions
These will be displayed to the user when a file is imported. The leagues need to be assigned manually by the person who imported them.
NOTE: This assumes that a competition name is unique to a season. If the same name is used in a different season, this needs to be changed
so that the subquery includes WHERE season_id = pSeasonID
*/

/*
First, delete the competitions which are still NULL from previous imports
*/
DELETE FROM competition_lookup
WHERE league_id IS NULL;

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'competition_lookup'), 3, ROW_COUNT());

INSERT INTO competition_lookup (competition_name, season_id, league_id)
SELECT DISTINCT competition_name, pSeasonID, NULL
FROM match_import
WHERE competition_name NOT IN (
	SELECT competition_name
    FROM competition_lookup
);

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'competition_lookup'), 1, ROW_COUNT());

/*
Insert new teams. Clubs are added manually by the person importing the data
*/
INSERT INTO team (team_name, club_id)
SELECT home_team, NULL
FROM match_import
WHERE home_team NOT IN (
	SELECT team_name
    FROM team
)
UNION
SELECT away_team, NULL
FROM match_import
WHERE away_team NOT IN (
	SELECT team_name
    FROM team
);

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'team'), 1, ROW_COUNT());

/*
Insert new grounds
*/


INSERT INTO ground (main_name, alternative_name)
SELECT DISTINCT ground, ground
FROM match_import
WHERE ground NOT IN (
	SELECT alternative_name
	FROM ground
);

CALL LogTableOperation(pImportedFileID, (SELECT id FROM processed_table WHERE table_name = 'ground'), 1, ROW_COUNT());





/*

SPLIT

*/

END$$
DELIMITER ;

#DROP DATABASE dbunittest;
#CREATE DATABASE dbunittest;

USE dbunittest;

SET collation_connection = 'utf8_general_ci';

ALTER DATABASE dbunittest CHARACTER SET utf8 COLLATE utf8_general_ci;

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
VALUES 
(2, 'bbrumm', 'brummthecar@gmail.com', 'x', 'Ben', 'Brumm', 1, 1),
(3, 'bbeveridge', 'jbbeveridge@bigpond.com', 'x', 'Brendan', 'Beveridge', 2, 1),
(4, 'jhillgrove', 'Jock@aflbarwon.com.au', 'x', 'Jason', 'Hillgrove', 2, 1),
(5, 'gmanager', 'genmanager@gful.com.au', 'x', 'General', 'Manager', 2, 1),
(6, 'dbaensch', 'None', 'x', 'Darren', 'Baensch', 3, 0),
(7, 'skeating', 'stevekeating34@icloud.com', 'x', 'Steve', 'Keating', 3, 1),
(8, 'hphilpott', 'None', 'x', 'Howard', 'Philpott', 3, 0),
(9, 'rtrotter', 'None', 'x', 'Rohan', 'Trotter', 3, 0),
(10, 'agrant', 'None', 'x', 'Alan', 'Grant', 3, 0),
(11, 'chood', 'colin.hood@suncorp.com.au', 'x', 'Colin', 'Hood', 3, 1),
(12, 'dsantospirito', 'None', 'x', 'Darren', 'Santospirito', 3, 1),
(13, 'tbrooks', 'None', 'x', 'Tony', 'Brooks', 4, 0),
(14, 'aedwick', 'None', 'x', 'Adam', 'Edwick', 4, 0),
(15, 'kmcmaster', 'None', 'x', 'Kevin', 'McMaster', 3, 0),
(16, 'ldonohue', 'None', 'x', 'Larry', 'Donohue', 3, 0),
(17, 'dreid', 'reid.davin.d@edumail.vic.gov.au', 'x', 'Davin', 'Reid', 4, 1),
(18, 'kclissold', 'kelvin@clissold.id.au', 'x', 'Kel', 'Clissold', 4, 1),
(19, 'rsteel', 'rvnsteel@bigpond.com.au', 'x', 'Robert', 'Steel', 4, 1),
(21, 'bsmith', 'None', 'x', 'Brad', 'Smith', 6, 1),
(24, 'bbrummtest', 'brummthecar@gmail.com', MD5('test'), 'Ben', 'Brumm', 3, 1),
(25, 'bbtest2', 'None', '81dc9bdb52d04dc20036dbd8313ed055', 'bb1F', 'bb1L', 3, 1);

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

INSERT INTO user_permission_selection 
VALUES (200,4,31),(201,5,31),(202,7,31),(203,11,31),(204,12,31),(205,24,31),(206,21,31);


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

INSERT INTO role_permission_selection (id, permission_selection_id, role_id) VALUES
(1,1,1),
(2,2,1),
(3,3,1),
(4,4,1),
(5,5,1),
(6,6,1),
(7,7,1),
(8,8,1),
(9,9,1),
(10,10,1),
(11,11,1),
(12,12,1),
(13,13,1),
(14,14,1),
(15,15,1),
(16,16,1),
(17,17,1),
(18,18,1),
(19,19,1),
(20,20,1),
(21,21,1),
(22,22,1),
(23,23,1),
(24,24,1),
(25,25,1),
(26,26,1),
(27,27,1),
(28,28,1),
(29,29,1),
(30,30,1),
(35,6,2),
(44,16,2),
(46,18,2),
(48,20,2),
(49,21,2),
(50,22,2),
(51,23,2),
(52,24,2),
(53,25,2),
(54,26,2),
(59,1,2),
(60,2,2),
(61,4,2),
(62,5,2),
(63,7,2),
(64,8,2),
(65,9,2),
(66,10,2),
(67,11,2),
(68,12,2),
(69,13,2),
(70,14,2),
(71,15,2),
(73,17,2),
(75,19,2),
(83,27,2),
(84,28,2),
(85,29,2),
(86,30,2),
(88,6,3),
(97,16,3),
(99,18,3),
(101,20,3),
(102,21,3),
(103,22,3),
(104,23,3),
(105,24,3),
(106,25,3),
(107,26,3),
(112,2,3),
(113,7,3),
(114,8,3),
(115,9,3),
(116,10,3),
(117,11,3),
(118,12,3),
(119,13,3),
(120,14,3),
(121,15,3),
(123,17,3),
(125,19,3),
(133,27,3),
(134,28,3),
(135,29,3),
(136,30,3),
(138,6,4),
(147,16,4),
(149,18,4),
(151,20,4),
(152,21,4),
(153,22,4),
(154,23,4),
(155,24,4),
(156,25,4),
(157,26,4),
(162,2,4),
(163,7,4),
(164,8,4),
(165,9,4),
(166,10,4),
(167,11,4),
(168,12,4),
(169,13,4),
(170,14,4),
(171,15,4),
(173,17,4),
(175,19,4),
(190,32,4),
(189,32,3),
(188,32,2),
(187,32,1),
(183,27,4),
(184,28,4),
(185,29,4),
(186,30,4);


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


CREATE TABLE report (
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


INSERT INTO report VALUES
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


CREATE TABLE processed_table (
  id int(11) NOT NULL AUTO_INCREMENT,
  table_name varchar(50) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO processed_table VALUES (1,'match_import'),(2,'round'),(3,'umpire'),(4,'umpire_name_type'),(5,'match_staging'),(6,'match_played'),(7,'umpire_name_type_match'),(8,'mv_report_01'),(9,'mv_report_02'),(10,'mv_summary_staging'),(11,'mv_report_03'),(12,'mv_report_04'),(13,'mv_report_05'),(14,'mv_report_06'),(15,'mv_report_06_staging'),(16,'mv_umpire_list'),(17,'mv_denormalised'),(18,'ground'),(19,'mv_report_07'),(20,'dw_fact_match'),(21,'dw_dim_age_group'),(22,'dw_dim_league'),(23,'dw_dim_team'),(24,'dw_dim_time'),(25,'dw_dim_umpire'),(26,'staging_match'),(27,'staging_no_umpires'),(28,'staging_all_ump_age_league'),(29,'dw_mv_report_02'),(30,'dw_mv_report_04'),(31,'dw_mv_report_05'),(32,'dw_mv_report_06'),(33,'dw_mv_report_07'),(34,'dw_mv_report_01'),(35,'dw_rpt06_staging'),(37,'mv_report_07_stg1'),(36,'dw_rpt06_stg2'),(38,'competition_lookup'),(39,'team'),(41,'dw_mv_report_08');


CREATE TABLE table_operations (
  id int(11) NOT NULL AUTO_INCREMENT,
  imported_file_id int(11) NOT NULL,
  processed_table_id int(11) NOT NULL,
  operation_id int(11) NOT NULL,
  operation_datetime datetime NOT NULL,
  rowcount int(11) DEFAULT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE dw_fact_match (
  match_id int(11) DEFAULT NULL,
  umpire_key int(11) DEFAULT NULL,
  age_group_key int(11) DEFAULT NULL,
  league_key int(11) DEFAULT NULL,
  time_key int(11) DEFAULT NULL,
  home_team_key int(11) DEFAULT NULL,
  away_team_key int(11) DEFAULT NULL
);

CREATE TABLE dw_dim_time (
  time_key int(11) NOT NULL AUTO_INCREMENT,
  round_number int(2) DEFAULT NULL,
  match_date datetime DEFAULT NULL,
  date_year int(4) DEFAULT NULL,
  date_month int(2) DEFAULT NULL,
  date_day int(2) DEFAULT NULL,
  date_hour int(2) DEFAULT NULL,
  date_minute int(2) DEFAULT NULL,
  weekend_date date DEFAULT NULL,
  weekend_year int(4) DEFAULT NULL,
  weekend_month int(2) DEFAULT NULL,
  weekend_day int(2) DEFAULT NULL,
  PRIMARY KEY (time_key)
);


CREATE TABLE dw_dim_team (
  team_key int(11) NOT NULL AUTO_INCREMENT,
  team_name varchar(100) DEFAULT NULL,
  club_name varchar(100) DEFAULT NULL,
  PRIMARY KEY (team_key)
);

CREATE TABLE competition_lookup (
  id int(11) NOT NULL AUTO_INCREMENT,
  competition_name varchar(100) DEFAULT NULL COMMENT 'The competition name from the imported spreadsheet.',
  season_id int(11) DEFAULT NULL,
  league_id int(11) DEFAULT NULL,
  PRIMARY KEY (id)
);

INSERT INTO competition_lookup VALUES (1,'AFL Barwon - 2015 Blood Toyota Geelong FNL Seniors',1,3),(2,'AFL Barwon - 2015 Buckleys Entertainment Centre Geelong FNL Reserves',1,4),(3,'AFL Barwon - 2015 Dow Bellarine FNL Seniors',1,5),(4,'AFL Barwon - 2015 Buckleys Entertainment Centre Bellarine FNL Reserves',1,6),(5,'AFL Barwon - 2015 Colts Grading',1,7),(6,'AFL Barwon - 2015 Colts Practice Matches',1,8),(7,'GDFL - SMITHS HOLDEN CUP - SENIORS  2015',1,9),(8,'GDFL - BUCKLEYS CUP- RESERVES 2015',1,10),(9,'AFL Barwon - 2015 Under 16 Grading',1,11),(10,'AFL Barwon - 2015 Under 14 Grading',1,12),(11,'AFL Barwon - 2015 Under 16 Div 1 Buckley\'s Cup',1,13),(12,'AFL Barwon - 2015 Under 16 Div 2 Home Hardware Cup',1,14),(13,'AFL Barwon - 2015 Under 16 Div 3 Geelong Advertiser Cup',1,15),(14,'AFL Barwon - 2015 Under 16 Div 4 Geelong Tech Centre Cup',1,16),(15,'AFL Barwon - 2015 Under 16 Div 5 Coca Cola Cup',1,17),(16,'AFL Barwon - 2015 Under 14 Div 1 Kempe Cup',1,18),(17,'AFL Barwon - 2015 Under 14 Div 2 Buckley\'s Cup',1,19),(18,'AFL Barwon - 2015 Under 14 Div 3 GMHBA Cup',1,20),(19,'AFL Barwon - 2015 Under 14 Div 4 Supatramp Cup',1,21),(20,'AFL Barwon - 2015 Under 14 Div 5 Geelong Advertiser Cup',1,22),(21,'AFL Barwon - 2015 Under 14 Div 6 Red Onion Cup',1,23),(22,'AFL Barwon - 2015 Youth Girls',1,24),(23,'AFL Barwon - 2015 Junior Girls',1,25),(24,'AFL Barwon - 2015 Colts Div 1 KRock Cup',1,26),(25,'AFL Barwon - 2015 Colts Div 2 Bendigo Bank Cup',1,27),(26,'AFL Barwon - 2015 Colts Div 3 Corio Bay Health Group Cup',1,28),(27,'AFL Barwon - 2015 Colts Div 4 Corio Bay Health Group Cup',1,29),(56,'AFL Barwon - 2016 Dow Bellarine FNL Seniors',2,5),(58,'2016 CDFNL Seniors',2,30),(59,'AFL Barwon - 2016 Buckley\'s Bellarine FNL Reserves',2,6),(60,'2016 CDFNL Reserves',2,31),(61,'2016 CDFNL Under 17.5',2,32),(62,'2016 CDFNL Under 14.5',2,33),(63,'AFL Barwon - 2016 Blood Toyota Geelong FNL Seniors',2,3),(64,'AFL Barwon - 2016 Buckley\'s Geelong FNL Reserves',2,4),(65,'AFL Barwon - 2016 Colts Grading',2,7),(66,'AFL Barwon - 2016 Corio Bay Health Group Junior Girls',2,25),(67,'AFL Barwon - 2016 Corio Bay Health Group Youth Girls',2,24),(68,'AFL Barwon - 2016 Geelong Advertiser Under 12\'s',2,36),(69,'AFL Barwon - 2016 NLL Under 12\'s Walpole Shield',2,37),(70,'AFL Barwon - 2016 Under 14 Grading',2,12),(71,'AFL Barwon - 2016 Under 16 Grading',2,11),(72,'GDFL - Buckleys Cup Reserves 2016',2,10),(73,'GDFL - Smiths Holden Cup Seniors 2016',2,9),(74,'AFL Barwon - 2016 Colts Div 2 Bendigo Bank Cup',2,27),(75,'AFL Barwon - 2016 Colts Div 3 Corio Bay Health Group Cup',2,28),(76,'AFL Barwon - 2016 Colts Div 4 Corio Bay Health Group Cup',2,29),(77,'AFL Barwon - 2016 Junior Girls Corio Bay Health Group',2,25),(78,'AFL Barwon - 2016 Under 12\'s Geelong Advertiser ',2,36),(79,'AFL Barwon - 2016 Under 14 Div 1',2,38),(80,'AFL Barwon - 2016 Under 14 Div 2',2,39),(81,'AFL Barwon - 2016 Under 14 Div 3',2,40),(82,'AFL Barwon - 2016 Under 14 Div 4',2,41),(83,'AFL Barwon - 2016 Under 14 Div 5',2,42),(84,'AFL Barwon - 2016 Under 14 Div 6',2,43),(85,'AFL Barwon - 2016 Under 14 Div 7',2,44),(86,'AFL Barwon - 2016 Under 16 Div 1',2,45),(87,'AFL Barwon - 2016 Under 16 Div 2',2,46),(88,'AFL Barwon - 2016 Under 16 Div 3',2,47),(89,'AFL Barwon - 2016 Under 16 Div 4',2,48),(90,'AFL Barwon - 2016 Under 16 Div 5',2,49),(91,'AFL Barwon - 2016 Youth Girls Corio Bay Health Group',2,50),(92,'AFL Barwon - 2016 Colts Div 1 K Rock Cup',2,26),(96,'AFL Barwon - 2016 Under 14 Div 1 Kempe Cup',2,38),(97,'AFL Barwon - 2016 Under 14 Div 2 Buckley\'s Cup',2,39),(98,'AFL Barwon - 2016 Under 14 Div 3 GMHBA Cup',2,40),(99,'AFL Barwon - 2016 Under 14 Div 4 Supatramp Cup',2,41),(100,'AFL Barwon - 2016 Under 14 Div 5 Geelong Advertiser Cup',2,42),(101,'AFL Barwon - 2016 Under 14 Div 6 Red Onion Cup',2,43),(102,'AFL Barwon - 2016 Under 14 Div 7 National Heating & Cooling Cup',2,44),(103,'AFL Barwon - 2016 Under 16 Div 1 Buckley\'s Cup',2,45),(104,'AFL Barwon - 2016 Under 16 Div 2 Home Hardware Cup',2,46),(105,'AFL Barwon - 2016 Under 16 Div 3 Geelong Advertiser Cup',2,47),(106,'AFL Barwon - 2016 Under 16 Div 4 GTEC Cup',2,48),(107,'AFL Barwon - 2016 Under 16 Div 5 Coca Cola Cup',2,49),(108,'2017 CDFNL Reserves',3,31),(109,'2017 CDFNL Seniors',3,30),(110,'2017 CDFNL Under 14.5',3,33),(111,'2017 CDFNL Under 17.5',3,32),(112,'AFL Barwon - 2017 Bellarine FNL Buckley\'s Reserves Cup',3,6),(113,'AFL Barwon - 2017 Bellarine FNL Seniors Dow Cup',3,5),(114,'AFL Barwon - 2017 Colts Grading',3,7),(115,'AFL Barwon - 2017 Geelong FNL Reserves Buckley\'s Cup',3,4),(116,'AFL Barwon - 2017 Geelong FNL Seniors Blood Toyota Cup',3,3),(117,'GDFL - BUCKLEYS CUP Reserves 2017',3,10),(118,'GDFL - SMITHS HOLDEN CUP Seniors 2017',3,9),(119,'AFL Barwon - 2017 Colts & Juniors Practice Matches',3,8),(120,'AFL Barwon - 2017 Under 12 Girls Corio Bay Health Group',3,36),(121,'AFL Barwon - 2017 Under 12\'s Geelong Advertiser',3,36),(122,'AFL Barwon - 2017 Under 14 Grading',3,12),(123,'AFL Barwon - 2017 Under 15 Girls Corio Bay Health Group',3,51),(124,'AFL Barwon - 2017 Under 16 Grading',3,11),(125,'AFL Barwon - 2017 Under 19 Girls Corio Bay Health Group',3,52),(127,'AFL Barwon - 2017 NLL Under 12\'s Walpole Shield',3,37),(128,'AFL Barwon - 2017 Under 12 Girls',3,53),(129,'AFL Barwon - 2017 Under 15 Girls',3,51),(130,'AFL Barwon - 2017 Under 19 Girls',3,52),(131,'AFL Barwon - 2017 Colts Division 1',3,26),(132,'AFL Barwon - 2017 Colts Division 2',3,27),(133,'AFL Barwon - 2017 Colts Division 3',3,28),(134,'AFL Barwon - 2017 Colts Division 4',3,29),(135,'AFL Barwon - 2017 Under 14 Div 1 Kempe Cup',3,18),(136,'AFL Barwon - 2017 Under 14 Div 2 Buckley\'s Cup',3,39),(137,'AFL Barwon - 2017 Under 14 Div 3 The Drain Man Cup',3,40),(138,'AFL Barwon - 2017 Under 14 Div 4 Supatramp Cup',3,21),(139,'AFL Barwon - 2017 Under 14 Div 5 Geelong Advertiser Cup',3,42),(140,'AFL Barwon - 2017 Under 14 Div 6 National Heating & Cooling Cup',3,43),(141,'AFL Barwon - 2017 Under 15 Girls Div 1',3,54),(142,'AFL Barwon - 2017 Under 15 Girls Div 2',3,55),(143,'AFL Barwon - 2017 Under 15 Girls Grading',3,56),(144,'AFL Barwon - 2017 Under 16 Div 1 Buckley\'s Cup',3,13),(145,'AFL Barwon - 2017 Under 16 Div 2 The Drain Man Cup',3,46),(146,'AFL Barwon - 2017 Under 16 Div 3 Geelong Advertiser Cup ',3,47),(147,'AFL Barwon - 2017 Under 16 Div 4 Cleanaway Cup',3,48),(148,'AFL Barwon - 2017 Under 16 Div 5 Coca Cola Cup',3,17),(149,'AFL Barwon - 2017 Under 19 Girls Div 1',3,57),(150,'AFL Barwon - 2017 Under 19 Girls Div 2',3,58),(151,'AFL Barwon - 2017 Under 19 Girls Grading',3,59),(181,'2017 CDFNL AKD Softwoods Under 17.5',3,60),(182,'2017 CDFNL Bendigo Bank Seniors',3,61),(183,'2017 CDFNL Bulla Under 14.5',3,62),(184,'2017 CDFNL Phillips ABS Reserves',3,31),(185,'AFL Barwon - 2017 AFL Goldfields Division 2',3,64),(186,'AFL Barwon - 2017 Colts Division 1 K Rock Cup',3,26),(187,'AFL Barwon - 2017 Colts Division 2 Bendigo Bank Cup',3,27),(188,'AFL Barwon - 2017 Colts Division 3 Corio Bay Health Group Cup',3,28),(189,'AFL Barwon - 2017 Colts Division 4 Corio Bay Health Group Cup',3,29),(190,'2018 CDFNL AKD Softwoods Under 17.5',4,32),(191,'2018 CDFNL Bendigo Bank Seniors',4,30),(192,'2018 CDFNL Bulla Under 14.5',4,33),(193,'2018 CDFNL Phillips ABS Reserves',4,31),(194,'AFL Barwon - 2018 Bellarine FNL Buckley\'s Reserves Cup',4,6),(195,'AFL Barwon - 2018 Bellarine FNL Seniors Dow Cup',4,5),(196,'AFL Barwon - 2018 Epworth Senior Women\'s Grading',4,65),(197,'AFL Barwon - 2018 Geelong FNL Buckley\'s Reserves Cup',4,4),(198,'AFL Barwon - 2018 Geelong FNL Seniors Blood Toyota Cup',4,3),(199,'AFL Barwon - 2018 Under 13\'s Grading',4,66),(200,'AFL Barwon - 2018 Under 15\'s Girls Grading',4,56),(201,'AFL Barwon - 2018 Under 15\'s Grading',4,67),(202,'AFL Barwon - 2018 Under 17\'s Grading',4,68),(203,'AFL Barwon - 2018 Under 18\'s Girls Grading',4,69),(204,'AFL Barwon - 2018 Under 19\'s Grading',4,70),(205,'GDFL - Buckleys Cup 2018  Reserves',4,10),(206,'GDFL - Smiths Holden Cup 2018 Seniors',4,9),(207,'AFL Barwon - 2018 Senior Women\'s Division 1 Epworth Cup',4,71),(208,'AFL Barwon - 2018 Senior Women\'s Division 2 Corio Bay Health Group Cup',4,72),(209,'AFL Barwon - 2018 Under 13\'s Div 1 Kempe Cup',4,73),(210,'AFL Barwon - 2018 Under 13\'s Div 2 Buckley\'s Cup',4,74),(211,'AFL Barwon - 2018 Under 13\'s Div 3 The Drain Man Cup',4,75),(212,'AFL Barwon - 2018 Under 13\'s Div 4 Geelong Advertiser Cup',4,76),(213,'AFL Barwon - 2018 Under 13\'s Div 5 Supatramp Cup',4,77),(214,'AFL Barwon - 2018 Under 13\'s Div 6 Medibank Cup',4,78),(215,'AFL Barwon - 2018 Under 13\'s Div 7 Corio Bay Health Group Cup',4,79),(216,'AFL Barwon - 2018 Under 15\'s Div 1 Kempe Cup',4,80),(217,'AFL Barwon - 2018 Under 15\'s Div 2 Buckley\'s Cup',4,81),(218,'AFL Barwon - 2018 Under 15\'s Div 3 The Drain Man Cup',4,82),(219,'AFL Barwon - 2018 Under 15\'s Div 4 Supatramp Cup',4,83),(220,'AFL Barwon - 2018 Under 15\'s Div 5 Geelong Advertiser Cup',4,84),(223,'AFL Barwon - 2018 Under 17\'s Div 1 Buckley\'s Cup',4,87),(224,'AFL Barwon - 2018 Under 17\'s Div 2 The Drain Man Cup',4,88),(226,'AFL Barwon - 2018 Under 17\'s Div 4 Cleanaway Cup',4,90),(228,'AFL Barwon - 2018 Under 19 Div 1 GForce Cup',4,91),(229,'AFL Barwon - 2018 Under 19 Div 2 Bendigo Bank Cup',4,92),(230,'AFL Barwon - 2018 Under 19 Div 3 Corio Bay Health Group Cup',4,93),(231,'AFL Barwon - 2018 Under 17\'s Div 3 Medibank Cup',4,89),(234,'AFL Barwon - 2018 Under 15\'s Girls Div 1 Epworth Cup',4,80),(235,'AFL Barwon - 2018 Under 15\'s Girls Div 2 Corio Bay Health Group Cup',4,81),(236,'AFL Barwon - 2018 Under 18\'s Girls Div 1 Epworth Cup',4,94),(237,'AFL Barwon - 2018 Under 18\'s Girls Div 2 Corio Bay Health Group Cup',4,95),(238,'AFL Barwon - 2018 Under 18\'s Girls Epworth Cup',4,96);

CREATE TABLE league (
  ID int(11) NOT NULL AUTO_INCREMENT,
  league_name varchar(100) DEFAULT NULL COMMENT 'The name of a league of competition.',
  sponsored_league_name varchar(100) DEFAULT NULL COMMENT 'The full name of the league, including the sponsors name.',
  short_league_name varchar(200) DEFAULT NULL COMMENT 'The shorter name of the league, used for reports',
  age_group_division_id int(11) DEFAULT NULL COMMENT 'The division for an age group that this league belongs to.',
  region_id int(11) DEFAULT NULL,
  PRIMARY KEY (ID)
);


INSERT INTO league VALUES (3,'AFL Barwon Blood Toyota Geelong FNL','AFL Barwon Blood Toyota Geelong FNL','GFL',1,1),(4,'AFL Barwon Buckleys Entertainment Centre Geelong FNL','AFL Barwon Buckleys Entertainment Centre Geelong FNL','GFL',2,1),(5,'AFL Barwon Dow Bellarine FNL','AFL Barwon Dow Bellarine FNL','BFL',1,1),(6,'AFL Barwon Buckleys Entertainment Centre Bellarine FNL','AFL Barwon Buckleys Entertainment Centre Bellarine FNL','BFL',2,1),(7,'AFL Barwon','AFL Barwon','GJFL',3,1),(8,'AFL Barwon','AFL Barwon','GJFL',4,1),(9,'GDFL Smiths Holden Cup','GDFL Smiths Holden Cup','GDFL',1,1),(10,'GDFL Buckleys Cup','GDFL Buckleys Cup','GDFL',2,1),(11,'AFL Barwon','AFL Barwon','GJFL',5,1),(12,'AFL Barwon','AFL Barwon','GJFL',6,1),(13,'AFL Barwon Buckley\'s Cup','AFL Barwon Buckley\'s Cup','GJFL',7,1),(14,'AFL Barwon Home Hardware Cup','AFL Barwon Home Hardware Cup','GJFL',8,1),(15,'AFL Barwon Geelong Advertiser Cup','AFL Barwon Geelong Advertiser Cup','GJFL',9,1),(16,'AFL Barwon Geelong Tech Centre Cup','AFL Barwon Geelong Tech Centre Cup','GJFL',10,1),(17,'AFL Barwon Coca Cola Cup','AFL Barwon Coca Cola Cup','GJFL',11,1),(18,'AFL Barwon Kempe Cup','AFL Barwon Kempe Cup','GJFL',12,1),(19,'AFL Barwon Buckley\'s Cup','AFL Barwon Buckley\'s Cup','GJFL',13,1),(20,'AFL Barwon GMHBA Cup','AFL Barwon GMHBA Cup','GJFL',14,1),(21,'AFL Barwon Supatramp Cup','AFL Barwon Supatramp Cup','GJFL',15,1),(22,'AFL Barwon Geelong Advertiser Cup','AFL Barwon Geelong Advertiser Cup','GJFL',16,1),(23,'AFL Barwon Red Onion Cup','AFL Barwon Red Onion Cup','GJFL',17,1),(24,'AFL Barwon','AFL Barwon','GJFL',18,1),(25,'AFL Barwon','AFL Barwon','GJFL',19,1),(26,'AFL Barwon KRock Cup','AFL Barwon KRock Cup','GJFL',20,1),(27,'AFL Barwon Bendigo Bank Cup','AFL Barwon Bendigo Bank Cup','GJFL',21,1),(28,'AFL Barwon Corio Bay Health Group Cup','AFL Barwon Corio Bay Health Group Cup','GJFL',22,1),(29,'AFL Barwon Corio Bay Health Group Cup','AFL Barwon Corio Bay Health Group Cup','GJFL',23,1),(30,'CDFNL Seniors','CDFNL Seniors','CDFNL',1,2),(31,'CDFNL Reserves','CDFNL Reserves','CDFNL',2,2),(32,'CDFNL Under 17.5','CDFNL Under 17.5','CDFNL',24,2),(33,'CDFNL Under 14.5','CDFNL Under 14.5','CDFNL',25,2),(35,'AFL Barwon - Buckley\'s Bellarine FNL Reserves','AFL Barwon - Buckley\'s Bellarine FNL Reserves','BFL',2,1),(36,'AFL Barwon','AFL Barwon','GJFL',26,1),(37,'AFL Barwon Walpole Shield','AFL Barwon Walpole Shield','GJFL',26,1),(38,'AFL Barwon','AFL Barwon','GJFL',12,1),(39,'AFL Barwon','AFL Barwon','GJFL',13,1),(40,'AFL Barwon','AFL Barwon','GJFL',14,1),(41,'AFL Barwon','AFL Barwon','GJFL',15,1),(42,'AFL Barwon','AFL Barwon','GJFL',16,1),(43,'AFL Barwon','AFL Barwon','GJFL',17,1),(44,'AFL Barwon','AFL Barwon','GJFL',27,1),(45,'AFL Barwon','AFL Barwon','GJFL',7,1),(46,'AFL Barwon','AFL Barwon','GJFL',8,1),(47,'AFL Barwon','AFL Barwon','GJFL',9,1),(48,'AFL Barwon','AFL Barwon','GJFL',10,1),(49,'AFL Barwon','AFL Barwon','GJFL',11,1),(50,'AFL Barwon','AFL Barwon Corio Bay Health Group','GJFL',18,1),(51,'AFL Barwon','AFL Barwon','GJFL',29,1),(52,'AFL Barwon','AFL Barwon','GJFL',28,1),(53,'AFL Barwon','AFL Barwon','GJFL',30,1),(54,'AFL Barwon','AFL Barwon','GJFL',31,1),(55,'AFL Barwon','AFL Barwon','GJFL',32,1),(56,'AFL Barwon','AFL Barwon','GJFL',33,1),(57,'AFL Barwon','AFL Barwon','GJFL',34,1),(58,'AFL Barwon','AFL Barwon','GJFL',35,1),(59,'AFL Barwon','AFL Barwon','GJFL',36,1),(60,'AFL Barwon','AFL Barwon','CDFNL',24,2),(61,'AFL Barwon','AFL Barwon','CDFNL',1,2),(62,'AFL Barwon','AFL Barwon','CDFNL',25,2),(64,'AFL Barwon','AFL Barwon','Women',1,1),(65,'AFL Barwon','AFL Barwon','Women',42,1),(66,'AFL Barwon','AFL Barwon','GJFL',43,1),(67,'AFL Barwon','AFL Barwon','GJFL',44,1),(68,'AFL Barwon','AFL Barwon','GJFL',45,1),(69,'AFL Barwon','AFL Barwon','GJFL',46,1),(70,'AFL Barwon','AFL Barwon','GJFL',47,1),(71,'AFL Barwon','AFL Barwon','Women',48,1),(72,'AFL Barwon','AFL Barwon','Women',49,1),(73,'AFL Barwon','AFL Barwon','GJFL',50,1),(74,'AFL Barwon','AFL Barwon','GJFL',51,1),(75,'AFL Barwon','AFL Barwon','GJFL',52,1),(76,'AFL Barwon','AFL Barwon','GJFL',53,1),(77,'AFL Barwon','AFL Barwon','GJFL',54,1),(78,'AFL Barwon','AFL Barwon','GJFL',55,1),(79,'AFL Barwon','AFL Barwon','GJFL',56,1),(80,'AFL Barwon','AFL Barwon','GJFL',57,1),(81,'AFL Barwon','AFL Barwon','GJFL',58,1),(82,'AFL Barwon','AFL Barwon','GJFL',59,1),(83,'AFL Barwon','AFL Barwon','GJFL',60,1),(84,'AFL Barwon','AFL Barwon','GJFL',61,1),(87,'AFL Barwon','AFL Barwon','GJFL',62,1),(88,'AFL Barwon','AFL Barwon','GJFL',63,1),(89,'AFL Barwon','AFL Barwon','GJFL',64,1),(90,'AFL Barwon','AFL Barwon','GJFL',65,1),(91,'AFL Barwon','AFL Barwon','GJFL',66,1),(92,'AFL Barwon','AFL Barwon','GJFL',67,1),(93,'AFL Barwon','AFL Barwon','GJFL',68,1),(94,'AFL Barwon','AFL Barwon','GJFL',69,1),(95,'AFL Barwon','AFL Barwon','GJFL',70,1),(96,'AFL Barwon','AFL Barwon','GJFL',41,1);



CREATE TABLE team (
  ID int(11) NOT NULL AUTO_INCREMENT,
  team_name varchar(100) DEFAULT NULL COMMENT 'The team name within a club.',
  club_id int(11) DEFAULT NULL COMMENT 'The club that this team belongs to.',
  PRIMARY KEY (ID)
);

INSERT INTO team VALUES (1,'Anakie',63),(2,'Anglesea',64),(3,'Bannockburn',65),(4,'Bannockburn 1',65),(5,'Bannockburn 2',65),(6,'Barwon Heads',66),(7,'Bell Park',67),(8,'Bell Park 1',67),(9,'Bell Park 2',67),(10,'Bell Post Hill',68),(11,'Belmont Lions',69),(12,'Belmont Lions / Newcomb',70),(13,'Colac',71),(14,'Corio',72),(15,'Drysdale',73),(16,'Drysdale Bennett',74),(17,'Drysdale Byrne',75),(18,'Drysdale Eddy',76),(19,'Drysdale Hall',77),(20,'Drysdale Hector',78),(21,'Drysdale Hoyer',79),(22,'East Geelong',80),(23,'Geelong Amateur',81),(24,'Geelong Amateur 1',81),(25,'Geelong Amateur 2',81),(26,'Geelong West',82),(27,'Geelong West St Peters',83),(28,'Geelong West St Peters 1',83),(29,'Geelong West St Peters 2',83),(30,'Grovedale',84),(31,'Grovedale 1',84),(32,'Grovedale 2',84),(33,'Grovedale 3',84),(34,'Gwsp',85),(35,'Gwsp / Bannockburn',86),(36,'Inverleigh',87),(37,'Lara',88),(38,'Lara 1',88),(39,'Lara 2',88),(40,'Leopold',89),(41,'Leopold 1',89),(42,'Leopold 2',89),(43,'Modewarre',90),(44,'Newcomb',91),(45,'Newcomb Power',92),(46,'Newtown & Chilwell',93),(47,'Newtown & Chilwell 1',93),(48,'Newtown & Chilwell 2',93),(49,'North Geelong',94),(50,'North Shore',95),(51,'Ocean Grove',96),(52,'Ocean Grove 1',96),(53,'Ocean Grove 2',96),(54,'Ogcc 1',97),(55,'Ogcc 2',97),(56,'Ogcc 3',97),(57,'Portarlington',98),(58,'Queenscliff',99),(59,'South Barwon',100),(60,'South Barwon / Geelong Amateur',101),(61,'South Barwon 1',100),(62,'South Barwon 2',100),(63,'St Albans',102),(64,'St Albans Allthorpe',103),(65,'St Albans Reid',104),(66,'St Joseph\'s',105),(67,'St Joseph\'s 1',105),(68,'St Joseph\'s 2',105),(69,'St Joseph\'s 3',105),(70,'St Joseph\'s 4',105),(71,'St Joseph\'s Hill',106),(72,'St Joseph\'s Podbury',107),(73,'St Mary\'s',108),(74,'St Mary\'s 1',108),(75,'St Mary\'s 2',108),(76,'St Mary\'s 3',108),(77,'Thomson',109),(78,'Tigers Black',110),(79,'Tigers Gold',111),(80,'Torquay',112),(81,'Torquay 1',112),(82,'Torquay 2',112),(83,'Torquay Bumpstead',113),(84,'Torquay Coles',114),(85,'Torquay Dunstan',115),(86,'Torquay Jones',116),(87,'Torquay Nairn',117),(88,'Torquay Papworth',118),(89,'Torquay Pyers',119),(90,'Torquay Scott',120),(91,'Werribee Centrals',121),(92,'Winchelsea',122),(93,'Winchelsea / Grovedale',123),(94,'Birregurra',124),(95,'Lorne',125),(96,'Alvie',126),(97,'Apollo Bay',127),(98,'Colac Imperials',128),(99,'Irrewarra-beeac',129),(100,'Otway Districts',130),(101,'Simpson',131),(102,'South Colac',132),(103,'Western Eagles',133),(104,'Aireys Inlet',134),(105,'Ammos Blue',135),(106,'Ammos Green',135),(107,'Ammos White',135),(108,'Bannockburn / South Barwon',138),(109,'Barwon Heads Gulls',66),(110,'Barwon Heads Heads',66),(111,'Dragons',139),(112,'Drysdale 1',73),(113,'Drysdale 2',73),(114,'Drysdale Humphrey',73),(115,'Drysdale Mcintyre',73),(116,'Drysdale Mckeon',73),(117,'Drysdale Scott',73),(118,'Drysdale Smith',73),(119,'Drysdale Taylor',73),(120,'Drysdale Wilson',73),(121,'Eagles Black',140),(122,'Eagles Red',140),(123,'East Newcomb Lions',142),(124,'East Tigers',143),(125,'Flying Joeys',144),(126,'Gdfl Raiders',145),(127,'Grovedale Broad',84),(128,'Grovedale Ford',84),(129,'Grovedale Mcneel',84),(130,'Grovedale Waldron',84),(131,'Grovedale Williams',84),(132,'Grovedale Young',84),(133,'Lara Batman',88),(134,'Lara Flinders',88),(135,'Lara Hume',88),(136,'Leopold Brown',89),(137,'Leopold Dowsett',89),(138,'Leopold Ruggles',89),(139,'Lethbridge',146),(140,'Newtown & Chilwell Eagles',93),(141,'Ogcc Blue',97),(142,'Ogcc Orange',97),(143,'Ogcc Red',97),(144,'Ogcc White',97),(145,'Queenscliff Blue',99),(146,'Queenscliff Red',99),(147,'Roosters',147),(148,'Saints White',148),(149,'Seagulls',149),(150,'South Barwon Blue',100),(151,'South Barwon Red',100),(152,'South Barwon White',100),(153,'St Albans Butterworth',102),(154,'St Albans Grinter',102),(155,'St Albans Mcfarlane',102),(156,'St Albans Osborne',102),(157,'Surf Coast Suns',150),(158,'Teesdale Roos',151),(159,'Tigers',152),(160,'Torquay Boyse',112),(161,'Torquay Browning',112),(162,'Torquay Bruce',112),(163,'Torquay Coleman',112),(164,'Torquay Davey',112),(165,'Torquay Milliken',112),(166,'Torquay Stone',112),(167,'Torquay Watson',112),(168,'Winchelsea / Inverleigh',153),(169,'Lara 3',88),(170,'Geelong West Giants',82),(171,'Geelong West Giants 2',82),(172,'Modewarre / Grovedale',154),(173,'Geelong West Giants 1',82),(174,'Aireys Eels',134),(175,'Drysdale Butcher',73),(176,'Drysdale Grigg',73),(177,'Drysdale Richardson',73),(178,'Drysdale Wilton',73),(179,'Geelong West Giants / Newtown',82),(180,'Grovedale Smith',84),(181,'Modewarre / Winchelsea',90),(182,'Newtown & Chilwell 3',93),(183,'Portarlington Blue',98),(184,'Portarlington Red',98),(185,'St Joseph’s Hill',106),(186,'Swans',160),(187,'Swans Blue',160),(188,'Torquay Canning',112),(189,'Drysdale Ruggles',73),(190,'Geelong Amateur 3',81),(191,'Geelong Amateur Girls',81),(192,'Grovedale Dale',84),(193,'Grovedale Delaney',84),(194,'Little River',161),(195,'St Joseph’s Podbury',107),(196,'Swans Red',160),(197,'Swans White',160),(198,'Torquay Davies',112),(199,'Giants',162),(200,'Leaping Joeys',164),(201,'Ocean Grove Red',96),(202,'Ocean Grove White',96),(203,'Torquay Bayles',112),(204,'Eagles',140),(205,'Saints Blue',148),(206,'Spotswood',163),(207,'Lara Blue',88),(208,'Ocean Grove 3',96),(209,'Saints Green',148),(210,'Lara White',88),(211,'Grovedale Shiell',84),(212,'St Joseph\'s Jackman',105),(213,'Grovedale Fisher',84),(214,'Ocean Grove Blue',96),(215,'Leopold Pitt',89),(216,'Leopold Butteriss',89),(217,'Aireys Inlet Eels',134),(218,'Ammos Barton',135),(219,'Ammos Clark',135),(220,'Ammos Farrell',135),(221,'Ammos Hickey',135),(222,'Ammos Hunter',135),(223,'Ammos Lovick',135),(224,'Ammos Mcgrath',135),(225,'Ammos Mcsparron',135),(226,'Ammos Young',135),(227,'Bannockburn Tigers',65),(228,'Bannockburn Tigers Giles',65),(229,'Bannockburn Tigers Hickleton',65),(230,'Bannockburn Tigers Taylor',65),(231,'Barwon Heads Seagals',66),(232,'Bell Post Hill / Bannockburn',167),(233,'Belmont Power',168),(234,'Drysdale Colley',73),(235,'Drysdale Inglis',73),(236,'Drysdale Nelis',73),(237,'Drysdale Preece',73),(238,'Drysdale Timmins',73),(239,'East Geelong / Geelong West Giants',169),(240,'Grovedale / South Barwon',170),(241,'Grovedale / St Albans',171),(242,'Inverleigh Hawks',87),(243,'Lara Cook',88),(244,'Lara Founds',88),(245,'Lara Gillett',88),(246,'Lara Jenkins',88),(247,'Lara Mathieson',88),(248,'Lara Orr',88),(249,'Lara Smith',88),(250,'Lara Wilson',88),(251,'Leopold Hughes',89),(252,'Leopold Quinlan',89),(253,'North Shore / Geelong West Giants',172),(254,'South Barwon Swans',100),(255,'St Josephs',105),(256,'St Josephs 1',105),(257,'St Josephs 2',105),(258,'St Josephs 3',105),(259,'St Josephs Hill',105),(260,'St Josephs Podbury',105),(261,'St Mary\'s Dunstan',108),(262,'St Mary\'s Mcmahon',108),(263,'St Mary\'s Redden',108),(264,'St Mary\'s Rodgers',108),(265,'Thomson East',109),(266,'Torquay Davie',112),(267,'Ammos Harwood',135),(268,'Drysdale Hildebrand',73),(269,'Grovedale Ross',84),(270,'St Albans/newtown & Chilwell',173),(271,'St Mary\'s Digby',108),(272,'St Mary\'s Dobbyn',108),(273,'St Mary\'s Hosking',108),(274,'St Mary\'s Johnson',108),(275,'St Mary\'s Turnley',108);


CREATE TABLE age_group_division (
  ID int(11) NOT NULL AUTO_INCREMENT,
  age_group_id int(11) DEFAULT NULL,
  division_id int(11) DEFAULT NULL,
  PRIMARY KEY (ID)
);


INSERT INTO age_group_division VALUES (1,1,4),(2,2,4),(3,3,5),(4,3,6),(5,4,5),(6,5,5),(7,4,7),(8,4,8),(9,4,9),(10,4,10),(11,4,11),(12,5,7),(13,5,8),(14,5,9),(15,5,10),(16,5,11),(17,5,12),(18,6,4),(19,7,4),(20,3,7),(21,3,8),(22,3,9),(23,3,10),(24,8,4),(25,9,4),(26,10,4),(27,5,13),(28,11,4),(29,12,4),(30,13,4),(31,12,7),(32,12,8),(33,12,5),(34,11,7),(35,11,8),(36,11,5),(37,14,4),(38,15,4),(39,16,4),(40,17,4),(41,18,4),(42,1,5),(43,17,5),(44,16,5),(45,15,5),(46,18,5),(47,14,5),(48,1,7),(49,1,8),(50,17,7),(51,17,8),(52,17,9),(53,17,10),(54,17,11),(55,17,12),(56,17,13),(57,16,7),(58,16,8),(59,16,9),(60,16,10),(61,16,11),(62,15,7),(63,15,8),(64,15,9),(65,15,10),(66,14,7),(67,14,8),(68,14,9),(69,18,7),(70,18,8);


CREATE TABLE age_group (
  ID int(11) NOT NULL AUTO_INCREMENT,
  age_group varchar(20) DEFAULT NULL,
  display_order int(2) DEFAULT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO age_group VALUES (1,'Seniors',1),(2,'Reserves',2),(3,'Colts',3),(4,'Under 16',15),(5,'Under 14',25),(6,'Youth Girls',80),(7,'Junior Girls',90),(8,'Under 17.5',10),(9,'Under 14.5',20),(10,'Under 12',30),(11,'Under 19 Girls',50),(12,'Under 15 Girls',60),(13,'Under 12 Girls',70),(14,'Under 19',6),(15,'Under 17',12),(16,'Under 15',17),(17,'Under 13',27),(18,'Under 18 Girls',53);

CREATE TABLE division (
  ID int(11) NOT NULL,
  division_name varchar(20) DEFAULT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO division VALUES (4,'None'),(5,'Grading'),(6,'Practice'),(7,'Div 1'),(8,'Div 2'),(9,'Div 3'),(10,'Div 4'),(11,'Div 5'),(12,'Div 6'),(13,'Div 7');

CREATE TABLE dw_dim_age_group (
  age_group_key int(11) NOT NULL AUTO_INCREMENT,
  age_group varchar(50) DEFAULT NULL,
  sort_order int(2) DEFAULT NULL,
  division varchar(100) DEFAULT NULL,
  PRIMARY KEY (age_group_key)
);

CREATE TABLE dw_rpt06_stg2 (
  umpire_type varchar(200) DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  first_umpire varchar(200) DEFAULT NULL,
  second_umpire varchar(200) DEFAULT NULL,
  date_year int(11) DEFAULT NULL,
  match_id int(11) DEFAULT NULL,
  short_league_name varchar(200) DEFAULT NULL
);

CREATE TABLE dw_rpt06_staging (
  umpire_key int(11) DEFAULT NULL,
  umpire_type varchar(200) DEFAULT NULL,
  last_first_name varchar(200) DEFAULT NULL,
  match_id int(11) DEFAULT NULL,
  date_year int(11) DEFAULT NULL,
  league_key int(11) DEFAULT NULL,
  age_group varchar(100) DEFAULT NULL,
  region_name varchar(100) DEFAULT NULL,
  short_league_name varchar(200) DEFAULT NULL
);


CREATE TABLE region (
  id int(11) NOT NULL,
  region_name varchar(50) NOT NULL,
  PRIMARY KEY (id)
);


INSERT INTO region VALUES (1,'Geelong'),(2,'Colac');

CREATE TABLE club (
  id int(11) NOT NULL AUTO_INCREMENT,
  club_name varchar(100) DEFAULT NULL,
  PRIMARY KEY (id)
);



INSERT INTO club VALUES (63,'Anakie'),(64,'Anglesea'),(65,'Bannockburn'),(66,'Barwon Heads'),(67,'Bell Park'),(68,'Bell Post Hill'),(69,'Belmont Lions'),(70,'Belmont Lions / Newcomb'),(71,'Colac'),(72,'Corio'),(73,'Drysdale'),(74,'Drysdale Bennett'),(75,'Drysdale Byrne'),(76,'Drysdale Eddy'),(77,'Drysdale Hall'),(78,'Drysdale Hector'),(79,'Drysdale Hoyer'),(80,'East Geelong'),(81,'Geelong Amateur'),(82,'Geelong West'),(83,'Geelong West St Peters'),(84,'Grovedale'),(85,'Gwsp'),(86,'Gwsp / Bannockburn'),(87,'Inverleigh'),(88,'Lara'),(89,'Leopold'),(90,'Modewarre'),(91,'Newcomb'),(92,'Newcomb Power'),(93,'Newtown & Chilwell'),(94,'North Geelong'),(95,'North Shore'),(96,'Ocean Grove'),(97,'Ogcc'),(98,'Portarlington'),(99,'Queenscliff'),(100,'South Barwon'),(101,'South Barwon / Geelong Amateur'),(102,'St Albans'),(103,'St Albans Allthorpe'),(104,'St Albans Reid'),(105,'St Joseph\'s'),(106,'St Joseph\'s Hill'),(107,'St Joseph\'s Podbury'),(108,'St Mary\'s'),(109,'Thomson'),(110,'Tigers Black'),(111,'Tigers Gold'),(112,'Torquay'),(113,'Torquay Bumpstead'),(114,'Torquay Coles'),(115,'Torquay Dunstan'),(116,'Torquay Jones'),(117,'Torquay Nairn'),(118,'Torquay Papworth'),(119,'Torquay Pyers'),(120,'Torquay Scott'),(121,'Werribee Centrals'),(122,'Winchelsea'),(123,'Winchelsea / Grovedale'),(124,'Birregurra'),(125,'Lorne'),(126,'Alvie'),(127,'Apollo Bay'),(128,'Colac Imperials'),(129,'Irrewarra-beeac'),(130,'Otway Districts'),(131,'Simpson'),(132,'South Colac'),(133,'Western Eagles'),(134,'Aireys Inlet'),(135,'Ammos'),(138,'Bannockburn / South Barwon'),(139,'Dragons'),(140,'Eagles'),(142,'East Newcomb'),(143,'East Tigers'),(144,'Flying Joeys'),(145,'Gdfl Raiders'),(146,'Lethbridge'),(147,'Roosters'),(148,'Saints'),(149,'Seagulls'),(150,'Surf Coast'),(151,'Teesdale'),(152,'Tigers'),(153,'Winchelsea / Inverleigh'),(154,'Modewarre / Grovedale'),(155,'Grovedale Shiell'),(156,'St Joseph\'s Jackman'),(157,'Grovedale Fisher'),(158,'Ocean Grove Blue'),(159,'Modewarre / Winchelsea'),(160,'Swans'),(161,'Little River'),(162,'Giants'),(163,'Spotswood'),(164,'Leaping Joeys'),(165,'Leopold Pitt'),(166,'Leopold Butteriss'),(167,'Bell Post Hill / Bannockburn'),(168,'Belmont'),(169,'East Geelong / Geelong West'),(170,'Grovedale / South Barwon'),(171,'Grovedale / St Albans'),(172,'North Shore / Geelong West'),(173,'St Albans/Newtown & Chilwell');

CREATE TABLE mv_report_07_stg1 (
  match_id int(11) DEFAULT NULL,
  umpire_type varchar(100) DEFAULT NULL,
  age_group varchar(50) DEFAULT NULL,
  short_league_name varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  umpire_key int(11) NOT NULL DEFAULT '0',
  region_name varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  sort_order int(2) DEFAULT NULL,
  league_sort_order int(11) DEFAULT NULL
);


CREATE TABLE incomplete_records (
  record_type varchar(100) DEFAULT NULL,
  source_id int(11) DEFAULT NULL,
  source_value varchar(100) DEFAULT NULL
);


CREATE TABLE short_league_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  short_league_name varchar(100) DEFAULT NULL,
  display_order int(2) DEFAULT NULL,
  PRIMARY KEY (id)
);



INSERT INTO short_league_name VALUES (1,'BFL',1),(2,'GFL',2),(3,'GDFL',3),(4,'GJFL',4),(5,'CDFNL',5),(6,'Women',6);


CREATE TABLE operation_ref (
  id int(11) NOT NULL AUTO_INCREMENT,
  operation_name varchar(50) NOT NULL,
  PRIMARY KEY (id)
);


INSERT INTO operation_ref VALUES (1,'INSERT'),(2,'UPDATE'),(3,'DELETE');

CREATE TABLE log_role_changes (
  username_changed varchar(255) DEFAULT NULL,
  role_changed int(11) DEFAULT NULL,
  role_action int(1) DEFAULT NULL,
  username_changed_by varchar(255) DEFAULT NULL,
  changed_datetime datetime DEFAULT NULL
);


CREATE TABLE log_active_changes (
  username_changed varchar(255) DEFAULT NULL,
  new_active int(11) DEFAULT NULL,
  role_action int(1) DEFAULT NULL,
  username_changed_by varchar(255) DEFAULT NULL,
  changed_datetime datetime DEFAULT NULL
);

CREATE TABLE log_privilege_changes (
  username_changed varchar(255) DEFAULT NULL,
  privilege_changed int(11) DEFAULT NULL,
  privilege_action int(1) DEFAULT NULL,
  username_changed_by varchar(255) DEFAULT NULL,
  changed_datetime datetime DEFAULT NULL
);




DELIMITER $$
CREATE PROCEDURE `FindMissingData`()
BEGIN

DECLARE vSeasonYear INT(4);

TRUNCATE TABLE incomplete_records;
/*
Find new or incomplete competition records.
These records were inserted as part of the ETL job, but the league was not assigned, as it needs a user to confirm the values.
*/

INSERT INTO incomplete_records(record_type, source_id, source_value)
SELECT DISTINCT 'competition', id, competition_name
FROM competition_lookup
WHERE league_id IS NULL;

/*
Find new or incomplete teams
*/

INSERT INTO incomplete_records(record_type, source_id, source_value)
SELECT DISTINCT 'team', id, team_name
FROM team
WHERE club_id IS NULL;

END$$
DELIMITER ;
		       

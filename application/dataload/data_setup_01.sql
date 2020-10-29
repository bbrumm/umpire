#DROP DATABASE databas6;
#CREATE DATABASE databas6;

#dbunittest
#databas6


USE databas6;

SET collation_connection = 'utf8_general_ci';

ALTER DATABASE databas6 CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE password_reset_request (
  request_datetime datetime,
  activation_id varchar(200),
  ip_address varchar(50),
  user_name varchar(255),
  email_address varchar(255)
);

CREATE TABLE query_log (
id INT(10) AUTO_INCREMENT,
query_time DATETIME,
sql_query VARCHAR(2000),
query_params VARCHAR(2000),
PRIMARY KEY (id)
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


INSERT INTO umpire_users (id, user_name, user_email, user_password, first_name, last_name, activation_id, role_id, active)
VALUES
(2,'bbrumm','brummthecar@gmail.com','2395e5df2a7cb538bbcfff229d69109c','Ben','Brumm','Fgo5cVCehLPbtJq',1,1),
(3,'bbeveridge','jbbeveridge@bigpond.com','be07295bfc45fec5fb59b756bc8d811c','Brendan','Beveridge',NULL,2,1),
(4,'jhillgrove','Jock@aflbarwon.com.au','609e966a95797e8c3c6b0884fec99d9c','Jason','Hillgrove',NULL,2,1),
(5,'gmanager','genmanager@gful.com.au','50f4f05d34fb39a37e9a36f46fe70c5c','General','Manager',NULL,2,1),
(6,'dbaensch','None','8bf0963c46769aef353106188d58b172','Darren','Baensch',NULL,3,0),
(7,'skeating','stevekeating34@icloud.com','3b75bee32bcfcabab2bedcee8f377046','Steve','Keating',NULL,3,1),
(8,'hphilpott','None','ee699dc4fb87bb2c0b4ce86620eb79d0','Howard','Philpott',NULL,3,0),
(9,'rtrotter','None','99cd059274ee165fc9456879228f9042','Rohan','Trotter',NULL,3,0),
(10,'agrant','None','06a1a8fc080ab3d201f505c9eff02b68','Alan','Grant',NULL,3,0),
(11,'chood','colin.hood@suncorp.com.au','694fce6651c2cd42ab0b359cd61d2aa7','Colin','Hood',NULL,3,1),
(12,'dsantospirito','None','79d8f386333d6e4b8ed4e58180cd859b','Darren','Santospirito',NULL,3,1),
(13,'tbrooks','None','a95f49876d6b63abc33072d0017ab443','Tony','Brooks',NULL,4,0),
(14,'aedwick','None','0e95171afd8a31c536cc5c191cc85863','Adam','Edwick',NULL,4,1),
(15,'kmcmaster','None','eb8ee9966a2525291271ca667f0269fe','Kevin','McMaster',NULL,3,0),
(16,'ldonohue','None','fa91941aa51ea9d0512a4bb0f55984f1','Larry','Donohue',NULL,3,0),
(17,'dreid','reid.davin.d@edumail.vic.gov.au','7bade10f6d21fa03baa90c8587da57da','Davin','Reid',NULL,4,0),
(18,'kclissold','kelvin@clissold.id.au','700307a755666ab878cd4bafcc2203ad','Kel','Clissold',NULL,4,1),
(19,'rsteel','rvnsteel@bigpond.com.au','2ae60e9eba9d971008b7c6bcf6723209','Robert','Steel',NULL,4,1),
(21,'bsmith','None','0d4cebded2367e6503125cd0f4cf914e','Brad','Smith',NULL,4,1),
(24, 'bbrummtest', 'brummthecar@gmail.com', MD5('test'), 'Ben', 'Brumm', 'odH07igcAaKqyxP', 2, 1),
(25, 'bbtest2', 'None', '81dc9bdb52d04dc20036dbd8313ed055', 'bb1F', 'bb1L', 'D38wdVGvi4p5rzy', 3, 1),
(31, 'adame', 'None', 'none', 'Adam', 'Edwick', NULL, 4, 1),
(41, 'mdavison', 'None', 'TBC', 'Mark', 'Davison', NULL, 4, 1),
(42, 'ljohnston', 'None', 'TBC', 'Leigh', 'Johnston', NULL, 4, 1);







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
VALUES (278,2,31),(279,3,31),(280,13,2),(281,13,6),(282,13,8),(283,13,9),(284,13,10),(285,13,11),(286,13,12),(287,13,13),(288,13,14),(289,13,15),(290,13,16),(291,13,18),(292,13,20),(293,13,21),
(294,13,22),(295,13,23),(296,13,24),(297,13,25),(298,13,26),(299,13,28),(300,13,29),(301,13,30),(302,13,7),(303,13,17),(304,13,19),(305,13,32),(306,13,27),(307,14,2),(308,14,6),(309,14,7),(310,14,8),
(311,14,9),(312,14,10),(313,14,11),(314,14,12),(315,14,13),(316,14,14),(317,14,15),(318,14,16),(319,14,17),(320,14,18),(321,14,19),(322,14,20),(323,14,21),(324,14,22),(325,14,23),(326,14,24),
(327,14,25),(328,14,26),(329,14,27),(330,14,28),(331,14,29),(332,14,30),(333,14,32),(334,17,2),(335,17,6),(336,17,7),(337,17,8),(338,17,9),(339,17,10),(340,17,11),(341,17,12),(342,17,13),(343,17,14),
(344,17,15),(345,17,16),(346,17,17),(347,17,18),(348,17,19),(349,17,20),(350,17,21),(351,17,22),(352,17,23),(353,17,24),(354,17,25),(355,17,26),(356,17,27),(357,17,28),(358,17,29),(359,17,30),
(360,17,32),(361,18,2),(362,18,6),(477,18,7),
(363,18,25),(364,19,29),(365,19,13),(366,18,12),(367,19,24),(368,18,11),(369,19,10),(370,18,14),(371,18,10),(372,18,9),(373,18,17),(374,18,18),(375,18,19),(376,18,8),
(377,18,21),(378,20,26),(379,18,23),(380,20,21),(381,20,19),(382,18,26),(383,20,16),(384,18,28),(385,18,29),(386,18,30),(387,18,32),
(388,19,2),(389,19,6),(390,19,7),(391,19,8),(392,19,34),(394,19,11),(395,19,12),(396,19,23),(397,19,14),(398,19,15),(399,19,16),(400,19,17),(401,19,18),(402,19,19),(403,19,20),(404,19,21),(405,19,22),(410,19,27),(411,19,28),(412,19,9),(413,19,30),(414,19,32),(421,19,33),(447,19,26),
(393,18,33),(406,18,24),(407,18,22),(408,18,20),(409,18,16),
(415,20,2),(416,20,6),(417,20,7),(418,20,8),(419,20,9),(420,20,10),
(422,20,11),(423,17,34),(424,20,12),(425,17,33),(426,20,13),
(427,14,34),(428,20,14),(429,14,33),(430,20,15),(431,13,34),(432,18,27),(433,20,17),(434,20,18),(435,18,15),(436,20,20),(437,18,34),(438,20,22),(439,20,23),(440,20,24),(441,20,25),(442,18,13),
(443,20,27),(444,20,28),(445,13,33),(446,20,29),(448,20,30),(449,19,25),(450,20,32),
(451,41,2),
(452,41,6),
(453,41,7),
(454,41,8),
(455,41,34),
(456,41,11),
(457,41,12),
(458,41,23),
(459,41,14),
(461,41,15),
(462,41,16),
(463,41,17),
(464,41,18),
(465,41,19),
(466,41,20),
(467,41,21),
(468,41,22),
(469,41,27),
(471,41,28),
(472,41,9),
(473,41,30),
(474,41,32),
(475,41,33),
(476,41,26),
(478,41,24),
(479,41,25),
(480,41,10),
(481,41,13),
(482,41,29)

;




INSERT INTO user_permission_selection
VALUES (200,4,31),(201,5,31),(202,7,31),(203,11,31),(204,12,31),(205,24,31),
(100, 21, 29),
(101, 21, 13),
(102, 21, 24),
(103, 21, 10),
(104, 21, 2),
(105, 21, 6),
(106, 21, 7),
(107, 21, 8),
(108, 21, 34),
(109, 21, 11),
(110, 21, 12),
(111, 21, 23),
(112, 21, 14),
(113, 21, 15),
(114, 21, 16),
(115, 21, 17),
(116, 21, 18),
(117, 21, 19),
(118, 21, 20),
(119, 21, 21),
(120, 21, 22),
(121, 21, 27),
(122, 21, 28),
(123, 21, 9),
(124, 21, 30),
(125, 21, 32),
(126, 21, 33),
(127, 21, 26),
(128, 21, 25);

INSERT INTO user_permission_selection (user_id, permission_selection_id) VALUES
(31, 2),
(31, 6),
(31, 7),
(31, 8),
(31, 9),
(31, 10),
(31, 11),
(31, 12),
(31, 13),
(31, 14),
(31, 15),
(31, 16),
(31, 17),
(31, 18),
(31, 19),
(31, 20),
(31, 21),
(31, 22),
(31, 23),
(31, 24),
(31, 25),
(31, 26),
(31, 27),
(31, 28),
(31, 29),
(31, 30),
(31, 32),
(31, 33),
(31, 34);


INSERT INTO user_permission_selection (user_id, permission_selection_id) VALUES
(42, 2),
(42, 6),
(42, 7),
(42, 8),
(42, 9),
(42, 10),
(42, 11),
(42, 12),
(42, 13),
(42, 14),
(42, 15),
(42, 16),
(42, 17),
(42, 18),
(42, 19),
(42, 20),
(42, 21),
(42, 22),
(42, 23),
(42, 24),
(42, 25),
(42, 26),
(42, 27),
(42, 28),
(42, 29),
(42, 30),
(42, 32),
(42, 33),
(42, 34);



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

INSERT INTO report_selection_parameter_values VALUES (1,1,'Geelong',1),(2,1,'Colac',2),(3,2,'BFL',1),(4,2,'GFL',2),(5,2,'GDFL',3),(6,2,'GJFL',4),(7,2,'CDFNL',5),(8,3,'Field',1),(9,3,'Boundary',2),(10,3,'Goal',3),(11,4,'Seniors',1),(12,4,'Reserves',2),(13,4,'Colts',3),(14,4,'Under 17.5',10),(15,4,'Under 16',15),(16,4,'Under 14.5',20),(17,4,'Under 14',25),(18,4,'Under 12',30),(19,4,'Youth Girls',80),(20,4,'Junior Girls',90),(21,4,'Under 19 Girls',50),(22,4,'Under 15 Girls',60),(23,4,'Under 12 Girls',70),(24,2,'Women',6),(25,4,'Under 19',6),(26,4,'Under 17',12),(27,4,'Under 15',17),(28,4,'Under 13',27),(29,4,'Under 18 Girls',53),
(30, 4, 'Under 18', 9);



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

INSERT INTO season VALUES (1,2015),(2,2016),(3,2017),(4,2018),(5, 2019),(6,2020);

CREATE TABLE valid_selection_combinations (
  id int(11) DEFAULT NULL,
  pv_region_id int(11) DEFAULT NULL,
  pv_league_id int(11) DEFAULT NULL,
  pv_age_group_id int(11) DEFAULT NULL
);


INSERT INTO valid_selection_combinations VALUES (1,1,3,11),(2,1,3,12),(3,1,4,11),(4,1,4,12),(5,1,5,11),(6,1,5,12),(7,1,24,11),(8,1,24,12),(9,1,6,13),(10,1,6,15),(11,1,6,17),(12,1,6,18),(13,1,6,20),(14,1,6,19),(15,1,6,21),(16,1,6,22),(17,1,6,23),(18,2,7,11),(19,2,7,12),(20,2,7,14),(21,2,7,16),(22,1,6,25),(23,1,6,26),(24,1,6,27),(25,1,6,28),(26,1,6,29),
(27, 2, 7, 30),(28, 2, 7, 27),(29, 2, 7, 28),(30, 1, 6, 30);


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

CREATE TABLE report_5_columns (
	short_league_name VARCHAR(50),
	subtotal VARCHAR(50),
	region_name VARCHAR(50),
    sort_order INT(11)
);


INSERT INTO report_5_columns (short_league_name, subtotal, region_name, sort_order) VALUES
('BFL', 'Games', 'Geelong', 10),
('BFL', 'Total', 'Geelong', 20),
('BFL', 'Pct', 'Geelong', 30),
('GDFL', 'Games', 'Geelong', 40),
('GDFL', 'Total', 'Geelong', 50),
('GDFL', 'Pct', 'Geelong', 60),
('GFL', 'Games', 'Geelong', 70),
('GFL', 'Total', 'Geelong', 80),
('GFL', 'Pct', 'Geelong', 90),
('GJFL', 'Games', 'Geelong', 100),
('GJFL', 'Total', 'Geelong', 110),
('GJFL', 'Pct', 'Geelong', 120),
('Women', 'Games', 'Geelong', 130),
('Women', 'Total', 'Geelong', 140),
('Women', 'Pct', 'Geelong', 150),
('All', 'Total', 'Geelong', 160),
('CDFNL', 'Games', 'Colac', 500),
('CDFNL', 'Total', 'Colac', 510),
('CDFNL', 'Pct', 'Colac', 520),
('All', 'Total', 'Colac', 530);


COMMIT;

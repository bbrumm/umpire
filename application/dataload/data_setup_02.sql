#DROP DATABASE databas6;
#CREATE DATABASE databas6;

#dbunittest
#databas6


USE databas6;

SET collation_connection = 'utf8_general_ci';

ALTER DATABASE databas6 CHARACTER SET utf8 COLLATE utf8_general_ci;


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


INSERT INTO league VALUES (3,'AFL Barwon Blood Toyota Geelong FNL','AFL Barwon Blood Toyota Geelong FNL','GFL',1,1),(4,'AFL Barwon Buckleys Entertainment Centre Geelong FNL','AFL Barwon Buckleys Entertainment Centre Geelong FNL','GFL',2,1),(5,'AFL Barwon Dow Bellarine FNL','AFL Barwon Dow Bellarine FNL','BFL',1,1),(6,'AFL Barwon Buckleys Entertainment Centre Bellarine FNL','AFL Barwon Buckleys Entertainment Centre Bellarine FNL','BFL',2,1),(7,'AFL Barwon','AFL Barwon','GJFL',3,1),(8,'AFL Barwon','AFL Barwon','GJFL',4,1),(9,'GDFL Smiths Holden Cup','GDFL Smiths Holden Cup','GDFL',1,1),(10,'GDFL Buckleys Cup','GDFL Buckleys Cup','GDFL',2,1),(11,'AFL Barwon','AFL Barwon','GJFL',5,1),(12,'AFL Barwon','AFL Barwon','GJFL',6,1),(13,'AFL Barwon Buckley\'s Cup','AFL Barwon Buckley\'s Cup','GJFL',7,1),(14,'AFL Barwon Home Hardware Cup','AFL Barwon Home Hardware Cup','GJFL',8,1),(15,'AFL Barwon Geelong Advertiser Cup','AFL Barwon Geelong Advertiser Cup','GJFL',9,1),(16,'AFL Barwon Geelong Tech Centre Cup','AFL Barwon Geelong Tech Centre Cup','GJFL',10,1),(17,'AFL Barwon Coca Cola Cup','AFL Barwon Coca Cola Cup','GJFL',11,1),(18,'AFL Barwon Kempe Cup','AFL Barwon Kempe Cup','GJFL',12,1),(19,'AFL Barwon Buckley\'s Cup','AFL Barwon Buckley\'s Cup','GJFL',13,1),(20,'AFL Barwon GMHBA Cup','AFL Barwon GMHBA Cup','GJFL',14,1),(21,'AFL Barwon Supatramp Cup','AFL Barwon Supatramp Cup','GJFL',15,1),(22,'AFL Barwon Geelong Advertiser Cup','AFL Barwon Geelong Advertiser Cup','GJFL',16,1),(23,'AFL Barwon Red Onion Cup','AFL Barwon Red Onion Cup','GJFL',17,1),(24,'AFL Barwon','AFL Barwon','GJFL',18,1),(25,'AFL Barwon','AFL Barwon','GJFL',19,1),(26,'AFL Barwon KRock Cup','AFL Barwon KRock Cup','GJFL',20,1),(27,'AFL Barwon Bendigo Bank Cup','AFL Barwon Bendigo Bank Cup','GJFL',21,1),(28,'AFL Barwon Corio Bay Health Group Cup','AFL Barwon Corio Bay Health Group Cup','GJFL',22,1),(29,'AFL Barwon Corio Bay Health Group Cup','AFL Barwon Corio Bay Health Group Cup','GJFL',23,1),(30,'CDFNL Seniors','CDFNL Seniors','CDFNL',1,2),(31,'CDFNL Reserves','CDFNL Reserves','CDFNL',2,2),(32,'CDFNL Under 17.5','CDFNL Under 17.5','CDFNL',24,2),(33,'CDFNL Under 14.5','CDFNL Under 14.5','CDFNL',25,2),(35,'AFL Barwon - Buckley\'s Bellarine FNL Reserves','AFL Barwon - Buckley\'s Bellarine FNL Reserves','BFL',2,1),(36,'AFL Barwon','AFL Barwon','GJFL',26,1),(37,'AFL Barwon Walpole Shield','AFL Barwon Walpole Shield','GJFL',26,1),(38,'AFL Barwon','AFL Barwon','GJFL',12,1),(39,'AFL Barwon','AFL Barwon','GJFL',13,1),(40,'AFL Barwon','AFL Barwon','GJFL',14,1),(41,'AFL Barwon','AFL Barwon','GJFL',15,1),(42,'AFL Barwon','AFL Barwon','GJFL',16,1),(43,'AFL Barwon','AFL Barwon','GJFL',17,1),(44,'AFL Barwon','AFL Barwon','GJFL',27,1),(45,'AFL Barwon','AFL Barwon','GJFL',7,1),(46,'AFL Barwon','AFL Barwon','GJFL',8,1),(47,'AFL Barwon','AFL Barwon','GJFL',9,1),(48,'AFL Barwon','AFL Barwon','GJFL',10,1),(49,'AFL Barwon','AFL Barwon','GJFL',11,1),(50,'AFL Barwon','AFL Barwon Corio Bay Health Group','GJFL',18,1),(51,'AFL Barwon','AFL Barwon','GJFL',29,1),(52,'AFL Barwon','AFL Barwon','GJFL',28,1),(53,'AFL Barwon','AFL Barwon','GJFL',30,1),(54,'AFL Barwon','AFL Barwon','GJFL',31,1),(55,'AFL Barwon','AFL Barwon','GJFL',32,1),(56,'AFL Barwon','AFL Barwon','GJFL',33,1),(57,'AFL Barwon','AFL Barwon','GJFL',34,1),(58,'AFL Barwon','AFL Barwon','GJFL',35,1),(59,'AFL Barwon','AFL Barwon','GJFL',36,1),(60,'AFL Barwon','AFL Barwon','CDFNL',24,2),(61,'AFL Barwon','AFL Barwon','CDFNL',1,2),(62,'AFL Barwon','AFL Barwon','CDFNL',25,2),(64,'AFL Barwon','AFL Barwon','Women',1,1),(65,'AFL Barwon','AFL Barwon','Women',42,1),(66,'AFL Barwon','AFL Barwon','GJFL',43,1),(67,'AFL Barwon','AFL Barwon','GJFL',44,1),(68,'AFL Barwon','AFL Barwon','GJFL',45,1),(69,'AFL Barwon','AFL Barwon','GJFL',46,1),(70,'AFL Barwon','AFL Barwon','GJFL',47,1),(71,'AFL Barwon','AFL Barwon','Women',48,1),(72,'AFL Barwon','AFL Barwon','Women',49,1),(73,'AFL Barwon','AFL Barwon','GJFL',50,1),(74,'AFL Barwon','AFL Barwon','GJFL',51,1),(75,'AFL Barwon','AFL Barwon','GJFL',52,1),(76,'AFL Barwon','AFL Barwon','GJFL',53,1),(77,'AFL Barwon','AFL Barwon','GJFL',54,1),(78,'AFL Barwon','AFL Barwon','GJFL',55,1),(79,'AFL Barwon','AFL Barwon','GJFL',56,1),(80,'AFL Barwon','AFL Barwon','GJFL',57,1),(81,'AFL Barwon','AFL Barwon','GJFL',58,1),(82,'AFL Barwon','AFL Barwon','GJFL',59,1),(83,'AFL Barwon','AFL Barwon','GJFL',60,1),(84,'AFL Barwon','AFL Barwon','GJFL',61,1),(87,'AFL Barwon','AFL Barwon','GJFL',62,1),(88,'AFL Barwon','AFL Barwon','GJFL',63,1),(89,'AFL Barwon','AFL Barwon','GJFL',64,1),(90,'AFL Barwon','AFL Barwon','GJFL',65,1),(91,'AFL Barwon','AFL Barwon','GJFL',66,1),(92,'AFL Barwon','AFL Barwon','GJFL',67,1),(93,'AFL Barwon','AFL Barwon','GJFL',68,1),(94,'AFL Barwon','AFL Barwon','GJFL',69,1),(95,'AFL Barwon','AFL Barwon','GJFL',70,1),(96,'AFL Barwon','AFL Barwon','GJFL',41,1),
(102, 'CDFNL Under 18', 'CDFNL Under 18', 'CDFNL', 71, 2);



CREATE TABLE team (
  ID int(11) NOT NULL AUTO_INCREMENT,
  team_name varchar(100) DEFAULT NULL COMMENT 'The team name within a club.',
  club_id int(11) DEFAULT NULL COMMENT 'The club that this team belongs to.',
  PRIMARY KEY (ID)
);

INSERT INTO team VALUES (1,'Anakie',63),(2,'Anglesea',64),(3,'Bannockburn',65),(4,'Bannockburn 1',65),(5,'Bannockburn 2',65),(6,'Barwon Heads',66),(7,'Bell Park',67),(8,'Bell Park 1',67),(9,'Bell Park 2',67),(10,'Bell Post Hill',68),(11,'Belmont Lions',69),(12,'Belmont Lions / Newcomb',70),(13,'Colac',71),(14,'Corio',72),(15,'Drysdale',73),(16,'Drysdale Bennett',74),(17,'Drysdale Byrne',75),(18,'Drysdale Eddy',76),(19,'Drysdale Hall',77),(20,'Drysdale Hector',78),(21,'Drysdale Hoyer',79),(22,'East Geelong',80),(23,'Geelong Amateur',81),(24,'Geelong Amateur 1',81),(25,'Geelong Amateur 2',81),(26,'Geelong West',82),(27,'Geelong West St Peters',83),(28,'Geelong West St Peters 1',83),(29,'Geelong West St Peters 2',83),(30,'Grovedale',84),(31,'Grovedale 1',84),(32,'Grovedale 2',84),(33,'Grovedale 3',84),(34,'Gwsp',85),(35,'Gwsp / Bannockburn',86),(36,'Inverleigh',87),(37,'Lara',88),(38,'Lara 1',88),(39,'Lara 2',88),(40,'Leopold',89),(41,'Leopold 1',89),(42,'Leopold 2',89),(43,'Modewarre',90),(44,'Newcomb',91),(45,'Newcomb Power',92),(46,'Newtown & Chilwell',93),(47,'Newtown & Chilwell 1',93),(48,'Newtown & Chilwell 2',93),(49,'North Geelong',94),(50,'North Shore',95),(51,'Ocean Grove',96),(52,'Ocean Grove 1',96),(53,'Ocean Grove 2',96),(54,'Ogcc 1',97),(55,'Ogcc 2',97),(56,'Ogcc 3',97),(57,'Portarlington',98),(58,'Queenscliff',99),(59,'South Barwon',100),(60,'South Barwon / Geelong Amateur',101),(61,'South Barwon 1',100),(62,'South Barwon 2',100),(63,'St Albans',102),(64,'St Albans Allthorpe',103),(65,'St Albans Reid',104),(66,'St Joseph\'s',105),(67,'St Joseph\'s 1',105),(68,'St Joseph\'s 2',105),(69,'St Joseph\'s 3',105),(70,'St Joseph\'s 4',105),(71,'St Joseph\'s Hill',106),(72,'St Joseph\'s Podbury',107),(73,'St Mary\'s',108),(74,'St Mary\'s 1',108),(75,'St Mary\'s 2',108),(76,'St Mary\'s 3',108),(77,'Thomson',109),(78,'Tigers Black',110),(79,'Tigers Gold',111),(80,'Torquay',112),(81,'Torquay 1',112),(82,'Torquay 2',112),(83,'Torquay Bumpstead',113),(84,'Torquay Coles',114),(85,'Torquay Dunstan',115),(86,'Torquay Jones',116),(87,'Torquay Nairn',117),(88,'Torquay Papworth',118),(89,'Torquay Pyers',119),(90,'Torquay Scott',120),(91,'Werribee Centrals',121),(92,'Winchelsea',122),(93,'Winchelsea / Grovedale',123),(94,'Birregurra',124),(95,'Lorne',125),(96,'Alvie',126),(97,'Apollo Bay',127),(98,'Colac Imperials',128),(99,'Irrewarra-beeac',129),(100,'Otway Districts',130),(101,'Simpson',131),(102,'South Colac',132),(103,'Western Eagles',133),(104,'Aireys Inlet',134),(105,'Ammos Blue',135),(106,'Ammos Green',135),(107,'Ammos White',135),(108,'Bannockburn / South Barwon',138),(109,'Barwon Heads Gulls',66),(110,'Barwon Heads Heads',66),(111,'Dragons',139),(112,'Drysdale 1',73),(113,'Drysdale 2',73),(114,'Drysdale Humphrey',73),(115,'Drysdale Mcintyre',73),(116,'Drysdale Mckeon',73),(117,'Drysdale Scott',73),(118,'Drysdale Smith',73),(119,'Drysdale Taylor',73),(120,'Drysdale Wilson',73),(121,'Eagles Black',140),(122,'Eagles Red',140),(123,'East Newcomb Lions',142),(124,'East Tigers',143),(125,'Flying Joeys',144),(126,'Gdfl Raiders',145),(127,'Grovedale Broad',84),(128,'Grovedale Ford',84),(129,'Grovedale Mcneel',84),(130,'Grovedale Waldron',84),(131,'Grovedale Williams',84),(132,'Grovedale Young',84),(133,'Lara Batman',88),(134,'Lara Flinders',88),(135,'Lara Hume',88),(136,'Leopold Brown',89),(137,'Leopold Dowsett',89),(138,'Leopold Ruggles',89),(139,'Lethbridge',146),(140,'Newtown & Chilwell Eagles',93),(141,'Ogcc Blue',97),(142,'Ogcc Orange',97),(143,'Ogcc Red',97),(144,'Ogcc White',97),(145,'Queenscliff Blue',99),(146,'Queenscliff Red',99),(147,'Roosters',147),(148,'Saints White',148),(149,'Seagulls',149),(150,'South Barwon Blue',100),(151,'South Barwon Red',100),(152,'South Barwon White',100),(153,'St Albans Butterworth',102),(154,'St Albans Grinter',102),(155,'St Albans Mcfarlane',102),(156,'St Albans Osborne',102),(157,'Surf Coast Suns',150),(158,'Teesdale Roos',151),(159,'Tigers',152),(160,'Torquay Boyse',112),(161,'Torquay Browning',112),(162,'Torquay Bruce',112),(163,'Torquay Coleman',112),(164,'Torquay Davey',112),(165,'Torquay Milliken',112),(166,'Torquay Stone',112),(167,'Torquay Watson',112),(168,'Winchelsea / Inverleigh',153),(169,'Lara 3',88),(170,'Geelong West Giants',82),(171,'Geelong West Giants 2',82),(172,'Modewarre / Grovedale',154),(173,'Geelong West Giants 1',82),(174,'Aireys Eels',134),(175,'Drysdale Butcher',73),(176,'Drysdale Grigg',73),(177,'Drysdale Richardson',73),(178,'Drysdale Wilton',73),(179,'Geelong West Giants / Newtown',82),(180,'Grovedale Smith',84),(181,'Modewarre / Winchelsea',90),(182,'Newtown & Chilwell 3',93),(183,'Portarlington Blue',98),(184,'Portarlington Red',98),(185,'St Joseph�s Hill',106),(186,'Swans',160),(187,'Swans Blue',160),(188,'Torquay Canning',112),(189,'Drysdale Ruggles',73),(190,'Geelong Amateur 3',81),(191,'Geelong Amateur Girls',81),(192,'Grovedale Dale',84),(193,'Grovedale Delaney',84),(194,'Little River',161),(195,'St Joseph�s Podbury',107),(196,'Swans Red',160),(197,'Swans White',160),(198,'Torquay Davies',112),(199,'Giants',162),(200,'Leaping Joeys',164),(201,'Ocean Grove Red',96),(202,'Ocean Grove White',96),(203,'Torquay Bayles',112),(204,'Eagles',140),(205,'Saints Blue',148),(206,'Spotswood',163),(207,'Lara Blue',88),(208,'Ocean Grove 3',96),(209,'Saints Green',148),(210,'Lara White',88),(211,'Grovedale Shiell',84),(212,'St Joseph\'s Jackman',105),(213,'Grovedale Fisher',84),(214,'Ocean Grove Blue',96),(215,'Leopold Pitt',89),(216,'Leopold Butteriss',89),(217,'Aireys Inlet Eels',134),(218,'Ammos Barton',135),(219,'Ammos Clark',135),(220,'Ammos Farrell',135),(221,'Ammos Hickey',135),(222,'Ammos Hunter',135),(223,'Ammos Lovick',135),(224,'Ammos Mcgrath',135),(225,'Ammos Mcsparron',135),(226,'Ammos Young',135),(227,'Bannockburn Tigers',65),(228,'Bannockburn Tigers Giles',65),(229,'Bannockburn Tigers Hickleton',65),(230,'Bannockburn Tigers Taylor',65),(231,'Barwon Heads Seagals',66),(232,'Bell Post Hill / Bannockburn',167),(233,'Belmont Power',168),(234,'Drysdale Colley',73),(235,'Drysdale Inglis',73),(236,'Drysdale Nelis',73),(237,'Drysdale Preece',73),(238,'Drysdale Timmins',73),(239,'East Geelong / Geelong West Giants',169),(240,'Grovedale / South Barwon',170),(241,'Grovedale / St Albans',171),(242,'Inverleigh Hawks',87),(243,'Lara Cook',88),(244,'Lara Founds',88),(245,'Lara Gillett',88),(246,'Lara Jenkins',88),(247,'Lara Mathieson',88),(248,'Lara Orr',88),(249,'Lara Smith',88),(250,'Lara Wilson',88),(251,'Leopold Hughes',89),(252,'Leopold Quinlan',89),(253,'North Shore / Geelong West Giants',172),(254,'South Barwon Swans',100),(255,'St Josephs',105),(256,'St Josephs 1',105),(257,'St Josephs 2',105),(258,'St Josephs 3',105),(259,'St Josephs Hill',105),(260,'St Josephs Podbury',105),(261,'St Mary\'s Dunstan',108),(262,'St Mary\'s Mcmahon',108),(263,'St Mary\'s Redden',108),(264,'St Mary\'s Rodgers',108),(265,'Thomson East',109),(266,'Torquay Davie',112),(267,'Ammos Harwood',135),(268,'Drysdale Hildebrand',73),(269,'Grovedale Ross',84),(270,'St Albans/newtown & Chilwell',173),(271,'St Mary\'s Digby',108),(272,'St Mary\'s Dobbyn',108),(273,'St Mary\'s Hosking',108),(274,'St Mary\'s Johnson',108),(275,'St Mary\'s Turnley',108);


INSERT INTO team (id, team_name, club_id) VALUES
(291, 'Ammos Tenace', 181),
(301, 'Ammos Westwood', 191),
(311, 'Bannockburn Arklay', 201),
(321, 'Bannockburn Giles', 211),
(331, 'Bannockburn Hickleton', 221),
(341, 'Bannockburn Taylor', 231),
(351, 'Bannockburn Turnley', 241),
(361, 'Barwon Heads Blue', 251),
(371, 'East Geelong Thomson', 261),
(381, 'Grovedale Kelly', 271),
(391, 'Grovedale Miers', 281),
(281, 'Grovedale Tigers', 84),
(401, 'Leopold A', 291),
(411, 'Leopold B', 301),
(421, 'Ncfnc Hall', 311),
(431, 'Ncfnc Harrington', 321),
(441, 'Ncfnc Harris', 331),
(451, 'Newcomb Lions', 341),
(461, 'Ogcc Dean', 351),
(471, 'Ogcc Pearson', 361),
(481, 'Ogcc Walter', 371),
(491, 'South Barwon Garvey', 381),
(501, 'South Barwon Holz', 391),
(511, 'South Barwon Mccann', 401),
(521, 'South Barwon Thompson', 411),
(531, 'South Barwon Tomkins', 421),
(541, 'St Joeys', 431),
(551, 'St Joeys 1', 441),
(561, 'St Joeys 2', 451),
(571, 'Torquay Miliken', 461),
(581, 'Ammos Abfalter', 471),
(591, 'Ammos Clarke', 481),
(601, 'Ammos Kershaw', 491),
(611, 'Ammos King', 501),
(621, 'Ammos Westy', 511),
(631, 'Anakie/north Shore', 521),
(641, 'Barwon Heads White', 531),
(651, 'Bell Park Burke', 541),
(661, 'Bell Park Jarvis', 551),
(671, 'Bell Park Lynch', 561),
(681, 'Bell Park Rizun', 571),
(691, 'Geelong West Giants Grey', 581),
(701, 'Geelong West Giants Orange', 591),
(711, 'Grovedale Hood', 601),
(721, 'Grovedale Jen', 611),
(731, 'Little River/anakie', 621),
(741, 'Ncfnc Alford', 631),
(751, 'Ncfnc Hyland', 641),
(761, 'Ncfnc Morrissy', 651),
(771, 'Ncfnc Orr', 661),
(781, 'Ncfnc Taylor', 671),
(791, 'Ogcc Every', 681),
(801, 'South Barwon Brebner', 691),
(811, 'South Barwon Corrigan', 701),
(821, 'South Barwon Stewart', 711),
(831, 'South Barwon Walerys', 721),
(841, 'St Albans King', 731),
(851, 'Winchelsea Blues', 741);

CREATE TABLE age_group_division (
  ID int(11) NOT NULL AUTO_INCREMENT,
  age_group_id int(11) DEFAULT NULL,
  division_id int(11) DEFAULT NULL,
  PRIMARY KEY (ID)
);


INSERT INTO age_group_division VALUES (1,1,4),(2,2,4),(3,3,5),(4,3,6),(5,4,5),(6,5,5),(7,4,7),(8,4,8),(9,4,9),(10,4,10),(11,4,11),(12,5,7),(13,5,8),(14,5,9),(15,5,10),(16,5,11),(17,5,12),(18,6,4),(19,7,4),(20,3,7),(21,3,8),(22,3,9),(23,3,10),(24,8,4),(25,9,4),(26,10,4),(27,5,13),(28,11,4),(29,12,4),(30,13,4),(31,12,7),(32,12,8),(33,12,5),(34,11,7),(35,11,8),(36,11,5),(37,14,4),(38,15,4),(39,16,4),(40,17,4),(41,18,4),(42,1,5),(43,17,5),(44,16,5),(45,15,5),(46,18,5),(47,14,5),(48,1,7),(49,1,8),(50,17,7),(51,17,8),(52,17,9),(53,17,10),(54,17,11),(55,17,12),(56,17,13),(57,16,7),(58,16,8),(59,16,9),(60,16,10),(61,16,11),(62,15,7),(63,15,8),(64,15,9),(65,15,10),(66,14,7),(67,14,8),(68,14,9),(69,18,7),(70,18,8),(71, 19, 4);


CREATE TABLE age_group (
  ID int(11) NOT NULL AUTO_INCREMENT,
  age_group varchar(20) DEFAULT NULL,
  display_order int(2) DEFAULT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO age_group VALUES (1,'Seniors',1),(2,'Reserves',2),(3,'Colts',3),(4,'Under 16',15),(5,'Under 14',25),(6,'Youth Girls',80),(7,'Junior Girls',90),
(8,'Under 17.5',10),(9,'Under 14.5',20),(10,'Under 12',30),(11,'Under 19 Girls',50),(12,'Under 15 Girls',60),
(13,'Under 12 Girls',70),(14,'Under 19',6),(15,'Under 17',12),(16,'Under 15',17),(17,'Under 13',27),(18,'Under 18 Girls',53),(19,'Under 18', 8);

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

INSERT INTO club (id, club_name) VALUES
(181, 'Ammos Tenace'),
(191, 'Ammos Westwood'),
(201, 'Bannockburn Arklay'),
(211, 'Bannockburn Giles'),
(221, 'Bannockburn Hickleton'),
(231, 'Bannockburn Taylor'),
(241, 'Bannockburn Turnley'),
(251, 'Barwon Heads Blue'),
(261, 'East Geelong Thomson'),
(271, 'Grovedale Kelly'),
(281, 'Grovedale Miers'),
(291, 'Leopold A'),
(301, 'Leopold B'),
(311, 'Ncfnc Hall'),
(321, 'Ncfnc Harrington'),
(331, 'Ncfnc Harris'),
(341, 'ewcomb Lions'),
(351, 'Ogcc Dean'),
(361, 'Ogcc Pearson'),
(371, 'Ogcc Walter'),
(381, 'South Barwon Garvey'),
(391, 'South Barwon Holz'),
(401, 'South Barwon Mccann'),
(411, 'South Barwon Thompson'),
(421, 'South Barwon Tomkins'),
(431, 'St Joeys'),
(441, 'St Joeys 1'),
(451, 'St Joeys 2'),
(461, 'Torquay Miliken'),
(471, 'Ammos Abfalter'),
(481, 'Ammos Clarke'),
(491, 'Ammos Kershaw'),
(501, 'Ammos King'),
(511, 'Ammos Westy'),
(521, 'Anakie/North Shore'),
(531, 'Barwon Heads White'),
(541, 'Bell Park Burke'),
(551, 'Bell Park Jarvis'),
(561, 'Bell Park Lynch'),
(571, 'Bell Park Rizun'),
(581, 'Geelong West Giants Grey'),
(591, 'Geelong West Giants Orange'),
(601, 'Grovedale Hood'),
(611, 'Grovedale Jen'),
(621, 'Little River/Anakie'),
(631, 'Ncfnc Alford'),
(641, 'Ncfnc Hyland'),
(651, 'Ncfnc Morrissy'),
(661, 'Ncfnc Orr'),
(671, 'Ncfnc Taylor'),
(681, 'Ogcc Every'),
(691, 'South Barwon Brebner'),
(701, 'South Barwon Corrigan'),
(711, 'South Barwon Stewart'),
(721, 'South Barwon Walerys'),
(731, 'St Albans King'),
(741, 'Winchelsea Blues');

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


create index idx_cl_league ON competition_lookup(league_id);
create index idx_cl_season ON competition_lookup(season_id);
create index idx_untm_matchid ON umpire_name_type_match(match_id);
create index idx_untm_untid ON umpire_name_type_match(umpire_name_type_id);


CREATE TABLE umpire_match_baseline (
  last_name varchar(100) DEFAULT NULL,
  first_name varchar(100) DEFAULT NULL,
  games_pre_2014 int(10) DEFAULT NULL,
  games_2014 int(10) DEFAULT NULL,
  games_2015 int(10) DEFAULT NULL,
  games_2016 int(10) DEFAULT NULL,
  games_2017 int(10) DEFAULT NULL,
  games_career_end2017 int(10) DEFAULT NULL,
  KEY idx_umb_fnln (last_name,first_name)
);


INSERT INTO umpire_match_baseline(last_name,first_name,games_pre_2014,games_2014,games_2015,games_2016,games_2017,games_career_end2017)
VALUES
('Abbott','Trevor',220,53,27,32,45,377),
('Abrehart','Jack',82,0,26,2,2,112),
('Abrehart','Jonathan',49,33,0,0,0,82),
('Abrehart','Thomas',51,26,21,2,2,102),
('Adams','Russell',0,0,0,0,4,4),
('Alizada','Javid',0,0,9,0,0,9),
('Allcorn','Mason',0,0,20,19,22,61),
('Allison','Daina',0,0,4,0,0,4),
('Allison','John',27,26,0,0,0,53),
('Amisse','Samira',0,0,0,0,24,24),
('Anderson','David',0,0,0,0,6,6),
('Anderson','Liam',0,0,0,0,4,4),
('Anderson','Nicholas',0,0,0,0,6,6),
('Anderson','Tyson',0,0,34,0,0,34),
('Angelevski','Peter',1,0,0,0,0,1),
('Archer','Wade',0,10,0,0,0,10),
('Armstrong','Bree',0,0,31,30,8,69),
('Armstrong','Dean',49,0,35,18,17,119),
('Armstrong','Halle',0,0,0,27,10,37),
('Armstrong','Wayne',0,0,0,0,22,22),
('Arnott','Grace',7,11,0,0,0,18),
('Arnott','Tim',144,22,3,0,0,169),
('Ashworth','Neil',0,0,0,0,29,29),
('Atkins','Sian',0,0,0,41,6,47),
('Atkinson','Jesse',0,0,0,28,40,68),
('Atkinson','Mike',0,0,0,12,0,12),
('Baensch','Darren',566,25,22,0,0,613),
('Baker-Brooks','Jake',220,41,34,28,40,363),
('Barker','Paris',0,0,0,0,18,18),
('Barmby','Matthew',24,22,27,0,0,73),
('Barnett','Brydon',0,15,19,25,27,86),
('Barrand','Michael',0,0,0,0,2,2),
('Bartlett','Patrick',0,0,23,37,1,61),
('Barton','Lawrie',33,23,20,0,0,76),
('Bayne','Angus',0,0,0,0,9,9),
('Beale','Louis',0,0,0,14,14,28),
('Beddison','Ian',188,18,40,41,43,330),
('Bee','Deniel',2,0,0,0,1,3),
('Belfrage','Hayley',0,18,13,10,0,41),
('Bell','Chris',0,0,6,10,3,19),
('Bell','Gwenda',224,31,31,34,33,353),
('Woolsey','Louise',42,34,32,29,28,165),
('Bennett','Ross',570,24,23,0,0,617),
('Benson','Oliver',24,28,24,0,0,76),
('Benstead','Jack',0,9,20,0,0,29),
('Beveridge','Brendan',516,45,15,31,32,639),
('Binyon','Callum',26,26,18,25,0,95),
('Birch','Luke',0,0,0,11,0,11),
('Bisinella','Tiana',0,17,22,35,22,96),
('Black','Tahlia',0,0,0,0,4,4),
('Blight','Simon',66,1,0,0,0,67),
('Bliss','Charlie',0,0,0,0,19,19),
('Boardman','Matthew',0,0,0,7,7,14),
('Bonney','Krystl-lee',0,0,9,0,0,9),
('Boom','Marcus',0,0,23,30,24,77),
('Boreland','Joshua',0,0,0,0,33,33),
('Boseley','Justin',0,0,29,13,9,51),
('Boss','Michael',0,0,0,7,0,7),
('Bothe','William',0,0,0,0,34,34),
('Bourne','Alex',0,0,2,0,0,2),
('Bowen','Gary',0,0,0,0,3,3),
('Bowen','Joshua',20,21,17,0,0,58),
('Boyd','Christopher',0,0,0,0,2,2),
('Brayshaw','James',24,27,20,24,26,121),
('Brew','Alana',0,0,22,19,25,66),
('Brew','Kaylie',0,0,0,0,19,19),
('Brice','Patrick',5,10,32,35,37,119),
('Briggs','Marcus',219,32,30,33,25,339),
('Brogden','Bailey',0,0,21,0,0,21),
('Brough','Jeff',0,0,0,0,7,7),
('Brown','Joshua',187,16,16,3,7,229),
('Buchanan','Mackenzie',11,5,0,0,0,16),
('Buckley','Matthew',68,12,13,12,14,119),
('Bullock','Lucy',0,27,29,0,0,56),
('Burke','Breanna',38,1,0,0,0,39),
('Burke','Luke',99,26,16,3,0,144),
('Burns','Hayden',0,0,22,24,30,76),
('Burns','J.e.burns',147,0,0,0,9,156),
('Burns','Jonathan',12,0,21,34,36,103),
('Burns','Lachlan',17,0,21,12,18,68),
('Burns','Noah',0,0,0,0,28,28),
('Bury','Nathan',4,41,29,26,19,119),
('Bushfield','Mark',296,0,2,0,0,298),
('Butcher','Samuel',24,0,19,0,0,43),
('Caccamo','Jordan',16,6,0,0,0,22),
('Cain','Jack',14,13,12,0,0,39),
('Callander','Timothy',167,12,8,17,22,226),
('Callegher','Claude',144,36,32,0,0,212),
('Campbell','Russell',126,33,37,39,36,271),
('Cannard','Patrick',7,19,25,24,26,101),
('Cannard','Stephanie',53,0,0,0,0,53),
('Canny','Scott',0,0,36,0,0,36),
('Carroll','Dale',242,28,26,0,0,296),
('Carruthers','Chris',105,20,14,22,19,180),
('Cass','John',0,0,0,0,3,3),
('Cattlin','Matthew',0,0,0,10,0,10),
('Chaston','David',560,10,20,21,19,630),
('Chaston','Sam',146,22,27,0,0,168),
('Christensen','Samuel',7,1,0,0,0,8),
('Clarke','Brody',91,16,0,0,0,107),
('Clemm','Daniel',28,0,0,10,0,38),
('Clissold','Kelvin',267,0,1,15,2,285),
('Coghill','Geoffrey',0,0,0,0,2,2),
('Cole','Lauren',119,0,8,0,0,127),
('Collard','Rhys',26,17,0,0,0,43),
('Connolly','Tom',0,0,0,0,20,20),
('Conway','Stephen',0,0,0,11,0,11),
('Courtney','Tom',0,0,11,15,13,39),
('Coutts','Bailey',29,28,21,18,28,124),
('Coxon','Xavier',0,0,0,0,2,2),
('Crapper','Josh',0,13,18,10,19,60),
('Crichton','Lewis',0,0,0,1,0,1),
('Crocker','Oscar',0,0,0,20,14,34),
('Crucitti','Jess',107,39,40,39,34,259),
('Cullen','Greg',374,1,15,24,42,456),
('Curnuck','Cameron',0,0,0,0,11,11),
('Curwood','Simon',20,11,0,0,0,31),
('D''Alessandro','Mark',0,0,6,62,73,141),
('Dahlhaus','Jay',0,0,13,27,11,51),
('Dallimore','Phillip',0,0,0,61,0,61),
('Danaher','Ryan',41,17,22,26,0,106),
('Davalga','Hollie',0,0,0,0,28,28),
('Davalga','Oscar',0,0,0,0,17,17),
('Davies','Darrell',16,34,38,22,52,162),
('Davies','Jarrod',66,15,12,0,0,93),
('Davison','Mark',56,35,23,38,57,209),
('Davison','Olivia',0,18,12,0,0,30),
('Dawes','Shaianne',144,28,2,0,0,174),
('De Been','Rebecca',7,12,10,14,22,65),
('De Man','Matthew',0,0,5,8,0,13),
('Dean','Darryl',24,8,10,12,14,68),
('Debeljuh','Natalie',0,0,20,38,0,58),
('Delahunty','Paul',78,39,37,23,0,177),
('Delaney','Emma',94,24,16,0,0,134),
('Denney','Joshua',0,4,5,8,0,17),
('Dickson','Eric',0,0,23,18,29,70),
('Dimitrievski','Christian',0,14,0,0,0,14),
('Dines','Ben',0,0,0,0,12,12),
('Dines','Jessica',0,0,0,0,17,17),
('Dixon','Scott',181,15,8,0,0,204),
('Dodds','Harry',0,0,0,0,48,48),
('Doherty','Nathan',0,12,0,0,0,12),
('Dolley','Chris',26,0,8,7,0,41),
('Doolan','Briane',48,18,21,15,0,102),
('Dorling','Daniel',419,32,35,31,36,553),
('Dougherty','Liam',0,0,0,0,16,16),
('Dowling','Ray',5,0,1,0,0,6),
('Draper','Flynn',0,21,20,0,0,41),
('Driessen','Adam',61,26,4,0,31,122),
('Driessen','Janice',0,3,0,0,0,3),
('Dudding','Ken',233,39,32,34,22,360),
('Durbridge','Jarvis',0,1,0,0,0,1),
('Dyer','Reuben',0,0,0,0,11,11),
('Eagle','Coby',0,2,0,0,0,2),
('Eden','Jack',63,32,0,0,0,95),
('Edwards','Callum',31,23,17,17,27,115),
('Edwards','Craig',10,0,0,2,15,27),
('Edwards','Lachlan',62,0,21,0,0,83),
('Edwick','Adam',767,5,13,4,5,794),
('Egan','Karissa',55,8,1,0,0,64),
('Andy Egberts','Jack',0,0,0,0,5,5),
('Elliott','Brodie',0,5,19,24,0,48),
('Elliott','Peter',199,50,47,49,49,394),
('Ellis','Liam',0,9,6,0,0,15),
('England','Bridget',36,10,10,4,0,60),
('Ennor','Jay',0,0,16,32,32,80),
('Espig','Cody',78,37,32,0,0,147),
('Evans','Angus',0,23,20,21,14,78),
('Evans','Darcy',0,0,0,0,3,3),
('Evans','Jack',0,0,0,0,2,2),
('Facey','Joshua',0,0,0,1,6,7),
('Facey','William',0,0,0,0,11,11),
('Fagan','Paul',0,0,7,0,0,7),
('Fanning','Murray',525,0,1,0,0,526),
('Farnsworth','Ben',211,4,0,0,0,215),
('Farrow','Tiffany',167,40,34,0,0,241),
('Farrow','Will',0,3,9,0,0,12),
('Faull','Liliana',0,0,0,0,7,7),
('Feaver','Marcus',257,0,33,0,0,290),
('Ferguson','Christopher',0,0,18,39,52,109),
('Filbay','Bridgette',0,0,0,0,6,6),
('Filiti','Wendy',140,9,0,0,0,149),
('Finn','Stephen',0,11,0,0,0,11),
('Finn','Zac',0,14,10,5,0,29),
('Fisher','Hamish',0,6,2,0,0,8),
('Flexman','Indy',0,10,0,0,0,10),
('Flexman','Jett',0,10,0,0,0,10),
('Florio','Francesco',0,0,0,0,5,5),
('Foley','Warren',13,0,0,11,31,55),
('Foran','Gabby',6,9,0,0,0,15),
('Ford','Julie',27,36,39,35,39,176),
('Formosa','Jason',342,20,26,24,21,433),
('Formosa','Laurie',397,20,12,4,0,433),
('Forssman','Bailey',0,12,0,0,0,12),
('Forssman','Brendan',46,18,0,0,0,64),
('Forssman','Hayden',0,8,0,0,0,8),
('Fox','Jake',0,0,0,0,23,23),
('Fox','Logan',0,0,0,0,20,20),
('Fraser','Wayne',157,16,23,29,20,245),
('Frazer','David',65,16,11,2,1,95),
('Fry','Adam',0,0,0,1,0,1),
('Gahan','Darren',402,33,25,38,33,531),
('Gahan','Jarryd',5,3,0,0,0,8),
('Gardiner','Laura',0,0,10,0,0,10),
('Geall','Cooper',0,0,0,0,28,28),
('Gee','Isaac',0,0,0,4,2,6),
('Gerdtz','Corey',0,32,0,0,0,32),
('Gerrard','Anthony',0,0,0,0,13,13),
('Giarrusso','Vito',158,12,0,0,0,170),
('Giles','Jack',0,0,0,6,23,29),
('Gorman','Tom',102,1,34,38,0,175),
('Gould','Kingsley',0,0,0,22,26,48),
('Gower','Harry',0,0,0,22,33,55),
('Graham','Jonah',0,0,30,24,0,54),
('Graham','Lachlan',64,42,14,0,0,120),
('Graham','Mark',0,0,0,1,0,1),
('Graham','Michael',48,41,39,36,34,198),
('Graham','Peter',0,0,0,0,10,10),
('Grandy','Ross',88,32,33,36,42,231),
('Grant','Allan',26,0,0,1,0,27),
('Gray','Mark',351,10,0,0,24,385),
('Gray','Mitch',387,0,33,24,19,463),
('Green','Jayson',0,0,0,0,40,40),
('Grills','Ethan',0,0,16,15,17,48),
('Grills','Jonathan',0,0,0,0,12,12),
('Grist','Lachlan',0,0,0,19,15,34),
('Grossman','Brydie',49,16,16,0,0,81),
('Grossman','Graham',179,45,11,0,18,253),
('Grossman','Paul',608,32,10,0,18,668),
('Grossman','Ryden',160,21,22,0,0,203),
('Groves','Sam',61,10,18,0,0,89),
('Grozdanovski','Peter',89,27,26,29,33,204),
('Grubb','Shaun',96,2,0,0,0,98),
('Guarnaccia','Steve',150,22,24,20,19,235),
('Guy','Andrew',106,44,40,46,38,274),
('Guy','Jaymee',87,23,0,2,20,132),
('Haines','Lachlan',0,0,0,12,15,27),
('Hamer','Lochlin',0,0,27,45,35,107),
('Hamill-Beach','Rhys',0,23,28,29,60,140),
('Hamilton','Brodey',82,21,42,47,37,229),
('Hamilton','Jake',0,18,19,18,22,77),
('Hamilton','Sabine',0,0,0,9,0,9),
('Harris','David',0,0,0,1,0,1),
('Harrison','Karlin',133,27,6,0,9,175),
('Harrison','William',399,19,17,8,29,472),
('Hastie','Jack',0,20,22,23,23,88),
('Hauenstein','Darren',0,0,0,1,0,1),
('Hauser-Teasdale','Christopher',0,0,0,7,0,7),
('Hawker','Nigel',11,15,0,0,0,26),
('Hay','Raymond',0,0,0,1,9,10),
('Helwig','Kieren',0,0,16,50,35,101),
('Henshaw','Brett',99,37,28,27,0,191),
('Hickey','Michaela',134,20,0,0,0,154),
('Hickey','Peter',0,0,0,0,3,3),
('Higgins','Jamieson',0,0,9,27,40,76),
('Higgins','Taylor',0,0,16,16,40,72),
('Hilder','John',509,20,20,27,27,603),
('Hill','Noah',9,0,0,0,12,21),
('Hillgrove','Jason',234,0,2,7,2,245),
('Hitchcock','Daniel',0,0,12,52,71,135),
('Hitchcock','Thomas',0,0,20,52,71,143),
('Hockey','Mark',155,0,0,14,13,182),
('Hodgart','Benjamin',0,0,0,0,17,17),
('Hodge','Callum',50,0,20,19,11,100),
('Hogan','Sean',0,14,0,0,0,14),
('Hollis','Paul',65,22,7,3,27,124),
('Holloway','Harrison',0,0,0,34,0,34),
('Holt','Marcus',44,19,13,15,17,108),
('Hood','Colin',3,1,3,0,0,7),
('Hooper','Noel',1326,72,66,54,71,1589),
('Horne','Cameron',128,0,14,0,0,142),
('Hose','Dale',58,39,38,41,41,217),
('House','David',533,39,41,26,36,675),
('Houtsma','Noah',0,0,0,1,0,1),
('Houtsma','Will',10,22,20,34,8,94),
('Howard','Mitchell',0,0,17,14,0,31),
('Howe','Cooper',0,0,15,0,0,15),
('Howe','Jasper',0,11,0,0,0,11),
('Howell-Pavia','Lewis',93,6,1,0,0,100),
('Hyatt','Aaron',4,4,1,0,0,9),
('Imbrogno','Nicholas',0,0,0,16,0,16),
('Irving','Levi',0,0,16,28,15,59),
('Jacimovic','Stephen',12,0,0,5,2,19),
('James','Joshua',0,0,0,32,26,58),
('Jamshidi','Obaidulla',0,13,4,4,0,21),
('Johnston','Jacquie',90,5,0,0,0,95),
('Jones','Adam',123,26,20,19,22,210),
('Jones','Christopher',0,0,24,45,59,128),
('Jones','Paul',13,12,12,16,15,68),
('Jones','Peter',431,4,0,0,0,435),
('Jones','Richard',158,0,34,41,37,270),
('Jones','Wayne',0,0,0,0,5,5),
('Jones-Murphy','Timothy',72,32,0,0,0,104),
('Jose','Connor',0,0,0,0,4,4),
('Juhasz','Steve',310,12,0,0,0,322),
('Karpala','Chas',0,12,2,0,0,14),
('Kaye','Harry',15,20,0,0,0,35),
('Kearney','Neil',488,45,46,35,35,649),
('Keating','Steve',265,0,16,16,11,308),
('Kelly','Brian',0,0,0,1,0,1),
('Kelly','Christopher',0,0,2,0,0,2),
('Kelly','Des',0,0,2,0,0,2),
('Kelly','Jack',0,0,2,0,0,2),
('Kennedy','Lloyd',224,21,20,28,17,310),
('Kent','Lachlan',0,0,5,11,0,16),
('Kerr','Craig',0,0,0,0,8,8),
('Kerr','William',0,0,0,22,18,40),
('Killick','Jayson',0,0,0,0,35,35),
('King','Adam',0,0,20,20,0,40),
('King','Cody',0,8,0,0,0,8),
('Kleidon','Jacob',0,9,14,14,0,37),
('Knight','Daniel',0,0,0,10,1,11),
('Knight','Linda',0,0,0,0,2,2),
('Knight','Rob',0,0,0,0,29,29),
('Knuckey','Wilson',0,0,14,0,0,14),
('Kocsi','Nicholas',76,0,0,9,0,85),
('Konzag','Ryan',0,0,0,0,6,6),
('Kosmetschke','Luke',122,29,40,19,1,211),
('Kozina','Josip',248,36,27,27,44,382),
('Krajnc','Jarrod',12,43,43,0,0,98),
('Kramme','Craig',0,0,0,0,16,16),
('Kramme','David',0,0,0,0,15,15),
('Kramme','Jayde',0,0,0,0,14,14),
('Kruger-Robinson','Stephen',0,16,0,0,0,16),
('Kupke','Ryan',0,0,0,16,0,16),
('Kupsch','Zachary',0,11,0,0,0,11),
('Laffy','Finn',0,0,0,11,14,25),
('Lane','Oscar',0,0,13,0,0,13),
('Langton','Jontee',0,0,16,18,20,54),
('Lawrence','Roy',536,34,46,53,63,732),
('Lee','Angus',0,0,27,0,0,27),
('Leiper','Calvin',0,0,8,0,0,8),
('Lever','Chris',0,15,22,8,0,45),
('Lewis','Ethan',0,10,0,0,0,10),
('Lewis','Keziah',0,11,0,0,0,11),
('Lillie','James',0,0,0,32,33,65),
('Lindsay','Dylan',0,0,0,0,19,19),
('Lloyd','Lachie',9,24,42,42,0,117),
('Lobbe','Dean',0,16,20,20,19,75),
('Looker','Darren',0,10,0,0,0,10),
('Looker','Joshua',0,45,0,0,0,45),
('Love','Anthony',333,28,0,0,0,361),
('Lowther','Callen',0,0,0,19,0,19),
('Lunt','Benjamin',0,11,38,30,31,110),
('Lunt','Deborah',0,0,0,23,16,39),
('Lunt','Jordan',0,19,30,24,17,90),
('Lynch','Ashliegh',0,0,0,26,31,57),
('Lynch','Cohen',0,0,0,0,34,34),
('Lyon','James',0,0,0,53,48,101),
('MacPherson','Pippa',0,0,0,5,0,5),
('Madden','Lachlan',48,20,0,0,0,68),
('Maddock','David',68,22,8,16,0,114),
('Maes','Jessie',38,0,8,0,0,46),
('Maher','Jarryd',0,0,4,0,0,4),
('Mahoney','Callum',0,0,0,2,8,10),
('Maiden','James',0,19,14,16,1,50),
('Maiden','Liam',17,15,0,0,0,32),
('Malcolm','Max',0,0,0,48,34,82),
('Maloney','Terry',469,33,23,26,31,582),
('Manning','James',0,9,0,21,0,30),
('Marshall','Kati',0,0,0,0,2,2),
('Martin','Christopher',293,0,41,43,35,412),
('Martin','Harvey',0,0,0,0,21,21),
('Martin','Lucy',0,0,17,11,0,28),
('Maynard','Brendan',0,8,0,0,0,8),
('Mazaraki','Oscar',0,0,0,0,15,15),
('McCarthy','Luke',0,17,9,4,0,30),
('McCollam','Zachary',0,3,14,20,0,37),
('McCosh','Jason',0,0,0,1,4,5),
('McCrae','Liliana',0,0,13,0,0,13),
('McDonald','Stephen',271,25,23,50,7,376),
('McDonald','Steven',625,28,34,21,21,729),
('McDowell','Paul',0,17,29,30,25,101),
('McElhinney','Paul',446,23,29,27,20,545),
('McGlade','Aaron',95,21,0,21,22,159),
('Mcgrath','Caleb',0,0,23,44,56,123),
('Mckee','Piers',0,0,0,0,33,33),
('McKenzie','John',184,35,0,0,0,219),
('Mckenzie','Molly',0,0,23,35,39,97),
('Mckenzie','Rodney',504,0,21,20,21,566),
('Mckew','Samuel',0,0,0,0,5,5),
('Mckinnon','Andrew',0,0,0,0,1,1),
('McLaurin','John',28,3,0,0,0,31),
('McLeod','Scott',112,0,0,0,0,112),
('McMahon','Chris',0,0,17,17,0,34),
('McMahon','Jack',0,0,0,0,12,12),
('McMahon','Joel',14,20,16,21,0,71),
('McMahon','Nicky',16,31,22,19,0,88),
('McMaster','Damian',591,14,21,18,20,664),
('McMillan','Craig',762,40,19,41,43,905),
('Meaney','Renee',40,15,0,0,0,55),
('Mensch','Baxter',0,8,0,0,0,8),
('Menzies','Hugh',0,0,0,14,24,38),
('Menzies','Raymond',392,0,0,0,0,392),
('Mewha','Gary',0,0,0,0,3,3),
('Millar','Neil',0,0,0,0,11,11),
('Miller','Ingrid',0,0,6,5,0,11),
('Milligan','James',120,21,22,16,24,203),
('Miocic','Max',0,0,0,0,16,16),
('Mirabile','Marissa',0,18,12,6,0,36),
('Mirabile','Salvatore',778,0,42,37,30,887),
('Mirabile','Sam',729,49,0,0,0,778),
('Mitchell','Harrison',0,0,8,0,0,8),
('Mitchell','Kenneth',0,0,0,2,0,2),
('Mitchell','Logan',0,0,12,0,0,12),
('Moerenhout','Samuel',156,41,0,39,37,273),
('Monk','Travis',0,2,0,0,0,2),
('Monteith','Oliver',17,0,0,16,0,33),
('Monteith','Rory',0,0,0,12,24,36),
('Moore','Taylor',36,16,0,31,34,117),
('Morgan','Harry',47,0,34,0,0,81),
('Morris','Justin',206,0,0,0,8,214),
('Morrison','Connor',0,14,25,31,0,70),
('Moyle','Aiden',0,0,0,21,20,41),
('Moyle','Hannah',0,0,0,22,0,22),
('Muraca','Elijah',0,0,14,15,0,29),
('Muraca','Jared',29,0,0,11,0,40),
('Muraca','Thomas',20,13,15,25,31,104),
('Murdoch','Michael',0,0,32,36,33,101),
('Murnane','Nicholas',20,0,24,26,0,70),
('Nelson','Arnika',0,0,0,13,0,13),
('Nelson','Regan',0,0,0,0,7,7),
('Neville','Rick',150,25,35,27,22,259),
('Neville','Timothy',164,34,15,0,0,213),
('Nicopoulos','Theo',0,0,22,12,0,34),
('Nisbet','William',0,0,0,1,2,3),
('Noble','Reece',0,0,24,16,12,52),
('Nolan','Mark',679,0,16,15,21,731),
('Nuessler','Peter',145,0,0,14,19,178),
('Nyatsanga','Tinashe',85,3,0,0,0,88),
('O''Connor','Dermott',0,0,0,24,12,36),
('O''Dwyer','Bernard',0,0,0,0,26,26),
('O''Leary','Michael',0,0,0,0,10,10),
('O''Neil','Kelly',37,0,30,33,0,100),
('O''Neill','Jack',54,23,26,24,31,158),
('Oggero','William',0,0,0,0,9,9),
('Oldfield','Craig',219,21,19,10,7,276),
('Osborne','Mark',896,35,35,36,10,1012),
('Ozols','Peter',0,0,0,0,3,3),
('Palmer','Anthony',35,24,29,15,47,150),
('Palmer','Jacob',0,0,22,18,14,54),
('Palmer','Scarlett',0,18,21,6,0,45),
('Parrello','Dean',243,9,17,24,10,303),
('Parrello','Will',17,0,0,8,0,25),
('Peart','Hannah',0,0,4,4,0,8),
('Peeler','Benjamin',0,0,0,30,34,64),
('Perry','Bianca',0,0,0,0,1,1),
('Phillips','Andy',82,20,23,35,33,193),
('Phillips','Corey',13,18,0,0,0,31),
('Phillips','Tarik',0,0,0,0,14,14),
('Philpott','Cheyenne',0,0,12,8,1,21),
('Philpott','Grant',3,1,19,7,0,30),
('Philpott','Howard',279,0,0,0,0,279),
('Place','Cameron',0,0,17,12,17,46),
('Platt','Cameron',17,23,31,33,0,104),
('Plumb','Kevin',38,0,0,0,18,56),
('Plumridge','David',192,43,26,49,53,363),
('Plumridge','Victoria',0,30,26,37,37,130),
('Podbury','Tom',0,0,0,0,8,8),
('Pokorny','Stan',734,0,0,0,0,734),
('Potter','Max',0,0,0,7,0,7),
('Powers','Shane',0,24,0,0,0,24),
('Pratt','Matthew',0,0,15,44,47,106),
('Pratt','Nicholas',0,0,0,0,18,18),
('Previti','Frank',16,10,30,28,27,111),
('Qiu','Amy',20,0,38,21,40,119),
('Quin','Andrew',145,0,3,0,17,165),
('Radosavljevic','Damian',0,0,32,0,0,32),
('Radosavljevic','Michael',0,0,21,0,0,21),
('Rae','Gabriel',0,0,0,21,25,46),
('Rafferty','Sean',528,0,0,0,32,560),
('Rakas-hoare','Brandon',0,0,0,0,13,13),
('Ramsay','Monique',0,0,0,0,18,18),
('Rankin','Bradley',0,0,0,0,10,10),
('Reed','Aaron',137,27,0,0,0,164),
('Reed','Greg',113,17,0,0,0,130),
('Reeves','Abbey',0,0,16,8,27,51),
('Reeves','Ethan',0,7,21,20,49,97),
('Reid','Davin',99,22,22,22,1,166),
('Richards','Matthew',0,17,17,4,0,38),
('Richardson','James',0,0,0,0,13,13),
('Richardson','Shane',159,35,27,28,32,281),
('Riches','Aaron',75,27,23,21,20,166),
('Riches','Benjamin',0,0,2,0,0,2),
('Richmond-Craig','Brandon',0,0,24,22,15,61),
('Ritchie','Cameron',0,0,0,0,27,27),
('Robbins','Dale',0,0,0,0,1,1),
('Robbins','Jasper',0,0,13,24,0,37),
('Roberts','Trae',10,0,23,31,31,95),
('Robertson','Angus',1,0,19,0,0,20),
('Robertson','Bernard',0,0,0,13,0,13),
('Robertson','Declan',0,7,8,0,0,15),
('Robertson','Joshua',97,24,21,24,20,186),
('Robinson','Daniel',0,14,35,41,40,130),
('Robinson','Graeme',66,0,27,39,44,176),
('Rockliff','Connor',0,0,26,23,0,49),
('Rodgers','Jerrison',0,0,0,11,0,11),
('Rollinson','Angus',67,13,0,0,0,80),
('Rolph','Caleb',0,0,3,0,0,3),
('Rooke','Lachlan',0,0,27,0,0,27),
('Ross','Will',0,20,27,29,22,98),
('Ross-watson','Nicholas',0,0,26,29,21,76),
('Royce','Jack',0,1,3,0,0,4),
('Rubini','Eddie',214,35,31,43,35,358),
('Russell','Logan',103,24,14,22,0,163),
('Ryan','Clayton',0,0,9,23,39,71),
('Ryan','Denis',0,0,7,9,16,32),
('Ryan','Jacinta',0,17,0,0,0,17),
('Sadler','Tanner',0,23,17,0,0,40),
('Saltalamacchia','Josh',0,20,33,26,0,79),
('Sammut','Jesse',0,13,20,35,30,98),
('Santospirito','Darren',441,0,1,0,0,442),
('Scarrott','John',0,0,0,0,2,2),
('Scherek','Chris',0,0,11,0,0,11),
('Schilder','Peter',439,45,35,30,34,583),
('Schintler','Christian',0,0,0,25,38,63),
('Schintler','Jesse',0,0,0,31,45,76),
('Scholes','Jett',18,3,0,0,0,21),
('Schroeder','Murray',0,7,0,0,0,7),
('Schroeder','Seamus',0,13,0,0,0,13),
('Scott','Albert',0,0,0,0,3,3),
('Scott','Leopold',0,0,0,0,5,5),
('Scott','Lionel',0,0,0,0,2,2),
('Seidel-davies','Heath',0,0,0,0,5,5),
('Serle','Lachlan',2,0,0,4,7,13),
('Serle','Penny',2,0,0,1,2,5),
('Shalley','Greg',0,0,0,0,3,3),
('Shannon','Cohen',87,35,27,21,41,211),
('Shannon','Lykeira',12,0,0,18,30,60),
('Sharma','Abhishek',0,11,18,0,0,29),
('Shaw','Hayden',0,15,0,0,0,15),
('Sheaf','Tyler',16,13,0,5,0,34),
('Shedden','Robert',23,38,40,34,31,166),
('Shewell','Tyler',16,10,0,0,0,26),
('Shiell','Tyson',0,0,20,28,45,93),
('Silo','Patrick',0,0,8,0,0,8),
('Simovic','Dragan',0,0,0,0,11,11),
('Sinclair','Jesse',0,0,0,13,11,24),
('Sirolli','Christopher',0,0,0,0,8,8),
('Skurrie','Nigel',109,0,8,0,0,117),
('Smith','Alastair',204,32,0,0,21,257),
('Smith','Angela',16,11,0,0,0,27),
('Smith','Bradley',356,21,24,24,22,447),
('Smith','Brayden',0,0,0,0,8,8),
('Smith','Harry',11,7,0,0,0,18),
('Sobh','Billy',0,14,6,3,0,23),
('Solly','Dane',26,19,0,0,0,45),
('Spalding','Wayne',14,23,16,0,0,53),
('Spasojevic','Caspar',0,0,15,0,0,15),
('Steel','Nicholas',188,0,0,40,2,230),
('Steel','Robert',376,46,45,42,0,509),
('Stephens','Jake',0,0,0,14,24,38),
('Stephenson','Adrian',340,18,19,11,0,388),
('Stephenson','James',385,23,0,0,0,408),
('Stephenson','Ray',770,0,0,0,0,770),
('Stevenson','Maurice',0,0,0,0,2,2),
('Stojanovic','Mitchell',0,16,0,0,0,16),
('Stokes','Rod',748,27,39,37,35,886),
('Stubbings','Eden',114,0,0,0,0,114),
('Susak','Michael',176,0,0,2,19,197),
('Sweet','Patrick',8,0,0,24,40,72),
('Sykstus','Kyle',0,0,0,16,31,47),
('Tarr','Alexandra',0,0,14,0,0,14),
('Tate','Benjamin',0,0,0,1,12,13),
('Tatnell','John',404,8,13,14,23,462),
('Tattersall','Austin',0,10,39,25,27,101),
('Tattersall','Spencer',0,0,0,17,6,23),
('Tevelein','Dean',0,0,0,0,4,4),
('Thomas','Blake',0,0,0,16,35,51),
('Thompson','Peter',34,43,42,44,37,200),
('Thornton','Jack',0,0,16,16,20,52),
('Timberlake','James',75,20,16,27,0,138),
('Timilsina','Saroj',0,0,0,0,5,5),
('Tingiri','Benjamin',0,0,0,0,26,26),
('Tingiri','Timothy',0,0,0,0,21,21),
('Tino','Nikolas',0,9,6,0,0,15),
('Tomkins','Stephen',0,0,0,0,24,24),
('Touwslager','Albert',561,39,35,33,36,704),
('Trotter','Paul',315,2,0,2,0,319),
('Trotter','Rohan',0,0,0,1,0,1),
('Tullis','Kelsey',78,23,6,29,22,158),
('Tullis','Mitchell',35,23,0,0,0,58),
('Underwood','Richard',280,23,18,20,9,350),
('Urban','Peter',495,31,27,31,33,617),
('Verdichizzi','Jess',139,11,21,21,13,205),
('Vergona','Francis',65,8,0,0,0,73),
('Ververs','Jack',454,12,11,0,0,477),
('Ververs','Trent',301,32,9,0,0,342),
('Villanti','John',663,38,35,26,0,762),
('Visintin','Jack',90,17,21,20,18,166),
('Visintin','Max',44,19,21,17,21,122),
('Waight','Jarrod',0,0,3,0,21,24),
('Wallis','Tomek',0,0,0,0,19,19),
('Walsh','Kieren',0,11,0,0,0,11),
('Walsh','Xavier',0,0,15,21,0,36),
('Ward','Erin',0,10,0,0,0,10),
('Warriner','Paul',129,22,33,0,0,184),
('Watson','Brock',0,0,0,16,15,31),
('Watson','Nick',71,0,26,22,22,141),
('Watson','Zachary',0,0,0,15,0,15),
('Webber','Brayden',0,0,11,12,11,34),
('Webber','Darcey',0,12,18,20,29,79),
('Weber','Joel',0,0,0,1,2,3),
('Weber','Travis',0,0,0,2,1,3),
('Wekwerth','Karen',40,23,29,27,5,124),
('Wells','Kaine',0,0,13,3,0,16),
('Welsh','Grant',0,0,0,0,23,23),
('Werry','Allan',443,0,25,24,24,516),
('West','David',147,2,0,0,0,149),
('West','Peter',342,19,6,19,22,408),
('Wheadon','Donald',0,0,0,0,1,1),
('Whelan','Gerard',0,0,0,23,31,54),
('Whitford','Keith',1,0,0,0,0,1),
('Wignall','Corey',0,0,0,13,0,13),
('Wilkes','Lachlan',0,0,18,22,12,52),
('Williams','Daniel',0,0,0,0,2,2),
('Williams','Josh',0,0,0,19,20,39),
('Williams','Robert',0,0,0,23,26,49),
('Williams','Susan',0,0,0,9,20,29),
('Williamson','Edward',0,0,0,1,0,1),
('Williamson','Paul',0,16,3,38,22,79),
('Witcombe','Matthew',0,0,14,0,0,14),
('Witham','Jye',0,21,21,20,29,91),
('Witten','Brendan',0,0,9,26,18,53),
('Wood','Shalia',38,32,19,28,16,133),
('Wood','Taleitha',11,0,11,13,21,56),
('Wurf','Jack',36,34,48,30,0,148),
('Young','Flynn',0,0,0,0,1,1),
('Young','Oscar',0,21,18,21,6,66),
('Zada','Jawad',0,0,13,0,0,13),
('Zarb','Jonathan',31,0,0,28,18,77),
('Anderson','Robert',0,0,0,0,0,0),
('Ash','Bailey',0,0,0,0,0,0),
('Auditore','Jonathan',0,0,0,0,0,0),
('Ball','Trevor',0,0,0,0,0,0),
('Barby','Ethan',0,0,0,0,0,0),
('Barnett','Charlie',0,0,0,0,0,0),
('Barnhart','Aidan',0,0,0,0,0,0),
('Belfield','Liam',0,0,0,0,0,0),
('Bell','Jack',0,0,0,0,0,0),
('Beste','Flynn',0,0,0,0,0,0),
('Bisinella','Alex',0,0,0,0,0,0),
('Blake','Max',0,0,0,0,0,0),
('Blake','Trent',0,0,0,0,0,0),
('Blyton','Ezekiel',0,0,0,0,0,0),
('Blyton','Melody',0,0,0,0,0,0),
('Boseley','Isaac',0,0,0,0,0,0),
('Boyd','Bruce',0,0,0,0,0,0),
('Boyd','Micheal',0,0,0,0,0,0),
('Breakey','Alistair',0,0,0,0,0,0),
('Brooks','Tony',0,0,0,0,0,0),
('Burgess','Christopher',0,0,0,0,0,0),
('Chandler','Jonathan',0,0,0,0,0,0),
('Crawford','Blake',0,0,0,0,0,0),
('Curran','Christopher',0,0,0,0,0,0),
('Curtis','Harper',0,0,0,0,0,0),
('Curtis','Tye',0,0,0,0,0,0),
('Dallimore','Zak',0,0,0,0,0,0),
('Davis','Emily',0,0,0,0,0,0),
('Davis','Thomas',0,0,0,0,0,0),
('De Leeuw','Nicholas',0,0,0,0,0,0),
('Deigan','Thomas',0,0,0,0,0,0),
('Dew','Sebastian',0,0,0,0,0,0),
('Dixon','Ben',0,0,0,0,0,0),
('Doak','Adrian',0,0,0,0,0,0),
('Dowling','Raymond',0,0,0,0,0,0),
('Egan','Alana',0,0,0,0,0,0),
('Egan','Christopher',0,0,0,0,0,0),
('Elliott','Jack',0,0,0,0,0,0),
('Ellis','Hudson',0,0,0,0,0,0),
('Elvey','Brendan',0,0,0,0,0,0),
('Evans','Douglas',0,0,0,0,0,0),
('Fiala','Giuseppe',0,0,0,0,0,0),
('Frampton','Jacob',0,0,0,0,0,0),
('Frampton','Thomas',0,0,0,0,0,0),
('Gardiner','Matthew',2,1,0,0,0,3),
('Gillespie','Catherine',0,0,0,0,0,0),
('Ginn','Peter',0,0,0,0,0,0),
('Golebiowski','Jemma',0,0,0,0,0,0),
('Green','Brian',0,0,0,0,0,0),
('Hall','Corvan',0,0,0,0,0,0),
('Hamblin','Daniel',0,0,0,0,0,0),
('Hanrahan','Ashley',0,0,0,0,0,0),
('Harbison','David',0,0,0,0,0,0),
('Harty','Lachlan',0,0,0,0,0,0),
('Hay','Tiffany',0,0,0,0,0,0),
('Hill','Noah',0,0,0,9,0,9),
('Hodge','Callum',23,27,20,19,11,100),
('Hickey','Gerard',0,0,0,0,0,0),
('Holmes-henley','Jackson',0,0,0,0,0,0),
('Ireland','Steve',0,0,0,0,0,0),
('Jarrett','Darian',0,0,0,0,0,0),
('Javid Alizada','Mohammad',0,0,0,0,0,0),
('Jawad Hassan Zada','Mohammad',0,0,0,0,0,0),
('Jenkin','Beau',0,0,0,0,0,0),
('Jenning','Jackson',0,0,0,0,0,0),
('Jones','Archer',0,0,0,0,0,0),
('King','Gavin',0,0,0,0,0,0),
('Knight','Adam',0,0,0,0,0,0),
('Leak','Mickayla',0,0,0,0,0,0),
('Leigh','Harrison',0,0,0,0,0,0),
('Lucas','Peter',0,0,0,0,0,0),
('Lyddy','Connor',0,0,0,0,0,0),
('Mahoney','Lachlan',0,0,0,0,0,0),
('Mahoney','Peter',0,0,0,0,0,0),
('Marshall','Adam',0,0,0,0,0,0),
('Marshall','Bernard',0,0,0,0,0,0),
('Martin','Angus',0,0,0,0,0,0),
('McCarney','Adam',0,0,0,0,0,0),
('McCosh','Emily',0,0,0,0,0,0),
('McCullagh','Liam',0,0,0,0,0,0),
('McDonald','Charlie',0,0,0,0,0,0),
('McNamara','Rory',0,0,0,0,0,0),
('Meehan','Hendrik',0,0,0,0,0,0),
('Menzies','Luke',0,0,0,0,0,0),
('Millard','Caleb',0,0,0,0,0,0),
('Monaghan','Jensen',0,0,0,0,0,0),
('Mooney','Nicholas',0,0,0,0,0,0),
('Moynahan','Ethan',0,0,0,0,0,0),
('Mulheron','Peter',0,0,0,0,0,0),
('Murnane','Lindsay',0,0,0,0,0,0),
('Neaves','Michael',0,0,0,0,0,0),
('Neil Anderson','Robert',0,0,0,0,0,0),
('Nowell','Zachary',0,0,0,0,0,0),
('Nuessler','Thomas',0,0,0,0,0,0),
('O''Neill','Charlie',0,0,0,0,0,0),
('O''Neill','Harry',0,0,0,0,0,0),
('O''Neill','Ruby',0,0,0,0,0,0),
('O''Regan','Khai',0,0,0,0,0,0),
('Overman','Oliver',0,0,0,0,0,0),
('Page','Frederick',0,0,0,0,0,0),
('Palmer','Tony',35,0,0,0,0,35),
('Parsons','Chris',0,0,0,0,0,0),
('Paul','John',0,0,0,0,0,0),
('Pearce','Alen',0,0,0,0,0,0),
('Peck','Jonathan',0,0,0,0,0,0),
('Perriss','Archimedes',0,0,0,0,0,0),
('Perriss','Luke',0,0,0,0,0,0),
('Perriss','Willoughby',0,0,0,0,0,0),
('Pompe','Rene',0,0,0,0,0,0),
('Pyke','Edison',0,0,0,0,0,0),
('Pyke','Ian',0,0,0,0,0,0),
('Redford','James',0,0,0,0,0,0),
('Rielly','Lucas',0,0,0,0,0,0),
('Robertson','David',0,0,0,0,0,0),
('Roberts','Trae',0,10,23,31,31,95),
('Rofe','William',0,0,0,0,0,0),
('Sanders','Martin',0,0,0,0,0,0),
('Scheitner','Amy',48,33,0,0,0,81),
('Serle','Damian',0,0,0,0,0,0),
('Sheridan','Kallum',0,0,0,0,0,0),
('Smith','Harley',0,0,0,0,0,0),
('Smith','Shakira',0,0,0,0,0,0),
('Stannard','Jacob',0,0,0,0,0,0),
('Stevenson','Casey',0,0,0,0,0,0),
('Stinchcombe','Hamish',0,0,0,0,0,0),
('Sweet','Patrick',0,0,8,24,40,72),
('Tebble','Raymond',0,0,0,0,0,0),
('Theodore','Gary',0,0,0,0,0,0),
('Thornton','Jesse',0,0,0,0,0,0),
('Troeth','Thomas',0,0,0,0,0,0),
('Trotter','Aaron',0,0,0,0,0,0),
('Van Kuringen','Andrew',0,0,0,0,0,0),
('Wessner','Karl',0,0,0,0,0,0),
('William','Kerr',0,0,0,0,0,0);



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

COMMIT;
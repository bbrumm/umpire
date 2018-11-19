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

#DROP DATABASE databas6;
#CREATE DATABASE databas6;

#dbunittest
#databas6


USE databas6;

SET collation_connection = 'utf8_general_ci';

ALTER DATABASE databas6 CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `backup_report_07_2018` (
  `umpire_type` varchar(100) DEFAULT NULL,
  `age_group` varchar(100) DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `short_league_name` varchar(200) DEFAULT NULL,
  `season_year` int(4) DEFAULT NULL,
  `age_sort_order` int(2) DEFAULT NULL,
  `league_sort_order` int(2) DEFAULT NULL,
  `umpire_count` varchar(100) DEFAULT NULL,
  `match_count` int(11) DEFAULT NULL
);


INSERT INTO `backup_report_07_2018` VALUES ('Boundary','Reserves','Geelong','BFL',2018,2,1,'3 Umpires',1),('Field','Reserves','Geelong','BFL',2018,2,1,'2 Umpires',2),('Field','Reserves','Geelong','BFL',2018,2,1,'3 Umpires',3),('Goal','Reserves','Geelong','BFL',2018,2,1,'2 Umpires',80),('Boundary','Seniors','Geelong','BFL',2018,1,1,'2 Umpires',19),('Boundary','Seniors','Geelong','BFL',2018,1,1,'3 Umpires',38),('Field','Seniors','Geelong','BFL',2018,1,1,'2 Umpires',8),('Field','Seniors','Geelong','BFL',2018,1,1,'3 Umpires',72),('Goal','Seniors','Geelong','BFL',2018,1,1,'2 Umpires',80),('Boundary','Reserves','Colac','CDFNL',2018,2,5,'3 Umpires',6),('Field','Reserves','Colac','CDFNL',2018,2,5,'3 Umpires',1),('Goal','Reserves','Colac','CDFNL',2018,2,5,'2 Umpires',65),('Boundary','Seniors','Colac','CDFNL',2018,1,5,'2 Umpires',6),('Boundary','Seniors','Colac','CDFNL',2018,1,5,'3 Umpires',50),('Field','Seniors','Colac','CDFNL',2018,1,5,'2 Umpires',19),('Field','Seniors','Colac','CDFNL',2018,1,5,'3 Umpires',56),('Goal','Seniors','Colac','CDFNL',2018,1,5,'2 Umpires',75),('Field','Under 14.5','Colac','CDFNL',2018,20,5,'2 Umpires',1),('Boundary','Under 17.5','Colac','CDFNL',2018,10,5,'2 Umpires',1),('Boundary','Under 17.5','Colac','CDFNL',2018,10,5,'3 Umpires',10),('Field','Under 17.5','Colac','CDFNL',2018,10,5,'2 Umpires',18),('Field','Under 17.5','Colac','CDFNL',2018,10,5,'3 Umpires',13),('Goal','Under 17.5','Colac','CDFNL',2018,10,5,'2 Umpires',7),('Boundary','Reserves','Geelong','GDFL',2018,2,3,'2 Umpires',2),('Boundary','Reserves','Geelong','GDFL',2018,2,3,'3 Umpires',4),('Field','Reserves','Geelong','GDFL',2018,2,3,'2 Umpires',59),('Field','Reserves','Geelong','GDFL',2018,2,3,'3 Umpires',1),('Goal','Reserves','Geelong','GDFL',2018,2,3,'2 Umpires',90),('Boundary','Seniors','Geelong','GDFL',2018,1,3,'2 Umpires',25),('Boundary','Seniors','Geelong','GDFL',2018,1,3,'3 Umpires',40),('Field','Seniors','Geelong','GDFL',2018,1,3,'2 Umpires',8),('Field','Seniors','Geelong','GDFL',2018,1,3,'3 Umpires',82),('Goal','Seniors','Geelong','GDFL',2018,1,3,'2 Umpires',90),('Boundary','Reserves','Geelong','GFL',2018,2,2,'3 Umpires',1),('Goal','Reserves','Geelong','GFL',2018,2,2,'2 Umpires',84),('Boundary','Seniors','Geelong','GFL',2018,1,2,'2 Umpires',35),('Boundary','Seniors','Geelong','GFL',2018,1,2,'3 Umpires',40),('Field','Seniors','Geelong','GFL',2018,1,2,'2 Umpires',4),('Field','Seniors','Geelong','GFL',2018,1,2,'3 Umpires',80),('Goal','Seniors','Geelong','GFL',2018,1,2,'2 Umpires',84),('Field','Under 13','Geelong','GJFL',2018,27,4,'2 Umpires',53),('Field','Under 13','Geelong','GJFL',2018,27,4,'3 Umpires',2),('Boundary','Under 15','Geelong','GJFL',2018,17,4,'3 Umpires',1),('Field','Under 15','Geelong','GJFL',2018,17,4,'2 Umpires',227),('Field','Under 15','Geelong','GJFL',2018,17,4,'3 Umpires',37),('Field','Under 15 Girls','Geelong','GJFL',2018,60,4,'2 Umpires',15),('Field','Under 15 Girls','Geelong','GJFL',2018,60,4,'3 Umpires',5),('Boundary','Under 17','Geelong','GJFL',2018,12,4,'2 Umpires',4),('Field','Under 17','Geelong','GJFL',2018,12,4,'2 Umpires',188),('Field','Under 17','Geelong','GJFL',2018,12,4,'3 Umpires',21),('Field','Under 18 Girls','Geelong','GJFL',2018,53,4,'2 Umpires',55),('Field','Under 18 Girls','Geelong','GJFL',2018,53,4,'3 Umpires',2),('Boundary','Under 19','Geelong','GJFL',2018,6,4,'2 Umpires',3),('Boundary','Under 19','Geelong','GJFL',2018,6,4,'3 Umpires',49),('Field','Under 19','Geelong','GJFL',2018,6,4,'2 Umpires',81),('Field','Under 19','Geelong','GJFL',2018,6,4,'3 Umpires',109),('Goal','Under 19','Geelong','GJFL',2018,6,4,'2 Umpires',185),('Field','Seniors','Geelong','Women',2018,1,6,'2 Umpires',69),('Goal','Seniors','Geelong','Women',2018,1,6,'2 Umpires',67);


COMMIT;
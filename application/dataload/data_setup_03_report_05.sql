#DROP DATABASE databas6;
#CREATE DATABASE databas6;

#dbunittest
#databas6

USE databas6;
SET collation_connection = 'utf8_general_ci';
ALTER DATABASE databas6 CHARACTER SET utf8 COLLATE utf8_general_ci;



CREATE TABLE `backup_report_05_2018` (
  `umpire_type` varchar(100) DEFAULT NULL,
  `age_group` varchar(100) DEFAULT NULL,
  `age_sort_order` int(2) DEFAULT NULL,
  `short_league_name` varchar(100) DEFAULT NULL,
  `league_sort_order` int(2) DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `match_no_ump` int(11) DEFAULT NULL,
  `total_match_count` int(11) DEFAULT NULL,
  `match_pct` int(11) DEFAULT NULL,
  `season_year` int(4) DEFAULT NULL
);



INSERT INTO `backup_report_05_2018` VALUES ('Boundary','Seniors',1,'GFL',1,'Geelong',0,84,0,2018),('Field','Seniors',1,'GFL',1,'Geelong',0,84,0,2018),('Goal','Seniors',1,'GFL',1,'Geelong',0,84,0,2018),('Boundary','Reserves',2,'GFL',1,'Geelong',83,84,98,2018),('Field','Reserves',2,'GFL',1,'Geelong',68,84,80,2018),('Goal','Reserves',2,'GFL',1,'Geelong',0,84,0,2018),('Boundary','Seniors',1,'BFL',2,'Geelong',0,80,0,2018),('Field','Seniors',1,'BFL',2,'Geelong',0,80,0,2018),('Goal','Seniors',1,'BFL',2,'Geelong',0,80,0,2018),('Boundary','Reserves',2,'BFL',2,'Geelong',77,80,96,2018),('Field','Reserves',2,'BFL',2,'Geelong',73,80,91,2018),('Goal','Reserves',2,'BFL',2,'Geelong',0,80,0,2018),('Boundary','Seniors',1,'GDFL',3,'Geelong',0,90,0,2018),('Field','Seniors',1,'GDFL',3,'Geelong',0,90,0,2018),('Goal','Seniors',1,'GDFL',3,'Geelong',0,90,0,2018),('Boundary','Reserves',2,'GDFL',3,'Geelong',79,90,87,2018),('Field','Reserves',2,'GDFL',3,'Geelong',17,90,18,2018),('Goal','Reserves',2,'GDFL',3,'Geelong',0,90,0,2018),('Boundary','Seniors',1,'CDFNL',4,'Colac',0,75,0,2018),('Field','Seniors',1,'CDFNL',4,'Colac',0,75,0,2018),('Goal','Seniors',1,'CDFNL',4,'Colac',0,75,0,2018),('Boundary','Reserves',2,'CDFNL',4,'Colac',73,80,91,2018),('Field','Reserves',2,'CDFNL',4,'Colac',78,80,97,2018),('Goal','Reserves',2,'CDFNL',4,'Colac',9,80,11,2018),('Boundary','Under 17.5',10,'CDFNL',4,'Colac',63,75,84,2018),('Field','Under 17.5',10,'CDFNL',4,'Colac',41,75,54,2018),('Goal','Under 17.5',10,'CDFNL',4,'Colac',67,75,89,2018),('Boundary','Under 14.5',20,'CDFNL',4,'Colac',75,75,100,2018),('Field','Under 14.5',20,'CDFNL',4,'Colac',74,75,98,2018),('Goal','Under 14.5',20,'CDFNL',4,'Colac',75,75,100,2018),('Boundary','Under 15 Girls',60,'GJFL',10,'Geelong',32,32,100,2018),('Field','Under 15 Girls',60,'GJFL',10,'Geelong',11,32,34,2018),('Goal','Under 15 Girls',60,'GJFL',10,'Geelong',32,32,100,2018),('Boundary','Seniors',1,'Women',10,'Geelong',79,79,100,2018),('Field','Seniors',1,'Women',10,'Geelong',5,79,6,2018),('Goal','Seniors',1,'Women',10,'Geelong',11,79,13,2018),('Boundary','Under 13',27,'GJFL',10,'Geelong',450,450,100,2018),('Field','Under 13',27,'GJFL',10,'Geelong',378,450,84,2018),('Goal','Under 13',27,'GJFL',10,'Geelong',450,450,100,2018),('Boundary','Under 15',17,'GJFL',10,'Geelong',353,354,99,2018),('Field','Under 15',17,'GJFL',10,'Geelong',61,354,17,2018),('Goal','Under 15',17,'GJFL',10,'Geelong',354,354,100,2018),('Boundary','Under 17',12,'GJFL',10,'Geelong',229,233,98,2018),('Field','Under 17',12,'GJFL',10,'Geelong',11,233,4,2018),('Goal','Under 17',12,'GJFL',10,'Geelong',233,233,100,2018),('Boundary','Under 18 Girls',53,'GJFL',10,'Geelong',73,73,100,2018),('Field','Under 18 Girls',53,'GJFL',10,'Geelong',12,73,16,2018),('Goal','Under 18 Girls',53,'GJFL',10,'Geelong',73,73,100,2018),('Boundary','Under 19',6,'GJFL',10,'Geelong',104,193,53,2018),('Field','Under 19',6,'GJFL',10,'Geelong',2,193,1,2018),('Goal','Under 19',6,'GJFL',10,'Geelong',6,193,3,2018);


COMMIT;
drop table if exists report_table;


CREATE TABLE report_table (
  report_table_id INT PRIMARY KEY,
  report_name VARCHAR(40),
  table_name VARCHAR(40)
);

INSERT INTO report_table (report_table_id, report_name, table_name) VALUES (1, '1', 'mv_report_01');

/*
UPDATE report_table SET report_name = 5 WHERE report_table_id = 1;
*/

DROP TABLE if exists report_column;

CREATE TABLE report_column (
  report_column_id INT PRIMARY KEY,
  column_name VARCHAR(200)
);


INSERT INTO report_column (report_column_id, column_name) VALUES (1,'BFL|Anglesea');
INSERT INTO report_column (report_column_id, column_name) VALUES (2,'BFL|Barwon_Heads');
INSERT INTO report_column (report_column_id, column_name) VALUES (3,'BFL|Drysdale');
INSERT INTO report_column (report_column_id, column_name) VALUES (4,'BFL|Geelong_Amateur');
INSERT INTO report_column (report_column_id, column_name) VALUES (5,'BFL|Modewarre');
INSERT INTO report_column (report_column_id, column_name) VALUES (6,'BFL|Newcomb_Power');
INSERT INTO report_column (report_column_id, column_name) VALUES (7,'BFL|Ocean_Grove');
INSERT INTO report_column (report_column_id, column_name) VALUES (8,'BFL|Portarlington');
INSERT INTO report_column (report_column_id, column_name) VALUES (9,'BFL|Queenscliff');
INSERT INTO report_column (report_column_id, column_name) VALUES (10,'BFL|Torquay');
INSERT INTO report_column (report_column_id, column_name) VALUES (11,'GDFL|Anakie');
INSERT INTO report_column (report_column_id, column_name) VALUES (12,'GDFL|Bannockburn');
INSERT INTO report_column (report_column_id, column_name) VALUES (13,'GDFL|Bell_Post_Hill');
INSERT INTO report_column (report_column_id, column_name) VALUES (14,'GDFL|Belmont_Lions');
INSERT INTO report_column (report_column_id, column_name) VALUES (15,'GDFL|Corio');
INSERT INTO report_column (report_column_id, column_name) VALUES (16,'GDFL|East_Geelong');
INSERT INTO report_column (report_column_id, column_name) VALUES (17,'GDFL|Geelong_West');
INSERT INTO report_column (report_column_id, column_name) VALUES (18,'GDFL|Inverleigh');
INSERT INTO report_column (report_column_id, column_name) VALUES (19,'GDFL|North_Geelong');
INSERT INTO report_column (report_column_id, column_name) VALUES (20,'GDFL|Thomson');
INSERT INTO report_column (report_column_id, column_name) VALUES (21,'GDFL|Werribee_Centrals');
INSERT INTO report_column (report_column_id, column_name) VALUES (22,'GDFL|Winchelsea');
INSERT INTO report_column (report_column_id, column_name) VALUES (23,'GFL|Bell_Park');
INSERT INTO report_column (report_column_id, column_name) VALUES (24,'GFL|Colac');
INSERT INTO report_column (report_column_id, column_name) VALUES (25,'GFL|Grovedale');
INSERT INTO report_column (report_column_id, column_name) VALUES (26,'GFL|Gwsp');
INSERT INTO report_column (report_column_id, column_name) VALUES (27,'GFL|Lara');
INSERT INTO report_column (report_column_id, column_name) VALUES (28,'GFL|Leopold');
INSERT INTO report_column (report_column_id, column_name) VALUES (29,'GFL|Newtown_&_Chilwell');
INSERT INTO report_column (report_column_id, column_name) VALUES (30,'GFL|North_Shore');
INSERT INTO report_column (report_column_id, column_name) VALUES (31,'GFL|South_Barwon');
INSERT INTO report_column (report_column_id, column_name) VALUES (32,'GFL|St_Albans');
INSERT INTO report_column (report_column_id, column_name) VALUES (33,'GFL|St_Joseph''s');
INSERT INTO report_column (report_column_id, column_name) VALUES (34,'GFL|St_Mary''s');
INSERT INTO report_column (report_column_id, column_name) VALUES (35,'None|Anakie');
INSERT INTO report_column (report_column_id, column_name) VALUES (36,'None|Anglesea');
INSERT INTO report_column (report_column_id, column_name) VALUES (37,'None|Bannockburn');
INSERT INTO report_column (report_column_id, column_name) VALUES (38,'None|Barwon_Heads');
INSERT INTO report_column (report_column_id, column_name) VALUES (39,'None|Bell_Park');
INSERT INTO report_column (report_column_id, column_name) VALUES (40,'None|Belmont_Lions_/_Newcomb');
INSERT INTO report_column (report_column_id, column_name) VALUES (41,'None|Belmont_Lions');
INSERT INTO report_column (report_column_id, column_name) VALUES (42,'None|Colac');
INSERT INTO report_column (report_column_id, column_name) VALUES (43,'None|Corio');
INSERT INTO report_column (report_column_id, column_name) VALUES (44,'None|Drysdale_Bennett');
INSERT INTO report_column (report_column_id, column_name) VALUES (45,'None|Drysdale_Byrne');
INSERT INTO report_column (report_column_id, column_name) VALUES (46,'None|Drysdale_Eddy');
INSERT INTO report_column (report_column_id, column_name) VALUES (47,'None|Drysdale_Hall');
INSERT INTO report_column (report_column_id, column_name) VALUES (48,'None|Drysdale_Hector');
INSERT INTO report_column (report_column_id, column_name) VALUES (49,'None|Drysdale');
INSERT INTO report_column (report_column_id, column_name) VALUES (50,'None|East_Geelong');
INSERT INTO report_column (report_column_id, column_name) VALUES (51,'None|Geelong_Amateur');
INSERT INTO report_column (report_column_id, column_name) VALUES (52,'None|Geelong_West_St_Peters');
INSERT INTO report_column (report_column_id, column_name) VALUES (53,'None|Grovedale');
INSERT INTO report_column (report_column_id, column_name) VALUES (54,'None|Gwsp_/_Bannockburn');
INSERT INTO report_column (report_column_id, column_name) VALUES (55,'None|Inverleigh');
INSERT INTO report_column (report_column_id, column_name) VALUES (56,'None|Lara');
INSERT INTO report_column (report_column_id, column_name) VALUES (57,'None|Leopold');
INSERT INTO report_column (report_column_id, column_name) VALUES (58,'None|Modewarre');
INSERT INTO report_column (report_column_id, column_name) VALUES (59,'None|Newcomb');
INSERT INTO report_column (report_column_id, column_name) VALUES (60,'None|Newtown_&_Chilwell');
INSERT INTO report_column (report_column_id, column_name) VALUES (61,'None|North_Geelong');
INSERT INTO report_column (report_column_id, column_name) VALUES (62,'None|North_Shore');
INSERT INTO report_column (report_column_id, column_name) VALUES (63,'None|Ocean_Grove');
INSERT INTO report_column (report_column_id, column_name) VALUES (64,'None|Ogcc');
INSERT INTO report_column (report_column_id, column_name) VALUES (65,'None|Portarlington');
INSERT INTO report_column (report_column_id, column_name) VALUES (66,'None|Queenscliff');
INSERT INTO report_column (report_column_id, column_name) VALUES (67,'None|South_Barwon_/_Geelong_Amateur');
INSERT INTO report_column (report_column_id, column_name) VALUES (68,'None|South_Barwon');
INSERT INTO report_column (report_column_id, column_name) VALUES (69,'None|St_Albans_Allthorpe');
INSERT INTO report_column (report_column_id, column_name) VALUES (70,'None|St_Albans_Reid');
INSERT INTO report_column (report_column_id, column_name) VALUES (71,'None|St_Albans');
INSERT INTO report_column (report_column_id, column_name) VALUES (72,'None|St_Joseph''s_Hill');
INSERT INTO report_column (report_column_id, column_name) VALUES (73,'None|St_Joseph''s_Podbury');
INSERT INTO report_column (report_column_id, column_name) VALUES (74,'None|St_Joseph''s');
INSERT INTO report_column (report_column_id, column_name) VALUES (75,'None|St_Mary''s');
INSERT INTO report_column (report_column_id, column_name) VALUES (76,'None|Tigers_Gold');
INSERT INTO report_column (report_column_id, column_name) VALUES (77,'None|Torquay_Bumpstead');
INSERT INTO report_column (report_column_id, column_name) VALUES (78,'None|Torquay_Coles');
INSERT INTO report_column (report_column_id, column_name) VALUES (79,'None|Torquay_Dunstan');
INSERT INTO report_column (report_column_id, column_name) VALUES (80,'None|Torquay_Jones');
INSERT INTO report_column (report_column_id, column_name) VALUES (81,'None|Torquay_Nairn');
INSERT INTO report_column (report_column_id, column_name) VALUES (82,'None|Torquay_Papworth');
INSERT INTO report_column (report_column_id, column_name) VALUES (83,'None|Torquay_Pyers');
INSERT INTO report_column (report_column_id, column_name) VALUES (84,'None|Torquay_Scott');
INSERT INTO report_column (report_column_id, column_name) VALUES (85,'None|Torquay');
INSERT INTO report_column (report_column_id, column_name) VALUES (86,'None|Werribee_Centrals');
INSERT INTO report_column (report_column_id, column_name) VALUES (87,'None|Winchelsea_/_Grovedale');
INSERT INTO report_column (report_column_id, column_name) VALUES (88,'None|Winchelsea');



DROP TABLE if exists report_column_lookup_display;

CREATE TABLE report_column_lookup_display (
  report_column_lookup_display_id INT PRIMARY KEY,
  report_column_id INT,
  column_display_filter_name VARCHAR(200),
  column_display_name VARCHAR(200),
  FOREIGN KEY (report_column_id) REFERENCES report_column(report_column_id) ON DELETE CASCADE
);



INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (1, 1, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (2, 2, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (3, 3, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (4, 4, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (5, 5, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (6, 6, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (7, 7, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (8, 8, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (9, 9, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (10, 10, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (11, 11, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (12, 12, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (13, 13, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (14, 14, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (15, 15, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (16, 16, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (17, 17, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (18, 18, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (19, 19, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (20, 20, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (21, 21, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (22, 22, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (23, 23, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (24, 24, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (25, 25, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (26, 26, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (27, 27, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (28, 28, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (29, 29, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (30, 30, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (31, 31, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (32, 32, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (33, 33, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (34, 34, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (35, 35, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (36, 36, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (37, 37, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (38, 38, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (39, 39, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (40, 40, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (41, 41, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (42, 42, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (43, 43, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (44, 44, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (45, 45, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (46, 46, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (47, 47, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (48, 48, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (49, 49, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (50, 50, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (51, 51, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (52, 52, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (53, 53, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (54, 54, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (55, 55, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (56, 56, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (57, 57, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (58, 58, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (59, 59, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (60, 60, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (61, 61, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (62, 62, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (63, 63, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (64, 64, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (65, 65, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (66, 66, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (67, 67, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (68, 68, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (69, 69, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (70, 70, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (71, 71, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (72, 72, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (73, 73, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (74, 74, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (75, 75, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (76, 76, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (77, 77, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (78, 78, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (79, 79, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (80, 80, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (81, 81, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (82, 82, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (83, 83, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (84, 84, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (85, 85, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (86, 86, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (87, 87, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (88, 88, 'short_league_name', 'None');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (89, 1, 'club_name', 'Anglesea');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (90, 2, 'club_name', 'Barwon Heads');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (91, 3, 'club_name', 'Drysdale');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (92, 4, 'club_name', 'Geelong Amateur');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (93, 5, 'club_name', 'Modewarre');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (94, 6, 'club_name', 'Newcomb Power');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (95, 7, 'club_name', 'Ocean Grove');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (96, 8, 'club_name', 'Portarlington');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (97, 9, 'club_name', 'Queenscliff');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (98, 10, 'club_name', 'Torquay');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (99, 11, 'club_name', 'Anakie');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (100, 12, 'club_name', 'Bannockburn');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (101, 13, 'club_name', 'Bell Post Hill');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (102, 14, 'club_name', 'Belmont Lions');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (103, 15, 'club_name', 'Corio');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (104, 16, 'club_name', 'East Geelong');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (105, 17, 'club_name', 'Geelong West');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (106, 18, 'club_name', 'Inverleigh');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (107, 19, 'club_name', 'North Geelong');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (108, 20, 'club_name', 'Thomson');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (109, 21, 'club_name', 'Werribee Centrals');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (110, 22, 'club_name', 'Winchelsea');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (111, 23, 'club_name', 'Bell Park');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (112, 24, 'club_name', 'Colac');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (113, 25, 'club_name', 'Grovedale');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (114, 26, 'club_name', 'Gwsp');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (115, 27, 'club_name', 'Lara');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (116, 28, 'club_name', 'Leopold');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (117, 29, 'club_name', 'Newtown & Chilwell');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (118, 30, 'club_name', 'North Shore');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (119, 31, 'club_name', 'South Barwon');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (120, 32, 'club_name', 'St Albans');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (121, 33, 'club_name', 'St Joseph''s');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (122, 34, 'club_name', 'St Mary''s');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (123, 35, 'club_name', 'Anakie');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (124, 36, 'club_name', 'Anglesea');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (125, 37, 'club_name', 'Bannockburn');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (126, 38, 'club_name', 'Barwon Heads');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (127, 39, 'club_name', 'Bell Park');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (128, 40, 'club_name', 'Belmont Lions / Newcomb');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (129, 41, 'club_name', 'Belmont Lions');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (130, 42, 'club_name', 'Colac');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (131, 43, 'club_name', 'Corio');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (132, 44, 'club_name', 'Drysdale Bennett');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (133, 45, 'club_name', 'Drysdale Byrne');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (134, 46, 'club_name', 'Drysdale Eddy');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (135, 47, 'club_name', 'Drysdale Hall');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (136, 48, 'club_name', 'Drysdale Hector');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (137, 49, 'club_name', 'Drysdale');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (138, 50, 'club_name', 'East Geelong');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (139, 51, 'club_name', 'Geelong Amateur');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (140, 52, 'club_name', 'Geelong West St Peters');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (141, 53, 'club_name', 'Grovedale');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (142, 54, 'club_name', 'Gwsp / Bannockburn');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (143, 55, 'club_name', 'Inverleigh');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (144, 56, 'club_name', 'Lara');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (145, 57, 'club_name', 'Leopold');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (146, 58, 'club_name', 'Modewarre');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (147, 59, 'club_name', 'Newcomb');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (148, 60, 'club_name', 'Newtown & Chilwell');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (149, 61, 'club_name', 'North Geelong');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (150, 62, 'club_name', 'North Shore');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (151, 63, 'club_name', 'Ocean Grove');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (152, 64, 'club_name', 'Ogcc');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (153, 65, 'club_name', 'Portarlington');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (154, 66, 'club_name', 'Queenscliff');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (155, 67, 'club_name', 'South Barwon / Geelong Amateur');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (156, 68, 'club_name', 'South Barwon');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (157, 69, 'club_name', 'St Albans Allthorpe');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (158, 70, 'club_name', 'St Albans Reid');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (159, 71, 'club_name', 'St Albans');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (160, 72, 'club_name', 'St Joseph''s Hill');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (161, 73, 'club_name', 'St Joseph''s Podbury');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (162, 74, 'club_name', 'St Joseph''s');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (163, 75, 'club_name', 'St Mary''s');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (164, 76, 'club_name', 'Tigers Gold');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (165, 77, 'club_name', 'Torquay Bumpstead');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (166, 78, 'club_name', 'Torquay Coles');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (167, 79, 'club_name', 'Torquay Dunstan');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (168, 80, 'club_name', 'Torquay Jones');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (169, 81, 'club_name', 'Torquay Nairn');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (170, 82, 'club_name', 'Torquay Papworth');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (171, 83, 'club_name', 'Torquay Pyers');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (172, 84, 'club_name', 'Torquay Scott');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (173, 85, 'club_name', 'Torquay');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (174, 86, 'club_name', 'Werribee Centrals');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (175, 87, 'club_name', 'Winchelsea / Grovedale');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (176, 88, 'club_name', 'Winchelsea');


DROP TABLE if exists report_column_lookup;

CREATE TABLE report_column_lookup (
  report_column_lookup_id INT PRIMARY KEY,
  filter_name VARCHAR(40),
  filter_value VARCHAR(100),
  report_table_id INT,
  report_column_id INT,
  FOREIGN KEY (report_table_id) REFERENCES report_table(report_table_id) ON DELETE CASCADE,
  FOREIGN KEY (report_column_id) REFERENCES report_column(report_column_id) ON DELETE CASCADE

);



INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (1, 'short_league_name',  'All', 1, 11);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (2, 'short_league_name',  'All', 1, 12);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (3, 'short_league_name',  'All', 1, 43);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (4, 'short_league_name',  'All', 1, 16);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (5, 'short_league_name',  'All', 1, 19);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (6, 'short_league_name',  'All', 1, 65);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (7, 'short_league_name',  'All', 1, 21);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (8, 'short_league_name',  'All', 1, 22);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (9, 'short_league_name',  'All', 1, 23);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (10, 'short_league_name',  'All', 1, 13);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (11, 'short_league_name',  'All', 1, 14);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (12, 'short_league_name',  'All', 1, 24);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (13, 'short_league_name',  'All', 1, 4);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (14, 'short_league_name',  'All', 1, 17);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (15, 'short_league_name',  'All', 1, 25);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (16, 'short_league_name',  'All', 1, 26);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (17, 'short_league_name',  'All', 1, 18);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (18, 'short_league_name',  'All', 1, 27);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (19, 'short_league_name',  'All', 1, 28);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (20, 'short_league_name',  'All', 1, 29);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (21, 'short_league_name',  'All', 1, 30);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (22, 'short_league_name',  'All', 1, 31);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (23, 'short_league_name',  'All', 1, 33);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (24, 'short_league_name',  'All', 1, 34);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (25, 'short_league_name',  'All', 1, 10);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (26, 'short_league_name',  'All', 1, 38);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (27, 'short_league_name',  'All', 1, 49);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (28, 'short_league_name',  'All', 1, 50);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (29, 'short_league_name',  'All', 1, 52);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (30, 'short_league_name',  'All', 1, 53);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (31, 'short_league_name',  'All', 1, 55);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (32, 'short_league_name',  'All', 1, 57);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (33, 'short_league_name',  'All', 1, 59);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (34, 'short_league_name',  'All', 1, 60);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (35, 'short_league_name',  'All', 1, 63);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (36, 'short_league_name',  'All', 1, 68);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (37, 'short_league_name',  'All', 1, 71);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (38, 'short_league_name',  'All', 1, 74);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (39, 'short_league_name',  'All', 1, 75);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (40, 'short_league_name',  'All', 1, 85);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (41, 'short_league_name',  'All', 1, 1);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (42, 'short_league_name',  'All', 1, 2);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (43, 'short_league_name',  'All', 1, 15);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (44, 'short_league_name',  'All', 1, 20);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (45, 'short_league_name',  'All', 1, 36);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (46, 'short_league_name',  'All', 1, 39);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (47, 'short_league_name',  'All', 1, 62);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (48, 'short_league_name',  'All', 1, 41);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (49, 'short_league_name',  'All', 1, 42);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (50, 'short_league_name',  'All', 1, 61);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (51, 'short_league_name',  'All', 1, 64);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (52, 'short_league_name',  'All', 1, 80);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (53, 'short_league_name',  'All', 1, 82);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (54, 'short_league_name',  'All', 1, 87);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (55, 'short_league_name',  'All', 1, 5);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (56, 'short_league_name',  'All', 1, 6);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (57, 'short_league_name',  'All', 1, 9);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (58, 'short_league_name',  'All', 1, 32);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (59, 'short_league_name',  'All', 1, 45);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (60, 'short_league_name',  'All', 1, 47);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (61, 'short_league_name',  'All', 1, 48);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (62, 'short_league_name',  'All', 1, 56);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (63, 'short_league_name',  'All', 1, 66);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (64, 'short_league_name',  'All', 1, 70);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (65, 'short_league_name',  'All', 1, 77);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (66, 'short_league_name',  'All', 1, 83);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (67, 'short_league_name',  'All', 1, 58);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (68, 'short_league_name',  'All', 1, 7);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (69, 'short_league_name',  'All', 1, 3);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (70, 'short_league_name',  'All', 1, 8);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (71, 'short_league_name',  'All', 1, 73);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (72, 'short_league_name',  'All', 1, 51);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (73, 'short_league_name',  'All', 1, 88);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (74, 'short_league_name',  'All', 1, 35);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (75, 'short_league_name',  'All', 1, 37);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (76, 'short_league_name',  'All', 1, 67);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (77, 'short_league_name',  'All', 1, 72);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (78, 'short_league_name',  'All', 1, 79);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (79, 'short_league_name',  'All', 1, 86);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (80, 'short_league_name',  'All', 1, 46);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (81, 'short_league_name',  'All', 1, 40);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (82, 'short_league_name',  'All', 1, 78);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (83, 'short_league_name',  'All', 1, 54);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (84, 'short_league_name',  'All', 1, 69);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (85, 'short_league_name',  'All', 1, 44);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (86, 'short_league_name',  'All', 1, 84);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (87, 'short_league_name',  'All', 1, 81);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (88, 'short_league_name',  'All', 1, 76);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (89, 'short_league_name',  'BFL', 1, 4);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (90, 'short_league_name',  'BFL', 1, 10);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (91, 'short_league_name',  'BFL', 1, 1);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (92, 'short_league_name',  'BFL', 1, 2);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (93, 'short_league_name',  'BFL', 1, 5);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (94, 'short_league_name',  'BFL', 1, 6);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (95, 'short_league_name',  'BFL', 1, 9);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (96, 'short_league_name',  'BFL', 1, 7);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (97, 'short_league_name',  'BFL', 1, 3);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (98, 'short_league_name',  'BFL', 1, 8);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (99, 'short_league_name',  'GDFL', 1, 11);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (100, 'short_league_name',  'GDFL', 1, 12);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (101, 'short_league_name',  'GDFL', 1, 16);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (102, 'short_league_name',  'GDFL', 1, 19);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (103, 'short_league_name',  'GDFL', 1, 21);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (104, 'short_league_name',  'GDFL', 1, 22);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (105, 'short_league_name',  'GDFL', 1, 13);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (106, 'short_league_name',  'GDFL', 1, 14);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (107, 'short_league_name',  'GDFL', 1, 17);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (108, 'short_league_name',  'GDFL', 1, 18);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (109, 'short_league_name',  'GDFL', 1, 15);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (110, 'short_league_name',  'GDFL', 1, 20);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (111, 'short_league_name',  'GFL', 1, 23);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (112, 'short_league_name',  'GFL', 1, 24);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (113, 'short_league_name',  'GFL', 1, 25);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (114, 'short_league_name',  'GFL', 1, 26);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (115, 'short_league_name',  'GFL', 1, 27);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (116, 'short_league_name',  'GFL', 1, 28);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (117, 'short_league_name',  'GFL', 1, 29);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (118, 'short_league_name',  'GFL', 1, 30);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (119, 'short_league_name',  'GFL', 1, 31);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (120, 'short_league_name',  'GFL', 1, 33);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (121, 'short_league_name',  'GFL', 1, 34);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (122, 'short_league_name',  'GFL', 1, 32);

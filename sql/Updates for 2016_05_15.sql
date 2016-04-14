ALTER TABLE mv_report_04 ADD COLUMN region VARCHAR(100);


INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (187,'Under 12|GJFL','SUM','1','24');
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (188,'No U12 Field|Clubs','MAX','1','612');
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (189,'No U12 Field|No.','SUM','1','692');
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (190,'Boundary|Under 12|GJFL',NULL,'1','45');
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (191,'Field|Under 12|GJFL',NULL,'1','145');
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order) VALUES (192,'Goal|Under 12|GJFL',NULL,'1','245');


INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (706, 'short_league_name',  'GJFL', 2, 187);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (707, 'age_group',  'Under 12', 2, 187);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (708, 'age_group',  'All', 2, 187);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (709, 'short_league_name',  'GJFL', 3, 188);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (710, 'short_league_name',  'GJFL', 3, 189);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (711, 'age_group',  'All', 3, 188);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (712, 'age_group',  'All', 3, 189);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (713, 'age_group',  'Under 12', 3, 188);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (714, 'age_group',  'Under 12', 3, 189);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (715, 'umpire_type',  'All', 3, 188);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (716, 'umpire_type',  'All', 3, 189);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (717, 'umpire_type',  'Field', 3, 188);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (718, 'umpire_type',  'Field', 3, 189);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (719, 'short_league_name',  'GJFL', 4, 190);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (720, 'short_league_name',  'GJFL', 4, 191);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (721, 'short_league_name',  'GJFL', 4, 192);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (722, 'age_group',  'All', 4, 190);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (723, 'age_group',  'All', 4, 191);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (724, 'age_group',  'All', 4, 192);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (725, 'age_group',  'Under 12', 4, 190);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (726, 'age_group',  'Under 12', 4, 191);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (727, 'age_group',  'Under 12', 4, 192);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (728, 'umpire_type',  'All', 4, 190);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (729, 'umpire_type',  'All', 4, 191);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (730, 'umpire_type',  'All', 4, 192);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (731, 'umpire_type',  'Boundary', 4, 190);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (732, 'umpire_type',  'Field', 4, 191);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (733, 'umpire_type',  'Goal', 4, 192);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (734, 'umpire_type',  'All', 2, 187);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (735, 'umpire_type',  'Boundary', 2, 187);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (736, 'umpire_type',  'Goal', 2, 187);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (737, 'umpire_type',  'Field', 2, 187);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (738, 'short_league_name',  'GJFL', 3, 188);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id) VALUES (739, 'short_league_name',  'GJFL', 3, 189);


INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (412, 187, 'age_group', 'Under 12');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (413, 188, 'umpire_type_age_group', 'No U12 Field');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (414, 189, 'umpire_type_age_group', 'No U12 Field');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (415, 190, 'age_group', 'Under 12');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (416, 191, 'age_group', 'Under 12');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (417, 192, 'age_group', 'Under 12');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (418, 187, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (419, 188, 'short_league_name', 'Clubs');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (420, 189, 'short_league_name', 'No.');

INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (421, 190, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (422, 191, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (423, 192, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (424, 190, 'umpire_type', 'Boundary');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (425, 191, 'umpire_type', 'Field');
INSERT INTO report_column_lookup_display (report_column_lookup_display_id, report_column_id, column_display_filter_name, column_display_name) VALUES (426, 192, 'umpire_type', 'Goal');




ALTER TABLE mv_report_02 ADD COLUMN `Under 12|GJFL` INT(11);

ALTER TABLE mv_report_03 ADD COLUMN `No U12 Field|Clubs` VARCHAR(1000);
ALTER TABLE mv_report_03 ADD COLUMN `No U12 Field|No.` INT(11);

ALTER TABLE mv_report_04 ADD COLUMN `Boundary|Under 12|GJFL` INT(11);
ALTER TABLE mv_report_04 ADD COLUMN `Field|Under 12|GJFL` INT(11);
ALTER TABLE mv_report_04 ADD COLUMN `Goal|Under 12|GJFL` INT(11);



INSERT INTO league (ID, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES(36, 'AFL Barwon', 'AFL Barwon', 'GJFL', 26, 1);
INSERT INTO league (ID, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) VALUES(37, 'AFL Barwon Walpole Shield', 'AFL Barwon Walpole Shield', 'GJFL', 26, 1);


INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(62, 'AFL Barwon - 2016 Blood Toyota Geelong FNL Seniors', 2, 3);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(63, 'AFL Barwon - 2016 Buckley''s Geelong FNL Reserves', 2, 4);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(64, 'AFL Barwon - 2016 Colts Grading', 2, 7);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(65, 'AFL Barwon - 2016 Corio Bay Health Group Junior Girls', 2, 24);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(66, 'AFL Barwon - 2016 Corio Bay Health Group Youth Girls', 2, 25);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(67, 'AFL Barwon - 2016 Geelong Advertiser Under 12''s', 2, 36);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(68, 'AFL Barwon - 2016 NLL Under 12''s Walpole Shield', 2, 37);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(69, 'AFL Barwon - 2016 Under 14 Grading', 2, 11);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(70, 'AFL Barwon - 2016 Under 16 Grading', 2, 12);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(71, 'GDFL - Buckleys Cup Reserves 2016', 2, 10);
INSERT INTO competition_lookup (id, competition_name, season_id, league_id) VALUES(72, 'GDFL - Smiths Holden Cup Seniors 2016', 2, 9);


INSERT INTO ground (ID, main_name, alternative_name) VALUES(57, 'Anakie Reserve Anakie', 'Anakie Reserve Anakie');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(58, 'Bellbrae Reserve', 'Bellbrae Reserve');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(59, 'Birregurra Recreation Reserve', 'Birregurra Recreation Reserve');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(60, 'Galvin Park', 'Galvin Park');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(61, 'Gellibrand Recreation Reserve', 'Gellibrand Recreation Reserve');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(62, 'Grovedale Secondary College', 'Grovedale Secondary College');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(63, 'Hamlyn Park Oval 2', 'Hamlyn Park Oval 2');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(64, 'Jetts Oval (winter Reserve)', 'Jetts Oval (winter Reserve)');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(65, 'Lara Recreation Reserve No. 2', 'Lara Recreation Reserve No. 2');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(66, 'Portarlington Recreation Reserve No. 2', 'Portarlington Recreation Reserve No. 2');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(67, 'Queens Park Top Oval', 'Queens Park Top Oval');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(68, 'Teesdale (don Wallace) Recreation Reserve', 'Teesdale (don Wallace) Recreation Reserve');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(69, 'The Gordon Tafe Oval (st Albans Reserve)', 'The Gordon Tafe Oval (st Albans Reserve)');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(70, 'Victoria Park Bannockburn', 'Victoria Park Bannockburn');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(71, 'Victoria Park No. 2', 'Victoria Park No. 2');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(72, 'West Oval Church St Geelong West', 'West Oval Church St Geelong West');
INSERT INTO ground (ID, main_name, alternative_name) VALUES(73, 'Winter Reserve No. 2', 'Winter Reserve No. 2');






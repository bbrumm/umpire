SELECT * FROM bbrumm_umpire_data.report_column;

INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (263, 'BFL|Games', NULL, 1, 1);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (264, 'BFL|Total', NULL, 1, 2);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (265, 'BFL|Pct', NULL, 1, 3);

INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (266, 'GDFL|Games', NULL, 1, 4);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (267, 'GDFL|Total', NULL, 1, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (268, 'GDFL|Pct', NULL, 1, 6);

INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (269, 'GFL|Games', NULL, 1, 7);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (270, 'GFL|Total', NULL, 1, 8);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (271, 'GFL|Pct', NULL, 1, 9);

INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (272, 'GJFL|Games', NULL, 1, 10);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (273, 'GJFL|Total', NULL, 1, 11);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (274, 'GJFL|Pct', NULL, 1, 12);

INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (275, 'CDFNL|Games', NULL, 1, 13);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (276, 'CDFNL|Total', NULL, 1, 14);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (277, 'CDFNL|Pct', NULL, 1, 15);


INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (811, 'short_league_name', 'BFL', 5, 263);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (812, 'short_league_name', 'BFL', 5, 264);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (813, 'short_league_name', 'BFL', 5, 265);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (814, 'short_league_name', 'GDFL', 5, 266);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (815, 'short_league_name', 'GDFL', 5, 267);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (816, 'short_league_name', 'GDFL', 5, 268);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (817, 'short_league_name', 'GFL', 5, 269);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (818, 'short_league_name', 'GFL', 5, 270);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (819, 'short_league_name', 'GFL', 5, 271);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (820, 'short_league_name', 'GJFL', 5, 272);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (821, 'short_league_name', 'GJFL', 5, 273);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (822, 'short_league_name', 'GJFL', 5, 274);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (823, 'short_league_name', 'CDFNL', 5, 275);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (824, 'short_league_name', 'CDFNL', 5, 276);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (825, 'short_league_name', 'CDFNL', 5, 277);


/*TODO: Adjudt these records so that there are 2 records for each field, one for short_;eague_name and another for subtotal*/
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (568, 263, 'subtotal', 'Games');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (569, 264, 'subtotal', 'Total');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (570, 265, 'subtotal', 'Pct');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (571, 266, 'subtotal', 'Games');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (572, 267, 'subtotal', 'Total');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (573, 268, 'subtotal', 'Pct');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (574, 269, 'subtotal', 'Games');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (575, 270, 'subtotal', 'Total');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (576, 271, 'subtotal', 'Pct');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (577, 272, 'subtotal', 'Games');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (578, 273, 'subtotal', 'Total');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (579, 274, 'subtotal', 'Pct');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (580, 275, 'subtotal', 'Games');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (581, 276, 'subtotal', 'Total');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (582, 277, 'subtotal', 'Pct');



INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (583, 263, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (584, 264, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (585, 265, 'short_league_name', 'BFL');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (586, 266, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (587, 267, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (588, 268, 'short_league_name', 'GDFL');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (589, 269, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (590, 270, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (591, 271, 'short_league_name', 'GFL');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (592, 272, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (593, 273, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (594, 274, 'short_league_name', 'GJFL');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (595, 275, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (596, 276, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (597, 277, 'short_league_name', 'CDFNL');

/*
UPDATE report_column_lookup_display
SET column_display_filter_name = 'subtotal' WHERE report_column_lookup_display_ID BETWEEN 568 and 582;
*/
ALTER TABLE mv_report_05 CHANGE BFL `BFL|Games` INT;
ALTER TABLE mv_report_05 CHANGE GDFL `GDFL|Games` INT;
ALTER TABLE mv_report_05 CHANGE GFL `GFL|Games` INT;
ALTER TABLE mv_report_05 CHANGE GJFL `GJFL|Games` INT;
ALTER TABLE mv_report_05 CHANGE CDFNL `CDFNL|Games` INT;

ALTER TABLE mv_report_05 ADD COLUMN `BFL|Total` INT;
ALTER TABLE mv_report_05 ADD COLUMN `BFL|Pct` INT;
ALTER TABLE mv_report_05 ADD COLUMN `GDFL|Total` INT;
ALTER TABLE mv_report_05 ADD COLUMN `GDFL|Pct` INT;
ALTER TABLE mv_report_05 ADD COLUMN `GFL|Total` INT;
ALTER TABLE mv_report_05 ADD COLUMN `GFL|Pct` INT;
ALTER TABLE mv_report_05 ADD COLUMN `GJFL|Total` INT;
ALTER TABLE mv_report_05 ADD COLUMN `GJFL|Pct` INT;
ALTER TABLE mv_report_05 ADD COLUMN `CDFNL|Total` INT;
ALTER TABLE mv_report_05 ADD COLUMN `CDFNL|Pct` INT;

UPDATE report_column_lookup
SET report_table_id = 500
WHERE report_column_id IN (152, 153, 154, 155, 178);

INSERT INTO field_list (field_id, field_name) VALUES (10, 'subtotal');

UPDATE report_grouping_structure SET merge_field = 1 WHERE report_grouping_structure_id = 14;

INSERT INTO report_grouping_structure (report_grouping_structure_id, grouping_type, report_id, field_id, field_group_order, merge_field)
VALUES (19, 'Column', 5, 10, 2, 0);

update report_column set display_order = 20 where report_column_id = 156;
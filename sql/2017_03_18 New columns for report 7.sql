
INSERT INTO report_table (report_table_id, report_name, table_name, report_title)
VALUES (7, 7, 'mv_report_07', 'Report 7');



INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (279, 'GFL|2 Umpires', 'SUM', 0, 1);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (280, 'GFL|3 Umpires', 'SUM', 0, 2);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (281, 'BFL|2 Umpires', 'SUM', 0, 3);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (282, 'BFL|3 Umpires', 'SUM', 0, 4);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (283, 'GDFL|2 Umpires', 'SUM', 0, 5);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (284, 'GDFL|3 Umpires', 'SUM', 0, 6);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (285, 'GJFL|2 Umpires', 'SUM', 0, 7);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (286, 'GJFL|3 Umpires', 'SUM', 0, 8);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (287, 'CDFNL|2 Umpires', 'SUM', 0, 9);
INSERT INTO report_column (report_column_id, column_name, column_function, overall_total, display_order)
VALUES (288, 'CDFNL|3 Umpires', 'SUM', 0, 10);




INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (831, 'short_league_name', 'All', 7, 279);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (832, 'short_league_name', 'GFL', 7, 279);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (833, 'age_group', 'All', 7, 279);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (834, 'umpire_type', 'All', 7, 279);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (835, 'umpire_type', 'Field', 7, 279);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (836, 'short_league_name', 'All', 7, 280);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (837, 'short_league_name', 'GFL', 7, 280);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (838, 'age_group', 'All', 7, 280);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (839, 'umpire_type', 'All', 7, 280);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (840, 'umpire_type', 'Field', 7, 280);


INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (841, 'short_league_name', 'All', 7, 281);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (842, 'short_league_name', 'BFL', 7, 281);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (843, 'age_group', 'All', 7, 281);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (844, 'umpire_type', 'All', 7, 281);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (845, 'umpire_type', 'Field', 7, 281);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (846, 'short_league_name', 'All', 7, 282);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (847, 'short_league_name', 'BFL', 7, 282);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (848, 'age_group', 'All', 7, 282);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (849, 'umpire_type', 'All', 7, 282);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (850, 'umpire_type', 'Field', 7, 282);


INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (851, 'short_league_name', 'All', 7, 283);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (852, 'short_league_name', 'GDFL', 7, 283);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (853, 'age_group', 'All', 7, 283);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (854, 'umpire_type', 'All', 7, 283);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (855, 'umpire_type', 'Field', 7, 283);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (856, 'short_league_name', 'All', 7, 284);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (857, 'short_league_name', 'GDFL', 7, 284);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (858, 'age_group', 'All', 7, 284);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (859, 'umpire_type', 'All', 7, 284);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (860, 'umpire_type', 'Field', 7, 284);


INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (861, 'short_league_name', 'All', 7, 285);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (862, 'short_league_name', 'GJFL', 7, 285);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (863, 'age_group', 'All', 7, 285);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (864, 'umpire_type', 'All', 7, 285);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (865, 'umpire_type', 'Field', 7, 285);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (866, 'short_league_name', 'All', 7, 286);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (867, 'short_league_name', 'GJFL', 7, 286);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (868, 'age_group', 'All', 7, 286);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (869, 'umpire_type', 'All', 7, 286);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (870, 'umpire_type', 'Field', 7, 286);


INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (871, 'short_league_name', 'All', 7, 287);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (872, 'short_league_name', 'CDFNL', 7, 287);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (873, 'age_group', 'All', 7, 287);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (874, 'umpire_type', 'All', 7, 287);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (875, 'umpire_type', 'Field', 7, 287);

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (876, 'short_league_name', 'All', 7, 288);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (877, 'short_league_name', 'CDFNL', 7, 288);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (878, 'age_group', 'All', 7, 288);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (879, 'umpire_type', 'All', 7, 288);
INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (880, 'umpire_type', 'Field', 7, 288);




INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (600, 279, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (601, 279, 'umpire_count', '2 Umpires');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (602, 280, 'short_league_name', 'GFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (603, 280, 'umpire_count', '3 Umpires');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (604, 281, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (605, 281, 'umpire_count', '2 Umpires');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (606, 282, 'short_league_name', 'BFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (607, 282, 'umpire_count', '3 Umpires');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (608, 283, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (609, 283, 'umpire_count', '2 Umpires');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (610, 284, 'short_league_name', 'GDFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (611, 284, 'umpire_count', '3 Umpires');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (612, 285, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (613, 285, 'umpire_count', '2 Umpires');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (614, 286, 'short_league_name', 'GJFL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (615, 286, 'umpire_count', '3 Umpires');

INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (616, 287, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (617, 287, 'umpire_count', '2 Umpires');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (618, 288, 'short_league_name', 'CDFNL');
INSERT INTO report_column_lookup_display(report_column_lookup_display_ID, report_column_id, column_display_filter_name, column_display_name)
VALUES (619, 288, 'umpire_count', '3 Umpires');

ALTER TABLE mv_denormalised ADD COLUMN display_order INT(11);

ALTER TABLE mv_report_05 ADD COLUMN display_order INT(11);

INSERT INTO processed_table(id, table_name) VALUES (19, 'mv_report_07');

/*DROP TABLE mv_report_07;*/

CREATE TABLE mv_report_07 (
	age_group VARCHAR(100),
    umpire_type VARCHAR(100),
    short_league_name VARCHAR(100),
    season_year VARCHAR(4),
    region VARCHAR(100),
    display_order INT(11),
    `GFL|2 Umpires` INT(11),
    `GFL|3 Umpires` INT(11),
    `BFL|2 Umpires` INT(11),
    `BFL|3 Umpires` INT(11),
    `GDFL|2 Umpires` INT(11),
    `GDFL|3 Umpires` INT(11),
    `GJFL|2 Umpires` INT(11),
    `GJFL|3 Umpires` INT(11),
    `CDFNL|2 Umpires` INT(11),
    `CDFNL|3 Umpires` INT(11)
);


INSERT INTO field_list (field_id, field_name) VALUES (11, 'umpire_count');

INSERT INTO report_grouping_structure (report_grouping_structure_id, grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text)
VALUES (20, 'Column', 7, 2, 1, 1, NULL, NULL);

INSERT INTO report_grouping_structure (report_grouping_structure_id, grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text)
VALUES (21, 'Column', 7, 11, 2, 0, NULL, NULL);

INSERT INTO report_grouping_structure (report_grouping_structure_id, grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text)
VALUES (22, 'Row', 7, 5, 1, 0, 'Age Group', NULL);

INSERT INTO report_parameter_map (report_parameter_map_id, report_parameter_id, report_id, parameter_value)
VALUES (49, 1, 7, 1);
INSERT INTO report_parameter_map (report_parameter_map_id, report_parameter_id, report_id, parameter_value)
VALUES (50, 2, 7, '');
INSERT INTO report_parameter_map (report_parameter_map_id, report_parameter_id, report_id, parameter_value)
VALUES (51, 3, 7, 'text');
INSERT INTO report_parameter_map (report_parameter_map_id, report_parameter_id, report_id, parameter_value)
VALUES (52, 4, 7, 0);
INSERT INTO report_parameter_map (report_parameter_map_id, report_parameter_id, report_id, parameter_value)
VALUES (53, 5, 7, 200);
INSERT INTO report_parameter_map (report_parameter_map_id, report_parameter_id, report_id, parameter_value)
VALUES (54, 6, 7, '06 - Games with 2 or 3 Field Umpires (%seasonYear)');
INSERT INTO report_parameter_map (report_parameter_map_id, report_parameter_id, report_id, parameter_value)
VALUES (55, 7, 7, 'a4');
INSERT INTO report_parameter_map (report_parameter_map_id, report_parameter_id, report_id, parameter_value)
VALUES (56, 8, 7, 'landscape');

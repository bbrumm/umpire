CREATE TABLE report_parameter (
report_parameter_id INT(11) PRIMARY KEY auto_increment,
parameter_name VARCHAR(100) NOT NULL,
parameter_type VARCHAR(10) NOT NULL
);


CREATE TABLE report_parameter_map (
report_parameter_map_id INT(11) PRIMARY KEY auto_increment,
report_parameter_id INT(11),
report_id INT(11) NOT NULL,
parameter_value VARCHAR(200) DEFAULT NULL,
FOREIGN KEY (report_id) REFERENCES report_table (report_id),
FOREIGN KEY (report_parameter_id) REFERENCES report_parameter (report_parameter_id)
);



CREATE TABLE field_list (
field_id INT(11) PRIMARY KEY auto_increment,
field_name VARCHAR(200) NOT NULL
);

CREATE TABLE report_grouping_structure (
report_grouping_structure_id INT(11) PRIMARY KEY auto_increment,
grouping_type VARCHAR(10) NOT NULL,
report_id INT(3) NOT NULL,
field_id INT(5) NOT NULL,
field_group_order INT(3) NOT NULL,
merge_field INT(1) NOT NULL DEFAULT 0,
group_heading VARCHAR(100) DEFAULT NULL,
group_size_text VARCHAR(500) DEFAULT NULL,
FOREIGN KEY (report_id) REFERENCES report_table(report_id),
FOREIGN KEY (field_id) REFERENCES field_list(field_id)
);


INSERT INTO report_parameter(parameter_name,parameter_type) VALUES ('Value Field','Field');
INSERT INTO report_parameter(parameter_name,parameter_type) VALUES ('No Value To Display','Text');
INSERT INTO report_parameter(parameter_name,parameter_type) VALUES ('First Column Format','Text');
INSERT INTO report_parameter(parameter_name,parameter_type) VALUES ('Colour Cells','Text');
INSERT INTO report_parameter(parameter_name,parameter_type) VALUES ('PDF Resolution','Text');
INSERT INTO report_parameter(parameter_name,parameter_type) VALUES ('Display Title','Text');


INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (1,1, '1');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (1,2, ' ');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (1,3, 'text');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (1,4, '1');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (1,5, '200');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (1,6, '01 - Umpires and Clubs (%seasonYear)');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (2,1, '1');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (2,2, ' ');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (2,3, 'text');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (2,4, '0');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (2,5, '200');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (2,6, '02 - Umpire Names by League (%seasonYear)');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (3,1, '1');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (3,2, ' ');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (3,3, 'date');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (3,4, '0');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (3,5, '200');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (3,6, '03 - Summary by Week (Matches Where No Umpires Are Recorded) (%seasonYear)');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (4,1, '1');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (4,2, ' ');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (4,3, 'text');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (4,4, '0');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (4,5, '200');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (4,6, '04 - Summary by Club (Matches Where No Umpires Are Recorded) (%seasonYear)');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (5,1, '1');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (5,2, '0');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (5,3, 'text');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (5,4, '0');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (5,5, '100');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (5,6, '05 - Games with Zero Umpires For Each League (%seasonYear)');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (6,1, '1');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (6,2, ' ');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (6,3, 'text');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (6,4, '1');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (6,5, '200');
INSERT INTO report_parameter_map(report_id,report_parameter_id,parameter_value) VALUES (6,6, '06 - Umpire Pairing (%seasonYear)');

INSERT INTO field_list(field_name) VALUES ('match_count');
INSERT INTO field_list(field_name) VALUES ('short_league_name');
INSERT INTO field_list(field_name) VALUES ('club_name');
INSERT INTO field_list(field_name) VALUES ('full_name');
INSERT INTO field_list(field_name) VALUES ('age_group');
INSERT INTO field_list(field_name) VALUES ('umpire_type_age_group');
INSERT INTO field_list(field_name) VALUES ('weekdate');
INSERT INTO field_list(field_name) VALUES ('umpire_type');
INSERT INTO field_list(field_name) VALUES ('umpire_name');

INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 1, 2, 1, 1, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 1, 3, 2, 0, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Row', 1, 4, 1, 0, 'Name', 'Umpire_Name_First_Last');
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 2, 5, 1, 1, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 2, 2, 2, 0, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Row', 2, 4, 1, 0, 'Name', NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 3, 6, 1, 1, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 3, 2, 2, 0, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Row', 3, 7, 1, 0, 'Week (Sat)', NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 4, 8, 1, 1, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 4, 5, 2, 1, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 4, 2, 3, 0, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Row', 4, 3, 1, 0, 'Club', NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 5, 2, 1, 0, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Row', 5, 8, 1, 0, 'Discipline', NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Row', 5, 5, 2, 0, 'Age Group', NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Column', 6, 9, 1, 0, NULL, NULL);
INSERT INTO report_grouping_structure(grouping_type, report_id, field_id, field_group_order, merge_field, group_heading, group_size_text) VALUES ('Row', 6, 9, 1, 0, 'Umpire Name', 'Umpire_Name_First_Last');


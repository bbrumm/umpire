/*
Create Report Selection Parameters Table
*/

DROP TABLE report_selection_parameters;

CREATE TABLE report_selection_parameters (
parameter_id INT(11) NOT NULL PRIMARY KEY,
parameter_name VARCHAR(100) NOT NULL,
parameter_display_order INT(3) NOT NULL,
allow_multiple_selections boolean
);

INSERT INTO report_selection_parameters (parameter_id, parameter_name, parameter_display_order, allow_multiple_selections) VALUES (1, 'Region', 1, FALSE);
INSERT INTO report_selection_parameters (parameter_id, parameter_name, parameter_display_order, allow_multiple_selections) VALUES (2, 'League', 2, FALSE);
INSERT INTO report_selection_parameters (parameter_id, parameter_name, parameter_display_order, allow_multiple_selections) VALUES (3, 'Umpire Discipline', 3, FALSE);
INSERT INTO report_selection_parameters (parameter_id, parameter_name, parameter_display_order, allow_multiple_selections) VALUES (4, 'Age Group', 4, FALSE);


DROP TABLE report_selection_parameter_values;

CREATE TABLE report_selection_parameter_values (
parameter_value_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
parameter_id INT(11) NOT NULL,
parameter_value_name VARCHAR(100) NOT NULL,
parameter_display_order INT(3) NOT NULL
);

ALTER TABLE report_selection_parameter_values ADD CONSTRAINT fk_param_value FOREIGN KEY (parameter_id) REFERENCES report_selection_parameters(parameter_id);

INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (1, 'Geelong', 1);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (1, 'Colac', 2);

INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (2, 'BFL', 1);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (2, 'GFL', 2);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (2, 'GDFL', 3);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (2, 'CDFL', 4);

INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (3, 'Field', 1);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (3, 'Boundary', 2);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (3, 'Goal', 3);

INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Seniors', 1);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Reserves', 2);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Colts', 3);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Under 17.5', 4);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Under 16', 5);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Under 14.5', 6);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Under 14.5', 7);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Under 12', 8);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Youth Girls', 9);
INSERT INTO report_selection_parameter_values (parameter_id, parameter_value_name, parameter_display_order) VALUES (4, 'Junior Girls', 10);



SELECT *
FROM report_selection_parameter_values
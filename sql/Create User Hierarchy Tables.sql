CREATE TABLE permission (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
permission_name VARCHAR(100)
);

CREATE TABLE permission_selection (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
permission_id INT(11),
category VARCHAR(100),
selection_name VARCHAR(100),
FOREIGN KEY (permission_id) REFERENCES permission(id)
);

ALTER TABLE umpire_users ADD PRIMARY KEY (id);

CREATE TABLE user_permission_selection (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id INT(11),
permission_selection_id INT(11),
FOREIGN KEY (user_id) REFERENCES umpire_users(id),
FOREIGN KEY (permission_selection_id) REFERENCES permission_selection(id)
);


CREATE TABLE role (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
role_name VARCHAR(100)
);

CREATE TABLE sub_role (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
sub_role_name VARCHAR(100)
);

CREATE TABLE role_sub_role (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
role_id INT(11),
sub_role_id INT(11),
FOREIGN KEY (role_id) REFERENCES role(id),
FOREIGN KEY (sub_role_id) REFERENCES sub_role(id)
);

CREATE TABLE role_permission_selection (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
role_sub_role_id INT(11),
permission_selection_id INT(11),
FOREIGN KEY (role_sub_role_id) REFERENCES role_sub_role(id),
FOREIGN KEY (permission_selection_id) REFERENCES permission_selection(id)
);

ALTER TABLE umpire_users ADD COLUMN first_name VARCHAR(100);
ALTER TABLE umpire_users ADD COLUMN last_name VARCHAR(100);
ALTER TABLE umpire_users ADD COLUMN role_sub_role_id INT(11);

ALTER TABLE umpire_users ADD FOREIGN KEY (role_sub_role_id) REFERENCES role_sub_role(id);

INSERT INTO permission (permission_name) VALUES ('IMPORT_FILES');
INSERT INTO permission (permission_name) VALUES ('CREATE_PDF');
INSERT INTO permission (permission_name) VALUES ('VIEW_DATA_TEST');
INSERT INTO permission (permission_name) VALUES ('ADD_NEW_USERS');
INSERT INTO permission (permission_name) VALUES ('MODIFY_EXISTING_USERS');
INSERT INTO permission (permission_name) VALUES ('VIEW_REPORT');
INSERT INTO permission (permission_name) VALUES ('SELECT_REPORT_OPTION');


INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (1,'General', 'All');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (2,'General', 'All');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (3,'General', 'All');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (4,'General', 'All');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (5,'General', 'All');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Region', 'Geelong');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Region', 'Colac');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (6,'Report', 'Report 1');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (6,'Report', 'Report 2');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (6,'Report', 'Report 3');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (6,'Report', 'Report 4');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (6,'Report', 'Report 5');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (6,'Report', 'Report 6');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Seniors');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Reserves');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Colts');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Under 17.5');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Under 16');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Under 14.5');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Under 12');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Youth Girls');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Junior Girls');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'League', 'BFL');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'League', 'GFL');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'League', 'GDFL');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'League', 'GJFL');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'League', 'CDFNL');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Umpire Type', 'Boundary');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Umpire Type', 'Field');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Umpire Type', 'Goal');


INSERT INTO role (role_name) VALUES ('Owner');
INSERT INTO role (role_name) VALUES ('Administrator');
INSERT INTO role (role_name) VALUES ('Super User');
INSERT INTO role (role_name) VALUES ('Regular User');

INSERT INTO sub_role (sub_role_name) VALUES ('All');
INSERT INTO sub_role (sub_role_name) VALUES ('Geelong');
INSERT INTO sub_role (sub_role_name) VALUES ('Colac');

INSERT INTO role_sub_role (role_id, sub_role_id) VALUES (1, 1);
INSERT INTO role_sub_role (role_id, sub_role_id) VALUES (2, 2);
INSERT INTO role_sub_role (role_id, sub_role_id) VALUES (2, 3);
INSERT INTO role_sub_role (role_id, sub_role_id) VALUES (3, 2);
INSERT INTO role_sub_role (role_id, sub_role_id) VALUES (3, 3);
INSERT INTO role_sub_role (role_id, sub_role_id) VALUES (4, 2);
INSERT INTO role_sub_role (role_id, sub_role_id) VALUES (4, 3);


INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 1);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 2);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 3);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 4);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 5);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 6);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 7);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 8);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 9);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 10);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 11);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 12);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 13);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 14);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 15);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 16);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 17);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 18);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 19);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 20);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 21);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 22);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 23);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 24);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 25);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 26);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 27);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 28);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 29);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 30);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 1);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 2);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 4);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 5);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 6);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 8);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 9);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 10);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 11);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 12);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 13);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 14);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 15);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 16);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 17);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 18);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 19);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 20);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 21);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 22);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 23);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 24);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 25);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 26);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 27);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 28);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 29);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 30);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 1);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 2);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 4);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 5);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 7);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 8);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 9);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 10);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 11);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 12);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 13);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 14);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 15);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 16);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 17);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 18);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 19);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 20);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 21);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 22);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 23);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 24);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 25);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 26);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 27);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 28);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 29);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (3, 30);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 2);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 6);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 8);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 9);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 10);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 11);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 12);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 13);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 14);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 15);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 16);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 17);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 18);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 19);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 20);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 21);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 22);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 23);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 24);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 25);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 26);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 27);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 28);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 29);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 30);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 2);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 7);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 8);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 9);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 10);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 11);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 12);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 13);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 14);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 15);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 16);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 17);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 18);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 19);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 20);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 21);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 22);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 23);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 24);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 25);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 26);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 27);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 28);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 29);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (5, 30);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 2);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 6);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 8);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 9);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 10);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 11);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 12);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 13);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 14);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 15);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 16);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 17);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 18);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 19);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 20);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 21);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 22);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 23);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 24);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 25);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 26);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 27);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 28);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 29);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 30);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 2);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 7);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 8);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 9);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 10);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 11);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 12);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 13);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 14);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 15);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 16);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 17);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 18);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 19);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 20);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 21);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 22);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 23);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 24);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 25);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 26);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 27);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 28);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 29);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (7, 30);


UPDATE umpire_users SET role_sub_role_id = 1, first_name = 'Ben', last_name='Brumm' WHERE id = 2;
UPDATE umpire_users SET role_sub_role_id = 2, first_name = 'Brendan', last_name='Beveridge' WHERE id = 3;
UPDATE umpire_users SET role_sub_role_id = 2, first_name = 'Jason', last_name='Hillgrove' WHERE id = 4;
UPDATE umpire_users SET role_sub_role_id = 2, first_name = 'General', last_name='Manager' WHERE id = 5;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Darren', last_name='Baensch' WHERE id = 6;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Steve', last_name='Keating' WHERE id = 7;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Howard', last_name='Philpott' WHERE id = 8;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Rohan', last_name='Trotter' WHERE id = 9;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Alan', last_name='Grant' WHERE id = 10;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Colin', last_name='Hood' WHERE id = 11;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Darren', last_name='Santospirito' WHERE id = 12;
UPDATE umpire_users SET role_sub_role_id = 6, first_name = 'Tony', last_name='Brooks' WHERE id = 13;
UPDATE umpire_users SET role_sub_role_id = 6, first_name = 'Adam', last_name='Edwick' WHERE id = 14;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Kevin', last_name='McMaster' WHERE id = 15;
UPDATE umpire_users SET role_sub_role_id = 5, first_name = 'Larry', last_name='Donohue' WHERE id = 16;


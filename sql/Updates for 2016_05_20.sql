ALTER TABLE role ADD COLUMN display_order INT(11);
INSERT INTO permission (permission_name) VALUES ('VIEW_USER_ADMIN');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (8,'General', 'All');
INSERT INTO permission_selection(permission_id, category, selection_name) VALUES (7,'Age Group', 'Under 14');
UPDATE role SET display_order = id;
INSERT INTO role_sub_role (role_id, sub_role_id) VALUES (2, 1);

/*
7 is colac, which matches 17, 19, 27. they cant have 16, 18, 20, 21, 22, 23, 24, 25, 26, 32
6 is geelong, which means they cant have 17, 19, 27
*/

DELETE FROM role_permission_selection WHERE role_sub_role_id IN (2, 4, 6) and permission_selection_id IN (17, 19, 27);
DELETE FROM role_permission_selection WHERE role_sub_role_id IN (3, 5, 7) and permission_selection_id IN (16, 18, 20, 21, 22, 23, 24, 25, 26, 32);

INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (1, 32);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (2, 32);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (4, 32);
INSERT INTO role_permission_selection (role_sub_role_id, permission_selection_id) VALUES (6, 32);

INSERT INTO user_permission_selection (user_id, permission_selection_id) VALUES (2, 31);
INSERT INTO user_permission_selection (user_id, permission_selection_id) VALUES (3, 31);

SELECT u.id, u.user_name, u.user_email, u.first_name, u.last_name, r.role_name, s.sub_role_name,
rsr.id
FROM umpire_users u
INNER JOIN role_sub_role rsr ON u.role_sub_role_id = rsr.id
INNER JOIN role r ON rsr.role_id = r.id
INNER JOIN sub_role s ON s.id = rsr.sub_role_id;
/*WHERE u.user_name = 'dbaensch'*/
/*
INSERT INTO umpire_users (id, first_name, last_name, user_name, user_email, user_password, role_sub_role_id) VALUES (17, 'Davin', 'Reid', 'dreid', 'None', MD5('dreid2017'), 6);
INSERT INTO umpire_users (id, first_name, last_name, user_name, user_email, user_password, role_sub_role_id) VALUES (18, 'Kel', 'Clissold', 'kclissold','None', MD5('kclissold2017'), 6);
INSERT INTO umpire_users (id, first_name, last_name, user_name, user_email, user_password, role_sub_role_id) VALUES (19, 'Robert', 'Steel', 'rsteel', 'None', MD5('rsteel2017'), 6);
*/

Davin Reid,
Kel Clissold
Robert Steel


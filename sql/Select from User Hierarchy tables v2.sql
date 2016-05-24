SELECT * FROM umpire_users;

SELECT r.id, r.role_name,
s.id, s.sub_role_name
FROM role r
INNER JOIN role_sub_role rsr ON r.id = rsr.role_id
INNER JOIN sub_role s ON s.id = rsr.sub_role_id;

SELECT *
FROM permission;

SELECT *
FROM permission_selection;

/*Show Permission Names*/
SELECT p.id, p.permission_name, ps.id, ps.category, ps.selection_name
FROM permission p
INNER JOIN permission_selection ps ON p.id = ps.permission_id;



SELECT u.id, u.user_name, u.first_name, u.last_name, r.role_name, s.sub_role_name
FROM umpire_users u
INNER JOIN role_sub_role rsr ON u.role_sub_role_id = rsr.id
INNER JOIN role r ON rsr.role_id = r.id
INNER JOIN sub_role s ON s.id = rsr.sub_role_id
WHERE u.user_name = 'dbaensch'
LIMIT 1;


SELECT ps.id, ps.permission_id, p.permission_name, ps.selection_name
FROM permission_selection ps
INNER JOIN permission p ON ps.permission_id = p.id
WHERE (ps.id IN (
	SELECT ups.permission_selection_id
	FROM user_permission_selection ups
	WHERE user_id = 2
)
OR ps.id IN (
	SELECT rps.permission_selection_id
	FROM role_permission_selection rps
	INNER JOIN role_sub_role rsr ON rps.role_sub_role_id = rsr.id
	INNER JOIN umpire_users u ON rsr.id = u.role_sub_role_id
	WHERE u.id = 2
)
);


SELECT id, age_group FROM age_group ORDER BY display_order;

SELECT id, short_league_name FROM short_league_name ORDER BY display_order;


SELECT * FROM permission_selection;
/*
7 is colac, which matches 17, 19, 27. they cant have 16, 18, 20, 21, 22, 23, 24, 25, 26, 32
6 is geelong, which means they cant have 17, 19, 27
*/

DELETE FROM role_permission_selection WHERE role_sub_role_id IN (2, 4, 6) and permission_selection_id IN (17, 19, 27);
DELETE FROM role_permission_selection WHERE role_sub_role_id IN (3, 5, 7) and permission_selection_id IN (16, 18, 20, 21, 22, 23, 24, 25, 26, 32);
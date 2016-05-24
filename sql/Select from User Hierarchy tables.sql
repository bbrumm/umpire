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

SELECT p.id, p.permission_name, ps.id, ps.category, ps.selection_name
FROM permission p
INNER JOIN permission_selection ps ON p.id = ps.permission_id;




SELECT ps.id, ps.permission_id, p.permission_name, ps.selection_name 
FROM permission_selection ps 
INNER JOIN permission p ON ps.permission_id = p.id 
WHERE (ps.id IN ( 
	SELECT ups.permission_selection_id 
	FROM user_permission_selection ups 
	WHERE user_id = 2
) OR ps.id IN ( 
	SELECT rps.permission_selection_id 
	FROM role_permission_selection rps 
	INNER JOIN role_sub_role rsr ON rps.role_sub_role_id = rsr.id 
	INNER JOIN umpire_users u ON rsr.id = u.role_sub_role_id 
	WHERE u.id = 2));
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





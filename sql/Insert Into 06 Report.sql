INSERT INTO mv_umpire_list (umpire_type_name, age_group, umpire_name) 
SELECT DISTINCT
umpire_type.umpire_type_name, 
age_group.age_group, 
CONCAT(umpire.last_name, ', ', umpire.first_name) AS umpire_name
FROM umpire
INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
INNER JOIN umpire_name_type_match ON umpire_name_type.ID = umpire_name_type_match.umpire_name_type_id
INNER JOIN match_played ON match_played.ID = umpire_name_type_match.match_id
INNER JOIN round ON round.ID = match_played.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id;




INSERT INTO mv_report_06_staging (umpire_type_name, age_group, first_umpire, second_umpire, match_ID) 
SELECT 
umpire_type1.umpire_type_name, 
age_group.age_group, 
CONCAT(umpire1.last_name, ', ', umpire1.first_name) AS first_umpire, 
CONCAT(umpire2.last_name, ', ', umpire2.first_name) AS second_umpire,
match_played1.ID
FROM umpire AS umpire1
INNER JOIN umpire_name_type AS umpire_name_type1 ON umpire1.ID = umpire_name_type1.umpire_id
INNER JOIN umpire_type AS umpire_type1 ON umpire_type1.ID = umpire_name_type1.umpire_type_id
INNER JOIN umpire_name_type_match AS umpire_name_type_match1 ON umpire_name_type1.ID = umpire_name_type_match1.umpire_name_type_id
INNER JOIN match_played AS match_played1 ON match_played1.ID = umpire_name_type_match1.match_id
INNER JOIN match_played AS match_played2 ON match_played1.ID = match_played2.ID
INNER JOIN umpire_name_type_match AS umpire_name_type_match2 ON umpire_name_type_match2.match_id = match_played2.ID
INNER JOIN umpire_name_type AS umpire_name_type2 ON umpire_name_type_match2.umpire_name_type_id = umpire_name_type2.ID
INNER JOIN umpire AS umpire2 ON umpire_name_type2.umpire_id = umpire2.ID
INNER JOIN umpire_type AS umpire_type2 ON umpire_type2.ID = umpire_name_type2.umpire_type_id
INNER JOIN round ON round.ID = match_played1.round_id AND round.ID = match_played2.round_id
INNER JOIN league ON league.ID = round.league_id
INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
WHERE umpire1.first_name <> umpire2.first_name
AND umpire1.last_name <> umpire2.last_name
AND umpire_type1.ID = umpire_name_type2.umpire_type_id;


DELETE FROM mv_report_06;

INSERT INTO mv_report_06 (umpire_type_name, age_group, first_umpire, second_umpire, match_count) 
SELECT u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name, COUNT(s.match_id)
FROM mv_umpire_list u1
INNER JOIN mv_umpire_list u2 ON u1.umpire_type_name = u2.umpire_type_name AND u1.age_group = u2.age_group
LEFT OUTER JOIN mv_report_06_staging s
ON (u1.umpire_name = s.first_umpire 
AND u2.umpire_name = s.second_umpire 
AND u1.umpire_type_name = s.umpire_type_name
AND u1.age_group = s.age_group
)
GROUP BY u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name
ORDER BY u1.umpire_type_name, u1.age_group, u1.umpire_name, u2.umpire_name;

/*
SELECT 
umpire_type_name, 
age_group, 
first_umpire, 
second_umpire, 
COUNT(ID) AS match_count
FROM (
	SELECT 
	umpire_type1.umpire_type_name, 
    age_group.age_group, 
	match_played1.ID, 
	CONCAT(umpire1.last_name, ', ', umpire1.first_name) AS first_umpire, 
	CONCAT(umpire2.last_name, ', ', umpire2.first_name) AS second_umpire
	FROM umpire AS umpire1
	INNER JOIN umpire_name_type AS umpire_name_type1 ON umpire1.ID = umpire_name_type1.umpire_id
	INNER JOIN umpire_type AS umpire_type1 ON umpire_type1.ID = umpire_name_type1.umpire_type_id
	INNER JOIN umpire_name_type_match AS umpire_name_type_match1 ON umpire_name_type1.ID = umpire_name_type_match1.umpire_name_type_id
	INNER JOIN match_played AS match_played1 ON match_played1.ID = umpire_name_type_match1.match_id
	INNER JOIN match_played AS match_played2 ON match_played1.ID = match_played2.ID
	INNER JOIN umpire_name_type_match AS umpire_name_type_match2 ON umpire_name_type_match2.match_id = match_played2.ID
	INNER JOIN umpire_name_type AS umpire_name_type2 ON umpire_name_type_match2.umpire_name_type_id = umpire_name_type2.ID
	INNER JOIN umpire AS umpire2 ON umpire_name_type2.umpire_id = umpire2.ID
	INNER JOIN umpire_type AS umpire_type2 ON umpire_type2.ID = umpire_name_type2.umpire_type_id
	INNER JOIN round ON round.ID = match_played1.round_id AND round.ID = match_played2.round_id
	INNER JOIN league ON league.ID = round.league_id
	INNER JOIN age_group_division ON age_group_division.ID = league.age_group_division_id
	INNER JOIN age_group ON age_group.ID = age_group_division.age_group_id
	WHERE umpire1.first_name <> umpire2.first_name
	AND umpire1.last_name <> umpire2.last_name
	AND umpire_type1.ID = umpire_name_type2.umpire_type_id
) sub
GROUP BY umpire_type_name, age_group, first_umpire, second_umpire

*/
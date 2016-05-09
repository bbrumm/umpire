SELECT DISTINCT ground
FROM match_import
WHERE ground NOT IN (
SELECT main_name
FROM ground
)
ORDER BY ground;



SELECT concat_team FROM (
SELECT REPLACE(CONCAT(l.short_league_name, '|', c.club_name), ' ', '_') AS concat_team
FROM league l
INNER JOIN competition_lookup cl ON cl.league_id = l.id
INNER JOIN match_import m ON m.competition_name = cl.competition_name
INNER JOIN team t ON t.team_name = m.home_team
INNER JOIN club c ON c.id = t.club_id
UNION
SELECT REPLACE(CONCAT(l.short_league_name, '|', c.club_name), ' ', '_')
FROM league l
INNER JOIN competition_lookup cl ON cl.league_id = l.id
INNER JOIN match_import m ON m.competition_name = cl.competition_name
INNER JOIN team t ON t.team_name = m.away_team
INNER JOIN club c ON c.id = t.club_id) sub
WHERE sub.concat_team COLLATE utf8_general_ci NOT IN (
	SELECT COLUMN_NAME
	FROM INFORMATION_SCHEMA.COLUMNS
	WHERE table_name = 'mv_report_01'
)
ORDER BY concat_team;





SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_name = 'mv_report_01'
order by column_name;



SELECT m.away_team
FROM match_import m
WHERE m.away_team NOT IN (
	SELECT team_name
	FROM team
);



SELECT competition_name
FROM (
SELECT distinct m.competition_name,
d.division_name

FROM Match_import m
LEFT OUTER JOIN competition_lookup c ON m.competition_name = c.competition_name
LEFT OUTER JOIN
league l ON (l.ID = c.league_id)
	LEFT OUTER JOIN
age_group_division agd ON l.age_group_division_id = agd.ID
	LEFT OUTER JOIN
age_group ag ON ag.ID = agd.age_group_id
	LEFT OUTER JOIN
division d ON d.ID = agd.division_id
) sub
WHERE division_name IS NULL
ORDER BY competition_name;
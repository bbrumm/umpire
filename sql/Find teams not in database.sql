INSERT INTO team (team_name, club_id)
SELECT home_team, NULL
FROM match_import
WHERE home_team NOT IN (
	SELECT team_name
    FROM team
)
UNION
SELECT away_team, NULL
FROM match_import
WHERE away_team NOT IN (
	SELECT team_name
    FROM team
);

SELECT DISTINCT club_name
FROM club
ORDER BY club_name ASC;

SELECT
t.id AS team_id,
t.team_name,
c.id AS club_id,
c.club_name
FROM team t
LEFT JOIN club c ON t.club_id = c.id;

DELETE FROM team WHERE id IN (170, 171);

DELETE FROM match_played WHERE home_team_id IN (170, 171) OR away_team_id IN (170, 171);
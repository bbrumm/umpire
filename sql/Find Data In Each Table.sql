/*
Find data for a match in each of the tables
*/

SELECT *
FROM match_import m
WHERE m.competition_name = 'AFL Barwon - 2017 Bellarine FNL Seniors Dow Cup'
/*AND m.ground = ''*/
AND m.home_team = 'Newcomb'
AND m.away_team = 'Queenscliff'
AND m.round = 2;

SELECT *
FROM match_staging m
WHERE m.appointments_compname = 'AFL Barwon - 2017 Bellarine FNL Seniors Dow Cup'
AND m.appointments_hometeam = 'Newcomb'
AND m.appointments_awayteam = 'Queenscliff'
AND m.appointments_round = 2;

SELECT *
FROM match_played m
WHERE m.home_team_id = 44
AND m.away_team_id = 58
AND m.match_staging_id = 101;

/*
Find match data based on match_id
*/
SELECT m.id,
m.match_time,
g.alternative_name,
ht.team_name,
at.team_name,
m.*
FROM match_played m
INNER JOIN ground g ON m.ground_id = g.id
INNER JOIN team ht ON m.home_team_id = ht.ID
INNER JOIN team at ON m.away_team_id = at.ID
WHERE m.ID IN (
403136,
403137,
403138,
403139,
403255,
403256
);


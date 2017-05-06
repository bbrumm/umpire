SELECT m.*,
ht.team_name,
at.team_name
FROM match_played m
INNER JOIN team ht ON m.home_team_id = ht.ID
INNER JOIN team at ON m.away_team_id = at.ID
WHERE m.id IN (
400777,
400778,
400779,
400780,
400781,
400841,
400842,
400843,
400844,
400873,
400874,
400875,
400876,
400877,
400909,
400910,
400911,
400912,
400974,
400975,
400976,
400977,
400978
);

SELECT mi.*
FROM match_import mi
WHERE mi.competition_name = 'AFL Barwon - 2017 Bellarine FNL Seniors Dow Cup'
AND (mi.date, mi.home_team, mi.away_team) NOT IN (
	SELECT DATE(m.match_time),
    /*m.id,*/
	ht.team_name,
	at.team_name
    /*r.league_id,
    cl.id*/
	FROM match_played m
	LEFT JOIN team ht ON m.home_team_id = ht.ID
	LEFT JOIN team at ON m.away_team_id = at.ID
    LEFT JOIN round r ON m.round_id = r.id
    LEFT JOIN league l ON r.league_id = l.id
    LEFT JOIN competition_lookup cl ON l.id = cl.league_id
    WHERE cl.competition_name = 'AFL Barwon - 2017 Bellarine FNL Seniors Dow Cup'
    AND cl.season_id = 3

)
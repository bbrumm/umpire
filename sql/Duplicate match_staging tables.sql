DELETE n1 FROM names n1, names n2 WHERE n1.id > n2.id AND n1.name = n2.name;



DELETE m1
FROM match_staging m1, match_staging m2
WHERE m1.appointments_id > m2.appointments_id
AND m1.ground_id = m2.ground_id
AND m1.round_id = m2.round_id
AND m1.appointments_time = m2.appointments_time
AND m1.home_team_id = m2.home_team_id
AND m1.away_team_id = m2.away_team_id;


SELECT m1.*
FROM match_staging m1, match_staging m2
WHERE m1.appointments_id > m2.appointments_id
AND m1.ground_id = m2.ground_id
AND m1.round_id = m2.round_id
AND m1.appointments_time = m2.appointments_time
AND m1.home_team_id = m2.home_team_id
AND m1.away_team_id = m2.away_team_id;

SELECT m1.*
FROM match_staging m1;


SELECT m1.ground_id, m1.round_id, m1.appointments_time, m1.home_team_id, m1.away_team_id, COUNT(*)
FROM match_staging m1
GROUP BY m1.ground_id, m1.round_id, m1.appointments_time, m1.home_team_id, m1.away_team_id
HAVING COUNT(*) > 1
ORDER BY m1.ground_id, m1.round_id, m1.appointments_time, m1.home_team_id, m1.away_team_id;





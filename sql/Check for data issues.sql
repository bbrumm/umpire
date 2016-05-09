SELECT s.umpire_full_name, s.club_name, s.short_league_name, s.age_group, s.umpire_type, s.match_count AS match_count_staging, r.match_count AS match_count_report01
FROM test_matchcount_staging s
LEFT OUTER JOIN test_matchcount_report_01 r
ON s.umpire_full_name = r.umpire_full_name
AND s.club_name = r.club_name
AND s.short_league_name = r.short_league_name
AND s.age_group = r.age_group
AND s.umpire_type = r.umpire_type
WHERE r.season_year = 2016
AND s.match_count <> r.match_count
ORDER BY s.umpire_full_name, s.club_name, s.short_league_name, s.age_group, s.umpire_type;



SELECT *
FROM test_matchcount_staging
WHERE umpire_full_name = 'Dorling, Daniel';

SELECT *
FROM test_matchcount_report_01
WHERE umpire_full_name = 'Dorling, Daniel'
AND season_year = 2016;

SELECT *
FROM mv_report_01
WHERE full_name = 'Dorling, Daniel'
AND season_year = 2016;

SELECT *
FROM match_staging
WHERE appointments_hometeam IN ('Bell Post Hill', 'Bannockburn');
/*WHERE appointments_compname = 'GDFL - Smiths Holden Cup Seniors 2016'*/

SELECT *
FROM Round
where round_number = 1;

SELECT *
FROM team;

SELECT DISTINCT ground
FROM match_import
WHERE ground NOT IN (
SELECT main_name
FROM ground
)
ORDER BY ground;

SELECT * 
FROM ground
order by alternative_name;

DELETE FROM ground WHERE id = 64;

SELECT *
FROM Match_played
where ground_id IN (22, 64);

DELETE FROM match_played WHERE ground_id = 64;

SELECT * FROM club;


SELECT *
FROM Match_import
WHERE goal_umpire_1 = 'Ian Beddison'
OR goal_umpire_2 = 'Ian Beddison'
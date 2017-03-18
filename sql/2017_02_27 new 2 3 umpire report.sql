SELECT  
d2.match_played_ID,  
COUNT(DISTINCT d2.umpire_ID) AS umpire_count  
FROM mv_denormalised d2
WHERE d2.umpire_type_name = 'Field'
AND d2.age_group = 'Seniors'  
GROUP BY d2.match_played_ID
HAVING COUNT(DISTINCT d2.umpire_ID) IN (2, 3);

SELECT *
FROM mv_denormalised
WHERE match_played_ID = 341281;


SELECT *
FROM mv_denormalised
WHERE match_played_ID IN (
	SELECT  
	d2.match_played_ID 
	FROM mv_denormalised d2
	WHERE d2.umpire_type_name = 'Field'
	AND d2.age_group = 'Seniors'  
	GROUP BY d2.match_played_ID 
	HAVING COUNT(DISTINCT d2.umpire_ID) IN (2, 3)
);




SELECT   
d1.season_year, 
d1.age_group,  
d1.short_league_name,
sub.umpire_count,
COUNT(DISTINCT d1.match_played_ID) AS match_count
FROM mv_denormalised d1
INNER JOIN ( 
	SELECT  
	d2.match_played_ID,  
	COUNT(DISTINCT d2.umpire_ID) AS umpire_count  
	FROM mv_denormalised d2
	WHERE d2.umpire_type_name = 'Field'
	GROUP BY d2.season_year, d2.match_played_ID, d2.umpire_type_name, d2.age_group, d2.short_league_name
	HAVING COUNT(DISTINCT d2.umpire_ID) IN (2, 3)
) AS sub ON d1.match_played_ID = sub.match_played_ID  
WHERE d1.umpire_type_name = 'Field'
GROUP BY d1.season_year, d1.age_group_ID , d1.age_group, d1.short_league_name, sub.umpire_count;


/*
Report query to populate mv_report_07
*/
TRUNCATE mv_report_07;

INSERT INTO mv_report_07 (season_year, umpire_type, age_group, short_league_name, display_order,
`GFL|2 Umpires`, `GFL|3 Umpires`, 
`BFL|2 Umpires`, `BFL|3 Umpires`, 
`GDFL|2 Umpires`, `GDFL|3 Umpires`, 
`GJFL|2 Umpires`, `GJFL|3 Umpires`, 
`CDFNL|2 Umpires`, `CDFNL|3 Umpires`)
SELECT   
d1.season_year, 
'Field' as umpire_type,
d1.age_group,  
d1.short_league_name,
d1.display_order,
(CASE WHEN short_league_name = 'GFL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GFL|2 Umpires',
(CASE WHEN short_league_name = 'GFL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GFL|3 Umpires',
(CASE WHEN short_league_name = 'BFL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'BFL|2 Umpires',
(CASE WHEN short_league_name = 'BFL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'BFL|3 Umpires',
(CASE WHEN short_league_name = 'GDFL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GDFL|2 Umpires',
(CASE WHEN short_league_name = 'GDFL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GDFL|3 Umpires',
(CASE WHEN short_league_name = 'GJFL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GJFL|2 Umpires',
(CASE WHEN short_league_name = 'GJFL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'GJFL|3 Umpires',
(CASE WHEN short_league_name = 'CDFNL' AND sub.umpire_count = 2 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'CDFNL|2 Umpires',
(CASE WHEN short_league_name = 'CDFNL' AND sub.umpire_count = 3 THEN COUNT(DISTINCT d1.match_played_ID) ELSE 0 END) AS 'CDFNL|3 Umpires'
FROM mv_denormalised d1
INNER JOIN ( 
	SELECT  
	d2.match_played_ID,  
	COUNT(DISTINCT d2.umpire_ID) AS umpire_count  
	FROM mv_denormalised d2
	WHERE d2.umpire_type_name = 'Field'
	GROUP BY d2.season_year, d2.match_played_ID, d2.umpire_type_name, d2.age_group, d2.short_league_name
	HAVING COUNT(DISTINCT d2.umpire_ID) IN (2, 3)
) AS sub ON d1.match_played_ID = sub.match_played_ID  
WHERE d1.umpire_type_name = 'Field'
GROUP BY d1.season_year, d1.age_group_ID , d1.age_group, d1.short_league_name, sub.umpire_count;


SELECT * FROM mv_report_07;


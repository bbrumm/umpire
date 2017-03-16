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
	/*AND d2.age_group = 'Seniors'  */
	GROUP BY d2.season_year, d2.match_played_ID, d2.umpire_type_name, d2.age_group, d2.short_league_name
	HAVING COUNT(DISTINCT d2.umpire_ID) IN (2, 3)
) AS sub ON d1.match_played_ID = sub.match_played_ID  
WHERE d1.umpire_type_name = 'Field'
/*AND d1.age_group = 'Seniors'  */
GROUP BY d1.season_year, d1.age_group_ID , d1.age_group, d1.short_league_name, sub.umpire_count;
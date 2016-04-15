SELECT *
FROM mv_report_06
WHERE first_umpire = 'Bisinella, Tiana';

SELECT *
FROM mv_report_06_staging
WHERE first_umpire = 'Bisinella, Tiana';

SELECT first_umpire, second_umpire, count(*)
FROM mv_report_06_staging
WHERE first_umpire = 'Bisinella, Tiana'
GROUP BY first_umpire, second_umpire;
/*This table returns duplicate match_ids*/

/*Query that populates mv_06_report_staging. Also shows duplicates.*/
SELECT  
season.season_year, region.region_name, umpire_type1.umpire_type_name,  
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
INNER JOIN season ON season.id = round.season_id 
INNER JOIN region ON league.region_id = region.id 
WHERE umpire1.first_name <> umpire2.first_name 
AND umpire1.last_name <> umpire2.last_name 
AND umpire_type1.ID = umpire_name_type2.umpire_type_id 
AND season.season_year = '2016'
AND umpire1.last_name = 'Bisinella'
AND umpire1.first_name = 'Tiana';


/*Modified query that populates mv_report_06_staging to find duplicate record information*/
SELECT  
season.season_year, region.region_name, umpire_type1.umpire_type_name,  
age_group.age_group,  
CONCAT(umpire1.last_name, ', ', umpire1.first_name) AS first_umpire,  
CONCAT(umpire2.last_name, ', ', umpire2.first_name) AS second_umpire, 
match_played1.ID,
'CHECK',
umpire2.*,
umpire_name_type2.*

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
INNER JOIN season ON season.id = round.season_id 
INNER JOIN region ON league.region_id = region.id 
WHERE umpire1.first_name <> umpire2.first_name 
AND umpire1.last_name <> umpire2.last_name 
AND umpire_type1.ID = umpire_name_type2.umpire_type_id 
AND season.season_year = '2016'
AND umpire1.last_name = 'Bisinella'
AND umpire1.first_name = 'Tiana';
/*ALTER TABLE match_staging DROP COLUMN match_played_id ;*/

ALTER TABLE match_staging ADD COLUMN match_staging_id INT(11) PRIMARY KEY AUTO_INCREMENT;

ALTER TABLE match_played DROP COLUMN match_staging_id;
ALTER TABLE match_played ADD COLUMN match_staging_id INT(11);

/*
UPDATE match_staging ms SET ms.match_played_id = 
(SELECT mp.id
FROM match_played mp
WHERE (mp.away_team_id = ms.away_team_id) 
		AND (mp.home_team_id = ms.home_team_id) 
		AND (mp.ground_id = ms.ground_id) 
		AND (mp.round_id = ms.round_ID) 
         AND (mp.match_time = ms.appointments_time));
         
*/
      
SELECT
mp.id, 
ms.away_team_id,
ms.home_team_id,
ms.ground_id,
ms.round_ID,
ms.appointments_time
FROM match_staging ms
INNER JOIN match_played mp 
ON (mp.away_team_id = ms.away_team_id) 
AND (mp.home_team_id = ms.home_team_id) 
AND (mp.ground_id = ms.ground_id) 
AND (mp.round_id = ms.round_ID) 
AND (mp.match_time = ms.appointments_time);



      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging  ON
		
		 (match_played.away_team_id = match_staging.away_team_id) 
		AND (match_played.home_team_id = match_staging.home_team_id) 
		AND (match_played.ground_id = match_staging.ground_id) 
		AND (match_played.round_id = match_staging.round_ID) 
         AND (match_played.match_time = match_staging.appointments_time) 
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) 
		AND (umpire.last_name = match_staging.appointments_field1_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Field' 
CREATE TEMPORARY TABLE temp_umpire_details (
umpire_id INT(11),
first_name VARCHAR(100),
last_name VARCHAR(100),
umpire_type_id INT(11),
umpire_type_name VARCHAR(100),
umpire_name_type_id INT(11)
);

INSERT INTO temp_umpire_details (
umpire_id,
first_name,
last_name,
umpire_type_id,
umpire_type_name,
umpire_name_type_id)
SELECT
u.id,
u.first_name,
u.last_name,
ut.id,
ut.umpire_type_name,
unt.id
FROM umpire u
INNER JOIN umpire_name_type unt ON u.id = unt.umpire_id
INNER JOIN umpire_type ut ON unt.umpire_type_id = ut.id;

CREATE INDEX idx_tud_01 ON temp_umpire_details(first_name, last_name);
CREATE INDEX idx_tud_02 ON temp_umpire_details(umpire_type_name);

/* Runs in 0.109 sec */
      SELECT tud.umpire_name_type_id as umpire_name_type_id, mp.ID as match_id 
	FROM match_played mp
	INNER JOIN match_staging ms ON ms.match_staging_id = mp.match_staging_id
    INNER JOIN temp_umpire_details tud
		ON tud.first_name = ms.appointments_field1_first
        AND tud.last_name = ms.appointments_field1_last
	WHERE tud.umpire_type_name = 'Field';
    
    
    /*Runs in 0.187 sec */
    SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Field' ;
    
    /*
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Field' 

*/
/*

        SELECT umpire_name_type_id, match_id 
        FROM (
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Field' 
    
	UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field2_first) AND (umpire.last_name = match_staging.appointments_field2_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Field' 
	UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field3_first) AND (umpire.last_name = match_staging.appointments_field3_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Field' 
	UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary1_first) AND (umpire.last_name = match_staging.appointments_boundary1_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Boundary' 
	UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary2_first) AND (umpire.last_name = match_staging.appointments_boundary2_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Boundary' 
	UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary3_first) AND (umpire.last_name = match_staging.appointments_boundary3_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Boundary' 
	UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary4_first) AND (umpire.last_name = match_staging.appointments_boundary4_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Boundary' 
	UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
      FROM match_played 
      INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
      INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary5_first) AND (umpire.last_name = match_staging.appointments_boundary5_last) 
      INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
      INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
      WHERE umpire_type.umpire_type_name = 'Boundary' 
      UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
      FROM match_played 
      INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
      INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary6_first) AND (umpire.last_name = match_staging.appointments_boundary6_last) 
      INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
      INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
      WHERE umpire_type.umpire_type_name = 'Boundary' 
      UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal1_first) AND (umpire.last_name = match_staging.appointments_goal1_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Goal' 
	UNION ALL 
      SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id 
	FROM match_played 
	INNER JOIN match_staging ON match_staging.match_staging_id = match_played.match_staging_id
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal2_first) AND (umpire.last_name = match_staging.appointments_goal2_last) 
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id 
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id 
	WHERE umpire_type.umpire_type_name = 'Goal') AS ump;
    */
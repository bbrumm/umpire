INSERT INTO umpire_name_type_match ( umpire_name_type_id, match_id )

SELECT umpire_name_type_id, match_id
FROM (
	SELECT umpire_name_type.ID as umpire_name_type_id, match.ID as match_id
	FROM (umpire 
	INNER JOIN ([match] 
		INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)
		) ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last)
	) 
	INNER JOIN (umpire_type 
		INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id)
	ON umpire.ID = umpire_name_type.umpire_id
WHERE umpire_type.umpire_type_name="Field"
UNION ALL

	SELECT umpire_name_type.ID, match.ID
	FROM (umpire INNER JOIN ([match] INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)) ON (umpire.first_name = match_staging.appointments_field2_first) AND (umpire.last_name = match_staging.appointments_field2_last)) INNER JOIN (umpire_type INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
	WHERE (((umpire_type.umpire_type_name)="Field"))

	UNION ALL

	SELECT umpire_name_type.ID, match.ID
	FROM (umpire INNER JOIN ([match] INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)) ON (umpire.first_name = match_staging.appointments_field3_first) AND (umpire.last_name = match_staging.appointments_field3_last)) INNER JOIN (umpire_type INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
	WHERE (((umpire_type.umpire_type_name)="Field"))

	UNION ALL

	SELECT umpire_name_type.ID, match.ID
	FROM (umpire INNER JOIN ([match] INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)) ON (umpire.first_name = match_staging.appointments_boundary1_first) AND (umpire.last_name = match_staging.appointments_boundary1_last)) INNER JOIN (umpire_type INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
	WHERE (((umpire_type.umpire_type_name)="Boundary"))

	UNION ALL

	SELECT umpire_name_type.ID, match.ID
	FROM (umpire INNER JOIN ([match] INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)) ON (umpire.first_name = match_staging.appointments_boundary2_first) AND (umpire.last_name = match_staging.appointments_boundary2_last)) INNER JOIN (umpire_type INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
	WHERE (((umpire_type.umpire_type_name)="Boundary"))

	UNION ALL

	SELECT umpire_name_type.ID, match.ID
	FROM (umpire INNER JOIN ([match] INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)) ON (umpire.first_name = match_staging.appointments_boundary3_first) AND (umpire.last_name = match_staging.appointments_boundary3_last)) INNER JOIN (umpire_type INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
	WHERE (((umpire_type.umpire_type_name)="Boundary"))

	UNION ALL

	SELECT umpire_name_type.ID, match.ID
	FROM (umpire INNER JOIN ([match] INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)) ON (umpire.first_name = match_staging.appointments_boundary4_first) AND (umpire.last_name = match_staging.appointments_boundary4_last)) INNER JOIN (umpire_type INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
	WHERE (((umpire_type.umpire_type_name)="Boundary"))

	UNION ALL

	SELECT umpire_name_type.ID, match.ID
	FROM (umpire INNER JOIN ([match] INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)) ON (umpire.first_name = match_staging.appointments_goal1_first) AND (umpire.last_name = match_staging.appointments_goal1_last)) INNER JOIN (umpire_type INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
	WHERE (((umpire_type.umpire_type_name)="Goal"))

	UNION ALL

	SELECT umpire_name_type.ID, match.ID
	FROM (umpire INNER JOIN ([match] INNER JOIN match_staging ON (match.match_time = match_staging.appointments_time) AND (match.away_team_id = match_staging.away_team_id) AND (match.home_team_id = match_staging.home_team_id) AND (match.ground_id = match_staging.ground_id) AND (match.round_id = match_staging.round_ID)) ON (umpire.first_name = match_staging.appointments_goal2_first) AND (umpire.last_name = match_staging.appointments_goal2_last)) INNER JOIN (umpire_type INNER JOIN umpire_name_type ON umpire_type.ID = umpire_name_type.umpire_type_id) ON umpire.ID = umpire_name_type.umpire_id
	WHERE (((umpire_type.umpire_type_name)="Goal"))
)  AS ump;




INSERT INTO umpire_name_type_match ( umpire_name_type_id, match_id )
SELECT umpire_name_type_id, match_id
FROM (
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field1_first) AND (umpire.last_name = match_staging.appointments_field1_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Field"
	UNION ALL
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field2_first) AND (umpire.last_name = match_staging.appointments_field2_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Field"
	UNION ALL
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_field3_first) AND (umpire.last_name = match_staging.appointments_field3_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Field"
	UNION ALL
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary1_first) AND (umpire.last_name = match_staging.appointments_boundary1_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Boundary"
	UNION ALL
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary2_first) AND (umpire.last_name = match_staging.appointments_boundary2_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Boundary"
	UNION ALL
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary3_first) AND (umpire.last_name = match_staging.appointments_boundary3_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Boundary"
	UNION ALL
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_boundary4_first) AND (umpire.last_name = match_staging.appointments_boundary4_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Boundary"
	UNION ALL
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal1_first) AND (umpire.last_name = match_staging.appointments_goal1_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Goal"
	UNION ALL
	SELECT umpire_name_type.ID as umpire_name_type_id, match_played.ID as match_id
	FROM match_played
	INNER JOIN match_staging ON (match_played.match_time = match_staging.appointments_time) AND (match_played.away_team_id = match_staging.away_team_id) AND (match_played.home_team_id = match_staging.home_team_id) AND (match_played.ground_id = match_staging.ground_id) AND (match_played.round_id = match_staging.round_ID)
	INNER JOIN umpire ON (umpire.first_name = match_staging.appointments_goal2_first) AND (umpire.last_name = match_staging.appointments_goal2_last)
	INNER JOIN umpire_name_type ON umpire.ID = umpire_name_type.umpire_id
	INNER JOIN umpire_type ON umpire_type.ID = umpire_name_type.umpire_type_id
	WHERE umpire_type.umpire_type_name = "Goal"
)  AS ump;
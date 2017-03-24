DROP PROCEDURE `FindMissingData`;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `FindMissingData`()
BEGIN

DECLARE vSeasonYear INT(4);

TRUNCATE TABLE incomplete_records;
/*
Find new or incomplete competition records.
These records were inserted as part of the ETL job, but the league was not assigned, as it needs a user to confirm the values.
*/

INSERT INTO incomplete_records(record_type, source_id, source_value)
SELECT DISTINCT 'competition', id, competition_name
FROM competition_lookup
WHERE league_id IS NULL;

/*
Find new or incomplete teams
*/

INSERT INTO incomplete_records(record_type, source_id, source_value)
SELECT DISTINCT 'team', id, team_name
FROM team
WHERE club_id IS NULL;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE FindMissingData ()
BEGIN

SELECT DISTINCT competition_name
FROM competition_lookup
WHERE league_id IS NULL;

END$$
DELIMITER ;
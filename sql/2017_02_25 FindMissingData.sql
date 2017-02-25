DELIMITER $$
CREATE PROCEDURE `FindMissingData` ()
BEGIN

DECLARE vSeasonYear INT(4);

SELECT DISTINCT competition_name
FROM competition_lookup
WHERE league_id IS NULL;

END$$
DELIMITER ;
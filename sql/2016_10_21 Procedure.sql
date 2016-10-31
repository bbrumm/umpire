drop table test_insert;

CREATE TABLE test_insert (
logtime DATETIME,
test_value VARCHAR(10)
);

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_procedure`()
BEGIN

INSERT INTO test_insert(logtime, test_value) VALUES (NOW(), 'test');

END;


CALL `bbrumm_umpire_data`.`new_procedure`();


SELECT * FROM test_insert;
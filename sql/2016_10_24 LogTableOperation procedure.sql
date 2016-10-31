DROP PROCEDURE `LogTableOperation`;
DELIMITER //
CREATE PROCEDURE LogTableOperation(IN pImportedFileID INT, IN pProcessedTableID INT, IN pOperationID INT, IN pRowCount INT)
BEGIN

INSERT INTO table_operations (imported_file_id, processed_table_id, operation_id, operation_datetime, rowcount)
VALUES (pImportedFileID, pProcessedTableID, pOperationID, NOW(), pRowCount);


END//
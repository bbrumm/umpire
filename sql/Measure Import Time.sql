SELECT imported_file_id,
MIN(operation_datetime) AS start_time,
MAX(operation_datetime) AS end_time,
TIMEDIFF(MAX(operation_datetime),MIN(operation_datetime)) AS elapsed_time
FROM table_operations
GROUP BY imported_file_id
ORDER BY imported_file_id;

SELECT
op.id,
op.processed_table_id,
t.table_name,
r.operation_name,
op.operation_datetime,
op.rowcount
FROM table_operations op
INNER JOIN processed_table t ON op.processed_table_id = t.id
INNER JOIN operation_ref r ON op.operation_id = r.id
WHERE imported_file_id = 96

ORDER BY id ASC;
SELECT imported_file_id,
MIN(operation_datetime) AS start_time,
MAX(operation_datetime) AS end_time,
TIMEDIFF(MAX(operation_datetime),MIN(operation_datetime)) AS elapsed_time
FROM bbrumm_umpire_data.table_operations
GROUP BY imported_file_id
ORDER BY imported_file_id
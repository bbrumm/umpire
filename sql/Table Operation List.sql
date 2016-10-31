SELECT 
t.id,
t.operation_datetime, 
pt.table_name,
o.operation_name,
t.rowcount

FROM table_operations t
INNER JOIN processed_table pt ON t.processed_table_id = pt.id
INNER JOIN operation_ref o ON t.operation_id = o.id
/*WHERE t.imported_file_id = 2*/
/*WHERE pt.table_name = 'umpire_name_type_match'*/
ORDER BY id ASC;
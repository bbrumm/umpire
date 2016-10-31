SELECT * FROM test_insert ORDER BY logtime DESC;

SELECT * FROM table_operations ORDER BY operation_datetime DESC;

select * from season;
select * from imported_files;
/*
DROP TABLE match_staging;
DROP TABLE temp_match_staging_duplicates;
*/
CALL RunETLProcess(2, 57);

ROLLBACK;

/*
Run times (seconds)
Original: 223.843
After indexes on Umpire table:

*/


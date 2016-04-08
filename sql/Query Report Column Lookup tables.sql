SELECT * FROM report_column; /*Update this*/

SELECT * FROM report_column_lookup
WHERE filter_name = 'short_league_name'
AND filter_value = 'BFL'
ORDER BY report_column_id; /*Update this*/

SELECT * FROM report_column_lookup_display
/*WHHERE column_display_filter_name = 'short_league_name'*/
ORDER BY report_column_id;

 /*Update this*/

SELECT
rc.report_column_id,
rc.column_name,
rcl.report_column_lookup_id, 
rcl.filter_name, 
rcl.filter_value, 
rcl.report_table_id,
rcld.column_display_filter_name,
rcld.column_display_name

FROM report_column rc
INNER JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
INNER JOIN report_column_lookup_display rcld ON rc.report_column_id = rcld.report_column_id

WHERE rcld.column_display_name LIKE '%None%'







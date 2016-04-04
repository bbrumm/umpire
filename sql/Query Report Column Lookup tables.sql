SELECT * FROM report_column; /*Update this*/

SELECT * FROM report_column_lookup WHERE filter_name = 'short_league_name'; /*Update this*/

SELECT * FROM report_column_lookup_display WHERE column_display_filter_name = 'short_league_name'; /*Update this*/

SELECT
rc.report_column_id,
rc.column_name,
rcl.report_column_lookup_id, 
rcl.filter_name, 
rcl.filter_value, 
rcl.report_table_id

FROM report_column rc
INNER JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id

UPDATE report_column
SET column_function = NULL
WHERE report_column_id = 92;

INSERT INTO report_column_lookup (report_column_lookup_id, filter_name, filter_value, report_table_id, report_column_id)
VALUES (810, 'short_league_name', 'CDFNL', 2, 92);
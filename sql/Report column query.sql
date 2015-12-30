SELECT DISTINCT
    rc.column_name,
    rcld.report_column_id,
    (SELECT 
            rcld0.column_display_name
        FROM
            report_column_lookup_display rcld0
        WHERE
            rcld0.report_column_id = rcld.report_column_id
                AND rcld0.column_display_filter_name = 'age_group') AS age_group,
    (SELECT 
            rcld1.column_display_name
        FROM
            report_column_lookup_display rcld1
        WHERE
            rcld1.report_column_id = rcld.report_column_id
                AND rcld1.column_display_filter_name = 'short_league_name') AS short_league_name
FROM
    report_column_lookup_display rcld
        JOIN
    report_column rc ON rcld.report_column_id = rc.report_column_id
        JOIN
    report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
        JOIN
    report_table rt ON rcl.report_table_id = rt.report_table_id
WHERE
    rcl.filter_name = 'short_league_name'
        AND rcl.filter_value = 'All'
        AND rt.report_name = 02
AND rc.report_column_id IN (
	SELECT DISTINCT
		
		rcld2.report_column_id
	FROM
		report_column_lookup_display rcld2
			JOIN
		report_column rc2 ON rcld2.report_column_id = rc2.report_column_id
			JOIN
		report_column_lookup rcl2 ON rc2.report_column_id = rcl2.report_column_id
			JOIN
		report_table rt2 ON rcl2.report_table_id = rt2.report_table_id
	WHERE
		rcl2.filter_name = 'age_group'
			AND rcl2.filter_value = 'All'
			AND rt2.report_name = 02
)
ORDER BY rcld.report_column_id , rcld.column_display_filter_name;
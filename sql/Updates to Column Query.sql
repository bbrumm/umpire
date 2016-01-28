/* SELECT 
    GROUP_CONCAT(gc.column_name
        SEPARATOR ', ') AS COLS
FROM
    (
    */
    
    SELECT DISTINCT
        CASE
                WHEN rc.column_function IS NULL THEN CONCAT('`', rc.column_name, '` as `', rc.column_name, '`')
                ELSE CONCAT(rc.column_function, '(`', rc.column_name, '`', ') as `', rc.column_name, '`')
            END AS column_name
    FROM
        report_column rc
    JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
    JOIN report_table rt ON rcl.report_table_id = rt.report_table_id
    JOIN report_column_lookup_display rcld ON rcld.report_column_id = rc.report_column_id
    WHERE
        rcl.filter_name = 'short_league_name'
            AND rcl.filter_value = 'BFL'
            AND rt.report_name = 02
            AND rc.report_column_id IN (
            
            SELECT DISTINCT
                rcld2.report_column_id
                
            FROM
                report_column_lookup_display rcld2
            INNER JOIN report_column rc2 ON rcld2.report_column_id = rc2.report_column_id
            INNER JOIN report_column_lookup rcl2 ON rc2.report_column_id = rcl2.report_column_id
            INNER JOIN report_table rt2 ON rcl2.report_table_id = rt2.report_table_id
            WHERE
                (rcl2.filter_name = 'age_group'
                    AND rcl2.filter_value = 'All'
                    AND rt2.report_name = 02)
			
            AND rcld2.report_column_id IN (
            
				SELECT DISTINCT
					rcld3.report_column_id
					
				FROM
					report_column_lookup_display rcld3
				INNER JOIN report_column rc3 ON rcld3.report_column_id = rc3.report_column_id
				INNER JOIN report_column_lookup rcl3 ON rc3.report_column_id = rcl3.report_column_id
				INNER JOIN report_table rt3 ON rcl3.report_table_id = rt3.report_table_id
				WHERE
					(rcl3.filter_name = 'umpire_type'
						AND rcl3.filter_value = 'Field'
						AND rt3.report_name = 02)
						
				)
                
                
            )
            
            			
            AND rcld.column_display_filter_name = 'short_league_name'
            
            
    /*ORDER BY rc.column_name ASC) gc;*/
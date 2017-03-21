SELECT DISTINCT
    umpire_type_age_group, short_league_name
FROM
    (SELECT 
        weekend_date,
            CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group,
            short_league_name,
            GROUP_CONCAT(team_names) AS team_list,
            (SELECT 
                    COUNT(DISTINCT match_id)
                FROM
                    staging_no_umpires s2
                WHERE
                    s2.age_group = s.age_group
                        AND s2.umpire_type = s.umpire_type
                        AND s2.weekend_date = s.weekend_date
                        AND short_league_name IN ('BFL' , 'GFL', 'GDFL', 'GJFL', '')) AS match_count
    FROM
        staging_no_umpires s
    WHERE
        short_league_name IN ('BFL' , 'GFL', 'GDFL', 'GJFL', '')
            AND CONCAT(age_group, ' ', umpire_type) IN ('Seniors Boundary' , 'Seniors Goal', 'Reserve Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field')
    GROUP BY weekend_date , age_group , umpire_type , short_league_name) AS sub;
    
    
    
SELECT DISTINCT
CONCAT('No ', s.age_group, ' ', s.umpire_type) AS umpire_type_age_group,
s.short_league_name,
s.age_sort_order
FROM staging_all_ump_age_league s
WHERE CONCAT(s.age_group, ' ', s.umpire_type) IN ('Seniors Boundary' , 'Seniors Goal', 'Reserves Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field')
UNION ALL
SELECT DISTINCT
CONCAT('No ', s2.age_group, ' ', s2.umpire_type) AS umpire_type_age_group,
age_sort_order,
'Total'
FROM staging_all_ump_age_league s2
WHERE CONCAT(s2.age_group, ' ', s2.umpire_type) IN ('Seniors Boundary' , 'Seniors Goal', 'Reserves Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field')

ORDER BY age_sort_order, umpire_type, league_sort_order;

    
SELECT DISTINCT
	CONCAT('No ', sub.age_group, ' ', sub.umpire_type) AS umpire_type_age_group,
	sub.short_league_name
	FROM (
	SELECT
	s.age_group,
	s.umpire_type,
	s.short_league_name,
	s.age_sort_order
	FROM staging_all_ump_age_league s
	UNION ALL
	SELECT
	s.age_group,
	s.umpire_type,
	'Total',
	s.age_sort_order
	FROM staging_all_ump_age_league s
	ORDER BY age_sort_order, umpire_type, short_league_name
) sub
WHERE CONCAT(sub.age_group, ' ', sub.umpire_type) IN
	('Seniors Boundary' , 'Seniors Goal', 'Reserves Goal', 'Colts Field', 'Under 16 Field', 'Under 14 Field', 'Under 12 Field');



/* 27 s */
SELECT DISTINCT
a.age_group,
u.umpire_type,
l.short_league_name,
a.sort_order,
l.league_sort_order
FROM dw_dim_age_group a
INNER JOIN dw_fact_match m ON a.age_group_key = m.age_group_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
CROSS JOIN dw_dim_umpire u;

/* 0s */
SELECT DISTINCT
ag.age_group,
ut.umpire_type_name,
l.short_league_name
FROM age_group ag
INNER JOIN age_group_division agd ON ag.ID = agd.age_group_id
INNER JOIN league l ON l.age_group_division_id = agd.ID
CROSS JOIN umpire_type ut
ORDER BY ag.display_order, ut.umpire_type_name, l.short_league_name;






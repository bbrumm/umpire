SELECT DISTINCT
CONCAT('No ', age_group, ' ', umpire_type) AS umpire_type_age_group,
short_league_name
FROM (
SELECT
s.age_group,
s.umpire_type,
s.short_league_name,
s.region_name,
s.age_sort_order
FROM staging_all_ump_age_league s
UNION ALL
SELECT
s.age_group,
s.umpire_type,
'Total',
'Total',
s.age_sort_order
FROM staging_all_ump_age_league s
) sub
WHERE CONCAT(age_group, ' ', umpire_type) IN (
    'Seniors Boundary' ,
    'Seniors Goal',
    'Reserves Goal',
    'Colts Field',
    'Under 16 Field',
    'Under 14 Field',
    'Under 12 Field'
)
AND region_name IN ('Total', :pRegion)
GROUP BY CONCAT('No ', age_group, ' ', umpire_type), short_league_name
ORDER BY MIN(age_sort_order), CONCAT('No ', age_group, ' ', umpire_type), short_league_name;
SELECT
short_league_name,
subtotal
FROM report_5_columns
WHERE region_name = :pRegion
ORDER BY sort_order ASC;
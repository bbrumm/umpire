SELECT
short_league_name,
subtotal
FROM report_5_columns
WHERE region_name = :region
ORDER BY sort_order ASC;
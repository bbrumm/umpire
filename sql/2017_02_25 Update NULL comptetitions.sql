SELECT * FROM bbrumm_umpire_data.competition_lookup
WHERE league_id IS NULL;

SELECT *
FROM League;

SELECT *
FROM age_group_division;

SELECT *
FROM division;

SELECT *
FROM age_group;

SELECT
l.ID AS league_id,
l.league_name,
l.short_league_name,
l.region_id,
ag.id AS age_group_id,
ag.age_group,
d.id AS division_id,
d.division_name,
agd.id AS age_group_division_id
FROM league l
INNER JOIN age_group_division agd ON l.age_group_division_id = agd.ID
INNER JOIN age_group ag ON agd.age_group_id = ag.id
INNER JOIN division d ON agd.division_id = d.ID
WHERE 1=1
AND region_id = 1
ORDER BY League_id ASC;



UPDATE competition_lookup SET league_id = 38 WHERE competition_name = 'AFL Barwon - 2016 Under 14 Div 1 Kempe Cup';
UPDATE competition_lookup SET league_id = 39 WHERE competition_name = 'AFL Barwon - 2016 Under 14 Div 2 Buckley''s Cup';
UPDATE competition_lookup SET league_id = 40 WHERE competition_name = 'AFL Barwon - 2016 Under 14 Div 3 GMHBA Cup';
UPDATE competition_lookup SET league_id = 41 WHERE competition_name = 'AFL Barwon - 2016 Under 14 Div 4 Supatramp Cup';
UPDATE competition_lookup SET league_id = 42 WHERE competition_name = 'AFL Barwon - 2016 Under 14 Div 5 Geelong Advertiser Cup';
UPDATE competition_lookup SET league_id = 43 WHERE competition_name = 'AFL Barwon - 2016 Under 14 Div 6 Red Onion Cup';
UPDATE competition_lookup SET league_id = 44 WHERE competition_name = 'AFL Barwon - 2016 Under 14 Div 7 National Heating & Cooling Cup';
UPDATE competition_lookup SET league_id = 45 WHERE competition_name = 'AFL Barwon - 2016 Under 16 Div 1 Buckley''s Cup';
UPDATE competition_lookup SET league_id = 46 WHERE competition_name = 'AFL Barwon - 2016 Under 16 Div 2 Home Hardware Cup';
UPDATE competition_lookup SET league_id = 47 WHERE competition_name = 'AFL Barwon - 2016 Under 16 Div 3 Geelong Advertiser Cup';
UPDATE competition_lookup SET league_id = 48 WHERE competition_name = 'AFL Barwon - 2016 Under 16 Div 4 GTEC Cup';
UPDATE competition_lookup SET league_id = 49 WHERE competition_name = 'AFL Barwon - 2016 Under 16 Div 5 Coca Cola Cup';

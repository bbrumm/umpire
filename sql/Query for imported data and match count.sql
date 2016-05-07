SELECT sub.umpire_type, sub.umpire_full_name, 
CONCAT(RIGHT(sub.umpire_full_name,Length(sub.umpire_full_name)-InStr(sub.umpire_full_name,' ')),', ',
LEFT(sub.umpire_full_name,InStr(sub.umpire_full_name,' ')-1)) AS umpire_full_name_lastfirst
sub.competition_name, sub.team, l.short_league_name, ag.age_group, COUNT(sub.id) AS match_count, 

FROM (
SELECT 'Field' AS umpire_type, field_umpire_1 AS umpire_full_name, ID, competition_name, home_team AS team FROM match_import
UNION ALL
SELECT 'Field', field_umpire_1, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Field', field_umpire_2, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Field' AS umpire_type, field_umpire_2, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Field', field_umpire_3, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Field' AS umpire_type, field_umpire_3, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Boundary', boundary_umpire_1, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Boundary' AS umpire_type, boundary_umpire_1, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Boundary', boundary_umpire_2, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Boundary' AS umpire_type, boundary_umpire_2, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Boundary', boundary_umpire_3, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Boundary' AS umpire_type, boundary_umpire_3, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Boundary', boundary_umpire_4, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Boundary' AS umpire_type, boundary_umpire_4, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Boundary', boundary_umpire_5, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Boundary' AS umpire_type, boundary_umpire_5, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Boundary', boundary_umpire_6, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Boundary' AS umpire_type, boundary_umpire_6, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Goal', goal_umpire_1, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Goal' AS umpire_type, goal_umpire_1, ID, competition_name, away_team FROM match_import
UNION ALL
SELECT 'Goal', goal_umpire_2, ID, competition_name, home_team FROM match_import
UNION ALL
SELECT 'Goal' AS umpire_type, goal_umpire_2, ID, competition_name, away_team FROM match_import
) sub
LEFT OUTER JOIN competition_lookup c ON sub.competition_name = c.competition_name
LEFT OUTER JOIN league l ON c.league_id = l.id
LEFT OUTER JOIN age_group_division agd ON l.age_group_division_id = agd.id
LEFT OUTER JOIN age_group ag ON agd.age_group_id = ag.id
WHERE sub.umpire_full_name IS NOT NULL
GROUP BY sub.umpire_type, sub.umpire_full_name, sub.competition_name, sub.team, l.short_league_name, ag.age_group
ORDER BY sub.umpire_type, sub.umpire_full_name, sub.competition_name, sub.team, l.short_league_name, ag.age_group
CREATE VIEW test_matchcount_staging_sub AS 
SELECT 'Field' AS umpire_type, field_umpire_1 AS umpire_full_name, ID, competition_name, home_team AS team FROM match_import UNION ALL 
SELECT 'Field', field_umpire_1, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Field', field_umpire_2, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Field' AS umpire_type, field_umpire_2, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Field', field_umpire_3, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Field' AS umpire_type, field_umpire_3, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Boundary', boundary_umpire_1, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Boundary' AS umpire_type, boundary_umpire_1, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Boundary', boundary_umpire_2, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Boundary' AS umpire_type, boundary_umpire_2, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Boundary', boundary_umpire_3, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Boundary' AS umpire_type, boundary_umpire_3, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Boundary', boundary_umpire_4, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Boundary' AS umpire_type, boundary_umpire_4, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Boundary', boundary_umpire_5, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Boundary' AS umpire_type, boundary_umpire_5, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Boundary', boundary_umpire_6, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Boundary' AS umpire_type, boundary_umpire_6, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Goal', goal_umpire_1, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Goal' AS umpire_type, goal_umpire_1, ID, competition_name, away_team FROM match_import UNION ALL 
SELECT 'Goal', goal_umpire_2, ID, competition_name, home_team FROM match_import UNION ALL 
SELECT 'Goal' AS umpire_type, goal_umpire_2, ID, competition_name, away_team FROM match_import;




CREATE OR REPLACE VIEW test_matchcount_staging AS (
SELECT CONCAT(RIGHT(sub.umpire_full_name,Length(sub.umpire_full_name)-InStr(sub.umpire_full_name,' ')),', ', 
LEFT(sub.umpire_full_name,InStr(sub.umpire_full_name,' ')-1)) AS umpire_full_name, 
c.club_name, l.short_league_name, ag.age_group, sub.umpire_type, COUNT(sub.id) AS match_count 
FROM test_matchcount_staging_sub sub 
LEFT OUTER JOIN competition_lookup cl ON sub.competition_name = cl.competition_name 
LEFT OUTER JOIN league l ON cl.league_id = l.id 
LEFT OUTER JOIN age_group_division agd ON l.age_group_division_id = agd.id 
LEFT OUTER JOIN age_group ag ON agd.age_group_id = ag.id 
LEFT OUTER JOIN team t ON sub.team = t.team_name
LEFT OUTER JOIN club c ON t.club_id = c.id
WHERE sub.umpire_full_name IS NOT NULL 
GROUP BY sub.umpire_full_name, c.club_name, l.short_league_name, ag.age_group, sub.umpire_type 
ORDER BY sub.umpire_full_name, c.club_name, l.short_league_name, ag.age_group, sub.umpire_type);




CREATE OR REPLACE VIEW test_matchcount_report_01 AS 
SELECT full_name AS umpire_full_name, club_name, short_league_name, age_group, umpire_type_name AS umpire_type, season_year, 
IFNULL(`BFL|Anglesea`, 0) +   
IFNULL(`BFL|Barwon_Heads`, 0) +   
IFNULL(`BFL|Drysdale`, 0) +   
IFNULL(`BFL|Geelong_Amateur`, 0) +   
IFNULL(`BFL|Modewarre`, 0) +   
IFNULL(`BFL|Newcomb_Power`, 0) +   
IFNULL(`BFL|Ocean_Grove`, 0) +   
IFNULL(`BFL|Portarlington`, 0) +   
IFNULL(`BFL|Queenscliff`, 0) +   
IFNULL(`BFL|Torquay`, 0) +   
IFNULL(`GDFL|Anakie`, 0) +   
IFNULL(`GDFL|Bannockburn`, 0) +   
IFNULL(`GDFL|Bell_Post_Hill`, 0) +   
IFNULL(`GDFL|Belmont_Lions`, 0) +   
IFNULL(`GDFL|Corio`, 0) +   
IFNULL(`GDFL|East_Geelong`, 0) +   
IFNULL(`GDFL|Geelong_West`, 0) +   
IFNULL(`GDFL|Inverleigh`, 0) +   
IFNULL(`GDFL|North_Geelong`, 0) +   
IFNULL(`GDFL|Thomson`, 0) +   
IFNULL(`GDFL|Werribee_Centrals`, 0) +   
IFNULL(`GDFL|Winchelsea`, 0) +   
IFNULL(`GFL|Bell_Park`, 0) +   
IFNULL(`GFL|Colac`, 0) +   
IFNULL(`GFL|Grovedale`, 0) +   
IFNULL(`GFL|Gwsp`, 0) +   
IFNULL(`GFL|Lara`, 0) +   
IFNULL(`GFL|Leopold`, 0) +   
IFNULL(`GFL|Newtown_&_Chilwell`, 0) +   
IFNULL(`GFL|North_Shore`, 0) +   
IFNULL(`GFL|South_Barwon`, 0) +   
IFNULL(`GFL|St_Albans`, 0) +   
IFNULL(`GFL|St_Joseph's`, 0) +   
IFNULL(`GFL|St_Mary's`, 0) +   
IFNULL(`GJFL|Anakie`, 0) +   
IFNULL(`GJFL|Anglesea`, 0) +   
IFNULL(`GJFL|Bannockburn`, 0) +   
IFNULL(`GJFL|Barwon_Heads`, 0) +   
IFNULL(`GJFL|Bell_Park`, 0) +   
IFNULL(`GJFL|Belmont_Lions_/_Newcomb`, 0) +   
IFNULL(`GJFL|Belmont_Lions`, 0) +   
IFNULL(`GJFL|Colac`, 0) +   
IFNULL(`GJFL|Corio`, 0) +   
IFNULL(`GJFL|Drysdale_Bennett`, 0) +   
IFNULL(`GJFL|Drysdale_Byrne`, 0) +   
IFNULL(`GJFL|Drysdale_Eddy`, 0) +   
IFNULL(`GJFL|Drysdale_Hall`, 0) +   
IFNULL(`GJFL|Drysdale_Hector`, 0) +   
IFNULL(`GJFL|Drysdale`, 0) +  
IFNULL(`GJFL|East_Geelong`, 0) +   
IFNULL(`GJFL|Geelong_Amateur`, 0) +   
IFNULL(`GJFL|Geelong_West_St_Peters`, 0) +   
IFNULL(`GJFL|Grovedale`, 0) +   
IFNULL(`GJFL|Gwsp_/_Bannockburn`, 0) +   
IFNULL(`GJFL|Inverleigh`, 0) +   
IFNULL(`GJFL|Lara`, 0) +   
IFNULL(`GJFL|Leopold`, 0) +   
IFNULL(`GJFL|Modewarre`, 0) +   
IFNULL(`GJFL|Newcomb`, 0) +   
IFNULL(`GJFL|Newtown_&_Chilwell`, 0) +   
IFNULL(`GJFL|North_Geelong`, 0) +   
IFNULL(`GJFL|North_Shore`, 0) +   
IFNULL(`GJFL|Ocean_Grove`, 0) +   
IFNULL(`GJFL|Ogcc`, 0) +   
IFNULL(`GJFL|Portarlington`, 0) +   
IFNULL(`GJFL|Queenscliff`, 0) +   
IFNULL(`GJFL|South_Barwon_/_Geelong_Amateur`, 0) +   
IFNULL(`GJFL|South_Barwon`, 0) +   
IFNULL(`GJFL|St_Albans_Allthorpe`, 0) +   
IFNULL(`GJFL|St_Albans_Reid`, 0) +   
IFNULL(`GJFL|St_Albans`, 0) +   
IFNULL(`GJFL|St_Joseph's_Hill`, 0) +   
IFNULL(`GJFL|St_Joseph's_Podbury`, 0) +   
IFNULL(`GJFL|St_Joseph's`, 0) +   
IFNULL(`GJFL|St_Mary's`, 0) +   
IFNULL(`GJFL|Tigers_Gold`, 0) +   
IFNULL(`GJFL|Torquay_Bumpstead`, 0) +   
IFNULL(`GJFL|Torquay_Coles`, 0) +   
IFNULL(`GJFL|Torquay_Dunstan`, 0) +   
IFNULL(`GJFL|Torquay_Jones`, 0) +   
IFNULL(`GJFL|Torquay_Nairn`, 0) +   
IFNULL(`GJFL|Torquay_Papworth`, 0) +   
IFNULL(`GJFL|Torquay_Pyers`, 0) +   
IFNULL(`GJFL|Torquay_Scott`, 0) +   
IFNULL(`GJFL|Torquay`, 0) +   
IFNULL(`GJFL|Werribee_Centrals`, 0) +   
IFNULL(`GJFL|Winchelsea_/_Grovedale`, 0) +   
IFNULL(`GJFL|Winchelsea`, 0) +   
IFNULL(`CDFNL|Birregurra`, 0) +   
IFNULL(`CDFNL|Lorne`, 0) +   
IFNULL(`CDFNL|Apollo Bay`, 0) +   
IFNULL(`CDFNL|Alvie`, 0) +   
IFNULL(`CDFNL|Colac Imperials`, 0) +   
IFNULL(`CDFNL|Irrewarra-beeac`, 0) +   
IFNULL(`CDFNL|Otway Districts`, 0) +   
IFNULL(`CDFNL|Simpson`, 0) +   
IFNULL(`CDFNL|South Colac`, 0) +   
IFNULL(`CDFNL|Western Eagles`, 0) +  
IFNULL(`GJFL|Aireys Inlet`, 0) +   
IFNULL(`GJFL|Ammos Blue`, 0) +   
IFNULL(`GJFL|Ammos Green`, 0) +   
IFNULL(`GJFL|Ammos White`, 0) +   
IFNULL(`GJFL|Bannockburn / South Barwon`, 0) +   
IFNULL(`GJFL|Barwon Heads Gulls`, 0) +   
IFNULL(`GJFL|Barwon Heads Heads`, 0) +   
IFNULL(`GJFL|Dragons`, 0) +   
IFNULL(`GJFL|Drysdale 1`, 0) +   
IFNULL(`GJFL|Drysdale 2`, 0) +   
IFNULL(`GJFL|Drysdale Humphrey`, 0) +   
IFNULL(`GJFL|Drysdale Mcintyre`, 0) +   
IFNULL(`GJFL|Drysdale Mckeon`, 0) +   
IFNULL(`GJFL|Drysdale Scott`, 0) +   
IFNULL(`GJFL|Drysdale Smith`, 0) +   
IFNULL(`GJFL|Drysdale Taylor`, 0) +   
IFNULL(`GJFL|Drysdale Wilson`, 0) +   
IFNULL(`GJFL|Eagles Black`, 0) +   
IFNULL(`GJFL|Eagles Red`, 0) +   
IFNULL(`GJFL|East Newcomb Lions`, 0) +   
IFNULL(`GJFL|East Tigers`, 0) +   
IFNULL(`GJFL|Flying Joeys`, 0) +   
IFNULL(`GJFL|Gdfl Raiders`, 0) +   
IFNULL(`GJFL|Grovedale Broad`, 0) +   
IFNULL(`GJFL|Grovedale Ford`, 0) +   
IFNULL(`GJFL|Grovedale Mcneel`, 0) +   
IFNULL(`GJFL|Grovedale Waldron`, 0) +   
IFNULL(`GJFL|Grovedale Williams`, 0) +   
IFNULL(`GJFL|Grovedale Young`, 0) +   
IFNULL(`GJFL|Lara Batman`, 0) +   
IFNULL(`GJFL|Lara Flinders`, 0) +   
IFNULL(`GJFL|Lara Hume`, 0) +   
IFNULL(`GJFL|Leopold Brown`, 0) +   
IFNULL(`GJFL|Leopold Dowsett`, 0) +   
IFNULL(`GJFL|Leopold Ruggles`, 0) +   
IFNULL(`GJFL|Lethbridge`, 0) +   
IFNULL(`GJFL|Newtown & Chilwell Eagles`, 0) +   
IFNULL(`GJFL|Ogcc Blue`, 0) +   
IFNULL(`GJFL|Ogcc Orange`, 0) +   
IFNULL(`GJFL|Ogcc Red`, 0) +   
IFNULL(`GJFL|Ogcc White`, 0) +   
IFNULL(`GJFL|Queenscliff Blue`, 0) +   
IFNULL(`GJFL|Queenscliff Red`, 0) +   
IFNULL(`GJFL|Roosters`, 0) +   
IFNULL(`GJFL|Saints White`, 0) +   
IFNULL(`GJFL|Seagulls`, 0) +   
IFNULL(`GJFL|South Barwon Blue`, 0) +   
IFNULL(`GJFL|South Barwon Red`, 0) +  
IFNULL(`GJFL|South Barwon White`, 0) +   
IFNULL(`GJFL|St Albans Butterworth`, 0) +   
IFNULL(`GJFL|St Albans Grinter`, 0) +   
IFNULL(`GJFL|St Albans Mcfarlane`, 0) +   
IFNULL(`GJFL|St Albans Osborne`, 0) +   
IFNULL(`GJFL|Surf Coast Suns`, 0) +   
IFNULL(`GJFL|Teesdale Roos`, 0) +   
IFNULL(`GJFL|Tigers`, 0) +   
IFNULL(`GJFL|Torquay Boyse`, 0) +   
IFNULL(`GJFL|Torquay Browning`, 0) +   
IFNULL(`GJFL|Torquay Bruce`, 0) +   
IFNULL(`GJFL|Torquay Coleman`, 0) +   
IFNULL(`GJFL|Torquay Davey`, 0) +   
IFNULL(`GJFL|Torquay Milliken`, 0) +   
IFNULL(`GJFL|Torquay Stone`, 0) +   
IFNULL(`GJFL|Torquay Watson`, 0) +   
IFNULL(`GJFL|Winchelsea / Inverleigh`, 0)  +
IFNULL(`GFL|Geelong_West_St_Peters`, 0) + 
IFNULL(`BFL|Newcomb`, 0) + 
IFNULL(`GJFL|Bell Post Hill`, 0) +
IFNULL(`GJFL|Surf Coast`, 0) + 
IFNULL(`GJFL|East Newcomb`, 0)

AS match_count  
FROM mv_report_01;


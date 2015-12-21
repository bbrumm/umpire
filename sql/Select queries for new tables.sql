SELECT report_table_id, table_name FROM report_table WHERE report_name = 'report_05';


SELECT rc.column_name
FROM report_column rc
JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
JOIN report_table rt ON rcl.report_table_id = rt.report_table_id
WHERE rcl.filter_name = 'short_league_name'
AND rcl.filter_value = 'All'
AND rt.report_name = 'report_05'
ORDER BY rc.column_name ASC;


SET group_concat_max_len = 2048;

SELECT GROUP_CONCAT(gc.column_name SEPARATOR ', ')
FROM (
	SELECT CONCAT('`',rc.column_name,'`') as column_name
	FROM report_column rc
	JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
	JOIN report_table rt ON rcl.report_table_id = rt.report_table_id
    
	WHERE rcl.filter_name = 'short_league_name'
	AND rcl.filter_value = 'All'
	AND rt.report_name = '5'
	ORDER BY rc.column_name ASC
) gc;

/*V2*/
SELECT GROUP_CONCAT(gc.column_name SEPARATOR ', ')
FROM (
	SELECT DISTINCT CONCAT('SUM(', '`',rc.column_name,'`', ')') as column_name
	FROM report_column rc
	JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
	JOIN report_table rt ON rcl.report_table_id = rt.report_table_id
    JOIN report_column_lookup_display rcld ON rcld.report_column_id = rc.report_column_id
    JOIN mv_report_05 mv ON rcld.column_display_name = mv.short_league_name
	WHERE rcl.filter_name = 'short_league_name'
	AND rcl.filter_value = 'All'
	AND rt.report_name = '5'
    AND mv.age_group = 'Colts'
    AND rcld.column_display_filter_name = 'short_league_name'
	ORDER BY rc.column_name ASC
) gc;


SELECT GROUP_CONCAT(gc.column_name SEPARATOR ', ') as COLS FROM (SELECT DISTINCT CONCAT('`',rc.column_name,'`') as column_name FROM report_column rc JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id JOIN report_table rt ON rcl.report_table_id = rt.report_table_id JOIN report_column_lookup_display rcld ON rcld.report_column_id = rc.report_column_id JOIN mv_report_05 mv ON rcld.column_display_name = mv.short_league_name WHERE rcl.filter_name = 'short_league_name' AND rcl.filter_value = 'All' AND rt.report_name = 05 AND rcld.column_display_filter_name = 'short_league_name' ORDER BY rc.column_name ASC) gc; 




select * from mv_report_05_test;


	SELECT DISTINCT rc.column_name
	FROM report_column rc
	JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
	JOIN report_table rt ON rcl.report_table_id = rt.report_table_id
    JOIN report_column_lookup_display rcld ON rcld.report_column_id = rc.report_column_id
    JOIN mv_report_05 mv ON rcld.column_display_name = mv.short_league_name
	WHERE rcl.filter_name = 'short_league_name'
	AND rcl.filter_value = 'All'
	AND rt.report_name = '5'
    AND mv.age_group = 'Colts'
    AND rcld.column_display_filter_name = 'short_league_name'
	ORDER BY rc.column_name ASC;
    
    
    
SELECT DISTINCT age_group, short_league_name
FROM mv_report_05
ORDER BY age_group, short_league_name;

    
    
    
SELECT rc.column_name, rcld.column_display_filter_name, rcld.column_display_name
FROM report_column_lookup_display rcld
JOIN report_column rc ON rcld.report_column_id = rc.report_column_id
JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
JOIN report_table rt ON rcl.report_table_id = rt.report_table_id
WHERE rcl.filter_name = 'short_league_name'
AND rcl.filter_value = 'All'
AND rt.report_name = 5
ORDER BY rcld.report_column_id, rcld.column_display_filter_name;



    
SELECT DISTINCT rc.column_name, rcld.report_column_id, 
	(SELECT rcld3.column_display_name
	FROM report_column_lookup_display rcld3
	WHERE rcld3.report_column_id = rcld.report_column_id
	AND rcld3.column_display_filter_name = 'short_league_name') as short_league_name,
	(SELECT rcld2.column_display_name
	FROM report_column_lookup_display rcld2
	WHERE rcld2.report_column_id = rcld.report_column_id
	AND rcld2.column_display_filter_name = 'club_name') as club_name
FROM report_column_lookup_display rcld
JOIN report_column rc ON rcld.report_column_id = rc.report_column_id
JOIN report_column_lookup rcl ON rc.report_column_id = rcl.report_column_id
JOIN report_table rt ON rcl.report_table_id = rt.report_table_id
WHERE rcl.filter_name = 'short_league_name'
AND rcl.filter_value = 'All'
AND rt.report_name = 5
ORDER BY rcld.report_column_id, rcld.column_display_filter_name;


SELECT ag.age_group,
l.league_name,
l.short_league_name
FROM age_group ag
JOIN age_group_division agd ON ag.id = agd.age_group_id
JOIN league l ON agd.division_id = l.age_group_division_id;

SELECT DISTINCT age_group, short_league_name
FROM mv_report_05
ORDER BY age_group, short_league_name;



SELECT *
FROM mv_report_05_test
where full_name = 'Anderson, Tyson';


SELECT full_name, `BFL|Anglesea`, `BFL|Barwon_Heads`, `BFL|Drysdale`, `BFL|Geelong_Amateur`, SUM(`BFL|Modewarre`) as `BFL|Modewarre`, `BFL|Newcomb_Power`, `BFL|Ocean_Grove`, `BFL|Portarlington`, `BFL|Queenscliff`, `BFL|Torquay`, `GDFL|Anakie`, `GDFL|Bannockburn`, `GDFL|Bell_Post_Hill`, `GDFL|Belmont_Lions`, `GDFL|Corio`, `GDFL|East_Geelong`, `GDFL|Geelong_West`, `GDFL|Inverleigh`, `GDFL|North_Geelong`, `GDFL|Thomson`, `GDFL|Werribee_Centrals`, `GDFL|Winchelsea`, `GFL|Bell_Park`, `GFL|Colac`, `GFL|Grovedale`, `GFL|Gwsp`, `GFL|Lara`, `GFL|Leopold`, `GFL|Newtown_&_Chilwell`, `GFL|North_Shore`, `GFL|South_Barwon`, `GFL|St_Albans`, `GFL|St_Joseph's`, `GFL|St_Mary's`, `None|Anakie`, `None|Anglesea`, `None|Bannockburn`, `None|Barwon_Heads`, `None|Bell_Park`, `None|Belmont_Lions`, `None|Belmont_Lions_/_Newcomb`, `None|Colac`, `None|Corio`, `None|Drysdale`, `None|Drysdale_Bennett`, `None|Drysdale_Byrne`, `None|Drysdale_Eddy`, `None|Drysdale_Hall`, `None|Drysdale_Hector`, `None|East_Geelong`, `None|Geelong_Amateur`, `None|Geelong_West_St_Peters`, `None|Grovedale`, `None|Gwsp_/_Bannockburn`, `None|Inverleigh`, `None|Lara`, `None|Leopold`, `None|Modewarre`, `None|Newcomb`, `None|Newtown_&_Chilwell`, `None|North_Geelong`, `None|North_Shore`, `None|Ocean_Grove`, `None|Ogcc`, `None|Portarlington`, `None|Queenscliff`, `None|South_Barwon`, `None|South_Barwon_/_Geelong_Amateur`, `None|St_Albans`, `None|St_Albans_Allthorpe`, `None|St_Albans_Reid`, `None|St_Joseph's`, `None|St_Joseph's_Hill`, `None|St_Joseph's_Podbury`, `None|St_Mary's`, `None|Tigers_Gold`, `None|Torquay`, `None|Torquay_Bumpstead`, `None|Torquay_Coles`, `None|Torquay_Dunstan`, `None|Torquay_Jones`, `None|Torquay_Nairn`, `None|Torquay_Papworth`, `None|Torquay_Pyers`, `None|Torquay_Scott`, `None|Werribee_Centrals`, `None|Winchelsea`, `None|Winchelsea_/_Grovedale`
FROM mv_report_05_test WHERE umpire_type_name = 'Goal' GROUP BY full_name


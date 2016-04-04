UPDATE league
SET short_league_name = 'GJFL'
WHERE short_league_name = 'None';

ALTER TABLE mv_report_05 ADD COLUMN GJFL varchar(200);


ALTER TABLE mv_report_01 CHANGE `None|Corio` `GJFL|Corio` INT(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Portarlington`   `GJFL|Portarlington` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Barwon_Heads`   `GJFL|Barwon_Heads` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Drysdale`   `GJFL|Drysdale` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|East_Geelong`   `GJFL|East_Geelong` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Geelong_West_St_Peters`   `GJFL|Geelong_West_St_Peters` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Grovedale`   `GJFL|Grovedale` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Inverleigh`   `GJFL|Inverleigh` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Leopold`   `GJFL|Leopold` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Newcomb`   `GJFL|Newcomb` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Newtown_&_Chilwell`   `GJFL|Newtown_&_Chilwell` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Ocean_Grove`   `GJFL|Ocean_Grove` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|South_Barwon`   `GJFL|South_Barwon` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|St_Albans`   `GJFL|St_Albans` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|St_Joseph's`   `GJFL|St_Joseph's` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|St_Mary's`   `GJFL|St_Mary's` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay`   `GJFL|Torquay` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Anglesea`   `GJFL|Anglesea` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Bell_Park`   `GJFL|Bell_Park` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|North_Shore`   `GJFL|North_Shore` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Belmont_Lions`   `GJFL|Belmont_Lions` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Colac`   `GJFL|Colac` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|North_Geelong`   `GJFL|North_Geelong` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Ogcc`   `GJFL|Ogcc` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay_Jones`   `GJFL|Torquay_Jones` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay_Papworth`   `GJFL|Torquay_Papworth` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Winchelsea_/_Grovedale`   `GJFL|Winchelsea_/_Grovedale` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Drysdale_Byrne`   `GJFL|Drysdale_Byrne` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Drysdale_Hall`   `GJFL|Drysdale_Hall` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Drysdale_Hector`   `GJFL|Drysdale_Hector` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Lara`   `GJFL|Lara` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Queenscliff`   `GJFL|Queenscliff` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|St_Albans_Reid`   `GJFL|St_Albans_Reid` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay_Bumpstead`   `GJFL|Torquay_Bumpstead` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay_Pyers`   `GJFL|Torquay_Pyers` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Modewarre`   `GJFL|Modewarre` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|St_Joseph's_Podbury`   `GJFL|St_Joseph's_Podbury` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Geelong_Amateur`   `GJFL|Geelong_Amateur` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Winchelsea`   `GJFL|Winchelsea` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Anakie`   `GJFL|Anakie` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Bannockburn`   `GJFL|Bannockburn` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|South_Barwon_/_Geelong_Amateur`   `GJFL|South_Barwon_/_Geelong_Amateur` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|St_Joseph's_Hill`   `GJFL|St_Joseph's_Hill` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay_Dunstan`   `GJFL|Torquay_Dunstan` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Werribee_Centrals`   `GJFL|Werribee_Centrals` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Drysdale_Eddy`   `GJFL|Drysdale_Eddy` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Belmont_Lions_/_Newcomb`   `GJFL|Belmont_Lions_/_Newcomb` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay_Coles`   `GJFL|Torquay_Coles` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Gwsp_/_Bannockburn`   `GJFL|Gwsp_/_Bannockburn` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|St_Albans_Allthorpe`   `GJFL|St_Albans_Allthorpe` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Drysdale_Bennett`   `GJFL|Drysdale_Bennett` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay_Scott`   `GJFL|Torquay_Scott` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Torquay_Nairn`   `GJFL|Torquay_Nairn` int(11) DEFAULT NULL;
ALTER TABLE mv_report_01 CHANGE   `None|Tigers_Gold`   `GJFL|Tigers_Gold` int(11) DEFAULT NULL;

ALTER TABLE mv_report_02 CHANGE `Colts|None` `Colts|GJFL` int(11) DEFAULT NULL;
ALTER TABLE mv_report_02 CHANGE `Under 16|None` `Under 16|GJFL` int(11) DEFAULT NULL;
ALTER TABLE mv_report_02 CHANGE `Under 14|None` `Under 14|GJFL` int(11) DEFAULT NULL;
ALTER TABLE mv_report_02 CHANGE `Youth Girls|None` `Youth Girls|GJFL` int(11) DEFAULT NULL;
ALTER TABLE mv_report_02 CHANGE `Junior Girls|None` `Junior Girls|GJFL` int(11) DEFAULT NULL;




  ALTER TABLE mv_report_04 CHANGE `Boundary|Colts|None` `Boundary|Colts|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Boundary|Under 16|None` `Boundary|Under 16|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Boundary|Under 14|None` `Boundary|Under 14|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Boundary|Youth Girls|None` `Boundary|Youth Girls|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Boundary|Junior Girls|None` `Boundary|Junior Girls|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Field|Colts|None` `Field|Colts|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Field|Under 16|None` `Field|Under 16|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Field|Under 14|None` `Field|Under 14|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Field|Youth Girls|None` `Field|Youth Girls|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Field|Junior Girls|None` `Field|Junior Girls|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Goal|Colts|None` `Goal|Colts|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Goal|Under 16|None` `Goal|Under 16|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Goal|Under 14|None` `Goal|Under 14|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Goal|Youth Girls|None` `Goal|Youth Girls|GJFL` int(11) DEFAULT NULL;
  ALTER TABLE mv_report_04 CHANGE `Goal|Junior Girls|None` `Goal|Junior Girls|GJFL` int(11) DEFAULT NULL;
  
/*  ALTER TABLE mv_report_05 CHANGE `None` `GJFL`  int(11) DEFAULT NULL;*/



UPDATE report_column SET column_name =  'Junior Girls|GJFL' WHERE report_column_id = 100;
UPDATE report_column SET column_name =  'Boundary|Colts|GJFL' WHERE report_column_id = 125;
UPDATE report_column SET column_name =  'Boundary|Under 16|GJFL' WHERE report_column_id = 126;
UPDATE report_column SET column_name =  'Boundary|Under 14|GJFL' WHERE report_column_id = 127;
UPDATE report_column SET column_name =  'Boundary|Youth Girls|GJFL' WHERE report_column_id = 128;
UPDATE report_column SET column_name =  'Boundary|Junior Girls|GJFL' WHERE report_column_id = 129;
UPDATE report_column SET column_name =  'Field|Colts|GJFL' WHERE report_column_id = 136;
UPDATE report_column SET column_name =  'Field|Under 16|GJFL' WHERE report_column_id = 137;
UPDATE report_column SET column_name =  'Field|Under 14|GJFL' WHERE report_column_id = 138;
UPDATE report_column SET column_name =  'Field|Youth Girls|GJFL' WHERE report_column_id = 139;
UPDATE report_column SET column_name =  'Field|Junior Girls|GJFL' WHERE report_column_id = 140;
UPDATE report_column SET column_name =  'Goal|Colts|GJFL' WHERE report_column_id = 147;
UPDATE report_column SET column_name =  'Goal|Under 16|GJFL' WHERE report_column_id = 148;
UPDATE report_column SET column_name =  'Goal|Under 14|GJFL' WHERE report_column_id = 149;
UPDATE report_column SET column_name =  'Goal|Youth Girls|GJFL' WHERE report_column_id = 150;
UPDATE report_column SET column_name =  'Goal|Junior Girls|GJFL' WHERE report_column_id = 151;
UPDATE report_column SET column_name =  'GJFL' WHERE report_column_id = 155;
UPDATE report_column SET column_name =  'GJFL|Anakie' WHERE report_column_id = 35;
UPDATE report_column SET column_name =  'GJFL|Anglesea' WHERE report_column_id = 36;
UPDATE report_column SET column_name =  'GJFL|Bannockburn' WHERE report_column_id = 37;
UPDATE report_column SET column_name =  'GJFL|Barwon_Heads' WHERE report_column_id = 38;
UPDATE report_column SET column_name =  'GJFL|Bell_Park' WHERE report_column_id = 39;
UPDATE report_column SET column_name =  'GJFL|Belmont_Lions_/_Newcomb' WHERE report_column_id = 40;
UPDATE report_column SET column_name =  'GJFL|Belmont_Lions' WHERE report_column_id = 41;
UPDATE report_column SET column_name =  'GJFL|Colac' WHERE report_column_id = 42;
UPDATE report_column SET column_name =  'GJFL|Corio' WHERE report_column_id = 43;
UPDATE report_column SET column_name =  'GJFL|Drysdale_Bennett' WHERE report_column_id = 44;
UPDATE report_column SET column_name =  'GJFL|Drysdale_Byrne' WHERE report_column_id = 45;
UPDATE report_column SET column_name =  'GJFL|Drysdale_Eddy' WHERE report_column_id = 46;
UPDATE report_column SET column_name =  'GJFL|Drysdale_Hall' WHERE report_column_id = 47;
UPDATE report_column SET column_name =  'GJFL|Drysdale_Hector' WHERE report_column_id = 48;
UPDATE report_column SET column_name =  'GJFL|Drysdale' WHERE report_column_id = 49;
UPDATE report_column SET column_name =  'GJFL|East_Geelong' WHERE report_column_id = 50;
UPDATE report_column SET column_name =  'GJFL|Geelong_Amateur' WHERE report_column_id = 51;
UPDATE report_column SET column_name =  'GJFL|Geelong_West_St_Peters' WHERE report_column_id = 52;
UPDATE report_column SET column_name =  'GJFL|Grovedale' WHERE report_column_id = 53;
UPDATE report_column SET column_name =  'GJFL|Gwsp_/_Bannockburn' WHERE report_column_id = 54;
UPDATE report_column SET column_name =  'GJFL|Inverleigh' WHERE report_column_id = 55;
UPDATE report_column SET column_name =  'GJFL|Lara' WHERE report_column_id = 56;
UPDATE report_column SET column_name =  'GJFL|Leopold' WHERE report_column_id = 57;
UPDATE report_column SET column_name =  'GJFL|Modewarre' WHERE report_column_id = 58;
UPDATE report_column SET column_name =  'GJFL|Newcomb' WHERE report_column_id = 59;
UPDATE report_column SET column_name =  'GJFL|Newtown_&_Chilwell' WHERE report_column_id = 60;
UPDATE report_column SET column_name =  'GJFL|North_Geelong' WHERE report_column_id = 61;
UPDATE report_column SET column_name =  'GJFL|North_Shore' WHERE report_column_id = 62;
UPDATE report_column SET column_name =  'GJFL|Ocean_Grove' WHERE report_column_id = 63;
UPDATE report_column SET column_name =  'GJFL|Ogcc' WHERE report_column_id = 64;
UPDATE report_column SET column_name =  'GJFL|Portarlington' WHERE report_column_id = 65;
UPDATE report_column SET column_name =  'GJFL|Queenscliff' WHERE report_column_id = 66;
UPDATE report_column SET column_name =  'GJFL|South_Barwon_/_Geelong_Amateur' WHERE report_column_id = 67;
UPDATE report_column SET column_name =  'GJFL|South_Barwon' WHERE report_column_id = 68;
UPDATE report_column SET column_name =  'GJFL|St_Albans_Allthorpe' WHERE report_column_id = 69;
UPDATE report_column SET column_name =  'GJFL|St_Albans_Reid' WHERE report_column_id = 70;
UPDATE report_column SET column_name =  'GJFL|St_Albans' WHERE report_column_id = 71;
UPDATE report_column SET column_name =  'GJFL|St_Joseph\'s_Hill' WHERE report_column_id = 72;
UPDATE report_column SET column_name =  'GJFL|St_Joseph\'s_Podbury' WHERE report_column_id = 73;
UPDATE report_column SET column_name =  'GJFL|St_Joseph\'s' WHERE report_column_id = 74;
UPDATE report_column SET column_name =  'GJFL|St_Mary\'s' WHERE report_column_id = 75;
UPDATE report_column SET column_name =  'GJFL|Tigers_Gold' WHERE report_column_id = 76;
UPDATE report_column SET column_name =  'GJFL|Torquay_Bumpstead' WHERE report_column_id = 77;
UPDATE report_column SET column_name =  'GJFL|Torquay_Coles' WHERE report_column_id = 78;
UPDATE report_column SET column_name =  'GJFL|Torquay_Dunstan' WHERE report_column_id = 79;
UPDATE report_column SET column_name =  'GJFL|Torquay_Jones' WHERE report_column_id = 80;
UPDATE report_column SET column_name =  'GJFL|Torquay_Nairn' WHERE report_column_id = 81;
UPDATE report_column SET column_name =  'GJFL|Torquay_Papworth' WHERE report_column_id = 82;
UPDATE report_column SET column_name =  'GJFL|Torquay_Pyers' WHERE report_column_id = 83;
UPDATE report_column SET column_name =  'GJFL|Torquay_Scott' WHERE report_column_id = 84;
UPDATE report_column SET column_name =  'GJFL|Torquay' WHERE report_column_id = 85;
UPDATE report_column SET column_name =  'GJFL|Werribee_Centrals' WHERE report_column_id = 86;
UPDATE report_column SET column_name =  'GJFL|Winchelsea_/_Grovedale' WHERE report_column_id = 87;
UPDATE report_column SET column_name =  'GJFL|Winchelsea' WHERE report_column_id = 88;
UPDATE report_column SET column_name =  'Seniors|2 Umpires' WHERE report_column_id = 92;
UPDATE report_column SET column_name =  'Colts|GJFL' WHERE report_column_id = 96;
UPDATE report_column SET column_name =  'Under 16|GJFL' WHERE report_column_id = 97;
UPDATE report_column SET column_name =  'Under 14|GJFL' WHERE report_column_id = 98;
UPDATE report_column SET column_name =  'Youth Girls|GJFL' WHERE report_column_id = 99;


UPDATE report_column_lookup SET filter_value = 'GJFL' WHERE report_column_lookup_id IN (
3,
6,
26,
27,
28,
29,
30,
31,
32,
33,
34,
35,
36,
37,
38,
39,
40,
45,
46,
47,
48,
49,
50,
51,
52,
53,
54,
59,
60,
61,
62,
63,
64,
65,
66,
67,
71,
72,
73,
74,
75,
76,
77,
78,
79,
80,
81,
82,
83,
84,
85,
86,
87,
88,
130,
131,
132,
133,
134,
279,
280,
281,
282,
283,
290,
291,
292,
293,
294,
301,
302,
303,
304,
305
);

UPDATE report_column_lookup_display SET column_display_name = 'GJFL' WHERE column_display_filter_name = 'short_league_name' AND column_display_name = 'None';



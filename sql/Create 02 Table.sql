DROP TABLE IF EXISTS mv_report_02;

CREATE TABLE mv_report_02 (
full_name varchar(200) DEFAULT NULL,
umpire_type_name varchar(200) DEFAULT NULL,
short_league_name varchar(200) DEFAULT NULL,
age_group varchar(200) DEFAULT NULL,
`Seniors|BFL` int(11) DEFAULT NULL,
`Seniors|GDFL` int(11) DEFAULT NULL,
`Seniors|GFL` int(11) DEFAULT NULL,
`Reserves|BFL` int(11) DEFAULT NULL,
`Reserves|GDFL` int(11) DEFAULT NULL,
`Reserves|GFL` int(11) DEFAULT NULL,
`Colts|None` int(11) DEFAULT NULL,
`Under 16|None` int(11) DEFAULT NULL,
`Under 14|None` int(11) DEFAULT NULL,
`Youth Girls|None` int(11) DEFAULT NULL,
`Junior Girls|None` int(11) DEFAULT NULL,
`Seniors|2 Umpires` int(11) DEFAULT NULL
);
DROP TABLE dw_dim_umpire;
DROP TABLE dw_dim_age_group;
DROP TABLE dw_dim_league;
DROP TABLE dw_dim_team;
DROP TABLE dw_dim_time;
DROP TABLE dw_fact_match;
DROP TABLE staging_match;

CREATE TABLE dw_dim_umpire (
	umpire_key INT(11) PRIMARY KEY AUTO_INCREMENT,
	first_name VARCHAR(100),
	last_name VARCHAR(100),
	last_first_name VARCHAR(200),
	umpire_type VARCHAR(100)
);

CREATE TABLE dw_dim_age_group (
	age_group_key INT(11) PRIMARY KEY AUTO_INCREMENT,
    age_group VARCHAR(50),
    sort_order INT(2),
    division VARCHAR(100)
);

CREATE TABLE dw_dim_league (
	league_key INT(11) PRIMARY KEY AUTO_INCREMENT,
    short_league_name VARCHAR(50),
    full_name VARCHAR(200),
    region_name VARCHAR(100),
    competition_name VARCHAR(500),
    league_sort_order INT(11)
);

alter table `dw_dim_league` change column `short_name` `short_league_name` varchar(50);
alter table `dw_dim_league` add column `league_sort_order` INT(11);
truncate table dw_dim_league;

CREATE TABLE dw_dim_team (
	team_key INT(11) PRIMARY KEY AUTO_INCREMENT,
    team_name VARCHAR(100),
    club_name VARCHAR(100)
);

CREATE TABLE dw_dim_time (
	time_key INT(11) PRIMARY KEY AUTO_INCREMENT,
    round_number INT(2),
    match_date DATETIME,
	date_year INT(4),
    date_month INT(2),
    date_day INT(2),
    date_hour INT(2),
    date_minute INT(2),
    weekend_date DATE,
    weekend_year INT(4),
    weekend_month INT(2),
    weekend_day INT(2)
);

CREATE TABLE dw_fact_match (
	match_id INT(11),
	umpire_key INT(11),
    age_group_key INT(11),
    league_key INT(11),
    time_key INT(11),
    home_team_key INT(11),
    away_team_key INT(11)
);

CREATE TABLE staging_match (
	season_id INT(11),
    season_year INT(4),
    umpire_id INT(11),
    umpire_first_name VARCHAR(100),
    umpire_last_name VARCHAR(100),
    home_club VARCHAR(100),
    home_team VARCHAR(100),
    away_club VARCHAR(100),
    away_team VARCHAR(100),
    short_league_name VARCHAR(100),
    league_name VARCHAR(100),
    age_group_id INT(11),
    age_group_name VARCHAR(100),
    umpire_type_name VARCHAR(100),
    match_id INT(11),
    match_time DATETIME,
    region_id INT(11),
    region_name VARCHAR(100),
    division_name VARCHAR(100),
    competition_name VARCHAR(500)
);

CREATE TABLE staging_no_umpires (
	weekend_date DATETIME,
    age_group VARCHAR(100),
    umpire_type VARCHAR(100),
    short_league_name VARCHAR(100),
    team_names VARCHAR(400),
	match_id INT(11)
);

DROP TABLE staging_all_ump_age_league;

CREATE TABLE staging_all_ump_age_league (
	age_group VARCHAR(100),
    umpire_type VARCHAR(100),
    short_league_name VARCHAR(100),
    age_sort_order INT(11),
    league_sort_order INT(11)
);


CREATE TABLE dw_mv_report_04 (
	club_name VARCHAR(100),
	age_group VARCHAR(100),
	short_league_name VARCHAR(100),
	umpire_type VARCHAR(100),
    age_sort_order INT(11),
    league_sort_order INT(11),
	match_count INT(11)
);


/*
    s.id,
    s.season_year,
    u.id,
    u.first_name,
    u.last_name,
    hmc.club_name AS home_club,
    hmt.team_name AS home_team_name,
    awc.club_name AS away_club,
    awt.team_name AS away_team_name,
    l.short_league_name,
    l.league_name,
    ag.id,
    ag.age_group,
    ut.umpire_type_name,
    m.ID,
    r.id,
    r.region_name,
    d.division_name,
*/

/*
Populate DimUmpire
*/
INSERT INTO dw_dim_umpire (first_name, last_name, last_first_name, umpire_type)
SELECT
u.first_name,
u.last_name,
CONCAT(u.last_name, ', ', u.first_name) AS last_first_name,
ut.umpire_type_name AS umpire_type
FROM umpire u
INNER JOIN umpire_name_type unt ON u.id = unt.umpire_id
INNER JOIN umpire_type ut ON unt.umpire_type_id = ut.ID;

/*
Populate DimAgeGroup
*/
INSERT INTO dw_dim_age_group (age_group, sort_order, division)
SELECT
ag.age_group,
ag.display_order AS sort_order,
d.division_name
FROM age_group ag
INNER JOIN age_group_division agd ON ag.id = agd.age_group_id
INNER JOIN division d ON agd.division_id = d.ID
ORDER BY ag.display_order;

/*
Populate DimLeague
*/

TRUNCATE TABLE dw_dim_league;

INSERT INTO dw_dim_league (short_league_name, full_name, region_name, competition_name, league_sort_order)
SELECT DISTINCT
l.short_league_name,
l.league_name,
r.region_name,
c.competition_name,
CASE short_league_name
	WHEN 'GFL' THEN 1
	WHEN 'BFL' THEN 2
	WHEN 'GDFL' THEN 3
	WHEN 'CDFNL' THEN 4
	ELSE 10
END league_sort_order
FROM league l
INNER JOIN region r ON l.region_id = r.id
INNER JOIN competition_lookup c ON l.ID = c.league_id;



/*
Populate DimTeam
*/
INSERT INTO dw_dim_team (team_name, club_name)
SELECT
t.team_name,
c.club_name
FROM team t
INNER JOIN club c ON t.club_id = c.id
ORDER BY t.team_name, c.club_name;


/*
Populate DimTime
*/
INSERT INTO dw_dim_time (round_number, match_date, date_year, date_month, date_day, date_hour, date_minute, weekend_date, weekend_year, weekend_month, weekend_day)
SELECT
DISTINCT
r.round_number,
m.match_time,
YEAR(m.match_time) AS date_year,
MONTH(m.match_time) AS date_month,
DAY(m.match_time) AS date_day,
HOUR(m.match_time) AS date_hour,
MINUTE(m.match_time) AS date_minute,
ADDDATE(r.round_date, (5-Weekday(r.round_date))) AS weekend_date,
YEAR(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_year,
MONTH(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_month,
DAY(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_day
FROM match_played m
INNER JOIN round r ON m.round_id = r.id
ORDER BY m.match_time;



/*
Populate FactMatch
*/
INSERT INTO staging_match (season_id, season_year, umpire_id, umpire_first_name, umpire_last_name,
home_club, home_team, away_club, away_team, short_league_name, league_name, age_group_id, age_group_name, 
umpire_type_name, match_id, match_time, region_id, region_name, division_name, competition_name)
SELECT 
    s.id,
    s.season_year,
    u.id,
    u.first_name,
    u.last_name,
    hmc.club_name AS home_club,
    hmt.team_name AS home_team_name,
    awc.club_name AS away_club,
    awt.team_name AS away_team_name,
    l.short_league_name,
    l.league_name,
    ag.id,
    ag.age_group,
    ut.umpire_type_name,
    m.ID,
    m.match_time,
    r.id,
    r.region_name,
    d.division_name,
    cl.competition_name
FROM
match_played m
INNER JOIN    round rn ON rn.ID = m.round_id
INNER JOIN    league l ON l.ID = rn.league_id
INNER JOIN    age_group_division agd ON agd.ID = l.age_group_division_id
INNER JOIN    age_group ag ON ag.ID = agd.age_group_id
INNER JOIN    team hmt ON hmt.ID = m.home_team_id
INNER JOIN    club hmc ON hmc.ID = hmt.club_id
INNER JOIN    team awt ON awt.ID = m.away_team_id
INNER JOIN    club awc ON awc.ID = awt.club_id
INNER JOIN    division d ON agd.division_id = d.id
INNER JOIN    competition_lookup cl ON cl.league_id = l.ID
LEFT JOIN    umpire_name_type_match untm ON m.ID = untm.match_id
LEFT JOIN    umpire_name_type unt ON unt.ID = untm.umpire_name_type_id
LEFT JOIN    umpire_type ut ON ut.ID = unt.umpire_type_id
LEFT JOIN    umpire u ON u.ID = unt.umpire_id
INNER JOIN    season s ON s.id = rn.season_id
INNER JOIN    region r ON r.id = l.region_id
WHERE  s.id = 2;

TRUNCATE staging_no_umpires;

INSERT INTO staging_no_umpires (weekend_date, age_group, umpire_type, short_league_name, team_names, match_id)
SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Field',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Field'
)
UNION ALL

SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Boundary',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Boundary'
)
UNION ALL

SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Goal',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Goal'
);




TRUNCATE TABLE staging_all_ump_age_league;

INSERT INTO staging_all_ump_age_league (age_group, umpire_type, short_league_name, age_sort_order, league_sort_order)
SELECT DISTINCT
ag.age_group,
ut.umpire_type_name,
l.short_league_name,
ag.display_order,
NULL
FROM age_group ag
INNER JOIN age_group_division agd ON ag.ID = agd.age_group_id
INNER JOIN league l ON l.age_group_division_id = agd.ID
CROSS JOIN umpire_type ut;



/*
Create Indexes
*/

CREATE INDEX idx_dag_join ON dw_dim_age_group (age_group, division);
CREATE INDEX idx_dl_join ON dw_dim_league (short_name, full_name, region_name, competition_name);
CREATE INDEX idx_dtm_join ON dw_dim_team (team_name, club_name);
CREATE INDEX idx_dti_join ON dw_dim_time (match_date);
CREATE INDEX idx_sm_age ON staging_match (age_group_name, division_name);


CREATE INDEX idx_stg_no ON staging_no_umpires (umpire_type, short_league_name, age_group);
CREATE INDEX idx_stg_no_mid ON staging_no_umpires (match_id);



TRUNCATE dw_fact_match;

/*
Run time: 2s
*/
INSERT INTO dw_fact_match (match_id, umpire_key, age_group_key, league_key, time_key, home_team_key, away_team_key)
SELECT 
s.match_id,
du.umpire_key,
dag.age_group_key,
dl.league_key,
dt.time_key,
dth.team_key AS home_team_key,
dta.team_key AS away_team_key
FROM
staging_match s
LEFT JOIN dw_dim_umpire du ON (s.umpire_first_name = du.first_name
	AND s.umpire_last_name = du.last_name
	AND s.umpire_type_name = du.umpire_type
)
INNER JOIN dw_dim_age_group dag ON (
	s.age_group_name = dag.age_group
	AND s.division_name = dag.division
)
INNER JOIN dw_dim_league dl ON (
	s.short_league_name = dl.short_league_name
	AND s.league_name = dl.full_name
	AND s.region_name = dl.region_name
    AND s.competition_name = dl.competition_name
)
INNER JOIN dw_dim_team dth ON (
	s.home_team = dth.team_name
	AND s.home_club = dth.club_name
    )
INNER JOIN dw_dim_team dta ON (
	s.away_team = dta.team_name
	AND s.away_club = dta.club_name
    )
INNER JOIN dw_dim_time dt ON (
	s.match_time = dt.match_date
);



CREATE INDEX idx_dwfm_umpire ON dw_fact_match (umpire_key);
CREATE INDEX idx_dwfm_age_group ON dw_fact_match (age_group_key);
CREATE INDEX idx_dwfm_league ON dw_fact_match (league_key);
CREATE INDEX idx_dwfm_time ON dw_fact_match (time_key);
CREATE INDEX idx_dwfm_home ON dw_fact_match (home_team_key);
CREATE INDEX idx_dwfm_away ON dw_fact_match (away_team_key);
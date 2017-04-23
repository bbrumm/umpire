SELECT 
u.umpire_type,
a.age_group,
l.region_name,
u.last_first_name AS first_umpire,
u2.last_first_name AS second_umpire,
dti.date_year,
COUNT(DISTINCT m.match_id) AS match_count
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_fact_match m2 ON m2.match_id = m.match_id
INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	AND u.last_first_name <> u2.last_first_name
    AND u.umpire_type = u2.umpire_type
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time dti ON m.time_key = dti.time_key
INNER JOIN dw_dim_time dti2 ON m2.time_key = dti2.time_key
WHERE dti.date_year = 2017
AND dti2.date_year = 2017
GROUP BY u.umpire_type, a.age_group, u.last_first_name, u2.last_first_name, l.region_name;

DELETE FROM umpire_name_type
WHERE id NOT IN (
	SELECT umpire_name_type_id
	FROM umpire_name_type_match
);


DROP TABLE dw_rpt06_staging;

CREATE TABLE dw_rpt06_staging (
umpire_key INT,
umpire_type VARCHAR(200),
last_first_name VARCHAR(200),
match_id INT,
date_year INT,
league_key INT,
age_group VARCHAR(100),
region_name VARCHAR(100)
);

INSERT INTO dw_rpt06_staging (umpire_key, umpire_type, last_first_name, match_id, date_year, league_key, age_group, region_name)
SELECT DISTINCT
u.umpire_key,
u.umpire_type,
u.last_first_name,
m.match_id,
2017,
m.league_key,
a.age_group,
l.region_name
FROM dw_fact_match m
INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_time dti ON m.time_key = dti.time_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
WHERE dti.date_year = 2017;

CREATE INDEX idx_rpt6stg_join ON dw_rpt06_staging(last_first_name, umpire_type);

CREATE INDEX idx_rpt6stg_lfn ON dw_rpt06_staging(last_first_name);
CREATE INDEX idx_rpt6stg_ut ON dw_rpt06_staging(umpire_type);
CREATE INDEX idx_rpt6stg_uk ON dw_rpt06_staging(umpire_key);


ALTER TABLE dw_rpt06_staging DROP INDEX idx_rpt6stg_join;

SELECT
s.umpire_type,
s.age_group,
s.region_name,
s.last_first_name AS first_umpire,
s2.last_first_name AS second_umpire,
s.date_year,
COUNT(DISTINCT s.match_id) AS match_count
FROM dw_rpt06_staging s
INNER JOIN dw_rpt06_staging s2 ON s.match_id = s2.match_id
	AND s.umpire_type = s2.umpire_type
	AND s.umpire_key <> s2.umpire_key
GROUP BY s.umpire_type, s.age_group, s.region_name, s.last_first_name, s2.last_first_name;



SELECT
s.umpire_type,
s.age_group,
s.region_name,
s.last_first_name AS first_umpire,
s2.last_first_name AS second_umpire,
s.date_year,
s.match_id,
's--',
s.*,
's2--',
s2.*
FROM dw_rpt06_staging s
INNER JOIN dw_rpt06_staging s2 ON s.match_id = s2.match_id
	AND s.umpire_type = s2.umpire_type
	AND s.umpire_key <> s2.umpire_key
WHERE s.last_first_name = 'Amisse, Samira'
AND s2.last_first_name = 'Hill, Noah';


/*
Changes to Run - DONE
*/

CREATE TABLE dw_rpt06_staging (
umpire_key INT,
umpire_type VARCHAR(200),
last_first_name VARCHAR(200),
match_id INT,
date_year INT,
league_key INT,
age_group VARCHAR(100),
region_name VARCHAR(100)
);

CREATE INDEX idx_rpt6stg_ut ON dw_rpt06_staging(umpire_type);

DELETE FROM umpire_name_type
WHERE id NOT IN (
	SELECT umpire_name_type_id
	FROM umpire_name_type_match
);

INSERT INTO processed_table(id, table_name)
VALUES (35, 'dw_rpt06_staging');
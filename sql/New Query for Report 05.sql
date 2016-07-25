DELETE FROM mv_report_05 WHERE season_year = 2016;

INSERT INTO mv_report_05 (season_year, region, umpire_type, age_group, 
`BFL|Games`,
`BFL|Total`,
`BFL|Pct`,
`GDFL|Games`,
`GDFL|Total`,
`GDFL|Pct`,
`GFL|Games`,
`GFL|Total`,
`GFL|Pct`,
`GJFL|Games`,
`GJFL|Total`,
`GJFL|Pct`,
`CDFNL|Games`,
`CDFNL|Total`,
`CDFNL|Pct`,
`Total`)

SELECT 
    season_year,
    outer2.region,
    ua.umpire_type_name,
    ua.age_group,
    IFNULL(SUM(`BFL`), 0) AS `BFL|Games`,
    SUM(outer3.match_count_bfl) as `BFL|Total`,
    IFNULL(SUM(`BFL`), 0)/IF(SUM(outer3.match_count_bfl)=0,1,SUM(outer3.match_count_bfl))*100 AS `BFL|Pct`,
    IFNULL(SUM(`GDFL`), 0) AS `GDFL|Games`,
    SUM(outer3.match_count_gdfl) AS `GDFL|Total`,
    IFNULL(SUM(`GDFL`), 0)/if(SUM(outer3.match_count_gdfl)=0,1,SUM(outer3.match_count_gdfl))*100 AS `GDFL|Pct`,
    IFNULL(SUM(`GFL`), 0) AS `GFL|Games`,
    SUM(outer3.match_count_gfl) AS `GFL|Total`,
    IFNULL(SUM(`GFL`), 0)/IF(SUM(outer3.match_count_gfl)=0,1,SUM(outer3.match_count_gfl))*100 AS `GFL|Pct`,
    IFNULL(SUM(`GJFL`), 0) AS `GJFL|Games`,
    SUM(outer3.match_count_gjfl) AS `GJFL|Total`,
    IFNULL(SUM(`GJFL`), 0)/IF(SUM(outer3.match_count_gjfl)=0,1,SUM(outer3.match_count_gjfl))*100 AS `GJFL|Pct`,
    IFNULL(SUM(`CDFNL`), 0) AS `CDFNL|Games`,
    SUM(outer3.match_count_cdfnl) AS `CDFNL|Total`,
    IFNULL(SUM(`CDFNL`), 0)/IF(SUM(outer3.match_count_cdfnl)=0,1,SUM(outer3.match_count_cdfnl))*100 AS `CDFNL|Pct`,
    CASE
        WHEN outer2.region = 'Geelong' THEN IFNULL(SUM(`BFL` + `GDFL` + `GFL` + `GJFL`), 0)
        WHEN outer2.region = 'Colac' THEN IFNULL(SUM(`CDFNL`), 0)
        ELSE 0
    END AS Total
FROM
    (SELECT 
        ut.id AS umpire_type_id,
            ut.umpire_type_name,
            ag.id AS age_group_id,
            ag.age_group
    FROM
        umpire_type ut, age_group ag) AS ua
        LEFT JOIN
    (SELECT 
        season_year,
            region,
            umpire_type,
            age_group,
            age_group_ID,
            short_league_name,
            (CASE
                WHEN short_league_name = 'BFL' THEN match_count
                ELSE 0
            END) AS `BFL`,
            (CASE
                WHEN short_league_name = 'GDFL' THEN match_count
                ELSE 0
            END) AS `GDFL`,
            (CASE
                WHEN short_league_name = 'GFL' THEN match_count
                ELSE 0
            END) AS `GFL`,
            (CASE
                WHEN short_league_name = 'GJFL' THEN match_count
                ELSE 0
            END) AS `GJFL`,
            (CASE
                WHEN short_league_name = 'CDFNL' THEN match_count
                ELSE 0
            END) AS `CDFNL`
	FROM
        (SELECT 
        s.season_year,
            region,
            s.umpire_type,
            s.age_group,
            s.short_league_name,
            s.age_group_ID,
            COUNT(s.match_id) AS Match_Count
		FROM
			mv_summary_staging s
		WHERE
			s.season_year = 2016
		GROUP BY s.season_year , region , s.age_group , s.umpire_type , s.short_league_name , s.age_group_ID) 
		AS outer1) 
    AS outer2 ON ua.umpire_type_name = outer2.umpire_type
	AND ua.age_group = outer2.age_group
    LEFT JOIN
    (
		SELECT 
		region,
		age_group,
		short_league_name,
		COUNT(DISTINCT match_id) AS total_match_count,
        CASE WHEN short_league_name = 'BFL' THEN COUNT(DISTINCT match_id) ELSE 0 END AS match_count_bfl,
        CASE WHEN short_league_name = 'GFL' THEN COUNT(DISTINCT match_id) ELSE 0 END AS match_count_gfl,
        CASE WHEN short_league_name = 'GDFL' THEN COUNT(DISTINCT match_id) ELSE 0 END AS match_count_gdfl,
        CASE WHEN short_league_name = 'GJFL' THEN COUNT(DISTINCT match_id) ELSE 0 END AS match_count_gjfl,
        CASE WHEN short_league_name = 'CDFNL' THEN COUNT(DISTINCT match_id) ELSE 0 END AS match_count_cdfnl
		FROM mv_summary_staging
		GROUP BY region, age_group,	short_league_name
    ) AS outer3
    ON outer2.age_group = outer3.age_group
    AND outer2.short_league_name = outer3.short_league_name
GROUP BY season_year , outer2.region , ua.umpire_type_id , ua.age_group_id
ORDER BY season_year , outer2.region , ua.umpire_type_id , ua.age_group_id;

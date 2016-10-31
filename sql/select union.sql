    SELECT 
        'Field' AS `umpire_type`,
        `match_import`.`field_umpire_1` AS `umpire_full_name`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `team`
    FROM
        `match_import` 
		WHERE field_umpire_1 IS NOT NULL
    UNION ALL SELECT 
        'Field' AS `Field`,
        `match_import`.`field_umpire_1` AS `field_umpire_1`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE field_umpire_1 IS NOT NULL
    UNION ALL SELECT 
        'Field' AS `Field`,
        `match_import`.`field_umpire_2` AS `field_umpire_2`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE field_umpire_2 IS NOT NULL
    UNION ALL SELECT 
        'Field' AS `umpire_type`,
        `match_import`.`field_umpire_2` AS `field_umpire_2`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE field_umpire_2 IS NOT NULL
    UNION ALL SELECT 
        'Field' AS `Field`,
        `match_import`.`field_umpire_3` AS `field_umpire_3`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE field_umpire_3 IS NOT NULL
    UNION ALL SELECT 
        'Field' AS `umpire_type`,
        `match_import`.`field_umpire_3` AS `field_umpire_3`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE field_umpire_3 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `Boundary`,
        `match_import`.`boundary_umpire_1` AS `boundary_umpire_1`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_1 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `umpire_type`,
        `match_import`.`boundary_umpire_1` AS `boundary_umpire_1`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_1 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `Boundary`,
        `match_import`.`boundary_umpire_2` AS `boundary_umpire_2`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_2 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `umpire_type`,
        `match_import`.`boundary_umpire_2` AS `boundary_umpire_2`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_2 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `Boundary`,
        `match_import`.`boundary_umpire_3` AS `boundary_umpire_3`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_3 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `umpire_type`,
        `match_import`.`boundary_umpire_3` AS `boundary_umpire_3`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_3 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `Boundary`,
        `match_import`.`boundary_umpire_4` AS `boundary_umpire_4`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_4 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `umpire_type`,
        `match_import`.`boundary_umpire_4` AS `boundary_umpire_4`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_4 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `Boundary`,
        `match_import`.`boundary_umpire_5` AS `boundary_umpire_5`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_5 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `umpire_type`,
        `match_import`.`boundary_umpire_5` AS `boundary_umpire_5`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_5 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `Boundary`,
        `match_import`.`boundary_umpire_6` AS `boundary_umpire_6`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_6 IS NOT NULL
    UNION ALL SELECT 
        'Boundary' AS `umpire_type`,
        `match_import`.`boundary_umpire_6` AS `boundary_umpire_6`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE boundary_umpire_6 IS NOT NULL
    UNION ALL SELECT 
        'Goal' AS `Goal`,
        `match_import`.`goal_umpire_1` AS `goal_umpire_1`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE goal_umpire_1 IS NOT NULL
    UNION ALL SELECT 
        'Goal' AS `umpire_type`,
        `match_import`.`goal_umpire_1` AS `goal_umpire_1`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import` 
		WHERE goal_umpire_1 IS NOT NULL
    UNION ALL SELECT 
        'Goal' AS `Goal`,
        `match_import`.`goal_umpire_2` AS `goal_umpire_2`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`home_team` AS `home_team`
    FROM
        `match_import` 
		WHERE goal_umpire_2 IS NOT NULL
    UNION ALL SELECT 
        'Goal' AS `umpire_type`,
        `match_import`.`goal_umpire_2` AS `goal_umpire_2`,
        `match_import`.`ID` AS `ID`,
        `match_import`.`competition_name` AS `competition_name`,
        `match_import`.`away_team` AS `away_team`
    FROM
        `match_import`
		WHERE goal_umpire_2 IS NOT NULL;

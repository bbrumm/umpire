	SELECT LEFT(UMPIRE_FULLNAME,InStr(UMPIRE_FULLNAME,' ')-1) AS FIRST_NAME,
        RIGHT(UMPIRE_FULLNAME,Length(UMPIRE_FULLNAME)-InStr(UMPIRE_FULLNAME,' ')) AS LAST_NAME
        FROM (SELECT match_import.field_umpire_1 AS UMPIRE_FULLNAME
        	FROM match_import
        	UNION
        	SELECT match_import.field_umpire_2
        	FROM match_import
        	UNION
        	SELECT match_import.field_umpire_3
        	FROM match_import
        	UNION
        	SELECT match_import.boundary_umpire_1
        	FROM match_import
        	UNION
        	SELECT match_import.boundary_umpire_2
        	FROM match_import
        	UNION
        	SELECT match_import.boundary_umpire_3
        	FROM match_import
        	UNION
        	SELECT match_import.boundary_umpire_4
        	FROM match_import
        	UNION
        	SELECT match_import.goal_umpire_1
        	FROM match_import
        	UNION
        	SELECT match_import.goal_umpire_2
        	FROM match_import
        ) AS com 
        WHERE UMPIRE_FULLNAME IS NOT NULL
        AND NOT EXISTS (
        SELECT 1
        FROM umpire
        WHERE first_name = com.firstname
        AND last_name = com.lastname
        );
        
        
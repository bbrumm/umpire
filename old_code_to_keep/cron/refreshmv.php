<?php

include_once 'db.php';

class RefreshMV {
    
    public function refreshMVTables($seasonID, $seasonYear, $importedFileID) {
        /*
         $queryString = "CALL `RunETLProcess`(". $season->getSeasonID() .", ". $importedFileID .")";
         $query = $this->db->query($queryString);
         */
        $this->refreshMVTable1($seasonYear, $importedFileID);
        $this->refreshMVTable2($seasonYear, $importedFileID);
        $this->refreshMVTable4($seasonYear, $importedFileID);
        $this->refreshMVTable5($seasonYear, $importedFileID);
        $this->refreshMVTable6($seasonYear, $importedFileID);
        $this->refreshMVTable7($seasonYear, $importedFileID);
        $this->refreshMVTable8($seasonYear, $importedFileID);
        
    }
    
    
    private function refreshMVTable1($pSeasonYear, $importedFileID) {
        $this->updateKeyStatus("dw_mv_report_01", 0);
        $this->updateTableMV1($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_mv_report_01", 1);
        $this->updateKeyStatus("dw_mv_report_01", 1);
    }
    
    private function refreshMVTable2($pSeasonYear, $importedFileID) {
        $this->updateKeyStatus("dw_mv_report_02", 0);
        $this->updateTableMV2($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_mv_report_02", 1);
        $this->updateKeyStatus("dw_mv_report_02", 1);
    }
    
    private function refreshMVTable4($pSeasonYear, $importedFileID) {
        $this->updateKeyStatus("dw_mv_report_04", 0);
        $this->updateTableMV4($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_mv_report_04", 1);
        $this->updateKeyStatus("dw_mv_report_04", 1);
    }
    
    private function refreshMVTable5($pSeasonYear, $importedFileID) {
        $this->updateKeyStatus("dw_mv_report_05", 0);
        $this->updateTableMV5($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_mv_report_05", 1);
        $this->updateKeyStatus("dw_mv_report_05", 1);
    }
    
    private function refreshMVTable6($pSeasonYear, $importedFileID) {
        $this->updateKeyStatus("dw_rpt06_staging", 0);
        $this->updateTableMV6Staging($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_rpt06_staging", 1);
        $this->updateKeyStatus("dw_rpt06_staging", 1);
        
        $this->updateKeyStatus("dw_rpt06_stg2", 0);
        $this->updateTableMV6StagingPart2($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_rpt06_stg2", 1);
        $this->updateKeyStatus("dw_rpt06_stg2", 1);
        
        
        $this->updateKeyStatus("dw_mv_report_06", 0);
        $this->updateTableMV6($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_mv_report_06", 1);
        $this->updateKeyStatus("dw_mv_report_06", 1);
    }
    
    private function refreshMVTable7($pSeasonYear, $importedFileID) {
        $this->updateKeyStatus("dw_mv_report_07", 0);
        $this->updateTableMV7($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_mv_report_07", 1);
        $this->updateKeyStatus("dw_mv_report_07", 1);
    }
    
    private function refreshMVTable8($pSeasonYear, $importedFileID) {
        $this->updateKeyStatus("dw_mv_report_08", 0);
        $this->updateTableMV8($pSeasonYear);
        $this->logTableOperation($importedFileID, "dw_mv_report_08", 1);
        
        $this->updateTableMV8Totals();
        $this->logTableOperation($importedFileID, "dw_mv_report_08", 2);
        $this->updateKeyStatus("dw_mv_report_08", 1);
    }
    
    private function updateKeyStatus($pTable, $pStatus) {
        if ($pStatus == 0) {
            //$this->db->query("ALTER TABLE dw_mv_report_01 DISABLE KEYS;");
            $rows = dbQuery("ALTER TABLE dw_mv_report_01 DISABLE KEYS;");
        } elseif ($pStatus == 1) {
            //$this->db->query("ALTER TABLE dw_mv_report_01 ENABLE KEYS;");
            $rows = dbQuery("ALTER TABLE dw_mv_report_01 ENABLE KEYS;");
        } else {
            throw new Exception("Invalid status for disabling a key.");
        }
    }
    
    private function logTableOperation($pImportedFileID, $pTableName, $pOperation) {
        $queryString = "CALL LogTableOperation($pImportedFileID, (SELECT id FROM processed_table WHERE table_name = '$pTableName'), $pOperation, ROW_COUNT());";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV1($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_01 (last_first_name, short_league_name, club_name, age_group, region_name, umpire_type, season_year, match_count)
        SELECT
        u.last_first_name,
        l.short_league_name,
        te.club_name,
        a.age_group,
        l.region_name,
        u.umpire_type,
        ti.date_year,
        COUNT(DISTINCT m.match_id) AS match_count
        FROM dw_fact_match m
        INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        WHERE ti.date_year = $pSeasonYear
        GROUP BY u.last_first_name, l.short_league_name, te.club_name, a.age_group, l.region_name, u.umpire_type, ti.date_year;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV2($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_02 (last_first_name, short_league_name, age_group, age_sort_order, league_sort_order, two_ump_flag, region_name, umpire_type, season_year, match_count)
        SELECT
        u.last_first_name,
        l.short_league_name,
        a.age_group,
        a.sort_order,
        l.league_sort_order,
        0 AS two_ump_flag,
        l.region_name,
        u.umpire_type,
        ti.date_year,
        COUNT(DISTINCT m.match_id) AS match_count
        FROM dw_fact_match m
        INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        WHERE ti.date_year = $pSeasonYear
        GROUP BY u.last_first_name, l.short_league_name, a.age_group, a.sort_order, l.region_name, u.umpire_type, ti.date_year
        UNION ALL
        SELECT
        u.last_first_name,
        '2 Umpires' AS short_league_name,
        a.age_group,
        a.sort_order,
        10 AS league_sort_order,
        1 AS two_ump_flag,
        l.region_name,
        u.umpire_type,
        ti.date_year,
        COUNT(DISTINCT m.match_id) AS match_count
        FROM dw_fact_match m
        INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        INNER JOIN (
        SELECT
        m2.match_id,
        COUNT(DISTINCT u2.umpire_key) AS umpire_count
        FROM dw_fact_match m2
        INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
        INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
        WHERE u2.umpire_type = 'Field'
        AND a2.age_group = 'Seniors'
        GROUP BY m2.match_id
        HAVING COUNT(DISTINCT u2.umpire_key) = 2
        ) AS qryMatchesWithTwoUmpires ON m.match_id = qryMatchesWithTwoUmpires.match_id
        WHERE u.umpire_type = 'Field'
        AND a.age_group = 'Seniors'
        AND ti.date_year = $pSeasonYear
        GROUP BY u.last_first_name, l.short_league_name, a.age_group, a.sort_order, l.region_name, u.umpire_type, ti.date_year;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV4($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_04 (club_name, age_group, short_league_name, umpire_type, age_sort_order, league_sort_order, match_count, season_year, region_name)
        SELECT
        te.club_name,
        a.age_group,
        l.short_league_name,
        'Field' AS umpire_type,
        a.sort_order,
        l.league_sort_order,
        COUNT(DISTINCT match_id) AS match_count,
        ti.date_year,
        l.region_name
        FROM dw_fact_match m
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        WHERE m.match_id IN (
        SELECT match_id
        FROM staging_no_umpires s
        WHERE s.umpire_type = 'Field'
        AND s.short_league_name = l.short_league_name
        AND s.age_group = a.age_group
        )
        AND ti.date_year = $pSeasonYear
        GROUP BY te.club_name, a.age_group, l.short_league_name
        UNION ALL
        SELECT
        te.club_name,
        a.age_group,
        l.short_league_name,
        'Goal' AS umpire_type,
        a.sort_order,
        l.league_sort_order,
        COUNT(DISTINCT match_id) AS match_count,
        ti.date_year,
        l.region_name
        FROM dw_fact_match m
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        WHERE m.match_id IN (
        SELECT match_id
        FROM staging_no_umpires s
        WHERE s.umpire_type = 'Goal'
        AND s.short_league_name = l.short_league_name
        AND s.age_group = a.age_group
        )
        AND ti.date_year = $pSeasonYear
        GROUP BY te.club_name, a.age_group, l.short_league_name
        UNION ALL
        SELECT
        te.club_name,
        a.age_group,
        l.short_league_name,
        'Boundary' AS umpire_type,
        a.sort_order,
        l.league_sort_order,
        COUNT(DISTINCT match_id) AS match_count,
        ti.date_year,
        l.region_name
        FROM dw_fact_match m
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_team te ON (m.home_team_key = te.team_key OR m.away_team_key = te.team_key)
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        WHERE m.match_id IN (
        SELECT match_id
        FROM staging_no_umpires s
        WHERE s.umpire_type = 'Boundary'
        AND s.short_league_name = l.short_league_name
        AND s.age_group = a.age_group
        )
        AND ti.date_year = $pSeasonYear
        GROUP BY te.club_name, a.age_group, l.short_league_name;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV5($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_05 (umpire_type, age_group, age_sort_order, short_league_name, league_sort_order, region_name, match_no_ump, total_match_count, match_pct, season_year)
        SELECT
        ua.umpire_type,
        ua.age_group,
        ua.age_sort_order,
        ua.short_league_name,
        ua.league_sort_order,
        ua.region_name,
        IFNULL(sub_match_count.match_count, 0) AS match_no_ump,
        IFNULL(sub_total_matches.total_match_count, 0) AS total_match_count,
        IFNULL(FLOOR(sub_match_count.match_count / sub_total_matches.total_match_count * 100),0) AS match_pct,
        sub_total_matches.date_year
        FROM (
        SELECT
        umpire_type,
        age_group,
        short_league_name,
        age_sort_order,
        league_sort_order,
        region_name
        FROM staging_all_ump_age_league
        ) AS ua
        LEFT JOIN (
        SELECT
        a.age_group,
        l.short_league_name,
        a.sort_order,
        l.league_sort_order,
        ti.date_year,
        COUNT(DISTINCT match_id) AS total_match_count
        FROM dw_fact_match m
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        GROUP BY a.age_group , l.short_league_name , a.sort_order , l.league_sort_order, ti.date_year
        ) AS sub_total_matches
        ON ua.age_group = sub_total_matches.age_group
        AND ua.short_league_name = sub_total_matches.short_league_name
        LEFT JOIN (
        SELECT
        umpire_type,
        age_group,
        short_league_name,
        COUNT(s.match_id) AS Match_Count
        FROM staging_no_umpires s
        WHERE s.season_year = $pSeasonYear
        GROUP BY umpire_type , age_group , short_league_name
        ) AS sub_match_count
        ON ua.umpire_type = sub_match_count.umpire_type
        AND ua.age_group = sub_match_count.age_group
        AND ua.short_league_name = sub_match_count.short_league_name
        WHERE sub_total_matches.date_year = $pSeasonYear;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV6Staging($pSeasonYear) {
        $queryString = "INSERT INTO dw_rpt06_staging (umpire_key, umpire_type, last_first_name, match_id, date_year, league_key, age_group, region_name)
        SELECT DISTINCT
        u.umpire_key,
        u.umpire_type,
        u.last_first_name,
        m.match_id,
        dti.date_year,
        m.league_key,
        a.age_group,
        l.region_name
        FROM dw_fact_match m
        INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
        INNER JOIN dw_dim_time dti ON m.time_key = dti.time_key
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
        WHERE dti.date_year = $pSeasonYear;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV6StagingPart2($pSeasonYear) {
        $queryString = "INSERT INTO dw_rpt06_stg2 (umpire_type, age_group, region_name, first_umpire, second_umpire, date_year, match_id)
                SELECT DISTINCT
                s.umpire_type,
                s.age_group,
                s.region_name,
                s.last_first_name,
                s2.last_first_name,
                s.date_year,
                s.match_id
                FROM dw_rpt06_staging s
                INNER JOIN dw_rpt06_staging s2 ON s.match_id = s2.match_id
                    AND s.umpire_type = s2.umpire_type
                    AND s.umpire_key <> s2.umpire_key
                    AND s.league_key = s2.league_key;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV6($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_06 (umpire_type, age_group, region_name, first_umpire, second_umpire, season_year, match_count)
            SELECT
            s.umpire_type,
            s.age_group,
            s.region_name,
            s.first_umpire,
            s.second_umpire,
            s.date_year,
            COUNT(DISTINCT s.match_id) AS match_count
            FROM dw_rpt06_stg2 s
            GROUP BY s.umpire_type, s.age_group, s.region_name, s.first_umpire, s.second_umpire, s.date_year;";
        //query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV7($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_07 (umpire_type, age_group, region_name, short_league_name, season_year, age_sort_order, league_sort_order, umpire_count, match_count)
        SELECT
        m.umpire_type,
        m.age_group,
        m.region_name,
        m.short_league_name,
        $pSeasonYear AS season_year,
        m.sort_order AS age_sort_order,
        m.league_sort_order,
        CONCAT(sub.umpire_count, ' Umpires') AS umpire_count,
        COUNT(DISTINCT sub.match_id) AS match_count
        FROM mv_report_07_stg1 m
        INNER JOIN (
        SELECT
        s.match_id,
        s.umpire_type,
        s.age_group,
        s.short_league_name,
        COUNT(DISTINCT s.umpire_key) AS umpire_count
        FROM mv_report_07_stg1 s
        GROUP BY s.match_id,  s.umpire_type, s.age_group, s.short_league_name
        HAVING COUNT(DISTINCT s.umpire_key) IN (2, 3)
        
        ) AS sub
        ON m.match_id = sub.match_id
        AND m.umpire_type = sub.umpire_type
        AND m.age_group = sub.age_group
        GROUP BY m.short_league_name, m.age_group, m.region_name, m.umpire_type, m.sort_order, sub.umpire_count, m.league_sort_order;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV8($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_08 (season_year, full_name, match_count)
        SELECT
        ti.date_year,
        u.last_first_name,
        COUNT(DISTINCT m.match_id) AS match_count
        FROM dw_fact_match m
        INNER JOIN dw_dim_league l ON m.league_key = l.league_key
        INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
        INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
        WHERE ti.date_year = $pSeasonYear
        GROUP BY ti.date_year, u.first_name, u.last_name
        UNION ALL
        SELECT DISTINCT
        'Games Prior',
        u.last_first_name,
        u.games_prior
        FROM dw_dim_umpire u
        UNION ALL
        SELECT DISTINCT
        'Games Other Leagues',
        u.last_first_name,
        u.games_other_leagues
        FROM dw_dim_umpire u;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }
    
    private function updateTableMV8Totals() {
        $queryString = "UPDATE dw_mv_report_08 d
                INNER JOIN (
                    SELECT s.full_name,
                    SUM(s.match_count) AS total_match_count
                    FROM dw_mv_report_08 s
                    GROUP BY s.full_name
                ) AS grp ON d.full_name = grp.full_name
                SET d.total_match_count = grp.total_match_count;";
        //$query = $this->db->query($queryString);
        $rows = dbQuery($queryString);
    }

}
?>

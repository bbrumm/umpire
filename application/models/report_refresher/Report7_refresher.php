<?php
class Report7_refresher extends Report_table_refresher {

    function __construct() {
        parent::__construct();
    }

    public static function createRefresher($pImportFileID, $pSeason) {
        $reportTableRefresher = new Report7_refresher();
        $reportTableRefresher->setTableName("dw_mv_report_07");
        $reportTableRefresher->setImportFileID($pImportFileID);
        $reportTableRefresher->setSeasonYear($pSeason);

        return $reportTableRefresher;
    }

    public function refreshMVTable() {
        $this->updateTableMV7Staging($this->getSeasonYear());
        $this->logTableInsertOperation("mv_report_07_stg1", $this->getImportFileID());

        $this->disableKeys($this->getTableName());
        $this->deleteFromDWTableForYear($this->getTableName(), $this->getSeasonYear());
        $this->logTableDeleteOperation($this->getTableName(), $this->getImportFileID());
        $this->updateTableMV7($this->getSeasonYear());
        $this->logTableInsertOperation($this->getTableName(), $this->getImportFileID());
        $this->enableKeys($this->getTableName());
    }

    private function updateTableMV7Staging($pSeasonYear) {
        $queryString = "TRUNCATE TABLE mv_report_07_stg1;";
        $this->runQuery($queryString);

        $queryString = "INSERT INTO mv_report_07_stg1(match_id, umpire_type, age_group, short_league_name, umpire_key, region_name, sort_order, league_sort_order)
            SELECT
            m2.match_id,
            u2.umpire_type,
            a2.age_group,
            l2.short_league_name,
            u2.umpire_key,
            l2.region_name,
            a2.sort_order,
            l2.league_sort_order
            FROM dw_fact_match m2
            INNER JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
            INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
            INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
            INNER JOIN dw_dim_time ti2 ON m2.time_key = ti2.time_key
            WHERE ti2.date_year = ". $pSeasonYear .";";

        $this->runQuery($queryString);
    }

    private function updateTableMV7($pSeasonYear) {
        $queryString = "INSERT INTO dw_mv_report_07 (umpire_type, age_group, region_name, short_league_name, season_year, age_sort_order, league_sort_order, umpire_count, match_count)
            SELECT
            m.umpire_type,
            m.age_group,
            m.region_name,
            m.short_league_name,
            ". $pSeasonYear ." AS season_year,
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
        $this->runQuery($queryString);
    }


}

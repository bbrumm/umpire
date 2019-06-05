<?php
class Report8_refresher extends Report_table_refresher {

    function __construct() {
        parent::__construct();
    }

    public static function createRefresher($pImportFileID, $pSeason) {
        $reportTableRefresher = new Report8_refresher();
        $reportTableRefresher->setTableName("dw_mv_report_08");
        $reportTableRefresher->setImportFileID($pImportFileID);
        $reportTableRefresher->setSeasonYear($pSeason);
        $reportTableRefresher->setDataRefreshQuery(Report8_refresher::getUpdateMV8Query($reportTableRefresher->getSeasonYear()));

        return $reportTableRefresher;
    }

    private static function getUpdateMV8Query($pSeasonYear) {
        //Use the baseline data if the imported year is not 2018
        //This is because annual report/baseline data is more correct than the master spreadsheets
        if ($pSeasonYear <= 2017) {
            $queryString = "INSERT INTO dw_mv_report_08 (season_year, full_name, match_count, last_name, first_name)
                SELECT
                '". $pSeasonYear ."',
                CONCAT(b.last_name, ', ', b.first_name),
                b.games_$pSeasonYear,
                b.last_name,
                b.first_name
                FROM umpire_match_baseline b
                UNION ALL
                SELECT DISTINCT
                'Games Prior',
                u.last_first_name,
                u.games_prior,
                u.last_name,
                u.first_name
                FROM dw_dim_umpire u
                UNION ALL
                SELECT DISTINCT
                'Games Other Leagues',
                u.last_first_name,
                u.games_other_leagues,
                u.last_name,
                u.first_name
                FROM dw_dim_umpire u;";
        } else {
            $queryString = "INSERT INTO dw_mv_report_08 (season_year, full_name, match_count, last_name, first_name)
                SELECT
                ti.date_year,
                u.last_first_name,
                COUNT(DISTINCT m.match_id) AS match_count,
                u.last_name,
                u.first_name
                FROM dw_fact_match m
                INNER JOIN dw_dim_league l ON m.league_key = l.league_key
                INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
                INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
                WHERE ti.date_year = ". $pSeasonYear ."
                GROUP BY ti.date_year, u.first_name, u.last_name
                UNION ALL
                SELECT DISTINCT
                'Games Prior',
                u.last_first_name,
                u.games_prior,
                u.last_name,
                u.first_name
                FROM dw_dim_umpire u
                UNION ALL
                SELECT DISTINCT
                'Games Other Leagues',
                u.last_first_name,
                u.games_other_leagues,
                u.last_name,
                u.first_name
                FROM dw_dim_umpire u;";
        }
        return $queryString;
    }


    public function refreshMVTable() {
        $this->deleteFromDW8Table();

        parent::refreshMVTable();

        $this->updateTableMV8Totals();
        $this->logTableUpdateOperation();
        $this->enableKeys();
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
        $this->runQuery($queryString);
    }

    private function deleteFromDW8Table() {
        $queryString = "DELETE rec FROM dw_mv_report_08 rec 
          WHERE rec.season_year IN(CONVERT(". $this->getSeasonYear() .", CHAR), 'Games Other Leagues', 'Games Prior', 'Other Years');";
        $this->runQuery($queryString);
    }

}

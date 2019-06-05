<?php
class Report6_refresher extends Report_table_refresher {

    const SECOND_STAGING_TABLE = "dw_rpt06_stg2";
    const UMPIRE_TYPE_FIELD = "Field";
    const UMPIRE_TYPE_BOUNDARY = "Boundary";
    const UMPIRE_TYPE_GOAL = "Goal";
    
    function __construct() {
        parent::__construct();
    }

    public static function createRefresher($pImportFileID, $pSeason) {
        $reportTableRefresher = new Report6_refresher();
        $reportTableRefresher->setTableName("dw_mv_report_06");
        $reportTableRefresher->setImportFileID($pImportFileID);
        $reportTableRefresher->setSeasonYear($pSeason);

        return $reportTableRefresher;
    }

    public function refreshMVTable() {
        //TODO: Use the object's properties instead of passing parameters
        $this->disableKeys();
        $this->updateTableMV6Staging();
        $this->logTableInsertOperation();

        //TODO: Move all of these function calls into a single function, and call the new function. Lots of repetition here.

        //Run each of the umpire types into the staging 2 table
        //TODO: Remove the repeated calls to enable and disable keys
        $this->disableKeysForSpecificTable(self::SECOND_STAGING_TABLE);
        $this->updateTableMV6StagingPart2(self::UMPIRE_TYPE_FIELD);
        $this->logSpecificTableInsertOperation(self::SECOND_STAGING_TABLE);

        $this->updateTableMV6StagingPart2(self::UMPIRE_TYPE_GOAL);
        $this->logSpecificTableInsertOperation(self::SECOND_STAGING_TABLE);

        $this->updateTableMV6StagingPart2(self::UMPIRE_TYPE_BOUNDARY);
        $this->logSpecificTableInsertOperation(self::SECOND_STAGING_TABLE);

        //Now, insert into the staging table the opposite combination
        $this->updateTableMV6StagingPart2Opposite();
        $this->logSpecificTableInsertOperation(self::SECOND_STAGING_TABLE);
        $this->enableKeysForSpecificTable(self::SECOND_STAGING_TABLE);


        $this->disableKeys();
        $this->deleteFromDWTableForYear();
        $this->logTableDeleteOperation();
        $this->updateTableMV6();
        $this->logTableInsertOperation();
        $this->enableKeys();
    }

    private function updateTableMV6Staging() {
        $queryString = "INSERT INTO dw_rpt06_staging (umpire_key, umpire_type, last_first_name, match_id, date_year, league_key, age_group, region_name, short_league_name)
            SELECT DISTINCT
            u.umpire_key,
            u.umpire_type,
            u.last_first_name,
            m.match_id,
            dti.date_year,
            m.league_key,
            a.age_group,
            l.region_name,
            l.short_league_name
            FROM dw_fact_match m
            INNER JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
            INNER JOIN dw_dim_time dti ON m.time_key = dti.time_key
            INNER JOIN dw_dim_league l ON m.league_key = l.league_key
            INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
            WHERE dti.date_year = ". $this->getSeasonYear() .";";
        $this->runQuery($queryString);
    }

    private function updateTableMV6StagingPart2($pUmpireType) {
        $queryString = "INSERT INTO dw_rpt06_stg2 (umpire_type, age_group, region_name, short_league_name, first_umpire, second_umpire, date_year, match_id)
            SELECT DISTINCT
            s.umpire_type,
            s.age_group,
            s.region_name,
            s.short_league_name,
            s.last_first_name,
            s2.last_first_name,
            s.date_year,
            s.match_id
            FROM dw_rpt06_staging s
            INNER JOIN dw_rpt06_staging s2 ON s.match_id = s2.match_id
                AND s.umpire_type = s2.umpire_type
                AND s.umpire_key <> s2.umpire_key
                AND s.league_key = s2.league_key
            WHERE s.umpire_type = '". $pUmpireType ."'
            AND s.last_first_name > s2.last_first_name;";
        $this->runQuery($queryString);
    }

    private function updateTableMV6StagingPart2Opposite() {
        //Note: The first and second umpire fields are deliberately swapped.
        //This is because the original query only inserts a single combination, and this query inserts the second combination.
        $queryString = "INSERT INTO dw_rpt06_stg2 (umpire_type, age_group, region_name, short_league_name, first_umpire, second_umpire, date_year, match_id)
            SELECT 
            s.umpire_type,
            s.age_group,
            s.region_name,
            s.short_league_name,
            s.second_umpire,
            s.first_umpire,
            s.date_year,
            s.match_id
            FROM dw_rpt06_stg2 s";
        $this->runQuery($queryString);
    }

    private function updateTableMV6() {
        $queryString = "INSERT INTO dw_mv_report_06 (umpire_type, age_group, region_name, short_league_name, first_umpire, second_umpire, season_year, match_count)
        SELECT
        s.umpire_type,
        s.age_group,
        s.region_name,
        s.short_league_name,
        s.first_umpire,
        s.second_umpire,
        s.date_year,
        COUNT(DISTINCT s.match_id) AS match_count
        FROM dw_rpt06_stg2 s
        GROUP BY s.umpire_type, s.age_group, s.region_name, s.short_league_name, s.first_umpire, s.second_umpire, s.date_year;";
        $this->runQuery($queryString);
    }

}

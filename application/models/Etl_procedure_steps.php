<?php
/*
* @property Object db
*/
class Etl_procedure_steps extends CI_Model {
    const TABLE_DW_DIM_AGE_GROUP = "dw_dim_age_group";
    const TABLE_DW_DIM_LEAGUE = "dw_dim_league";
    const TABLE_DW_DIM_TEAM = "dw_dim_team";
    const TABLE_DW_DIM_TIME = "dw_dim_time";
    const TABLE_DW_DIM_UMPIRE = "dw_dim_umpire";
    const TABLE_STAGING_MATCH = "staging_match";
    const TABLE_STAGING_NO_UMP = "staging_no_umpires";
    const TABLE_STAGING_UMP_AGE_LG = "staging_all_ump_age_league";
    const TABLE_DW_RPT06_STG2 = "dw_rpt06_stg2";
    const TABLE_DW_RPT06_STG = "dw_rpt06_staging";
    const TABLE_MATCH_STAGING = "match_staging";
    const TABLE_DW_FACT_MATCH = "dw_fact_match";
    const TABLE_ROUND = "round";
    const TABLE_UMPIRE = "umpire";
    const TABLE_UMPIRE_NAME_TYPE = "umpire_name_type";
    const TABLE_MATCH_PLAYED = "match_played";
    const TABLE_UMPIRE_NAME_TYPE_MATCH = "umpire_name_type_match";
    const TABLE_COMPETITION_LOOKUP = "competition_lookup";
    const TABLE_TEAM = "team";
    const TABLE_GROUND = "ground";
	
    private $importFileID;
    private $currentSeason;
    private $queryBuilder;
    private $reportTableRefresher;
	
    function __construct() {
        parent::__construct();
        $this->load->model('Season');
	    $this->load->model('Etl_query_builder');
	    $this->load->model('report_refresher/Report_table_refresher');
	    $this->reportTableRefresher = new Report_table_refresher();
	    $this->queryBuilder = new Etl_query_builder();
    }

    public function runETLProcess($pSeason, $pImportedFileID) {
        $this->reportTableRefresher->setupScript();
        $this->importFileID = $pImportedFileID;

        $this->reportTableRefresher->setImportFileID($pImportedFileID);
        $this->reportTableRefresher->setSeasonYear($pSeason);

        $this->currentSeason = $pSeason;
        $this->queryBuilder->setSeason($pSeason);
        //TODO add exceptions or error logging if there are issues here, e.g. if INSERT statements insert 0 rows.

        $pSeason->setSeasonYear($this->lookupSeasonYear());
        $this->deleteUmpireNameTypeMatch();

        $this->deleteMatchPlayed();
        $this->deleteRound();
        $this->deleteMatchStaging();
        $this->deleteDWFactMatch();

        $this->insertRound();
        $this->insertUmpire();
        $this->insertUmpireNameType();
        $this->insertMatchStaging();
        $this->deleteDuplicateMatchStagingRecords();
        $this->insertMatchPlayed();
        $this->insertUmpireNameTypeMatch();

        $this->truncateDimFact();
        $this->insertDimUmpire();
        $this->insertDimAgeGroup();
        $this->insertDimLeague();
        $this->insertDimTeam();
        $this->insertDimTime();
        $this->insertStagingMatch();
        $this->insertStagingUmpAgeLeague();
        $this->insertFactMatch();
        $this->insertStagingNoUmpires();

        /*
        Insert New Competitions
        These will be displayed to the user when a file is imported. The leagues need to be assigned manually by the person who imported them.
        NOTE: This assumes that a competition name is unique to a season. If the same name is used in a different season, this needs to be changed
        so that the subquery includes WHERE season_id = pSeasonID

        First, delete the competitions which are still NULL from previous imports
        */
        $this->deleteCompetitionsWithMissingLeague();
        $this->insertCompetitionLookup();

        //Insert new teams. Clubs are added manually by the person importing the data
        $this->insertNewTeams();
        $this->insertNewGrounds();

        $this->reportTableRefresher->commitTransaction();
    }

    private function lookupSeasonYear() {
        $queryString = $this->queryBuilder->getLatestSeasonQuery();
        $query = $this->reportTableRefresher->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray[0]['season_year'];
    }

    private function deleteUmpireNameTypeMatch() {
        $this->reportTableRefresher->runDeleteETLStep(
            self::TABLE_UMPIRE_NAME_TYPE_MATCH, $this->queryBuilder->getDeleteUmpireNameTypeMatchQuery());
    }

    private function deleteMatchPlayed() {
        $this->reportTableRefresher->runDeleteETLStep(
            self::TABLE_MATCH_PLAYED, $this->queryBuilder->getDeleteMatchPlayedQuery());
    }

    private function deleteRound() {
        $this->reportTableRefresher->runDeleteETLStep(
            self::TABLE_ROUND, $this->queryBuilder->getDeleteRoundQuery());
    }

    private function deleteMatchStaging() {
	    $this->reportTableRefresher->truncateTable(self::TABLE_MATCH_STAGING);
        $this->reportTableRefresher->logSpecificTableDeleteOperation(self::TABLE_MATCH_STAGING);
    }

    private function deleteDWFactMatch() {
        $this->reportTableRefresher->runDeleteETLStep(
            self::TABLE_DW_FACT_MATCH, $this->queryBuilder->getDeleteDWFactMatchQuery());
    }

    private function insertRound() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_ROUND, $this->queryBuilder->getInsertRoundQuery());
    }

    private function insertUmpire() {
        $this->reportTableRefresher->runInsertETLStepWithoutKeys(
            self::TABLE_UMPIRE, $this->queryBuilder->getInsertUmpireQuery());
    }

    private function insertUmpireNameType() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_UMPIRE_NAME_TYPE, $this->queryBuilder->getInsertUmpireNameTypeQuery());
    }

    private function insertMatchStaging() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_MATCH_STAGING, $this->queryBuilder->getInsertMatchStagingQuery());
    }

    private function deleteDuplicateMatchStagingRecords() {
        $this->reportTableRefresher->runDeleteETLStep(
            self::TABLE_MATCH_STAGING, $this->queryBuilder->getDeleteDuplicateMatchStagingRecordsQuery());
    }

    private function insertMatchPlayed() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_MATCH_PLAYED, $this->queryBuilder->getInsertMatchPlayedQuery());
    }

    private function insertUmpireNameTypeMatch() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_UMPIRE_NAME_TYPE_MATCH, $this->queryBuilder->getInsertUmpireNameTypeMatchQuery());
    }

    private function truncateDimFact() {
        $this->reportTableRefresher->truncateTable(self::TABLE_DW_DIM_AGE_GROUP);
        $this->reportTableRefresher->truncateTable(self::TABLE_DW_DIM_LEAGUE);
        $this->reportTableRefresher->truncateTable(self::TABLE_DW_DIM_TEAM);
        $this->reportTableRefresher->truncateTable(self::TABLE_DW_DIM_TIME);
        $this->reportTableRefresher->truncateTable(self::TABLE_DW_DIM_UMPIRE);
        $this->reportTableRefresher->truncateTable(self::TABLE_STAGING_MATCH);
        $this->reportTableRefresher->truncateTable(self::TABLE_STAGING_NO_UMP);
        $this->reportTableRefresher->truncateTable(self::TABLE_STAGING_UMP_AGE_LG);
        $this->reportTableRefresher->truncateTable(self::TABLE_DW_RPT06_STG2);
        $this->reportTableRefresher->truncateTable(self::TABLE_DW_RPT06_STG);
    }

    private function insertDimUmpire() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_DW_DIM_UMPIRE, $this->queryBuilder->getInsertDimUmpireQuery());
    }

    private function insertDimAgeGroup() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_DW_DIM_AGE_GROUP, $this->queryBuilder->getInsertDimAgeGroupQuery());
    }

    private function insertDimLeague() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_DW_DIM_LEAGUE, $this->queryBuilder->getInsertDimLeagueQuery());
    }

    private function insertDimTeam() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_DW_DIM_TEAM, $this->queryBuilder->getInsertDimTeamQuery());
    }

    private function insertDimTime() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_DW_DIM_TIME, $this->queryBuilder->getInsertDimTimeQuery());
    }

    private function insertStagingMatch() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_STAGING_MATCH, $this->queryBuilder->getInsertStagingMatchQuery());
    }

    private function insertStagingUmpAgeLeague() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_STAGING_UMP_AGE_LG, $this->queryBuilder->getInsertStagingAllUmpAgeLeagueQuery());
    }

    private function insertFactMatch() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_DW_FACT_MATCH, $this->queryBuilder->getInsertDWFactMatchQuery());
    }

    private function insertStagingNoUmpires() {
        $this->reportTableRefresher->runInsertETLStep(
            self::TABLE_STAGING_NO_UMP, $this->queryBuilder->getInsertStagingNoUmpiresQuery());
    }

    private function deleteCompetitionsWithMissingLeague() {
        $queryString = "DELETE FROM competition_lookup WHERE league_id IS NULL;";
        $this->reportTableRefresher->runQuery($queryString);
	    $this->reportTableRefresher->logSpecificTableDeleteOperation(self::TABLE_COMPETITION_LOOKUP);
    }

    private function insertCompetitionLookup() {
        $this->reportTableRefresher->runInsertETLStepWithoutKeys(
            self::TABLE_COMPETITION_LOOKUP, $this->queryBuilder->getInsertCompetitionLookupQuery());
    }

    private function insertNewTeams() {
        $this->reportTableRefresher->runInsertETLStepWithoutKeys(
            self::TABLE_TEAM, $this->queryBuilder->getInsertTeamQuery());
    }

    private function insertNewGrounds() {
        $this->reportTableRefresher->runInsertETLStepWithoutKeys(
            self::TABLE_GROUND, $this->queryBuilder->getInsertNewGroundsQuery());
    }
}

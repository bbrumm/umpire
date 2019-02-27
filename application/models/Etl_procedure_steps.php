<?php
/*
* @property Object db
*/
class Etl_procedure_steps extends CI_Model
{
    const OPERATION_INSERT = 1;
    const OPERATION_UPDATE = 2;
    const OPERATION_DELETE = 3;
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
	
    function __construct() {
        parent::__construct();
        $this->load->model('Season');
	    $this->load->model('Etl_query_builder');
	    $this->queryBuilder = new Etl_query_builder();
    }

    public function runETLProcess($pSeason, $pImportedFileID) {
        $this->setupScript();
	$this->importFileID = $pImportedFileID;
	$this->currentSeason = $pSeason;
	$this->queryBuilder->setSeason($pSeason);
        //TODO add exceptions or error logging if there are issues here, e.g. if INSERT statements insert 0 rows.

        $pSeason->setSeasonYear($this->lookupSeasonYear());
        $this->deleteUmpireNameTypeMatch();

        $this->deleteMatchPlayed();
        $this->deleteRound();
        $this->deleteMatchStaging();
        $this->deleteMVReport1();
        $this->deleteMVReport2();
        $this->deleteMVReport4();
        $this->deleteMVReport5();
        $this->deleteMVReport6();
        $this->deleteMVReport7();
        $this->deleteMVReport8();
        $this->deleteDWFactMatch();

        $this->insertRound();
        $this->insertUmpire();
        $this->insertUmpireNameType();
        $this->insertMatchStaging();
        $this->deleteDuplicateMatchStagingRecords();
        $this->insertMatchPlayed();
        $this->insertUmpireNameTypeMatch($pSeason);

        $this->truncateDimFact();
        $this->insertDimUmpire();
        $this->insertDimAgeGroup();
        $this->insertDimLeague();
        $this->insertDimTeam();
        $this->insertDimTime();
        $this->insertStagingMatch();
        $this->insertStagingUmpAgeLeague();
        $this->insertFactMatch($pSeason);
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
        /*
        Insert new teams. Clubs are added manually by the person importing the data
        */
        $this->insertNewTeams();
        $this->insertNewGrounds();

        $this->commitTransaction();
    }

    private function runQuery($pQueryString) {
        return $this->db->query($pQueryString);
    }

    private function commitTransaction() {
        $queryString = "COMMIT;";
        $this->runQuery($queryString);
    }

    private function disableKeys($pTableName) {
        $queryString = "ALTER TABLE ". $pTableName ." DISABLE KEYS;";
        $this->runQuery($queryString);
    }

    private function enableKeys($pTableName) {
        $queryString = "ALTER TABLE ". $pTableName ." ENABLE KEYS;";
        $this->runQuery($queryString);
    }

    private function setupScript() {
        $queryString = "SET group_concat_max_len = 15000;";
        $this->runQuery($queryString);
    }

    private function lookupSeasonYear() {
        $queryString = $this->queryBuilder->getLatestSeasonQuery();
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray[0]['season_year'];
    }

    private function deleteUmpireNameTypeMatch() {
        $queryString = $this->queryBuilder->getDeleteUmpireNameTypeMatchQuery();
        $this->runQuery($queryString);
        $this->logTableDeleteOperation(self::TABLE_UMPIRE_NAME_TYPE_MATCH);
    }
	
	private function logTableInsertOperation($pTableName) {
	    $this->logTableOperation($pTableName, self::OPERATION_INSERT);
	}
	
	private function logTableDeleteOperation($pTableName) {
	    $this->logTableOperation($pTableName, self::OPERATION_DELETE);
	}

    private function logTableOperation($pTableName, $pOperationType) {
        $queryString = "INSERT INTO table_operations (imported_file_id, processed_table_id, operation_id, operation_datetime, rowcount)
VALUES (". $this->importFileID .", (SELECT id FROM processed_table WHERE table_name = '". $pTableName ."'), ". $pOperationType .",  NOW(), ROW_COUNT());";
        $this->runQuery($queryString);
    }

    private function deleteMatchPlayed() {
        $queryString = $this->queryBuilder->getDeleteMatchPlayedQuery();
        $this->runQuery($queryString);
        $this->logTableDeleteOperation(self::TABLE_MATCH_PLAYED);
    }

    private function deleteRound() {
        $queryString = $this->queryBuilder->getDeleteRoundQuery();
        $this->runQuery($queryString);
        $this->logTableDeleteOperation(self::TABLE_ROUND);
    }

    private function deleteMatchStaging() {
	$this->truncateTable(self::TABLE_MATCH_STAGING);
        $this->logTableDeleteOperation(self::TABLE_MATCH_STAGING);
    }

    private function deleteMVReport1() {
	$this->deleteMVReportTable("dw_mv_report_01");
    }

    private function deleteMVReport2() {
        $this->deleteMVReportTable("dw_mv_report_02");
    }

    private function deleteMVReport4() {
        $this->deleteMVReportTable("dw_mv_report_04");
    }

    private function deleteMVReport5() {
        $this->deleteMVReportTable("dw_mv_report_05");
    }

    private function deleteMVReport6() {
       $this->deleteMVReportTable("dw_mv_report_06");
    }

    private function deleteMVReport7() {
        $this->deleteMVReportTable("dw_mv_report_07");
    }

	//Report8 table delete is done differently as it contains more data
    private function deleteMVReport8() {
        $queryString = $this->queryBuilder->getDeleteMVReport8Query();
        $this->runQuery($queryString);
        $this->logTableDeleteOperation("dw_mv_report_08");
    }
	
    private function deleteMVReportTable($pTableName) {
        $queryString = "DELETE rec FROM ". $pTableName ." rec WHERE rec.season_year = ". $this->currentSeason->getSeasonYear() .";";
        $this->runQuery($queryString);
        $this->logTableDeleteOperation($pTableName);
    }

    private function deleteDWFactMatch() {
        $queryString = $this->queryBuilder->getDeleteDWFactMatchQuery();
        $this->runQuery($queryString);
        $this->logTableDeleteOperation("dw_fact_match");
    }

    private function insertRound() {
        $this->disableKeys(self::TABLE_ROUND);
        $queryString = $this->queryBuilder->getInsertRoundQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_ROUND);
        $this->enableKeys(self::TABLE_ROUND);
    }

    private function insertUmpire() {
        $queryString = $this->queryBuilder->getInsertUmpireQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_UMPIRE);
    }

    private function insertUmpireNameType() {
        $this->disableKeys(self::TABLE_UMPIRE_NAME_TYPE);
        $queryString = $this->queryBuilder->getInsertUmpireNameTypeQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_UMPIRE_NAME_TYPE);
        $this->enableKeys(self::TABLE_UMPIRE_NAME_TYPE);
    }

    private function insertMatchStaging() {
        $this->disableKeys(self::TABLE_MATCH_STAGING);
        $queryString = $this->queryBuilder->getInsertMatchStagingQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_MATCH_STAGING);
        $this->enableKeys(self::TABLE_MATCH_STAGING);
    }

    private function deleteDuplicateMatchStagingRecords() {
        $queryString = $this->queryBuilder->getDeleteDuplicateMatchStagingRecordsQuery();
        $this->runQuery($queryString);
        $this->logTableDeleteOperation(self::TABLE_MATCH_STAGING);
    }

    private function insertMatchPlayed() {
        $this->disableKeys(self::TABLE_MATCH_PLAYED);
        $queryString = $this->queryBuilder->getInsertMatchPlayedQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_MATCH_PLAYED);
        $this->enableKeys(self::TABLE_MATCH_PLAYED);
    }

    private function insertUmpireNameTypeMatch() {
        $this->disableKeys(self::TABLE_UMPIRE_NAME_TYPE_MATCH);
        $queryString = $this->queryBuilder->getInsertUmpireNameTypeMatchQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_UMPIRE_NAME_TYPE_MATCH);
        $this->enableKeys(self::TABLE_UMPIRE_NAME_TYPE_MATCH);
    }

    private function truncateDimFact() {
        $this->truncateTable(self::TABLE_DW_DIM_AGE_GROUP);
        $this->truncateTable(self::TABLE_DW_DIM_LEAGUE);
        $this->truncateTable(self::TABLE_DW_DIM_TEAM);
        $this->truncateTable(self::TABLE_DW_DIM_TIME);
        $this->truncateTable(self::TABLE_DW_DIM_UMPIRE);
        $this->truncateTable(self::TABLE_STAGING_MATCH);
        $this->truncateTable(self::TABLE_STAGING_NO_UMP);
        $this->truncateTable(self::TABLE_STAGING_UMP_AGE_LG);
        $this->truncateTable(self::TABLE_DW_RPT06_STG2);
        $this->truncateTable(self::TABLE_DW_RPT06_STG);
    }
	
    private function truncateTable($pTableName) {
	$queryString = "TRUNCATE ". $pTableName .";";
        $this->runQuery($queryString);	
    }

    private function insertDimUmpire() {
        $this->disableKeys(self::TABLE_DW_DIM_UMPIRE);
        $queryString = $this->queryBuilder->getInsertDimUmpireQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_DW_DIM_UMPIRE);
        $this->enableKeys(self::TABLE_DW_DIM_UMPIRE);
    }


    private function insertDimAgeGroup() {
        $this->disableKeys(self::TABLE_DW_DIM_AGE_GROUP);
        $queryString = $this->queryBuilder->getInsertDimAgeGroupQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_DW_DIM_AGE_GROUP);
        $this->enableKeys(self::TABLE_DW_DIM_AGE_GROUP);
    }

    private function insertDimLeague() {
        $this->disableKeys(self::TABLE_DW_DIM_LEAGUE);

        $queryString = $this->queryBuilder->getInsertDimLeagueQuery();
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_DW_DIM_LEAGUE);
        $this->enableKeys(self::TABLE_DW_DIM_LEAGUE);
    }

    private function insertDimTeam() {
        $this->disableKeys(self::TABLE_DW_DIM_TEAM);

        $queryString = $this->queryBuilder->getInsertDimTeamQuery();
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_DW_DIM_TEAM);
        $this->enableKeys(self::TABLE_DW_DIM_TEAM);
    }

    private function insertDimTime() {
        $this->disableKeys(self::TABLE_DW_DIM_TIME);

        $queryString = $this->queryBuilder->getInsertDimTimeQuery();
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_DW_DIM_TIME);
        $this->enableKeys(self::TABLE_DW_DIM_TIME);
    }

    private function insertStagingMatch() {
        $this->disableKeys(self::TABLE_STAGING_MATCH);

        $queryString = $this->queryBuilder->getInsertStagingMatchQuery();
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_STAGING_MATCH);
        $this->enableKeys(self::TABLE_STAGING_MATCH);
    }

    private function insertStagingUmpAgeLeague() {
        $this->disableKeys(self::TABLE_STAGING_UMP_AGE_LG);

        $queryString = $this->queryBuilder->getInsertStagingUmpAgeLeagueQuery();
        $this->runQuery($queryString);
	    
        $this->logTableInsertOperation(self::TABLE_STAGING_UMP_AGE_LG);
        $this->enableKeys(self::TABLE_STAGING_UMP_AGE_LG);
    }

    private function insertFactMatch() {
        $this->disableKeys(self::TABLE_DW_FACT_MATCH);
        $queryString = $this->queryBuilder->getInsertDWFactMatchQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_DW_FACT_MATCH);
        $this->enableKeys(self::TABLE_DW_FACT_MATCH);
    }

    private function insertStagingNoUmpires() {
        $this->disableKeys(self::TABLE_STAGING_NO_UMP);
        $queryString = $this->queryBuilder->getInsertStagingNoUmpiresQuery();
        $this->runQuery($queryString);
        $this->logTableInsertOperation(self::TABLE_STAGING_NO_UMP);
        $this->enableKeys(self::TABLE_STAGING_NO_UMP);
    }

    private function deleteCompetitionsWithMissingLeague() {
        $queryString = "DELETE FROM competition_lookup WHERE league_id IS NULL;";
        $this->runQuery($queryString);
	$this->logTableDeleteOperation(self::TABLE_COMPETITION_LOOKUP);
    }

    private function insertCompetitionLookup() {
        $queryString = $this->queryBuilder->getInsertCompetitionLookupQuery();
        $this->runQuery($queryString);
	$this->logTableInsertOperation(self::TABLE_COMPETITION_LOOKUP);
    }

    private function insertNewTeams() {
        $queryString = $this->queryBuilder->getInsertTeamQuery();
        $this->runQuery($queryString);
	$this->logTableInsertOperation(self::TABLE_TEAM);
    }

    private function insertNewGrounds() {
        $queryString = $this->queryBuilder->getInsertNewGroundsQuery();
        $this->runQuery($queryString);
	$this->logTableInsertOperation(self::TABLE_GROUND);
    }
}

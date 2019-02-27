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
        $queryString = "DELETE rec FROM dw_mv_report_08 rec 
WHERE rec.season_year IN(CONVERT(". $this->currentSeason->getSeasonYear() .", CHAR), 'Games Other Leagues', 'Games Prior', 'Other Years');;";
        $this->runQuery($queryString);
        $this->logTableDeleteOperation("dw_mv_report_08");
    }
	
    private function deleteMVReportTable($pTableName) {
        $queryString = "DELETE rec FROM ". $pTableName ." rec WHERE rec.season_year = ". $this->currentSeason->getSeasonYear() .";";
        $this->runQuery($queryString);
        $this->logTableDeleteOperation($pTableName);
    }

    private function deleteDWFactMatch() {
        $queryString = "DELETE rec FROM dw_fact_match rec
INNER JOIN dw_dim_time t ON rec.time_key = t.time_key
WHERE t.date_year = ". $this->currentSeason->getSeasonYear() .";";
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
        $queryString = "DELETE m1 FROM match_staging m1,
    match_staging m2 
WHERE
    m1.appointments_id > m2.appointments_id
    AND m1.ground_id = m2.ground_id
    AND m1.round_id = m2.round_id
    AND m1.appointments_time = m2.appointments_time
    AND m1.home_team_id = m2.home_team_id
    AND m1.away_team_id = m2.away_team_id;";
        $this->runQuery($queryString);
        $this->logTableDeleteOperation(self::TABLE_MATCH_STAGING);
    }

    private function insertMatchPlayed() {
        $this->disableKeys(self::TABLE_MATCH_PLAYED);

        $queryString = "INSERT INTO match_played (round_ID, ground_id, home_team_id, away_team_id, match_time, match_staging_id)
SELECT match_staging.round_ID, match_staging.ground_id, match_staging.home_team_id, 
match_staging.away_team_id, match_staging.appointments_time,
match_staging.match_staging_id
FROM match_staging;";
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

        /*
        Populate DimUmpire
        Uses LEFT JOIN to cater for umpires who haven't been imported (those that were pre-2015) as we want to include them in report 8
        */
        $queryString = "INSERT INTO dw_dim_umpire (first_name, last_name, last_first_name, umpire_type, games_prior, games_other_leagues)
SELECT DISTINCT
u.first_name,
u.last_name,
CONCAT(u.last_name, ', ', u.first_name) AS last_first_name,
ut.umpire_type_name AS umpire_type,
u.games_prior,
u.games_other_leagues
FROM umpire u
LEFT JOIN umpire_name_type unt ON u.id = unt.umpire_id
LEFT JOIN umpire_type ut ON unt.umpire_type_id = ut.ID;
";
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_DW_DIM_UMPIRE);
        $this->enableKeys(self::TABLE_DW_DIM_UMPIRE);
    }


    private function insertDimAgeGroup() {
        $this->disableKeys(self::TABLE_DW_DIM_AGE_GROUP);

        $queryString = "INSERT INTO dw_dim_age_group (age_group, sort_order, division)
SELECT
ag.age_group,
ag.display_order AS sort_order,
d.division_name
FROM age_group ag
INNER JOIN age_group_division agd ON ag.id = agd.age_group_id
INNER JOIN division d ON agd.division_id = d.ID
ORDER BY ag.display_order;";
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_DW_DIM_AGE_GROUP);
        $this->enableKeys(self::TABLE_DW_DIM_AGE_GROUP);
    }

    private function insertDimLeague() {
        $this->disableKeys(self::TABLE_DW_DIM_LEAGUE);

        $queryString = "INSERT INTO dw_dim_league (short_league_name, full_name, region_name, competition_name, league_year, league_sort_order)
SELECT DISTINCT
l.short_league_name,
l.league_name,
r.region_name,
c.competition_name,
s.season_year,
sl.display_order AS league_sort_order
FROM league l
INNER JOIN short_league_name sl ON l.short_league_name = sl.short_league_name
INNER JOIN region r ON l.region_id = r.id
INNER JOIN competition_lookup c ON l.ID = c.league_id
INNER JOIN season s ON c.season_id = s.id;";
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_DW_DIM_LEAGUE);
        $this->enableKeys(self::TABLE_DW_DIM_LEAGUE);
    }

    private function insertDimTeam() {
        $this->disableKeys(self::TABLE_DW_DIM_TEAM);

        $queryString = "INSERT INTO dw_dim_team (team_name, club_name)
SELECT
t.team_name,
c.club_name
FROM team t
INNER JOIN club c ON t.club_id = c.id
ORDER BY t.team_name, c.club_name;";
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_DW_DIM_TEAM);
        $this->enableKeys(self::TABLE_DW_DIM_TEAM);
    }

    private function insertDimTime() {
        $this->disableKeys(self::TABLE_DW_DIM_TIME);

        $queryString = "INSERT INTO dw_dim_time (match_date, date_year, date_month, date_day, date_hour, date_minute, weekend_date, weekend_year, weekend_month, weekend_day)
SELECT
DISTINCT
/*r.round_number,*/
m.match_time,
YEAR(m.match_time) AS date_year,
MONTH(m.match_time) AS date_month,
DAY(m.match_time) AS date_day,
HOUR(m.match_time) AS date_hour,
MINUTE(m.match_time) AS date_minute,
ADDDATE(r.round_date, (5-Weekday(r.round_date))) AS weekend_date,
YEAR(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_year,
MONTH(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_month,
DAY(ADDDATE(r.round_date, (5-Weekday(r.round_date)))) AS weekend_day
FROM match_played m
INNER JOIN round r ON m.round_id = r.id
ORDER BY m.match_time;";
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_DW_DIM_TIME);
        $this->enableKeys(self::TABLE_DW_DIM_TIME);
    }

    private function insertStagingMatch() {
        $this->disableKeys(self::TABLE_STAGING_MATCH);

        $queryString = "INSERT INTO staging_match (season_id, season_year, umpire_id, umpire_first_name, umpire_last_name,
home_club, home_team, away_club, away_team, short_league_name, league_name, age_group_id, age_group_name, 
umpire_type_name, match_id, match_time, region_id, region_name, division_name, competition_name)
SELECT 
    s.id,
    s.season_year,
    u.id,
    u.first_name,
    u.last_name,
    hmc.club_name AS home_club,
    hmt.team_name AS home_team_name,
    awc.club_name AS away_club,
    awt.team_name AS away_team_name,
    l.short_league_name,
    l.league_name,
    ag.id,
    ag.age_group,
    ut.umpire_type_name,
    m.ID,
    m.match_time,
    r.id,
    r.region_name,
    d.division_name,
    cl.competition_name
FROM
match_played m
INNER JOIN    round rn ON rn.ID = m.round_id
INNER JOIN    league l ON l.ID = rn.league_id
INNER JOIN    age_group_division agd ON agd.ID = l.age_group_division_id
INNER JOIN    age_group ag ON ag.ID = agd.age_group_id
INNER JOIN    team hmt ON hmt.ID = m.home_team_id
INNER JOIN    club hmc ON hmc.ID = hmt.club_id
INNER JOIN    team awt ON awt.ID = m.away_team_id
INNER JOIN    club awc ON awc.ID = awt.club_id
INNER JOIN    division d ON agd.division_id = d.id
INNER JOIN    competition_lookup cl ON cl.league_id = l.ID
LEFT JOIN    umpire_name_type_match untm ON m.ID = untm.match_id
LEFT JOIN    umpire_name_type unt ON unt.ID = untm.umpire_name_type_id
LEFT JOIN    umpire_type ut ON ut.ID = unt.umpire_type_id
LEFT JOIN    umpire u ON u.ID = unt.umpire_id
INNER JOIN    season s ON s.id = rn.season_id AND cl.season_id = s.id
INNER JOIN    region r ON r.id = l.region_id;";
        $this->runQuery($queryString);

        $this->logTableInsertOperation(self::TABLE_STAGING_MATCH);
        $this->enableKeys(self::TABLE_STAGING_MATCH);
    }

    private function insertStagingUmpAgeLeague() {
        $this->disableKeys(self::TABLE_STAGING_UMP_AGE_LG);

        $queryString = "INSERT INTO staging_all_ump_age_league (age_group, umpire_type, short_league_name, region_name, age_sort_order, league_sort_order)
SELECT DISTINCT
ag.age_group,
ut.umpire_type_name,
l.short_league_name,
r.region_name,
ag.display_order,
CASE short_league_name
	WHEN 'GFL' THEN 1
	WHEN 'BFL' THEN 2
	WHEN 'GDFL' THEN 3
	WHEN 'CDFNL' THEN 4
	ELSE 10
END league_sort_order
FROM age_group ag
INNER JOIN age_group_division agd ON ag.ID = agd.age_group_id
INNER JOIN league l ON l.age_group_division_id = agd.ID
CROSS JOIN umpire_type ut
INNER JOIN region r ON l.region_id = r.id;";
        $this->runQuery($queryString);
	    
        $this->logTableInsertOperation(self::TABLE_STAGING_UMP_AGE_LG);
        $this->enableKeys(self::TABLE_STAGING_UMP_AGE_LG);
    }

    private function insertFactMatch() {
        $this->disableKeys(self::TABLE_DW_FACT_MATCH);

        $queryString = "INSERT INTO dw_fact_match (match_id, umpire_key, age_group_key, league_key, time_key, home_team_key, away_team_key)
SELECT 
s.match_id,
du.umpire_key,
dag.age_group_key,
dl.league_key,
dt.time_key,
dth.team_key AS home_team_key,
dta.team_key AS away_team_key
FROM
staging_match s
LEFT JOIN dw_dim_umpire du ON (s.umpire_first_name = du.first_name
	AND s.umpire_last_name = du.last_name
	AND s.umpire_type_name = du.umpire_type
)
INNER JOIN dw_dim_age_group dag ON (
	s.age_group_name = dag.age_group
	AND s.division_name = dag.division
)
INNER JOIN dw_dim_league dl ON (
	s.short_league_name = dl.short_league_name
	AND s.league_name = dl.full_name
	AND s.region_name = dl.region_name
    AND s.competition_name = dl.competition_name
)
INNER JOIN dw_dim_team dth ON (
	s.home_team = dth.team_name
	AND s.home_club = dth.club_name
    )
INNER JOIN dw_dim_team dta ON (
	s.away_team = dta.team_name
	AND s.away_club = dta.club_name
    )
INNER JOIN dw_dim_time dt ON (
	s.match_time = dt.match_date
    AND s.season_year = dl.league_year
    AND s.season_year = ". $this->currentSeason->getSeasonYear() ."
);";
        $this->runQuery($queryString);
	
        $this->logTableInsertOperation(self::TABLE_DW_FACT_MATCH);
        $this->enableKeys(self::TABLE_DW_FACT_MATCH);
    }

    private function insertStagingNoUmpires() {
        $this->disableKeys(self::TABLE_STAGING_NO_UMP);

        $queryString = "INSERT INTO staging_no_umpires (weekend_date, age_group, umpire_type, short_league_name, team_names, match_id, season_year)
SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Field',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Field'
)
UNION ALL

SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Boundary',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Boundary'
)
UNION ALL

SELECT DISTINCT
ti.weekend_date,
a.age_group,
'Goal',
l.short_league_name,
CONCAT(th.team_name, ' vs ', ta.team_name) AS team_names,
m.match_id,
ti.date_year
FROM dw_fact_match m
LEFT JOIN dw_dim_umpire u ON m.umpire_key = u.umpire_key
INNER JOIN dw_dim_league l ON m.league_key = l.league_key
INNER JOIN dw_dim_age_group a ON m.age_group_key = a.age_group_key
INNER JOIN dw_dim_time ti ON m.time_key = ti.time_key
INNER JOIN dw_dim_team th ON m.home_team_key = th.team_key
INNER JOIN dw_dim_team ta ON m.away_team_key = ta.team_key
WHERE m.match_id NOT IN (
	SELECT
	DISTINCT
	m2.match_id
	FROM dw_fact_match m2
	LEFT JOIN dw_dim_umpire u2 ON m2.umpire_key = u2.umpire_key
	INNER JOIN dw_dim_league l2 ON m2.league_key = l2.league_key
	INNER JOIN dw_dim_age_group a2 ON m2.age_group_key = a2.age_group_key
	WHERE u2.umpire_type = 'Goal'
);
";
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
        $queryString = "INSERT INTO competition_lookup (competition_name, season_id, league_id)
SELECT DISTINCT competition_name, ". $this->currentSeason->getSeasonID() .", NULL
FROM match_import
WHERE competition_name NOT IN (
	SELECT competition_name
    FROM competition_lookup
);";
        $this->runQuery($queryString);
	$this->logTableInsertOperation(self::TABLE_COMPETITION_LOOKUP);
    }

    private function insertNewTeams() {
        $queryString = "INSERT INTO team (team_name, club_id)
SELECT home_team, NULL
FROM match_import
WHERE home_team NOT IN (
	SELECT team_name
    FROM team
)
UNION
SELECT away_team, NULL
FROM match_import
WHERE away_team NOT IN (
	SELECT team_name
    FROM team
);";
        $this->runQuery($queryString);
	$this->logTableInsertOperation(self::TABLE_TEAM);
    }

    private function insertNewGrounds() {
        $queryString = "INSERT INTO ground (main_name, alternative_name)
SELECT DISTINCT ground, ground
FROM match_import
WHERE ground NOT IN (
	SELECT alternative_name
	FROM ground
);";
        $this->runQuery($queryString);
	$this->logTableInsertOperation(self::TABLE_GROUND);
    }
}

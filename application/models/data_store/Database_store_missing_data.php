<?php
require_once 'IData_store_missing_data.php';
/*
* @property Object db
*/
class Database_store_missing_data extends CI_Model implements IData_store_missing_data
{

    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');
    }

    private function runQuery($queryString, $arrayParam = null) {
        return $this->db->query($queryString, $arrayParam);
    }

    public function updateSingleCompetition($pLeagueIDToUse, $pCompetitionData) {
        $queryString = "UPDATE competition_lookup
            SET league_id = ?
            WHERE id = ?;";
        $queryParams = array($pLeagueIDToUse, $pCompetitionData['competition_id']);
        //Log the query to see what is happening
        $this->logQuery($queryString, $queryParams);
        $this->runQuery($queryString, $queryParams);
        return true;
    }

    //TODO: Move this into a query logging class
    private function logQuery($pQueryToLog, $pParamArray) {
        $queryString = "INSERT INTO query_log(query_time, sql_query, query_params) ".
            "VALUES(NOW(), '" . substr(addslashes($pQueryToLog), 0, 2000) ."', '". addslashes(implode(",", $pParamArray)) ."');";
        $this->runQuery($queryString);
        $this->runQuery("COMMIT;");
    }

    public function findSingleLeagueIDFromParameters($competitionData) {
        $queryString = "SELECT MIN(l.id) AS league_id
            FROM league l
            INNER JOIN region r ON l.region_id = r.id
            INNER JOIN age_group_division agd ON l.age_group_division_id = agd.id
            INNER JOIN age_group ag ON agd.age_group_id = ag.id
            INNER JOIN division d ON agd.division_id = d.id
            WHERE 1=1
            AND r.id = '". $competitionData['region'] ."'
            AND l.short_league_name = '". $competitionData['short_league_name']."'
            AND d.id = '". $competitionData['division']."'
            AND ag.id = '". $competitionData['age_group']."';";

        $query = $this->runQuery($queryString);

        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();

        $leagueIDToUse = $resultArray[0]['league_id'];

        if (is_null($leagueIDToUse) || $leagueIDToUse == 0) {
            /* No matching leagues found. We need to insert some data first.
             * We have the short_league_name, the league_name, and the region_id.
             * We need the age_group_division_id
             */
            $leagueIDToUse = $this->insertNewLeague($competitionData);
            echo "OK";

        } else {
            //This value is used for the validation in the JavaScript code in the upload_success page.
            echo "OK";
        }

        $query->free_result();
        return $leagueIDToUse;
    }

    public function insertNewLeague($competitionData) {
        $queryString = "INSERT INTO league (league_name, sponsored_league_name, 
            short_league_name, age_group_division_id, region_id)
            SELECT
            'AFL Barwon' AS league_name,
            'AFL Barwon' AS sponsored_league_name,
            ? AS short_league_name,
            agd.id AS agd_id,
            ? AS region_id
            FROM
            age_group_division agd
            INNER JOIN age_group ag ON agd.age_group_id = ag.id
            INNER JOIN division d ON agd.division_id = d.id
            WHERE ag.id = ?
            AND d.id = ?;";

        $queryParams = array(
            $competitionData['short_league_name'],
            $competitionData['region'],
            $competitionData['age_group'],
            $competitionData['division']
        );

        $this->logQuery($queryString, $queryParams);
        $this->runQuery($queryString, $queryParams);

        $insertedLeagueID = $this->db->insert_id();
        return $insertedLeagueID;
    }

    public function checkAndInsertAgeGroupDivision($competitionData) {
        $queryString = "SELECT COUNT(agd.id) AS count_agd
            FROM age_group_division agd
            WHERE agd.age_group_id = ?
            AND agd.division_id = ?";
        $query = $this->runQuery($queryString, array(
            $competitionData['age_group'],
            $competitionData['division']
        ));

        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();

        $countOfAgeGroupDivisions = $resultArray[0]['count_agd'];

        if ($countOfAgeGroupDivisions == 0) {
            //Insert new AGD
            $queryString = "INSERT INTO age_group_division(age_group_id, division_id)
                VALUES (?, ?);";

            $this->runQuery($queryString, array(
                $competitionData['age_group'],
                $competitionData['division']
            ));

        }
    }

    public function insertAgeGroupDivision($competitionData) {}

    public function updateTeamAndClubTables(IData_store_matches $pDataStore, array $pPostData) {}

    public function insertNewClub($pClubName) {
        $queryString = "INSERT INTO club (club_name) VALUES (?);";
        $this->runQuery($queryString, array($pClubName));
        return $this->db->insert_id();
    }

    public function updateTeamTable($pTeamID, $pClubID) {
        $queryString = "UPDATE team
            SET club_id = ?
            WHERE id = ?;";
        //$this->debug_library->debugOutput("updateTeamTable", $pTeamID);
        $this->runQuery($queryString, array($pClubID, $pTeamID));
    }

}
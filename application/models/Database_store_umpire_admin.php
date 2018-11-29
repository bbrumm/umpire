<?php
require_once 'IData_store_umpire_admin.php';
class Database_store_umpire_admin extends CI_Model implements IData_store_umpire_admin
{

    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');

    }

    public function getAllUmpiresAndValues() {
        $queryString = "SELECT u.id, u.first_name, u.last_name,
            games_prior, games_other_leagues
            FROM umpire u
            ORDER BY u.last_name, u.first_name;";

        //Run query and store result in array
        $query = $this->db->query($queryString);
        return $query->result_array();
    }

    public function updateUmpireRecords($pUmpireArray) {
        $queryString = $this->buildUmpireUpdateQueryString($pUmpireArray);
        $query = $this->db->query($queryString);
    }

    private function buildUmpireUpdateQueryString($changedUmpireArray) {
        $queryString = "UPDATE umpire SET ";
        $queryStringGamesPrior = " games_prior = CASE ";
        $queryStringGamesOther = " games_other_leagues = CASE ";
        $queryStringWhere = " WHERE id IN (";

        $arrayCount = count($changedUmpireArray);

        for ($i=0; $i<$arrayCount; $i++) {
            $queryStringGamesPrior .= "WHEN id=". $changedUmpireArray[$i]->getID() ." THEN ". $changedUmpireArray[$i]->getGamesPlayedPrior()." ";
            $queryStringGamesOther .= "WHEN id=". $changedUmpireArray[$i]->getID() ." THEN ". $changedUmpireArray[$i]->getGamesPlayedOtherLeagues() ." ";
            $queryStringWhere .= $changedUmpireArray[$i]->getID() .",";
        }

        //Remove comma from end of string
        if (substr($queryStringWhere, -1, 1) == ',') {
            //If the last character is a comma, remove it, and the following space
            $queryStringWhere = substr($queryStringWhere, 0, -1) . ")";
        }
        $queryStringGamesPrior .= "ELSE games_prior END, ";
        $queryStringGamesOther.= "ELSE games_other_leagues END ";

        //Combine all components for query
        $queryString .= $queryStringGamesPrior . $queryStringGamesOther . $queryStringWhere . ";";
        //$this->debug_library->debugOutput("queryString for umpire update", $queryString);

        return $queryString;
    }

    public function logUmpireGamesHistory($pChangedUmpireArray) {
        $queryString = "INSERT INTO umpire_games_history (
            id, first_name, last_name, old_games_prior, old_games_other_leagues, 
            new_games_prior, new_games_other_leagues, updated_by, updated_date) VALUES ";

        //$this->debug_library->debugOutput("build: pPostArray", $pPostArray);

        $session_data = $this->session->userdata('logged_in');
        $username = $session_data['username'];

        $arrayCount = count($pChangedUmpireArray);
        $currentLoopCount = 0;
        foreach ($pChangedUmpireArray as $currentUmpire) {
            //$this->debug_library->debugOutput("build: arrayValue", $arrayValue);
            $currentLoopCount++;

            //Check if values have changed, if so, update data
            if ($currentUmpire->haveUmpireGamesNumbersChanged()) {
                $queryString .= " (".
                    $currentUmpire->getID().", 
                    '". $currentUmpire->getFirstName() ."',
                    '". $currentUmpire->getLastName() ."',
                    ". $currentUmpire->getOldGamesPlayedPrior() .",
                    ". $currentUmpire->getOldGamesPlayedOtherLeagues().",
                    ". $currentUmpire->getGamesPlayedPrior().",
                    ". $currentUmpire->getGamesPlayedOtherLeagues().",
                    '". $username."',
                    NOW() 
                    )";
            }

            if ($currentLoopCount < $arrayCount) {
                //Add a comma if this is NOT the last iteration of the array.
                $queryString .= ", ";
            }

        }
        $queryString .= ";";
        //$this->debug_library->debugOutput("queryString for umpire history insert: ", $queryString);
        $query = $this->db->query($queryString);

    }




    public function updateDimUmpireTable() {
        $queryString = "TRUNCATE TABLE dw_dim_umpire;";
        $query = $this->db->query($queryString);

        $queryString = "INSERT INTO dw_dim_umpire (first_name, last_name, last_first_name, umpire_type, games_prior, games_other_leagues)
            SELECT DISTINCT
            u.first_name,
            u.last_name,
            CONCAT(u.last_name, ', ', u.first_name) AS last_first_name,
            ut.umpire_type_name AS umpire_type,
            u.games_prior,
            u.games_other_leagues
            FROM umpire u
            INNER JOIN umpire_name_type unt ON u.id = unt.umpire_id
            INNER JOIN umpire_type ut ON unt.umpire_type_id = ut.ID;";
        $query = $this->db->query($queryString);
    }

    public function updateMVReport8Table() {
        $queryString = "DELETE FROM dw_mv_report_08
            WHERE season_year IN ('Games Prior', 'Games Other Leagues');";
        $query = $this->db->query($queryString);

        $queryString = "INSERT INTO dw_mv_report_08 (season_year, full_name, match_count)
            SELECT
            'Games Prior',
            u.last_first_name,
            u.games_prior
            FROM dw_dim_umpire u
            UNION ALL
            SELECT
            'Games Other Leagues',
            u.last_first_name,
            u.games_other_leagues
            FROM dw_dim_umpire u;";
        $query = $this->db->query($queryString);
    }



}
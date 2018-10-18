<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Umpireadminmodel extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model("Umpire");
        $this->load->library('Debug_library');
    }
    
    public function getAllUmpiresAndValues() {
        $queryString = "SELECT u.id, u.first_name, u.last_name,
            games_prior, games_other_leagues
            FROM umpire u
            ORDER BY u.last_name, u.first_name;";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        $umpireArray = '';
        
        for($i=0; $i<count($queryResultArray); $i++) {
            $newUmpire = new Umpire();
            $newUmpire->setId($queryResultArray[$i]['id']);
            $newUmpire->setFirstName($queryResultArray[$i]['first_name']);
            $newUmpire->setLastName($queryResultArray[$i]['last_name']);
            $newUmpire->setGamesPlayedPrior($queryResultArray[$i]['games_prior']);
            $newUmpire->setGamesPlayedOtherLeagues($queryResultArray[$i]['games_other_leagues']);
            $umpireArray[] = $newUmpire;
        }
        return $umpireArray;
    }
    
    public function updateUmpireGameValues($pPostArray) {
        $existingUmpireValues = $this->getAllUmpiresAndValues();
        
        $queryString = "UPDATE umpire SET ";
        $queryStringGamesPrior = " games_prior = CASE ";
        $queryStringGamesOther = " games_other_leagues = CASE ";
        $queryStringWhere = " WHERE id IN (";
        
        $arrayCount = count($pPostArray);
        $currentLoopCount = 0;
        $umpireArray = "";
        $countChangedUmpires = 0;
        foreach ($pPostArray as $arrayKey=>$arrayValue) { 
            $currentLoopCount++;
            //Convert post array to Umpire object
            $currentUmpire = new Umpire();            
            $currentUmpire->setId($arrayKey);
            //Find the Umpire object from the database results that matches this ID
            $matchedExistingUmpire = $this->findUmpireFromArrayUsingID($existingUmpireValues, $currentUmpire->getID());
            
            $currentUmpire->setGamesPlayedPrior($arrayValue['geelong_prior']);
            $currentUmpire->setGamesPlayedOtherLeagues($arrayValue['other_leagues']);
            $currentUmpire->setFirstName($matchedExistingUmpire->getFirstName());
            $currentUmpire->setLastName($matchedExistingUmpire->getLastName());
            $currentUmpire->setOldGamesPlayedPrior($matchedExistingUmpire->getGamesPlayedPrior());
            $currentUmpire->setOldGamesPlayedOtherLeagues($matchedExistingUmpire->getGamesPlayedOtherLeagues());
            
            //Check if values have changed, if so, update data
            if ($this->haveUmpireGamesNumbersChanged($currentUmpire)) {
                $queryStringGamesPrior .= "WHEN id=". $currentUmpire->getID() ." THEN ". $currentUmpire->getGamesPlayedPrior()." ";
                $queryStringGamesOther .= "WHEN id=". $currentUmpire->getID() ." THEN ". $currentUmpire->getGamesPlayedOtherLeagues() ." ";
                $queryStringWhere .= $currentUmpire->getID() .",";
                $countChangedUmpires++;
                $umpireArray[] = $currentUmpire;
            }
            
        }
        
        if ($countChangedUmpires == 0) {
            return "OK done";
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
        $this->debug_library->debugOutput("queryString for umpire update", $queryString);
        
        $query = $this->db->query($queryString);
        
        //TODO: Add check to see if this query ran successfully.
        
        $this->logUmpireGamesHistory($umpireArray);
        
        //Update the dw_dim_umpire table
        $this->updateDimUmpire();
        
        //Also update the dw_mv_report_08 table
        $this->updateMVReport8Table();

        return "OK done";

    }
    
    private function updateDimUmpire() {
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
    
    private function updateMVReport8Table() {
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
    
    
    private function haveUmpireGamesNumbersChanged(Umpire $pUmpire) {
        return ($pUmpire->getGamesPlayedPrior() <> $pUmpire->getOldGamesPlayedPrior() ||
            $pUmpire->getGamesPlayedOtherLeagues() <> $pUmpire->getOldGamesPlayedOtherLeagues());
    }

    private function logUmpireGamesHistory($pUmpireArray) {
        $queryString = "INSERT INTO umpire_games_history (
            id, first_name, last_name, old_games_prior, old_games_other_leagues, 
            new_games_prior, new_games_other_leagues, updated_by, updated_date) VALUES ";
            
        //$this->debug_library->debugOutput("build: pPostArray", $pPostArray);
        
        $session_data = $this->session->userdata('logged_in');
        $username = $session_data['username'];
        
        $arrayCount = count($pUmpireArray);
        $currentLoopCount = 0;
        foreach ($pUmpireArray as $currentUmpire) {
            //$this->debug_library->debugOutput("build: arrayValue", $arrayValue);
            $currentLoopCount++;
            
            //Check if values have changed, if so, update data
            if ($this->haveUmpireGamesNumbersChanged($currentUmpire)) {
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
    
    private function findUmpireFromArrayUsingID($pUmpireArray, $pIDValue) {
        $matchedUmpire = new Umpire();
        
        for ($i=0; $i < count($pUmpireArray); $i++) {
            if ($pUmpireArray[$i]->getID() == $pIDValue) {
                $matchedUmpire = $pUmpireArray[$i];
            }
        }
        return $matchedUmpire;
    }
    
}

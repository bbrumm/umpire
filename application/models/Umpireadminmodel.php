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
        //$this->debug_library->debugOutput("build: pPostArray", $pPostArray);
        
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

        return "OK done";

    }
    
    
    
    private function haveUmpireGamesNumbersChanged(Umpire $pUmpire) {
        if ($pUmpire->getGamesPlayedPrior() <> $pUmpire->getOldGamesPlayedPrior() ||
            $pUmpire->getGamesPlayedOtherLeagues() <> $pUmpire->getOldGamesPlayedOtherLeagues()) {
            //Either of the values are different.
            return true;
        } else {
            //Both are the same. No change was made.
            return false;
        }
        
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
?>
<?php

class Missing_data_updater extends CI_Model {
    
    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->library('Array_library');
        $this->load->model('Match_import');
        $this->load->model('Database_store');
    }
    
    public function loadPossibleLeaguesForComp(IData_store $pDataStore) {
        return $pDataStore->loadPossibleLeaguesForComp();
    }
    
    public function loadPossibleClubsForTeam(IData_store $pDataStore) {
        return $pDataStore->loadPossibleClubsForTeam();
    }
    
    public function loadPossibleRegions(IData_store $pDataStore) {
        return $pDataStore->loadPossibleRegions();
    }
    
    public function loadPossibleAgeGroups(IData_store $pDataStore) {
        return $pDataStore->loadPossibleAgeGroups();
    }
    
    public function loadPossibleShortLeagueNames(IData_store $pDataStore) {
        return $pDataStore->loadPossibleShortLeagueNames();
    }
    
    public function loadPossibleDivisions(IData_store $pDataStore) {
        return $pDataStore->loadPossibleDivisions();
    }
    
    public function updateDataAndRunETLProcedure(IData_store $pDataStore, $pPostArray) {
        //$this->debug_library->debugOutput("POST from updateDataAndRunETLProcedure", $pPostArray);
        
        if (array_key_exists('competition', $pPostArray)) {
            $this->updateCompetitionTable($pDataStore, $pPostArray['competition']);
        }
        if (array_key_exists('rdTeam', $pPostArray)) {
            $this->updateTeamAndClubTables($pDataStore, $pPostArray);
        }
    
        $season = Season::createSeasonFromID($this->Match_import->findSeasonToUpdate($pDataStore));
        $importedFileID = $this->Match_import->findLatestImportedFile($pDataStore);
        
        $this->Run_etl_stored_proc->runETLProcedure($pDataStore, $season, $importedFileID);
    }
    
    private function updateCompetitionTable(array $selectedData) {
        //TOOD: Change this to look up the competition/league tables for the user-selected data
        /*This function needs to loop through each competition on the previous page.
         * For each competition, it needs to find a league that matches the user selected data.
         * If one match is found, use that league.
         * If many matches are found, then there needs to be some kind of process to pick one automatically.
         * 
         */
        
        $this->debug_library->debugOutput("updateCompetitionTable selectedData", $selectedData);
        
        
        /*
        foreach ($selectedData as $rowKey => $rowValue) {
            $queryString = "UPDATE competition_lookup
                SET league_id = ?
                WHERE id = ?;";
            
            $this->debug_library->debugOutput("updateCompetitionTable", $queryString); 
            
            $query = $this->db->query($queryString, array($rowValue, $rowKey));
        }
        */
    }
    
    public function updateSingleCompetition(IData_store $pDataStore, array $competitionData) {
        //Find league_id to use, based on parameters.
        //If there are more than one (sometimes there is two), it is because there are two competition names, so just pick the earliest one
        //$leagueIDToUse = $this->findSingleLeagueIDFromParameters($competitionData);
        $leagueIDToUse = $pDataStore->findSingleLeagueIDFromParameters($competitionData);
        $pDataStore->updateSingleCompetition($pDataStore, $competitionData);
        return $leagueIDToUse;
    }
    /*
    private function findSingleLeagueIDFromParameters(array $competitionData) {
        
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
        
        $query = $this->db->query($queryString);
        
        //echo "findSingleLeagueQuery:<BR/>$queryString<BR />";
        
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();
        
        $leagueIDToUse = $resultArray[0]['league_id'];
        
         //echo "leagueToUse(". $leagueIDToUse.")<BR />";
        
        if (is_null($leagueIDToUse)) {
             No matching leagues found. We need to insert some data first.
              We have the short_league_name, the league_name, and the region_id.
              We need the age_group_division_id

            $leagueIDToUse = $this->insertNewLeague($competitionData);
            echo "OK";
            
        } else {
            //This value is used for the validation in the JavaScript code in the upload_success page.
            echo "OK";
        }
        
        $query->free_result();
        return $leagueIDToUse;
        
    }
    */

    public function insertNewLeague(IData_store $pDataStore, array $competitionData) {
        //First, check if there is an age_group_division that exists, and insert one if it does not.
        $pDataStore->checkAndInsertAgeGroupDivision($competitionData);

        $insertedLeagueID = $pDataStore->insertNewLeague($competitionData);
        return $insertedLeagueID;
    }
    
    
    private function updateTeamAndClubTables(IData_store $pDataStore, array $pPostData) {
        $this->debug_library->debugOutput("updateTeamAndClubTables POST", $pPostData);
        
        foreach ($pPostData['rdTeam'] as $teamID => $radioSelection) {
            switch ($radioSelection) {
                case 'new':
                    $newClubName = $pPostData['txtTeam'][$teamID];
                    //Insert into CLUB table
                    $clubID = $this->insertNewClub($pDataStore, $newClubName);
                    //Update TEAM table
                    $this->updateTeamTable($pDataStore, $teamID, $clubID);
                    
                    break;
                case 'existing':
                    $club_id = $pPostData['cboTeam'][$teamID];
                    //Run UPDATE statement on TEAM table
                    $this->updateTeamTable($pDataStore, $teamID, $club_id);
                    
                    break;
            }
            
        }
    }
    
    private function insertNewClub(IData_store $pDataStore, $newClubName) {
        return $pDataStore->insertNewClub($newClubName);
    }
    
    private function updateTeamTable(IData_store $pDataStore, $pTeamID, $pClubID) {
        $pDataStore->updateTeamTable($pTeamID, $pClubID);
    }
}
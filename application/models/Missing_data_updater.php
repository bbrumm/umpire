<?php
/*
* @property Object Match_import
* @property Object Run_etl_stored_proc
*/
class Missing_data_updater extends CI_Model {
    
    const RD_SELECTION_NEW = "new";
    const RD_SELECTION_EXISTING = "existing";
    
    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->library('Array_library');
        $this->load->model('Match_import');
        $this->load->model('Database_store_matches');
        $this->load->model('Run_etl_stored_proc');
    }
    
    public function loadPossibleLeaguesForComp(IData_store_matches $pDataStore) {
        return $pDataStore->loadPossibleLeaguesForComp();
    }
    
    public function loadPossibleClubsForTeam(IData_store_matches $pDataStore) {
        return $pDataStore->loadPossibleClubsForTeam();
    }
    
    public function loadPossibleRegions(IData_store_matches $pDataStore) {
        return $pDataStore->loadPossibleRegions();
    }
    
    public function loadPossibleAgeGroups(IData_store_matches $pDataStore) {
        return $pDataStore->loadPossibleAgeGroups();
    }
    
    public function loadPossibleShortLeagueNames(IData_store_matches $pDataStore) {
        return $pDataStore->loadPossibleShortLeagueNames();
    }
    
    public function loadPossibleDivisions(IData_store_matches $pDataStore) {
        return $pDataStore->loadPossibleDivisions();
    }
    
    public function updateDataAndRunETLProcedure(IData_store_matches $pDataStore, $pPostArray) {
        if (array_key_exists('competition', $pPostArray)) {
            $this->updateCompetitionTable($pDataStore, $pPostArray['competition']);
        }
        if (array_key_exists('rdTeam', $pPostArray)) {
            $this->updateTeamAndClubTables($pDataStore, $pPostArray);
        }
    
        $season = Season::createSeasonFromID($this->Match_import->findSeasonToUpdate($pDataStore));
        $importedFileID = $this->Match_import->findLatestImportedFile($pDataStore);
        
        $this->Run_etl_stored_proc->runETLProcedure($pDataStore, $season, $importedFileID);
        return true;
    }

    
    private function updateCompetitionTable($pDataStore, array $selectedData) {
        //TODO: Change this to look up the competition/league tables for the user-selected data
        /*This function needs to loop through each competition on the previous page.
         * For each competition, it needs to find a league that matches the user selected data.
         * If one match is found, use that league.
         * If many matches are found, then there needs to be some kind of process to pick one automatically.
         * 
         */
        
        //$this->debug_library->debugOutput("updateCompetitionTable selectedData", $selectedData);
        
        
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
    
    public function updateSingleCompetition(IData_store_matches $pDataStore, array $competitionData) {
        //Find league_id to use, based on parameters.
        //If there are more than one (sometimes there is two), it is because there are two competition names, so just pick the earliest one
        $leagueIDToUse = $pDataStore->findSingleLeagueIDFromParameters($competitionData);
        $pDataStore->updateSingleCompetition($leagueIDToUse, $competitionData);
        return $leagueIDToUse;
    }

    public function insertNewLeague(IData_store_matches $pDataStore, array $competitionData) {
        //First, check if there is an age_group_division that exists, and insert one if it does not.
        $pDataStore->checkAndInsertAgeGroupDivision($competitionData);
        $insertedLeagueID = $pDataStore->insertNewLeague($competitionData);
        return $insertedLeagueID;
    }
    
    
    private function updateTeamAndClubTables(IData_store_matches $pDataStore, array $pPostData) {
        foreach ($pPostData['rdTeam'] as $teamID => $radioSelection) {
            if ($radioSelection == self:RD_SELECTION_NEW) {
                $this->updateForNewClub($teamID, $pPostData);
            } elseif ($radioSelection == self:RD_SELECTION_EXISTING) {
                $this->updateForExistingClub($teamID, $pPostData);
            }
        }
    }
    
    private function updateForNewClub($teamID, $pPostData) {
        $newClubName = $pPostData['txtTeam'][$teamID];
        $clubID = $this->insertNewClub($pDataStore, $newClubName);
        $this->updateTeamTable($pDataStore, $teamID, $clubID);
    }
    
    private function updateForExistingClub($teamID, $pPostData) {
        $club_id = $pPostData['cboTeam'][$teamID];
        $this->updateTeamTable($pDataStore, $teamID, $club_id);
    }
    
    private function insertNewClub(IData_store_matches $pDataStore, $newClubName) {
        return $pDataStore->insertNewClub($newClubName);
    }
    
    private function updateTeamTable(IData_store_matches $pDataStore, $pTeamID, $pClubID) {
        $pDataStore->updateTeamTable($pTeamID, $pClubID);
    }
}

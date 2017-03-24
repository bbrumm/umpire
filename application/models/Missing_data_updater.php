<?php

class Missing_data_updater extends CI_Model {
    
    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->library('Array_library');
        $this->load->model('Match_import');
    }
    
    public function loadPossibleLeaguesForComp() {
        $queryString = "SELECT 
            l.id,
            l.league_name,
            l.short_league_name,
            l.age_group_division_id,
            agd.division_id,
            d.division_name,
            ag.age_group,
            l.region_id,
            r.region_name
            FROM league l
            INNER JOIN age_group_division agd ON l.age_group_division_id = agd.ID
            INNER JOIN division d ON agd.division_id = d.id
            INNER JOIN age_group ag ON agd.age_group_id = ag.id
            INNER JOIN region r ON l.region_id = r.id;";
        
        $query = $this->db->query($queryString);
        
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();
        $query->free_result();
        return $resultArray;
    }
    
    public function loadPossibleClubsForTeam() {
        $queryString = "SELECT DISTINCT id, club_name
            FROM club
            ORDER BY club_name ASC;";
        
        $query = $this->db->query($queryString);
        
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();
        $query->free_result();
        return $resultArray;
    }
    
    public function updateDataAndRunETLProcedure() {
        $this->debug_library->debugOutput("POST from updateDataAndRunETLProcedure", $_POST);
        
        if (array_key_exists('competition', $_POST)) {
            $this->updateCompetitionTable($_POST['competition']);
        }
        $this->updateTeamAndClubTables($_POST);
    
    
        $season = new Season();
        $season->setSeasonID($this->Match_import->findSeasonToUpdate());
        $importedFileID = $this->Match_import->findLatestImportedFile();
        $this->Run_etl_stored_proc->runETLProcedure($season, $importedFileID);
    }
    
    private function updateCompetitionTable(array $selectedData) {
        foreach ($selectedData as $rowKey => $rowValue) {
            $queryString = "UPDATE competition_lookup
                SET league_id = ?
                WHERE id = ?;";
            
            $this->debug_library->debugOutput("updateCompetitionTable", $queryString); 
            
            $query = $this->db->query($queryString, array($rowValue, $rowKey));
        }
    }
    
    private function updateTeamAndClubTables(array $pPostData) {
        $this->debug_library->debugOutput("updateTeamAndClubTables POST", $pPostData);
        
        foreach ($pPostData['rdTeam'] as $teamID => $radioSelection) {
            switch ($radioSelection) {
                case 'new':
                    $newClubName = $pPostData['txtTeam'][$teamID];
                    //Insert into CLUB table
                    $clubID = $this->insertNewClub($newClubName);
                    //Update TEAM table
                    $this->updateTeamTable($teamID, $clubID);
                    
                    break;
                case 'existing':
                    $club_id = $pPostData['cboTeam'][$teamID];
                    //Run UPDATE statement on TEAM table
                    $this->updateTeamTable($teamID, $club_id);
                    
                    break;
            }
            
        }
    }
    
    private function insertNewClub($newClubName) {
        $queryString = "INSERT INTO club (club_name, abbreviation)
            VALUES (?, NULL);";
        
        $query = $this->db->query($queryString, array($newClubName));
        $this->debug_library->debugOutput("insertNewClub", $newClubName);
        return $this->db->insert_id();
    }
    
    private function updateTeamTable($pTeamID, $pClubID) {
        $queryString = "UPDATE team
            SET club_id = ?
            WHERE id = ?;";
        $this->debug_library->debugOutput("updateTeamTable", $pTeamID);
        $query = $this->db->query($queryString, array($pClubID, $pTeamID));
    }
    
    
    
    
    
}


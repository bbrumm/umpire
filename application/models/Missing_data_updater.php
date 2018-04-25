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
    
    public function loadPossibleRegions() {
        $queryString = "SELECT DISTINCT id, region_name
            FROM region
            ORDER BY id ASC;";
        
        $query = $this->db->query($queryString);
        
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();
        $query->free_result();
        return $resultArray;
    }
    
    public function loadPossibleAgeGroups() {
        $queryString = "SELECT id, age_group
            FROM age_group
            ORDER BY display_order ASC;";
        
        $query = $this->db->query($queryString);
        
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();
        $query->free_result();
        return $resultArray;
    }
    
    public function loadPossibleShortLeagueNames() {
        $queryString = "SELECT id, short_league_name
            FROM short_league_name
            ORDER BY display_order ASC;";
        
        $query = $this->db->query($queryString);
        
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();
        $query->free_result();
        return $resultArray;
    }
    
    public function loadPossibleDivisions() {
        $queryString = "SELECT id, division_name
            FROM division
            ORDER BY id ASC;";
        
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
    
    public function updateSingleCompetition(array $competitionData) {
        //Find league_id to use, based on parameters.
        //If there are more than one (sometimes there is two), it is because there are two competition names, so just pick the earliest one
        
        $leagueIDToUse = $this->findSingleLeagueIDFromParameters($competitionData);
        
        //echo "compData:";
        //print_r($competitionData);
        
        $queryString = "UPDATE competition_lookup
            SET league_id = ?
            WHERE id = ?;";
        //$this->debug_library->debugOutput("updateSingleCompetition", $leagueIDToUse);
        $query = $this->db->query($queryString, array($leagueIDToUse, $competitionData['competition_id']));
        
        //echo "<BR />updateQuery:<BR />". $queryString ."<BR />";
        
        return $leagueIDToUse;
        
    }
    
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
        
        /*echo "<pre>";
        print_r($leagueIDToUse);
        echo "</pre>";
        */
        
        //echo "leagueToUse(". $leagueIDToUse.")<BR />";
        
        if (is_null($leagueIDToUse)) {
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
    
    private function insertNewLeague(array $competitionData) {
        //echo "compdata:<BR />";
        //print_r($competitionData);
        
        //First, check if there is an age_group_division that exists, and insert one if it does not.
        $this->checkAndInsertAgeGroupDivision($competitionData);
        
        
        $queryString = "INSERT INTO league (league_name, sponsored_league_name, 
            short_league_name, age_group_division_id, region_id)
            SELECT
            'AFL Barwon' AS league_name,
            'AFL Barwon' AS league_name,
            ? AS short_league_name,
            agd.id AS agd_id,
            ? AS region_id
            FROM
            age_group_division agd
            INNER JOIN age_group ag ON agd.age_group_id = ag.id
            INNER JOIN division d ON agd.division_id = d.id
            WHERE ag.id = ?
            AND d.id = ?;";
        
        //$this->debug_library->debugOutput("insertNewLeague", $queryString);
        
        $query = $this->db->query($queryString, array(
            $competitionData['short_league_name'],
            $competitionData['region'],
            $competitionData['age_group'],
            $competitionData['division']
        ));
        
        $insertedLeagueID = $this->db->insert_id();
        //echo "Last Query:<BR/>". $this->db->last_query() . "<BR />";
        
        //echo "inserted ID (". $insertedLeagueID .")<BR/>";
        
        return $insertedLeagueID;
        
        
        
    }
    
    private function checkAndInsertAgeGroupDivision(array $competitionData) {
        $queryString = "SELECT COUNT(agd.id) AS count_agd
            FROM age_group_division agd
            WHERE agd.age_group_id = ?
            AND agd.division_id = ?";
        $query = $this->db->query($queryString, array(
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
            
            $query = $this->db->query($queryString, array(
                $competitionData['age_group'],
                $competitionData['division']
            ));
            
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
        $queryString = "INSERT INTO club (club_name)
            VALUES (?);";
        
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
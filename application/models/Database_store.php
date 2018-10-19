<?php
require_once 'IData_store.php';
class Database_store extends CI_Model implements IData_store {
    
    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');


    }
    
    public function loadAllReportParameters($pReportNumber) {
        $queryString = "SELECT
            t.report_name,
            t.report_title,
            t.value_field_id,
            t.no_value_display,
            t.first_column_format,
            t.colour_cells,
            p.orientation,
            p.paper_size,
            p.resolution
            FROM t_report t
            INNER JOIN t_pdf_settings p ON t.pdf_settings_id = p.pdf_settings_id
            WHERE t.report_id = ". $pReportNumber .";";
        
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        $countResultsFound = count($queryResultArray);
        
        if ($countResultsFound > 0) {
            $reportParameter = Report_parameter::createNewReportParameter(
                $queryResultArray[0]['report_title'],
                $queryResultArray[0]['value_field_id'],
                $queryResultArray[0]['no_value_display'],
                $queryResultArray[0]['first_column_format'],
                $queryResultArray[0]['colour_cells'],
                $queryResultArray[0]['orientation'],
                $queryResultArray[0]['paper_size'],
                $queryResultArray[0]['resolution']
                );
            
            return $reportParameter;
        } else {
            throw new Exception("No results found in the report table for this report number: " . $pReportNumber);
        }
    }
    
    public function loadAllGroupingStructures($pReportNumber) {
        $queryString = "SELECT rgs.report_grouping_structure_id, rgs.grouping_type, " .
            "fl.field_name, rgs.field_group_order, rgs.merge_field, rgs.group_heading, rgs.group_size_text " .
            "FROM report_table rt " .
            "INNER JOIN report_grouping_structure rgs ON rt.report_name = rgs.report_id " .
            "INNER JOIN field_list fl ON rgs.field_id = fl.field_id " .
            "WHERE rt.report_name = ". $pReportNumber ." " .
            "ORDER BY rgs.grouping_type, rgs.field_group_order;";
        
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result();
        $countResultsFound = count($queryResultArray);
        
        if ($countResultsFound > 0) {
            //Create report param and report grouping objects for this report
            //$this->debug_library->debugOutput("queryResultArray:",  $queryResultArray);

            foreach ($queryResultArray as $row) {

                $reportGroupingStructure = Report_grouping_structure::createNewReportGroupingStructure(
                    $row->report_grouping_structure_id,
                    $row->grouping_type,
                    $row->field_name,
                    $row->field_group_order,
                    $row->merge_field,
                    $row->group_heading,
                    $row->group_size_text
                    );
                $reportGroupingStructureArray[] = $reportGroupingStructure;
            }
            
            return $reportGroupingStructureArray;
        } else {
            throw new Exception("No results found in the report_grouping_structure table for this report number: " . $pReportNumber);
        }
    }
    
    private function getResultArrayFromQuery($queryString) {
        $query = $this->db->query($queryString);
        
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();
        $query->free_result();
        return $resultArray;
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

        return $this->getResultArrayFromQuery($queryString);
    }
    
    public function loadPossibleClubsForTeam() {
        $queryString = "SELECT DISTINCT id, club_name
            FROM club
            ORDER BY club_name ASC;";
        
        return $this->getResultArrayFromQuery($queryString);
    }
    
    public function loadPossibleRegions() {
        $queryString = "SELECT DISTINCT id, region_name
            FROM region
            ORDER BY id ASC;";
        
        return $this->getResultArrayFromQuery($queryString);
    }
    
    public function loadPossibleAgeGroups() { 
        $queryString = "SELECT id, age_group
            FROM age_group
            ORDER BY display_order ASC;";
        
        return $this->getResultArrayFromQuery($queryString);
    }
    
    public function loadPossibleShortLeagueNames() {
        $queryString = "SELECT id, short_league_name
            FROM short_league_name
            ORDER BY display_order ASC;";
        
        return $this->getResultArrayFromQuery($queryString);
    }
    
    public function loadPossibleDivisions() {
        $queryString = "SELECT id, division_name
            FROM division
            ORDER BY id ASC;";
        
        return $this->getResultArrayFromQuery($queryString);
    }
    
    
    public function updateSingleCompetition($pLeagueIDToUse, $pCompetitionData) {
        $queryString = "UPDATE competition_lookup
            SET league_id = ?
            WHERE id = ?;";
        $query = $this->db->query($queryString, array($pLeagueIDToUse, $pCompetitionData['competition_id']));
        return true;
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

        $query = $this->db->query($queryString);

        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();

        $leagueIDToUse = $resultArray[0]['league_id'];

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

        $query = $this->db->query($queryString, array(
            $competitionData['short_league_name'],
            $competitionData['region'],
            $competitionData['age_group'],
            $competitionData['division']
        ));

        $insertedLeagueID = $this->db->insert_id();
        return $insertedLeagueID;
    }

    public function checkAndInsertAgeGroupDivision($competitionData) {
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

    public function insertAgeGroupDivision($competitionData) {

    }

    public function updateTeamAndClubTables(IData_store $pDataStore, array $pPostData) {

    }
    
    public function insertNewClub($pClubName) {
        $queryString = "INSERT INTO club (club_name) VALUES (?);";
        $query = $this->db->query($queryString, array($newClubName));
        return $this->db->insert_id();
    }
    
    public function updateTeamTable($pTeamID, $pClubID) {
        $queryString = "UPDATE team
            SET club_id = ?
            WHERE id = ?;";
        //$this->debug_library->debugOutput("updateTeamTable", $pTeamID);
        $query = $this->db->query($queryString, array($pClubID, $pTeamID));
    }
    
    //Match_import
    public function findSeasonToUpdate() {
        $queryString = "SELECT MAX(season.ID) AS season_id " .
            "FROM season " .
            "INNER JOIN match_import ON season.season_year = match_import.season;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['season_id'];
    }
    
    public function findLatestImportedFile() {
        $queryString = "SELECT MAX(imported_file_id) AS imported_file_id
            FROM table_operations";
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['imported_file_id'];
    }
    
    public function runETLProcedure($pSeason, $pImportedFileID) {
        $queryString = "CALL `RunETLProcess`(". $pSeason->getSeasonID() .", ". $pImportedFileID .")";
        $query = $this->db->query($queryString);
    }
    
    
    public function findMatchingUserFromUsernameAndPassword($pUsername, $pPassword) {
      $this->db->select('id, user_name, user_password');
      $this->db->from('umpire_users');
      $this->db->where('user_name', $pUsername);
      $this->db->where('user_password', MD5($pPassword));
      $this->db->limit(1);

      $query = $this->db->get();

      if ($query->num_rows() == 1) {
        return $query->result();
      } else {
        return false;
      }

    }

    public function checkUserActive($pUsername) {
        $this->db->select('id');
        $this->db->from('umpire_users');
        $this->db->where('user_name', $pUsername);
        $this->db->where('active', '1');
        
        $query = $this->db->get();
        
        return ($query->num_rows() == 1);
    }

    //TODO refactor this so that permissions are set for the user, because they should be whenever createUserFromNameAndRole
    public function getUserFromUsername($pUsername) {
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, u.user_email, r.role_name
            FROM umpire_users u 
            INNER JOIN role r ON u.role_id = r.id
            WHERE u.user_name = '$pUsername' 
            LIMIT 1;";
        
        $query = $this->db->query($queryString);

        return $query->num_rows();
        
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $user = User::createUserFromNameAndRole($row->ID, $row->user_name,
                $row->first_name, $row->last_name, $row->role_name, 1, $row->user_email);
            return $user;
        } else {
            return false;
        }
    }


    public function findPermissionsForUser(User $pUser) {
        $queryString = "SELECT ps.id, ps.permission_id, p.permission_name, ps.selection_name 
            FROM permission_selection ps 
            INNER JOIN permission p ON ps.permission_id = p.id 
            WHERE (ps.id IN ( 
            	SELECT ups.permission_selection_id 
            	FROM user_permission_selection ups 
            	WHERE user_id = ". $this->getId() ." 
            ) OR ps.id IN ( 
            	SELECT rps.permission_selection_id 
            	FROM role_permission_selection rps  
            	INNER JOIN umpire_users u ON rps.role_id = u.role_id 
            	WHERE u.id = ". $this->getId() ."
                AND u.role_id != 4));";
        
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
        
        return count($resultArray);
    }



    public function checkUserExistsForReset(User $pUser) {
        $this->db->select('id');
        $this->db->where('user_name', $pUser->getUsername());
        $this->db->where('user_email', $pUser->getEmailAddress());
        $query = $this->db->get('umpire_users');
        
        return ($query->num_rows() > 0);
        
    }

    public function logPasswordResetRequest($pRequestData) {
        $data = array(
            'request_datetime' => $pRequestData['request_datetime'],
            'activation_id' => $pRequestData['activation_id'],
            'ip_address' => $pRequestData['client_ip'],
            'user_name' => $pRequestData['username_entered'],
            'email_address' => $pRequestData['email_address_entered'] 
        );
        
        $queryStatus = $this->db->insert('password_reset_request', $data);

        return ($queryStatus == 1);
    }



    public function storeActivationID($pActivationID, $pUser) {
        $this->db->where('user_name', $pUser->getUsername());
        $this->db->where('user_email', $pUser->getEmailAddress());
        $this->db->update('umpire_users', array('activation_id'=>$pActivationID));
    }


    public function createUserFromActivationID($pActivationID) {
        $this->db->select('user_name');
        $this->db->where('activation_id', $pActivationID);
        $query = $this->db->get('umpire_users');
        
        $resultArray = $query->result();
        $user = new User();
        $user->setUsername($resultArray[0]->user_name);

        return $user;
    }


    public function updatePassword(User $pUser) {
        $this->db->where('user_name', $pUser->getUsername());
        $this->db->update('umpire_users', array('user_password'=>$pUser->getPassword()));
    }

    public function findOldPassword(User $pUser) {
        $this->db->select('user_password');
        $this->db->where('user_name', $this->getUsername());
        $query = $this->db->get('umpire_users');

        $resultArray = $query->result();

        return $resultArray[0]->user_password;
    }

    public function logPasswordReset($pData) {
        $queryStatus = $this->db->insert('password_reset_log', $pData);
    }




    public function updateEmailAddress() {
            $this->db->where('user_name', $this->getUsername());
            $this->db->update('umpire_users', array('user_email'=>$this->getEmailAddress()));
        }
    
    public function loadSelectableReportOptions($pParameterID) {
        $queryString = "SELECT parameter_value_name, parameter_display_order " .
            "FROM report_selection_parameter_values " .
            "WHERE parameter_id = $pParameterID " .
            "ORDER BY parameter_display_order;";
        
        $query = $this->db->query($queryString);
        
        foreach ($query->result() as $row) {
            //TODO: change this to a custom constructor
            $selectableReportOption = new Selectable_report_option();
            $selectableReportOption->setOptionName($row->parameter_value_name);
            $selectableReportOption->setOptionValue($row->parameter_value_name);
            $selectableReportOption->setOptionDisplayOrder($row->parameter_display_order);
            $selectableReportOptionsForParameter[] = $selectableReportOption;
        }
        return $selectableReportOptionsForParameter;
    }

    public function findUserFromUsernameAndPassword($username, $password) {
        $this->db->where('user_name', $username);
        $this->db->where('user_password', $password);

        // Run the query
        $query = $this->db->get('umpire_users');

        $resultArray = $query->result();

        $user = new User();
        $user->setId($resultArray[0]['id']);
        $user->setUsername($resultArray[0]['username']);
        $user->setEmailAddress($resultArray[0]['email_address']);

    }




    
    
}

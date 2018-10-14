<?php
require_once 'IData_store.php';
class Database_store extends CI_Model implements IData_store {
    
    public function __construct() {
        
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
        $queryResultArray = $query->result_array();
        $countResultsFound = count($queryResultArray);
        
        if ($countResultsFound > 0) {
            //Create report param and report grouping objects for this report
            foreach ($queryResultArray->result() as $row) {
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
    
    
    public function updateSingleCompetition() {
        
    }
    
    public function insertNewClub($pClubName) {
        $queryString = "INSERT INTO club (club_name) VALUES (?);";
        $query = $this->db->query($queryString, array($newClubName));
        //$this->debug_library->debugOutput("insertNewClub", $newClubName);
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
    
    
    public function userLogin($pUsername, $pPassword) {
  $this->db->select('id, user_name, user_password');
  $this->db->from('umpire_users');
  $this->db->where('user_name', $pUsername); 
  this->db->where('user_password', MD5($pPassword));
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


public function getUserFromUsername($pUsername) {
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, u.user_email, r.role_name
            FROM umpire_users u 
            INNER JOIN role r ON u.role_id = r.id
            WHERE u.user_name = '$pUsername' 
            LIMIT 1;";
        
        $query = $this->db->query($queryString);
        
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $user = User::createFromNameAndEmailDatabaseRow($row)
/*

            $this->setId($row->id);
            $this->setUsername($row->user_name);
            $this->setFirstName($row->first_name);
            $this->setLastName($row->last_name);
            $this->setRoleName($row->role_name);
            $this->setEmailAddress($row->user_email);
            
*/
            //Get permissions for this user, assign each record to an object and store in the permissionArray
            $user->setPermissionArrayForUser();
            
            return $user;
        } else {
            return false;
        }
    }


public function setPermissionArrayForUser() {
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
        
        $countNumberOfPermissions = count($resultArray);
        
        if ($countNumberOfPermissions > 0) {
        
            for($i=0; $i<$countNumberOfPermissions; $i++) {
                $userRolePermission = new User_role_permission();
                //TODO: change this to a custom constructor
                $userRolePermission->setId($resultArray[$i]['id']);
                $userRolePermission->setPermissionId($resultArray[$i]['permission_id']);
                $userRolePermission->setPermissionName($resultArray[$i]['permission_name']);
                $userRolePermission->setSelectionName($resultArray[$i]['selection_name']);
                $permissionArray[] = $userRolePermission;
            }
            
            
            $this->setPermissionArray($permissionArray);
        }
           
    }

}

public function checkUserExistsForReset() {
        $this->db->select('id');
        $this->db->where('user_name', $this->getUsername());
        $this->db->where('user_email', $this->getEmailAddress());
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


public function storeActivationID($pActivationID) {
        $this->db->where('user_name', $this->getUsername());
        $this->db->where('user_email', $this->getEmailAddress());
        $this->db->update('umpire_users', array('activation_id'=>$pActivationID));
        
    }


public function createUserFromActivationID() {
        $this->db->select('user_name');
        $this->db->where('activation_id', $this->getActivationID());
        $query = $this->db->get('umpire_users');
        
        $resultArray = $query->result();
        
        if ($query->num_rows() > 0){
            $this->setUsername($resultArray[0]->user_name);
            return true;
        } else {
            return false;
        }  
        
    }


public function updatePassword() {
        $this->db->where('user_name', $this->getUsername());
        $this->db->update('umpire_users', array('user_password'=>$this->getPassword()));
    }

 public function logPasswordReset() {
        $this->db->select('user_password');
        $this->db->where('user_name', $this->getUsername());
        $query = $this->db->get('umpire_users');
        
        $resultArray = $query->result();
        
        $oldPassword = $resultArray[0]->user_password;
       
        $data = array(
            'user_name' => $this->getUsername(),
            'new_password' => $this->getPassword(),
            'old_password' => $oldPassword,
            'reset_datetime' => date('Y-m-d H:i:s', time())
        );
        
        $queryStatus = $this->db->insert('password_reset_log', $data);
    }


public function updateEmailAddress() {
        $this->db->where('user_name', $this->getUsername());
        $this->db->update('umpire_users', array('user_email'=>$this->getEmailAddress()));
    }
    
    
}

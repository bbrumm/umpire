<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Useradminmodel extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model("User");
        $this->load->library('Debug_library');
    }
    
    public function getAllUsers() {
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, r.role_name, s.sub_role_name 
            FROM umpire_users u 
            INNER JOIN role_sub_role rsr ON u.role_sub_role_id = rsr.id 
            INNER JOIN role r ON rsr.role_id = r.id 
            INNER JOIN sub_role s ON s.id = rsr.sub_role_id 
            WHERE u.user_name NOT IN ('bbrumm');";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        $userArray = '';

        for($i=0; $i<count($queryResultArray); $i++) {
            $newUser = new User();
            
            $newUser->setId($queryResultArray[$i]['id']);
            $newUser->setUsername($queryResultArray[$i]['user_name']);
            $newUser->setFirstName($queryResultArray[$i]['first_name']);
            $newUser->setLastName($queryResultArray[$i]['last_name']);
            $newUser->setRoleName($queryResultArray[$i]['role_name']);
            $newUser->setSubRoleName($queryResultArray[$i]['sub_role_name']);
            $newUser->setPermissionArrayForUser();
            $userArray[] = $newUser;
        }
        return $userArray;
    }
    
    private function getArrayFromQuery($queryString) {
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
    }
    
    public function getRoleArray() {
        $queryString = "SELECT id, role_name, display_order FROM role WHERE role_name != 'Owner' ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getSubRoleArray() {
        $queryString = "SELECT id, sub_role_name FROM sub_role WHERE sub_role_name != 'All';";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getReportArray() {
        $queryString = "SELECT report_table_id, report_title FROM report_table;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getRegionArray() {
        $queryString = "SELECT id, region_name FROM region;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getUmpireDisciplineArray() {
        $queryString = "SELECT id, umpire_type_name FROM umpire_type;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getAgeGroupArray() {
        $queryString = "SELECT id, age_group FROM age_group ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }
    
    public function getLeagueArray() {
        $queryString = "SELECT id, short_league_name FROM short_league_name ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }
    
    private function createUserFromAddNew($pUserName, $pFirstName, $pLastName, $pPassword) {
        $newUser = new User();
        
        $newUser->setUsername($pUserName);
        $newUser->setFirstName($pFirstName);
        $newUser->setLastName($pLastName);
        $newUser->setPassword($pPassword);
        
        return $newUser;
        
    }
    
    public function addNewUser($pSubmittedData) {
        $newUser = new User();
        $newUser = $this->createUserFromAddNew(
                $pSubmittedData['username'], $pSubmittedData['firstname'], $pSubmittedData['lastname'], MD5($pSubmittedData['password']));
        return $this->insertUserIntoDatabase($newUser);
    }
    
    private function insertUserIntoDatabase(User $pUser) {
        //TODO: Replace the default role with a user selection, once it is built into the UI.
        $queryString = "INSERT INTO umpire_users
            (first_name, last_name, user_name, user_email, user_password, role_sub_role_id)
            VALUES (?, ?, ?, 'None', ?, 6);";
        
        $query = $this->db->query($queryString, array(
            $pUser->getFirstName(), $pUser->getLastName(), $pUser->getUsername(), $pUser->getPassword()
        ));
        
        if ($this->db->affected_rows() == 1) {
            return true;
        } else {
            throw new exception("There was an error when inserting the user. Please contact support.");
        }
        
    }
    

}
?>
<?php
require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Useradminmodel extends MY_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model("User");
    }
    
    public function getAllUsers() {
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, r.role_name, s.sub_role_name " .
            "FROM umpire_users u " .
            "INNER JOIN role_sub_role rsr ON u.role_sub_role_id = rsr.id " .
            "INNER JOIN role r ON rsr.role_id = r.id " .
            "INNER JOIN sub_role s ON s.id = rsr.sub_role_id " .
            "WHERE u.user_name NOT IN ('bbrumm');";
        
        //Run query and store result in array
        //echo "Query:<BR />";
        //echo $queryString . "<BR />";
        
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        $userArray = '';
        
        //echo "numrows " . $query->num_rows() . "<BR />";
        
        for($i=0; $i<count($queryResultArray); $i++) {
            $newUser = new User();
            
            $newUser->setId($queryResultArray[$i]['id']);
            $newUser->setUsername($queryResultArray[$i]['user_name']);
            $newUser->setFirstName($queryResultArray[$i]['first_name']);
            $newUser->setLastName($queryResultArray[$i]['last_name']);
            $newUser->setRoleName($queryResultArray[$i]['role_name']);
            $newUser->setSubRoleName($queryResultArray[$i]['sub_role_name']);
            $userArray[] = $newUser;
        }
        
        /*echo "<pre>";
        print_r($newUser);
        echo "</pre>";
        */
         /*if ($this->debugMode) {
         echo "getAllUsers:<BR/>";
         echo "<pre>";
         print_r($userArray);
         echo "</pre>";
         }
         */
        return $userArray;
    }
    
    public function getRoleArray() {
        $queryString = "SELECT id, role_name, display_order FROM role WHERE role_name != 'Owner' ORDER BY display_order;";
        
        //Run query and store result in array
        //echo "Query:<BR />";
        //echo $queryString . "<BR />";
        
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        
        return $queryResultArray;
    }
    
    public function getSubRoleArray() {
        $queryString = "SELECT id, sub_role_name FROM sub_role WHERE sub_role_name != 'All';";
    
        //Run query and store result in array
        //echo "Query:<BR />";
        //echo $queryString . "<BR />";
    
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
    
        return $queryResultArray;
    }
    
    
    
    
    
    

}
?>
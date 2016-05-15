<?php

class User extends CI_Model
{

    private $id;
    private $username;
    private $password;
    private $firstName;
    private $lastName;
    private $roleName;
    private $subRoleName;
    private $permissionArray;
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('useradmin/User_role_permission');
    }
    

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }
    
    public function getRoleName() {
        return $this->roleName;
    }
    
    public function getSubRoleName() {
        return $this->subRoleName;
    }
    
    public function getPermissionArray() {
        return $this->permissionArray;
    }
    
    
    
    

    public function setId($pValue)
    {
        $this->id = $pValue;
    }

    public function setUsername($pValue)
    {
        $this->username = $pValue;
    }

    public function setPassword($pValue)
    {
        $this->password = $pValue;
    }

    public function setFirstName($pValue)
    {
        $this->firstName = $pValue;
    }

    public function setLastName($pValue)
    {
        $this->lastName = $pValue;
    }
    
    public function setRoleName($pValue) {
        $this->roleName = $pValue;
    }
    
    public function setSubRoleName($pValue) {
        $this->subRoleName = $pValue;
    }
    
    public function setPermissionArray($pValue) {
        $this->permissionArray = $pValue;
    }
    
    
    
    
    

    function login($username, $password)
    {
        $this->db->select('id, user_name, user_password');
        $this->db->from('umpire_users');
        $this->db->where('user_name', $username);
        $this->db->where('user_password', MD5($password));
        $this->db->limit(1);
    
        $query = $this->db->get();
    
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    public function getUserFromUsername($username) {
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, r.role_name, s.sub_role_name " .
            "FROM umpire_users u " .
            "INNER JOIN role_sub_role rsr ON u.role_sub_role_id = rsr.id " .
            "INNER JOIN role r ON rsr.role_id = r.id " .
            "INNER JOIN sub_role s ON s.id = rsr.sub_role_id " .
            "WHERE u.user_name = '$username' " .
            "LIMIT 1;";
        
        $query = $this->db->query($queryString);
        
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $this->setId($row->id);
            $this->setUsername($row->user_name);
            $this->setFirstName($row->first_name);
            $this->setLastName($row->last_name);
            $this->setRoleName($row->role_name);
            $this->setSubRoleName($row->sub_role_name);
            
            //Get permissions for this user, assign each record to an object and store in the permissionArray
            $this->setPermissionArrayForUser();
            
            return true;
            
        } else {
            return false;
        }
        
        
    }
    
    public function setPermissionArrayForUser() {
        $queryString = "SELECT ps.id, ps.permission_id, p.permission_name, ps.selection_name " .
            "FROM permission_selection ps " .
            "INNER JOIN permission p ON ps.permission_id = p.id " .
            "WHERE (ps.id IN ( " .
            	"SELECT ups.permission_selection_id " .
            	"FROM user_permission_selection ups " .
            	"WHERE user_id = ". $this->getId() ." " .
            ") OR ps.id IN ( " .
            	"SELECT rps.permission_selection_id " .
            	"FROM role_permission_selection rps " .
            	"INNER JOIN role_sub_role rsr ON rps.role_sub_role_id = rsr.id " .
            	"INNER JOIN umpire_users u ON rsr.id = u.role_sub_role_id " .
            	"WHERE u.id = ". $this->getId() ."));";
        
        $query = $this->db->query($queryString);
        $resultArray = $query->result_array();
        
        
        for($i=0; $i<count($resultArray); $i++) {
            $userRolePermission = new User_role_permission();
            $userRolePermission->setId($resultArray[$i]['id']);
            $userRolePermission->setPermissionId($resultArray[$i]['permission_id']);
            $userRolePermission->setPermissionName($resultArray[$i]['permission_name']);
            $userRolePermission->setSelectionName($resultArray[$i]['selection_name']);
            $permissionArray[] = $userRolePermission;
        }
        
        $this->setPermissionArray($permissionArray);
           
    }
    
    private function findPermissionInArray($permissionName, $selectionName) {
        $permissionArray = $this->getPermissionArray();
        $permissionFound = false;
        for($i=0; $i<count($permissionArray); $i++) {
            $userRolePermission = new User_role_permission();
            $userRolePermission = $permissionArray[$i];
            if ($userRolePermission->getPermissionName() == $permissionName && 
                $userRolePermission->getSelectionName() == $selectionName) {
                    //Permission found.
                    $permissionFound = true;
                    break;
                }
        }
        
        return $permissionFound; 
        
    }
    
    public function userHasImportFilePermission() {
        return $this->findPermissionInArray('IMPORT_FILES', 'All');
    }
    
    public function userCanSeeUserAdminPage() {
        return $this->findPermissionInArray('VIEW_USER_ADMIN', 'All');
    }
    
    public function userCanSeeDataTestPage() {
        return $this->findPermissionInArray('VIEW_DATA_TEST', 'All');
    }
    
    public function userCanCreatePDF() {
        return $this->findPermissionInArray('CREATE_PDF', 'All');
    }
    
    public function userHasSpecificPermission($permissionName, $selectionName) {
       return $this->findPermissionInArray($permissionName, $selectionName);
    
    }
    
}
?>
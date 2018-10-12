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
    private $passwordResetURL;
    private $emailAddress;
    private $activationID;
    private $active;
    
    function __construct() {
        parent::__construct();
        $this->load->model('useradmin/User_role_permission');
    }
    

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
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
    
    public function getPasswordResetURL() {
        return $this->passwordResetURL;
    }
    
    public function getEmailAddress() {
        return $this->emailAddress;
    }
    
    public function getActivationID() {
        return $this->activationID;
    }
    
    public function getActive() {
        return $this->active;
    }
    
    
    
    //SET Functions
    public function setId($pValue) {
        $this->id = $pValue;
    }

    public function setUsername($pValue) {
        if(strlen($pValue) <= 255) {
            $this->username = $pValue;
        } else {
            throw new InvalidArgumentException('Username is too long.'); 
        }
    }

    public function setPassword($pValue) {
        $this->password = $pValue;
    }

    public function setFirstName($pValue) {
        $this->firstName = $pValue;
    }

    public function setLastName($pValue) {
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
    
    public function setPasswordResetURL($pValue) {
        $this->passwordResetURL = $pValue;
    }
    
    public function setEmailAddress($pValue) {
        if(strlen($pValue) <= 255) {
            $this->emailAddress = $pValue;
        } else {
            throw new InvalidArgumentException('Email address is too long.');
        }
    }
    
    public function setActivationID($pValue) {
        if(strlen($pValue) <= 200) {
            $this->activationID = $pValue;
        } else {
            throw new InvalidArgumentException('Activation ID is too long.');
        }
    }
    
    public function setActive($pValue) {
        if ($pValue == 1 || $pValue == 0) {
            $this->active = $pValue;
        } else {
            throw new InvalidArgumentException("User active value must be either 1 or 0.");
        }
    }
    
    public function isActive() {
        if ($this->getActive() == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    function login($username, $password) {
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
    
    public function checkUserActive($pUsername) {
        $this->db->select('id');
        $this->db->from('umpire_users');
        $this->db->where('user_name', $pUsername);
        $this->db->where('active', '1');
        
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getUserFromUsername($username) {
        $queryString = "SELECT u.id, u.user_name, u.first_name, u.last_name, u.user_email, r.role_name
            FROM umpire_users u 
            INNER JOIN role r ON u.role_id = r.id
            WHERE u.user_name = '$username' 
            LIMIT 1;";
        
        $query = $this->db->query($queryString);
        
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $this->setId($row->id);
            $this->setUsername($row->user_name);
            $this->setFirstName($row->first_name);
            $this->setLastName($row->last_name);
            $this->setRoleName($row->role_name);
            $this->setEmailAddress($row->user_email);
            
            //Get permissions for this user, assign each record to an object and store in the permissionArray
            $this->setPermissionArrayForUser();
            
            return true;
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
                $userRolePermission->setId($resultArray[$i]['id']);
                $userRolePermission->setPermissionId($resultArray[$i]['permission_id']);
                $userRolePermission->setPermissionName($resultArray[$i]['permission_name']);
                $userRolePermission->setSelectionName($resultArray[$i]['selection_name']);
                $permissionArray[] = $userRolePermission;
            }
            
            
            $this->setPermissionArray($permissionArray);
        }
           
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
    
    public function checkUserExistsForReset() {
        $this->db->select('id');
        $this->db->where('user_name', $this->getUsername());
        $this->db->where('user_email', $this->getEmailAddress());
        $query = $this->db->get('umpire_users');
        
        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
        
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
            
        if ($queryStatus == 1) {
            return true;
        } else {
            return false;
        }
        
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
        $this->logPasswordReset();
        
        $this->db->where('user_name', $this->getUsername());
        $this->db->update('umpire_users', array('user_password'=>$this->getPassword()));
    }
    
    private function logPasswordReset() {
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
    
    public function validatePassword($pNewPassword, $pConfirmPassword) {
        if ($pNewPassword == $pConfirmPassword && strlen($pNewPassword) >= 6) {
            return true;
        } else {
            return false;
        }
    }
    
    public function updateEmailAddress() {
        $this->db->where('user_name', $this->getUsername());
        $this->db->update('umpire_users', array('user_email'=>$this->getEmailAddress()));
    }
    
    
}
?>

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
    
    public static function createUserFromNameAndRole($pID, $pUsername,
        $pFirstName, $pLastName, $pRoleName, $pActive, $pEmailAddress) {
        
        $obj = new User();
        $obj->setId($pID);
        $obj->setUsername($pUsername);
        $obj->setFirstName($pFirstName);
        $obj->setLastName($pLastName);
        $obj->setRoleName($pRoleName);
        $obj->setActive($pActive);
        $obj->setEmailAddress($pEmailAddress);
        //$obj->setPermissionArrayForUser();
        
        return $obj;
    }

    /*
    public static function createUserFromUsername($pUsername) {
        $obj = new User();
        $obj->setUsername($pUsername);
        return $obj;
    }
    */

    public static function createUserFromNameAndPW($pUsername,
        $pFirstName, $pLastName, $pPassword) {
        $obj = new User();

        $obj->setUsername($pUsername);
        $obj->setFirstName($pFirstName);
        $obj->setLastName($pLastName);
        $obj->setPassword($pPassword);

        return $obj;
    }
    
    public static function createUserFromUserSubmittedData($pSubmittedData) {
        $obj = new User();
        $obj->setUsername($pSubmittedData['username']);
        $obj->setFirstName($pSubmittedData['firstname']);
        $obj->setLastName($pSubmittedData['lastname']);
        $obj->setPassword(MD5($pSubmittedData['password']));
        return $obj;
    }
    
    public static function createUserFromNameAndEmail($pUsername,
        $pFirstName, $pLastName, $pEmailAddress) {
        $obj = new User();
        $obj->setUsername($pUsername);
        $obj->setFirstName($pFirstName);
        $obj->setLastName($pLastName);
        $obj->setEmailAddress($pEmailAddress);
        return $obj;
    }

    public static function createUserFromActivationID($pUsername, $pActivationID) {
        $obj = new User();
        $obj->setUsername($pUsername);
        $obj->setActivationID($pActivationID);
        return $obj;
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
        if(strlen($pValue) > 0) {
            $this->password = $pValue;
        } else {
            throw new InvalidArgumentException('Password cannot be empty.');
        }
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
        return ($this->active == 1);
    }



}


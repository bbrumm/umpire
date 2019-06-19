<?php
class User_role_permission extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    private $id;
    private $permissionId;
    private $permissionName;
    private $selectionName;

    public static function createFromRow($pRow) {
        $instance = new User_role_permission();
        $instance->setId($pRow['id']);
        $instance->setPermissionId($pRow['permission_id']);
        $instance->setPermissionName($pRow['permission_name']);
        $instance->setSelectionName($pRow['selection_name']);
        return $instance;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getPermissionId() {
        return $this->permissionId;
    }

    public function getPermissionName() {
        return $this->permissionName;
    }

    public function getSelectionName() {
        return $this->selectionName;
    }

    public function setPermissionId($pValue) {
        if ($pValue != null) {
            $this->permissionId = $pValue;
        } else {
            throw new InvalidArgumentException("PermissionID used for creating user_role_permission is null.");
        }
    }

    public function setPermissionName($pValue) {
        if ($pValue != null) {
            $this->permissionName = $pValue;
        } else {
            throw new InvalidArgumentException("PermissionName used for creating user_role_permission is null.");
        }
    }

    public function setSelectionName($pValue) {
        if ($pValue != null) {
            $this->selectionName = $pValue;
        } else {
            throw new InvalidArgumentException("SelectionName used for creating user_role_permission is null.");
        }
    }
    
    public function setId($pValue) {
        if ($pValue != null) {
            $this->id = $pValue;
        } else {
            throw new InvalidArgumentException("ID used for creating user_role_permission is null.");
        }
    }  
}

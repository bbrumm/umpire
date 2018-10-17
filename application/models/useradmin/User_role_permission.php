<?php

class User_role_permission extends CI_Model
{
    
    function __construct()
    {
        parent::__construct();
    }

    private $id;
    private $permissionId;
    private $permissionName;
    private $selectionName;

    public static function createFromRow($pRow) {
        $instance = new User_role_permission();
        if($pRow['id'] != null &&
            $pRow['permission_id'] != null &&
            $pRow['permission_name'] != null &&
            $pRow['selection_name'] != null) {

            $instance->setId($pRow['id']);
            $instance->setPermissionId($pRow['permission_id']);
            $instance->setPermissionName($pRow['permission_name']);
            $instance->setSelectionName($pRow['selection_name']);

            return $instance;
        } else {
            throw new InvalidArgumentException("Data used for creating user_role_permission is null.");
        }

    }

    public function getId() {
        return $this->id;
    }
    
    public function getPermissionId()
    {
        return $this->permissionId;
    }

    public function getPermissionName()
    {
        return $this->permissionName;
    }

    public function getSelectionName()
    {
        return $this->selectionName;
    }

    public function setPermissionId($pValue)
    {
        $this->permissionId = $pValue;
    }

    public function setPermissionName($pValue)
    {
        $this->permissionName = $pValue;
    }

    public function setSelectionName($pValue)
    {
        $this->selectionName = $pValue;
    }
    
    public function setId($pValue) {
        $this->id = $pValue;
    }
        
}
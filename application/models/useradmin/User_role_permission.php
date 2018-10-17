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
        $instance->setId($pRow['id']);
        $instance->setPermissionId($pRow['permission_id']);
        $instance->setPermissionName($pRow['permission_name']);
        $instance->setSelectionName($pRow['selection_name']);

        return $instance;

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
<?php

class UserRolePermission extends CI_Model
{
    
    function __construct()
    {
        parent::__construct();
    }

    private $id;
    private $permissionId;
    private $permissionName;
    private $selectionName;

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
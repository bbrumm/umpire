<?php
class Umpire_match_record extends CI_Model
{
    function __construct() {
        parent::__construct();
    }

    private $umpireName;
    private $umpireType;
    private $shortLeagueName;
    private $ageGroup;
    private $clubName;


    //Get Functions
    public function getUmpireName() {
        return $this->umpireName;
    }

    public function getUmpireType() {
        return $this->umpireType;
    }

    public function getShortLeagueName() {
        return $this->shortLeagueName;
    }

    public function getAgeGroup() {
        return $this->ageGroup;
    }

    public function getClubName() {
        return $this->clubName;
    }


    //Set Functions
    public function setUmpireName($pValue) {
        $this->umpireName = $pValue;
    }
    
    public function setUmpireType($pValue) {
        $this->umpireType = $pValue;
    }
    
    public function setShortLeagueName($pValue) {
        $this->shortLeagueName = $pValue;
    }
    
    public function setAgeGroup($pValue) {
        $this->ageGroup = $pValue;
    }
    
    public function setClubName($pValue) {
        $this->clubName = $pValue;
    }
    
}
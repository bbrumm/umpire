<?php

class Umpire extends CI_Model
{
    
    private $id;
    private $firstName;
    private $lastName;
    private $gamesPlayedPrior;
    private $gamesPlayedOtherLeagues;
    private $oldGamesPlayedPrior;
    private $oldGamesPlayedOtherLeagues;
    
    function __construct() {
        parent::__construct();
    }
    
    
    public function getId() {
        return $this->id;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }
    
    public function getLastName() {
        return $this->lastName;
    }
    
    public function getGamesPlayedPrior() {
        return $this->gamesPlayedPrior;
    }
    
    public function getGamesPlayedOtherLeagues() {
        return $this->gamesPlayedOtherLeagues;
    }
    
    public function getOldGamesPlayedPrior() {
        return $this->oldGamesPlayedPrior;
    }
    
    public function getOldGamesPlayedOtherLeagues() {
        return $this->oldGamesPlayedOtherLeagues;
    }
    
    
    //SET Functions
    public function setId($pValue) {
        $this->id = $pValue;
    }
    
    public function setFirstName($pValue) {
        $this->firstName = $pValue;
    }
    
    public function setLastName($pValue) {
        $this->lastName = $pValue;
    }
    
    public function setGamesPlayedPrior($pValue) {
        if ($this->validateGamesPlayedValue($pValue) == true) {
            $this->gamesPlayedPrior= $pValue;
        }
    }
    
    public function setGamesPlayedOtherLeagues($pValue) {
        if ($this->validateGamesPlayedValue($pValue) == true) {
            $this->gamesPlayedOtherLeagues= $pValue;
        }
    }
    
    public function setOldGamesPlayedPrior($pValue) {
        if ($this->validateGamesPlayedValue($pValue) == true) {
            $this->oldGamesPlayedPrior= $pValue;
        }
    }
    
    public function setOldGamesPlayedOtherLeagues($pValue) {
        if ($this->validateGamesPlayedValue($pValue) == true) {
            $this->oldGamesPlayedOtherLeagues= $pValue;
        }
    }
    
    private function validateGamesPlayedValue($pInputValue) {
        if (is_numeric($pInputValue)) {
            return true;
        } else {
            throw new Exception('Games value must be numeric.');
        }
        
    }
    
}
?>
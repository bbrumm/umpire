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

    public static function createUmpireAllData($pID, $pFirstName, $pLastName, $pGamesPlayedPrior,
           $pGamesPlayedOtherLeagues, $pOldGamesPlayedPrior, $pOldGamesPlayedOtherLeagues) {
        $obj = new Umpire();
        $obj->setID($pID);
        $obj->setFirstName($pFirstName);
        $obj->setLastName($pLastName);
        $obj->setGamesPlayedPrior($pGamesPlayedPrior);
        $obj->setGamesPlayedOtherLeagues($pGamesPlayedOtherLeagues);
        $obj->setOldGamesPlayedPrior($pOldGamesPlayedPrior);
        $obj->setOldGamesPlayedOtherLeagues($pOldGamesPlayedOtherLeagues);

        return $obj;
    }

    public static function createUmpireFromQueryResult($resultArrayRow) {
        $obj = new Umpire();
        $obj->setID($resultArrayRow['id']);
        $obj->setFirstName($resultArrayRow['first_name']);
        $obj->setLastName($resultArrayRow['last_name']);
        $obj->setGamesPlayedPrior($resultArrayRow['games_prior']);
        $obj->setGamesPlayedOtherLeagues($resultArrayRow['games_other_leagues']);

        return $obj;
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
    private function setId($pValue) {
        $this->id = $pValue;
    }

    private function setFirstName($pValue) {
        $this->firstName = $pValue;
    }

    private function setLastName($pValue) {
        $this->lastName = $pValue;
    }

    private function setGamesPlayedPrior($pValue) {
        if ($this->validateGamesPlayedValue($pValue) == true) {
            $this->gamesPlayedPrior= $pValue;
        }
    }

    private function setGamesPlayedOtherLeagues($pValue) {
        if ($this->validateGamesPlayedValue($pValue) == true) {
            $this->gamesPlayedOtherLeagues= $pValue;
        }
    }

    private function setOldGamesPlayedPrior($pValue) {
        if ($this->validateGamesPlayedValue($pValue) == true) {
            $this->oldGamesPlayedPrior= $pValue;
        }
    }

    private function setOldGamesPlayedOtherLeagues($pValue) {
        if ($this->validateGamesPlayedValue($pValue) == true) {
            $this->oldGamesPlayedOtherLeagues= $pValue;
        }
    }

    private function validateGamesPlayedValue($pInputValue) {
        if (is_numeric($pInputValue)) {
            return true;
        } else {
            throw new InvalidArgumentException('Games value must be numeric.');
        }
        
    }
    
}


<?php
/*
Season
This class represents a season object, which includes a year and an ID.
*/
class Season
{
    function __construct() {
        //Nothing
    }
    
    private $seasonID;
    private $seasonYear;
    
    public static function createSeasonFromID($pSeasonID) {
        $instance = new Season();
        $instance->setSeasonID($pSeasonID);
        return $instance;
    }

    //Get Functions
    public function getSeasonID() {
        return $this->seasonID;
    }
    
    public function getSeasonYear() {
        return $this->seasonYear;
    }
    
    //Set Functions
    public function setSeasonID($pValue) {
        $this->seasonID = $pValue;
    }
    
    public function setSeasonYear($pValue) {
        if(is_numeric($pValue)) {
            if($pValue >= 2000 && $pValue <= 2100) {
                $this->seasonYear= $pValue;
            } else {
                throw new InvalidArgumentException('Season Year must be between 2000 and 2100. Value provided was ' . $pValue);
            }
        } else {
            throw new InvalidArgumentException('Season Year must be numeric. Value provided was ' . $pValue);
        }
    }
    
    
    
}

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
            $this->seasonYear= $pValue;
        } else {
            throw new Exception('Season Year must be numeric.');
        }
    }
    
}
?>
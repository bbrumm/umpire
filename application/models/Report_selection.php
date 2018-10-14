<?php
/*
 * Report_selection class
 * This represents a report that can be selected on the Report Selection page.
 * It is used to populate the drop-down of reports on the home page.
 */

class Report_selection extends CI_Model {
    
    private $reportID;
    private $reportName;
    private $regionEnabled;
    private $leagueEnabled;
    private $ageGroupEnabled;
    private $umpireTypeEnabled;
    
    public function __construct() {
        
    }
    
    public static function createNewReportSelection($pReportID, $pReportName,
        $pRegionEnabled, $pLeagueEnabled, $pAgeGroupEnabled, $pUmpireTypeEnabled) {
        
        $obj = new Report_selection();
        $obj->setReportID($pReportID);
        $obj->setReportName($pReportName);
        $obj->setRegionEnabled($pRegionEnabled);
        $obj->setLeagueEnabled($pLeagueEnabled);
        $obj->setAgeGroupEnabled($pAgeGroupEnabled);
        $obj->setUmpireTypeEnabled($pUmpireTypeEnabled);
        
        return $obj;
    }
    
    public static function createNewReportSelectionIDName($pReportID, $pReportName) {
        $obj = new Report_selection();
        $obj->setReportID($pReportID);
        $obj->setReportName($pReportName);
        
        return $obj;
    }

    
    public function getReportID() {
        return $this->reportID;
    }
    
    public function getReportName() {
        return $this->reportName;
    }
    
    public function getRegionEnabled() {
        return $this->regionEnabled;
    }
    
    public function getLeagueEnabled() {
        return $this->leagueEnabled;
    }
    
    public function getAgeGroupEnabled() {
        return $this->ageGroupEnabled;
    }
    
    public function getUmpireTypeEnabled() {
        return $this->umpireTypeEnabled;
    }
    
    private function setReportID($pValue) {
        if(is_numeric($pValue)) {
            $this->reportID = $pValue;
        } else {
            throw new InvalidArgumentException('ReportID must be numeric.');
        }
    }
    
    private function setReportName($pValue) {
        $this->reportName = $pValue;
    }
    
    private function setRegionEnabled($pValue) {
        if(($pValue == 0 || $pValue == 1) && is_numeric($pValue)) {
            $this->regionEnabled = $pValue;
        } else {
            throw new InvalidArgumentException('RegionEnabled must be 1 or 0.');
        }
    }
    
    private function setLeagueEnabled($pValue) {
        if(($pValue == 0 || $pValue == 1) && is_numeric($pValue)) {
            $this->leagueEnabled = $pValue;
        } else {
            throw new InvalidArgumentException('LeagueEnabled must be 1 or 0.');
        }
    }
    
    private function setAgeGroupEnabled($pValue) {
        if(($pValue == 0 || $pValue == 1) && is_numeric($pValue)) {
            $this->ageGroupEnabled = $pValue;
        } else {
            throw new InvalidArgumentException('AgeGroupEnabled must be 1 or 0.');
        }
    }
    
    private function setUmpireTypeEnabled($pValue) {
        if(($pValue == 0 || $pValue == 1) && is_numeric($pValue)) {
            $this->umpireTypeEnabled= $pValue;
        } else {
            throw new InvalidArgumentException('UmpireTypeEnabled must be 1 or 0.');
        }
    }
    
    
}
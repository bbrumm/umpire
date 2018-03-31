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
    
    public function setReportID($pValue) {
        if(is_numeric($pValue)) {
            $this->reportID = $pValue;
        } else {
            throw new Exception('ReportID must be numeric.');
        }
    }
    
    public function setReportName($pValue) {
        $this->reportName = $pValue;
    }
    
    public function setRegionEnabled($pValue) {
        if($pValue == 0 || $pValue == 1) {
            $this->regionEnabled = $pValue;
        } else {
            throw new Exception('RegionEnabled must be 1 or 0.');
        }
    }
    
    public function setLeagueEnabled($pValue) {
        if($pValue == 0 || $pValue == 1) {
            $this->leagueEnabled = $pValue;
        } else {
            throw new Exception('LeagueEnabled must be 1 or 0.');
        }
    }
    
    public function setAgeGroupEnabled($pValue) {
        if($pValue == 0 || $pValue == 1) {
            $this->ageGroupEnabled = $pValue;
        } else {
            throw new Exception('AgeGroupEnabled must be 1 or 0.');
        }
    }
    
    public function setUmpireTypeEnabled($pValue) {
        if($pValue == 0 || $pValue == 1) {
            $this->umpireTypeEnabled= $pValue;
        } else {
            throw new Exception('UmpireTypeEnabled must be 1 or 0.');
        }
    }
    
    
}
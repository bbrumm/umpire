<?php
/*
 * Requested_report_model class
 * This represents a set of values that are used to generate a report.
 * It contains the data that the user selects on the Home page,
 * and is used to find the right report to display.
 */

class Requested_report_model extends CI_Model {
    
    private $reportNumber;
    private $season;
    private $region;
    private $ageGroup;
    private $umpireType;
    private $league;
    private $pdfMode;
    
    public function __construct() {
           
    }
    
    public static function createRequestedReportFromValues(
        $pReportNumber, $pSeason, $pRegion, $pAgeGroup, $pUmpireType, $pLeague, $pPDFMode) {
        
        $instance = new Requested_report_model();
        $instance->setReportNumber($pReportNumber);
        $instance->setSeason($pSeason);
        $instance->setRegion($pRegion);
        $instance->setAgeGroup($pAgeGroup);
        $instance->setUmpireType($pUmpireType);
        $instance->setLeague($pLeague);
        $instance->setPDFMode($pPDFMode);
        
        return $instance;
    }
    
    public function getReportNumber() {
        return $this->reportNumber;
    }
    
    public function getSeason() {
        return $this->season;
    }
    
    public function getRegion() {
        return $this->region;
    }
    
    public function getAgeGroup() {
        return $this->ageGroup;
    }
    
    public function getUmpireType() {
        return $this->umpireType;
    }
    
    public function getLeague() {
        return $this->league;
    }
    
    public function getPDFMode() {
        return $this->pdfMode;
    }
    
    
    private function setReportNumber($pValue) {
        if(is_numeric($pValue)) {
            $this->reportNumber = $pValue;
        } else {
            throw new InvalidArgumentException('ReportNumber must be numeric.');
        }
    }
    
    public function setSeason($pValue) {
        if(is_numeric($pValue)) {
            $this->season = $pValue;
        } else {
            throw new InvalidArgumentException('Season must be numeric.');
        }
    }
    
    private function setRegion($pValue) {
        $this->region = $pValue;
    }
    
    private function setAgeGroup($pValue) {
        $this->ageGroup = $pValue;
    }
    
    private function setUmpireType($pValue) {
        $this->umpireType = $pValue;
    }
    
    private function setLeague($pValue) {
        $this->league = $pValue;
    }
    
    public function setPDFMode($pValue) {
        if(is_bool($pValue)) {
            $this->pdfMode = $pValue;
        } else {
            throw new InvalidArgumentException('PDFMode must be a boolean.');
        }
    }
    

}
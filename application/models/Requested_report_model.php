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
    
    public static function withValues($pValues) {
        $instance = new self();
        $instance->fill($pValues);
        return $instance;
    }
    
    protected function fill(array $pValues) {
        // fill all properties from array
        $this->setAgeGroup($pValues['ageGroup']);
        $this->setUmpireType($pValues['umpireType']);
        $this->setSeason($pValues['season']);
        $this->setLeague($pValues['league']);
        $this->setRegion($pValues['region']);
        $this->setReportNumber($pValues['reportNumber']);
        $this->setPDFMode($pValues['pdfMode']);
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
    
    
    public function setReportNumber($pValue) {
        if(is_numeric($pValue)) {
            $this->reportNumber = $pValue;
        } else {
            throw new Exception('ReportNumber must be numeric.');
        }
    }
    
    public function setSeason($pValue) {
        if(is_numeric($pValue)) {
            $this->season = $pValue;
        } else {
            throw new Exception('Season must be numeric.');
        }
    }
    
    public function setRegion($pValue) {
        $this->region = $pValue;
    }
    
    public function setAgeGroup($pValue) {
        $this->ageGroup = $pValue;
    }
    
    public function setUmpireType($pValue) {
        $this->umpireType = $pValue;
    }
    
    public function setLeague($pValue) {
        $this->league = $pValue;
    }
    
    public function setPDFMode($pValue) {
        if(is_bool($pValue)) {
            $this->pdfMode = $pValue;
        } else {
            throw new Exception('PDFMode must be a boolean.');
        }
    }
    
    //Determines if the Requested Report Model should use a value from the selectable field, or the hidden value
    //(e.g. if the PDF report was generated)
    public function findValueFromPostOrHidden($pPostArray, $pPostKey, $pPostKeyHidden) {
        if (array_key_exists($pPostKey, $pPostArray)) {
            return $_POST[$pPostKey];
        } else {
            return explode(",", $_POST[$pPostKeyHidden]);
        }
    }
}
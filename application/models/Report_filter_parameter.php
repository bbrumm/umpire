<?php
class Report_filter_parameter extends CI_Model {
    
    private $filterSQLValues;
    private $filterDisplayValues;
    private $useStrReplaceForSQL; //Used only for setting Region SQL values when PDF Mode is false
    
    public function __construct() {
        $this->load->library('Debug_library');
    }
    
    public function createFilterParameter($pFilterParameter, $pdfMode, $pFilterIsRegion = false) {
        $this->debug_library->debugOutput("pFilterParameters in createFilterParameter", $pFilterParameter);
        
        if ($pdfMode) {
            $this->filterSQLValues = $this->strReplaceWithApostrophe($pFilterParameter);
            $this->filterDisplayValues = $this->strReplaceWithoutApostrophe($pFilterParameter);
        } elseif ($pFilterIsRegion) {
            $this->filterSQLValues = $this->strReplaceWithApostrophe($pFilterParameter);
            //$this->filterDisplayValues = $this->implodeWithoutApostrophe($pFilterParameter);
            $this->filterDisplayValues = $pFilterParameter;
        } else {
            $this->filterSQLValues = $this->implodeWithApostrophe($pFilterParameter);
            $this->filterDisplayValues = $this->implodeWithoutApostrophe($pFilterParameter);
        }
    }
    
    public function getFilterSQLValues() {
        return $this->filterSQLValues;
    }
    
    public function getFilterDisplayValues() {
        return $this->filterDisplayValues;
    }
    
    /*
    
    //: Change this to single values, maybe with a parameter so the regionSQLValues can use strreplace instead of implode for the case below
    private function convertParametersToSQLReadyValues($pRequestedReport) {
        //Converts several of the reportParameters arrays into comma separate values that are ready for SQL queries
        $this->filterSQLValues = $this->strReplaceWithApostrophe($pRequestedReport->getUmpireType());
        
        
        if ($pRequestedReport->getPDFMode()) {
            $this->umpireTypeSQLValues = $this->strReplaceWithApostrophe($pRequestedReport->getUmpireType());
            $this->leagueSQLValues = $this->strReplaceWithApostrophe($pRequestedReport->getLeague());
            $this->ageGroupSQLValues = $this->strReplaceWithApostrophe($pRequestedReport->getAgeGroup());
            $this->regionSQLValues = $this->strReplaceWithApostrophe($pRequestedReport->getRegion());
        } else {
            $this->umpireTypeSQLValues = $this->implodeWithApostrophe($pRequestedReport->getUmpireType());
            $this->leagueSQLValues = $this->implodeWithApostrophe($pRequestedReport->getLeague());
            $this->ageGroupSQLValues = $this->implodeWithApostrophe($pRequestedReport->getAgeGroup());
            $this->regionSQLValues = $this->strReplaceWithApostrophe($pRequestedReport->getRegion());
        }
    }
    
    private function convertParametersToDisplayValues($pRequestedReport) {
         
        if ($pRequestedReport->getPDFMode()) {
            $this->umpireTypeDisplayValues = $this->strReplaceWithoutApostrophe($pRequestedReport->getUmpireType()) ;
            $this->leagueDisplayValues = $this->strReplaceWithoutApostrophe($pRequestedReport->getLeague());
            $this->ageGroupDisplayValues = $this->strReplaceWithoutApostrophe($pRequestedReport->getAgeGroup());
        } else {
            $this->umpireTypeDisplayValues = $this->implodeWithoutApostrophe($pRequestedReport->getUmpireType());
            $this->leagueDisplayValues = $this->implodeWithoutApostrophe($pRequestedReport->getLeague());
            $this->ageGroupDisplayValues = $this->implodeWithoutApostrophe($pRequestedReport->getAgeGroup());
        }
    }
    */
    
    private function strReplaceWithApostrophe($pInputString) {
        return str_replace(",", "','", "'" . rtrim($pInputString, ',')) . "'";
    }
    
    private function strReplaceWithoutApostrophe($pInputString) {
        return str_replace(",", ", ", rtrim($pInputString, ',')) . "'";
    }
    
    private function implodeWithApostrophe($pInputString) {
        return "'".implode("','", $pInputString)."'";
    }
    
    private function implodeWithoutApostrophe($pInputString) {
        return implode(", ", $pInputString);
    }
    
    
    
    
    
}
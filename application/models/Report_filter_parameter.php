<?php
class Report_filter_parameter extends CI_Model {
    
    private $filterSQLValues;
    private $filterDisplayValues;
    private $useStrReplaceForSQL; //Used only for setting Region SQL values when PDF Mode is false
    
    public function __construct() {
        $this->load->library('Debug_library');
    }
    
    public function createFilterParameter($pFilterParameter, $pdfMode, $pFilterIsRegion = false) {
        $this->debug_library->debugOutput("pFilterParameters in createFilterParameter: ", $pFilterParameter);
        
        if ($pdfMode) {
            $this->filterSQLValues = $this->strReplaceWithApostrophe($pFilterParameter);
            $this->filterDisplayValues = $this->strReplaceWithoutApostrophe($pFilterParameter);
        } elseif ($pFilterIsRegion) {
            //$this->filterSQLValues = $this->strReplaceWithApostrophe($pFilterParameter);
            //This Region value can sometimes be an array, or a single value.
            $this->filterSQLValues = "'" . $pFilterParameter[0] . "'";
            $this->filterDisplayValues = $pFilterParameter[0];
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
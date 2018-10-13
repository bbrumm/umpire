<?php
class Report_filter_parameter extends CI_Model {
    
    private $filterSQLValues;
    private $filterDisplayValues;
    private $useStrReplaceForSQL; //Used only for setting Region SQL values when PDF Mode is false
    
    public function __construct() {
        $this->load->library('Debug_library');
    }
    
    public function createFilterParameter($pFilterParameter, $pdfMode, $pFilterIsRegion = false) {
        //$this->debug_library->debugOutput("pFilterParameters in createFilterParameter: ", $pFilterParameter);
        //$this->debug_library->debugOutput("pFilterParameters isRegion: ", $pFilterIsRegion);
        if ($pdfMode) {
            $this->filterSQLValues = $this->strReplaceWithApostrophe($pFilterParameter);
            $this->filterDisplayValues = $this->strReplaceWithoutApostrophe($pFilterParameter);
        } elseif ($pFilterIsRegion) {
            
            //This Region value can sometimes be an array, or a single value.
            //TODO: Commented out the first line and uncommented the first line, 
            //because selecting a single Region causes the query to return nothing for report 1.
            //Come back here if there are still errors.
            if(is_array($pFilterParameter)) {
                $pFilterParameterRegion = $pFilterParameter[0];
            } else {
                $pFilterParameterRegion = $pFilterParameter;
            }
            
            //$this->filterSQLValues = "'" . $pFilterParameter[0] . "'";            
            $this->filterSQLValues = $this->strReplaceWithApostrophe($pFilterParameterRegion);
            $this->filterDisplayValues = $pFilterParameterRegion;
        } else {
            if(is_array($pFilterParameter)) {
            //$this->debug_library->debugOutput("pFilterParameters ready to implode: ", $pFilterParameter);
                if (count($pFilterParameter) > 0) {
                    $this->filterSQLValues = $this->implodeWithApostrophe($pFilterParameter);
                    $this->filterDisplayValues = $this->implodeWithoutApostrophe($pFilterParameter);
                } else {
                    throw new InvalidArgumentException("The provided filterParameter array is empty.");
                }
            } else {
                throw new InvalidArgumentException("The provided filterParameter value is expected to be an array, but a string was provided: ". $pFilterParameter);
            }
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
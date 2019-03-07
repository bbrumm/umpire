<?php
class Report_result extends CI_Model {


    public function __construct() {
        $this->load->model('separate_reports/Report_cell');
    }
    
    public function loadDataForReport($pDataStore, $pSeparateReport) {
    
    }
    
    private function convertResultArrayToCollection() {
    
    }

}

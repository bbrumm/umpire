<?php

class Run_etl_stored_proc extends CI_Model
{
    function __construct() {
        parent::__construct();
        $this->load->model('Season');
        $this->load->model('Refresh_mv_tables');
    }
    
    public function runETLProcedure(IData_store $pDataStore, $season, $importedFileID) {
        $pDataStore->runETLProcedure();
        $mvRefresher = new Refresh_mv_tables();
        $mvRefresher->refreshMVTables($pDataStore, $season, $importedFileID);
    }
    
}
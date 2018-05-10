<?php

class Run_etl_stored_proc extends CI_Model
{
    function __construct() {
        parent::__construct();
        $this->load->model('Season');
        $this->load->model('Refresh_mv_tables');
    }
    
    public function runETLProcedure($season, $importedFileID) {
        
        $queryString = "CALL `RunETLProcess`(". $season->getSeasonID() .", ". $importedFileID .")";
        $query = $this->db->query($queryString);
        
        $mvRefresher = new Refresh_mv_tables();
        $mvRefresher->refreshMVTables($season, $importedFileID);
    }
    
}
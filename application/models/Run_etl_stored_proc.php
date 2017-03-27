<?php

class Run_etl_stored_proc extends CI_Model
{
    function __construct() {
        parent::__construct();
        $this->load->model('Season');
    }
    
    public function runETLProcedure($season, $importedFileID) {
        $queryString = "CALL `RunETLProcess`(". $season->getSeasonID() .", ". $importedFileID .")";
        $query = $this->db->query($queryString);
    }
    
}
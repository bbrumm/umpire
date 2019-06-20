<?php
require_once 'IData_store_match_import.php';

class Array_store_match_import extends CI_Model implements IData_store_match_import
{


    public function __construct() {
        $this->load->library('Array_library');
    }

    public function findSeasonToUpdate() {
        //TODO write code
    }

    public function findLatestImportedFile() {
        //TODO write code
    }

    public function runETLProcedure($pSeason, $pImportedFileID) {
        //TODO write code
    }

}
<?php
require_once 'IData_store_match_import.php';
/*
* @property Object db
*/
class Database_store_match_import extends CI_Model implements IData_store_match_import {

    private function runQuery($queryString) {
        return $this->db->query($queryString);
    }

    public function findSeasonToUpdate() {
        //echo "<pre>".print_r(debug_backtrace(2),true)."</pre>";

        $queryString = "SELECT MAX(season.ID) AS season_id " .
            "FROM season " .
            "INNER JOIN match_import ON season.season_year = match_import.season;";
        $query = $this->runQuery($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['season_id'];
    }

    public function findLatestImportedFile() {
        $queryString = "SELECT MAX(imported_file_id) AS imported_file_id
            FROM table_operations";
        $query = $this->runQuery($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['imported_file_id'];
    }

    public function runETLProcedure($pSeason, $pImportedFileID) {
        $etlSteps = new Etl_procedure_steps();
        $etlSteps->runETLProcess($pSeason, $pImportedFileID);
    }

}
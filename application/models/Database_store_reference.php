<?php
require_once 'IData_store_reference.php';
/*
* @property Object db
* @property Object session
*/
class Database_store_reference extends CI_Model implements IData_store_reference
{
    private function runQuery($queryString, $arrayValues = null) {
        return $this->db->query($queryString, $arrayValues);
        //$this->db->close();
    }

    private function getArrayFromQuery($queryString) {
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
    }

    public function getRoleArray() {
        $queryString = "SELECT id, role_name, display_order FROM role WHERE role_name != 'Owner' ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }

    public function getReportArray() {
        $queryString = "SELECT report_id, report_name FROM report;";
        return $this->getArrayFromQuery($queryString);
    }

    public function getRegionArray() {
        $queryString = "SELECT id, region_name FROM region;";
        return $this->getArrayFromQuery($queryString);
    }

    public function getUmpireDisciplineArray() {
        $queryString = "SELECT id, umpire_type_name FROM umpire_type;";
        return $this->getArrayFromQuery($queryString);
    }

    public function getAgeGroupArray() {
        $queryString = "SELECT id, age_group FROM age_group ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }

    public function getLeagueArray() {
        $queryString = "SELECT id, short_league_name FROM short_league_name ORDER BY display_order;";
        return $this->getArrayFromQuery($queryString);
    }

    public function getPermissionSelectionArray() {
        $queryString = "SELECT id, permission_id, category, selection_name ".
            " FROM permission_selection ORDER BY category, display_order;";
        return $this->getArrayFromQuery($queryString);
    }

}
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
    }

    private function getArrayFromQuery($queryString) {
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
    }

    public function getRoleArray() {
        $queryString = "SELECT id, role_name, display_order 
            FROM role 
            WHERE role_name != 'Owner' ORDER BY display_order;";
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
        $queryString = "SELECT id, permission_id, category, selection_name
          FROM permission_selection
          ORDER BY category, display_order;";
        return $this->getArrayFromQuery($queryString);
    }

    public function loadPossibleLeaguesForComp() {
        $queryString = "SELECT
            l.id,
            l.league_name,
            l.short_league_name,
            l.age_group_division_id,
            agd.division_id,
            d.division_name,
            ag.age_group,
            l.region_id,
            r.region_name
            FROM league l
            INNER JOIN age_group_division agd ON l.age_group_division_id = agd.ID
            INNER JOIN division d ON agd.division_id = d.id
            INNER JOIN age_group ag ON agd.age_group_id = ag.id
            INNER JOIN region r ON l.region_id = r.id;";

        return $this->getArrayFromQuery($queryString);
    }

    public function loadPossibleClubsForTeam() {
        $queryString = "SELECT DISTINCT id, club_name
            FROM club
            ORDER BY club_name ASC;";

        return $this->getArrayFromQuery($queryString);
    }

    public function loadPossibleRegions() {
        $queryString = "SELECT DISTINCT id, region_name
            FROM region
            ORDER BY id ASC;";

        return $this->getArrayFromQuery($queryString);
    }

    public function loadPossibleAgeGroups() {
        $queryString = "SELECT id, age_group
            FROM age_group
            ORDER BY display_order ASC;";

        return $this->getArrayFromQuery($queryString);
    }

    public function loadPossibleShortLeagueNames() {
        $queryString = "SELECT id, short_league_name
            FROM short_league_name
            ORDER BY display_order ASC;";

        return $this->getArrayFromQuery($queryString);
    }

    public function loadPossibleDivisions() {
        $queryString = "SELECT id, division_name
            FROM division
            ORDER BY id ASC;";

        return $this->getArrayFromQuery($queryString);
    }

}
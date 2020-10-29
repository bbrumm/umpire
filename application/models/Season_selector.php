<?php
class Season_selector extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    private function runQuery($queryString, $arrayParam = null) {
        return $this->db->query($queryString, $arrayParam);
    }

    public function getListOfSeasons($pCurrentYear) {
        $queryString = "SELECT DISTINCT season_year
            FROM season
            WHERE season_year <= $pCurrentYear
            ORDER BY season_year ASC;";

        $query = $this->runQuery($queryString);
        $allSeasons = array();

        foreach ($query->result() as $row) {
            $season = new Season();
            $season->setSeasonYear($row->season_year);

            $allSeasons[] = $season;
        }
        return $allSeasons;
    }


}
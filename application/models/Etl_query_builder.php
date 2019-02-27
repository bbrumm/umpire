<?php
/*
* @property Object db
*/
class Etl_query_builder extends CI_Model
{

    private $season;

    function __construct() {
        parent::__construct();
        $this->load->model('Season');
    }

    public setSeason($pSeason) {
        $this->season = $pSeason;
    }

    
    public function getDeleteMatchPlayedQuery() {
        return "DELETE match_played FROM match_played
        INNER JOIN round ON match_played.round_id = round.ID 
WHERE round.season_id = ". $this->season->getSeasonID() .";";
    }

}

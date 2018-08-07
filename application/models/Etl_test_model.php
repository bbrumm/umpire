<?php

class Etl_test_model extends CI_Model
{
    
    var $debugMode;
    
    public function __construct()
    {
        //parent::__construct();
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->library('Array_library');
        
        
        $this->load->model("Umpire_match_record");
    }
    
    public function runTestQuery() {
        
        $queryString = "SELECT id, season_year FROM season;";
        
        $query = $this->db->query($queryString);
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray['testQuery'] = $query->result_array();
        
        return $queryResultArray;
        
    }
}
?>
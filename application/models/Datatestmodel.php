<?php
require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class DataTestModel extends MY_Model
{
    /* Code .. */
    
    var $debugMode;

    function __construct()
    {
        parent::__construct();
        $this->load->model("UmpireMatchRecord");
    }
    
    public function runAllTests() {
        /*Structure
         * Get a list of unique umpires, from the match_import table, because that table is the raw data and it could have been lost along the way.
         * Also get a unique list of teams
         * Then, run tests for report 1:
         * For each umpire in the list, count the number of matches for the team and the competition_name. Count them by umpire type.
         * Translate the competition name into the league and age group.
         * The output from this should be: umpire name, age group, umpire type, league, team name, count.
         * Then, run the same test on the MV_report_01 table. Input the umpire name, age group, umpire type, league, team name.
         * Get the count that is returned (which could be 0 or no results found)
         * 
         * Output the umpire data and the count for each check.
         * Repeat these for all umpires.
         * This is the end of the test for report 1.
         *
         */
         
        $this->debugMode = $this->config->item('debug_mode');
        $outputArray['tableOperations'] = $this->checkImportedTableOperations();
        $outputArray['report01'] = $this->runTestsForReport01();
        
        return $outputArray;
         
    }
    
    private function runTestsForReport01() {
        $umpireMatchCountArray = $this->getUmpireMatchCountForReport01();
        return $umpireMatchCountArray;
    }
    
    private function getUmpireMatchCountForReport01() {
        $queryString = "SELECT s.umpire_full_name, s.club_name, s.short_league_name, s.age_group, s.umpire_type, s.match_count AS match_count_staging, r.match_count AS match_count_report01 " .
            "FROM test_matchcount_staging s " .
            "LEFT OUTER JOIN test_matchcount_report_01 r " .
            "ON s.umpire_full_name = r.umpire_full_name " .
            "AND s.club_name = r.club_name " .
            "AND s.short_league_name = r.short_league_name " .
            "AND s.age_group = r.age_group " .
            "AND s.umpire_type = r.umpire_type " .
            "WHERE r.season_year = 2016 " .
            "AND s.match_count <> r.match_count " .
            "ORDER BY s.umpire_full_name, s.club_name, s.short_league_name, s.age_group, s.umpire_type;";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        /*
        if ($this->debugMode) {
            echo "getUmpireMatchCountForReport01:<BR/>";
            echo "<pre>";
            print_r($queryResultArray);
            echo "</pre>";
        }
        */
        return $queryResultArray;
        
    }
    
    private function checkImportedTableOperations() {
        $queryString = "SELECT t.operation_datetime, o.operation_name, p.table_name, t.rowcount " .
            "FROM table_operations t " .
            "INNER JOIN operation_ref o ON t.operation_id = o.id " .
            "INNER JOIN processed_table p ON t.processed_table_id = p.id " .
            "INNER JOIN imported_files f ON t.imported_file_id = f.imported_file_id " .
            "WHERE f.imported_file_id = ( " .
                "SELECT MAX(imported_file_id) " .
                "FROM imported_files " .
            ") " .
            "ORDER BY t.id;";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        /*
         if ($this->debugMode) {
         echo "getUmpireMatchCountForReport01:<BR/>";
         echo "<pre>";
         print_r($queryResultArray);
         echo "</pre>";
         }
         */
        return $queryResultArray;
    }
    

}
?>
        
        
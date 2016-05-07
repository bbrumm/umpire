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
        
        return $this->runTestsForReport01();
        
         
         
    }
    
    private function runTestsForReport01() {
        $uniqueUmpireList = $this->getUniqueUmpiresFromImportedData();
        $uniqueTeamList = $this->getUniqueTeamsFromImportedData();
        $umpireMatchCountBefore = $this->getAllMatchCounts();
        $umpireMatchCountAfter = $this->getUmpireDataFromReport01();
        
        
        //Loop through array of the  data for comparison (data before transforming)
        foreach ($umpireMatchCountBefore as $key => $val) {
            $umpireMatchRecord = new UmpireMatchRecord();
            $umpireMatchRecord->setUmpireName($val['umpire_full_name']);
            $umpireMatchRecord->setUmpireType($val['umpire_type']);
            $umpireMatchRecord->setShortLeagueName($val['short_league_name']);
            $umpireMatchRecord->setAgeGroup($val['age_group']);
            $umpireMatchRecord->setClubName($val['team']);
            
            $keyToAfterArray = $this->findUmpireMatchCountInArray($umpireMatchRecord, $umpireMatchCountAfter);
            
            if ($keyToAfterArray != NULL) {
                //echo "Match: " . $umpireMatchCountAfter[$keyToAfterArray]['match_count'] . "<BR />";
                $umpireMatchCountBefore[$key]['match_count_after'] = $umpireMatchCountAfter[$keyToAfterArray]['match_count'];
            } else {
                $umpireMatchCountBefore[$key]['match_count_after'] = 0;
            }
            
        }
        /*
        if ($this->debugMode) {
            echo "umpireMatchCountBefore:<BR/>";
            echo "<pre>";
            print_r($umpireMatchCountBefore);
            echo "</pre>";
        }
        */
        return $umpireMatchCountBefore;
        
        
    }
    
    private function findUmpireMatchCountInArray($umpireMatchRecord, $array) {
        foreach ($array as $key => $val) {
            if ($val['umpire_full_name'] === $umpireMatchRecord->getUmpireName() && 
                $val['umpire_type'] === $umpireMatchRecord->getUmpireType() && 
                $val['short_league_name'] === $umpireMatchRecord->getShortLeagueName() && 
                $val['age_group'] === $umpireMatchRecord->getAgeGroup() && 
                $val['club_name'] === $umpireMatchRecord->getClubName()) {
                return $key;
            }
        }
        return null;
    }
    
    private function getUniqueUmpiresFromImportedData() {
        $queryString = "SELECT DISTINCT umpire_full_name FROM ( " .
            "SELECT field_umpire_1 AS umpire_full_name FROM match_import " .
            "UNION ALL " .
            "SELECT field_umpire_2 FROM match_import " .
            "UNION ALL " .
            "SELECT field_umpire_3 FROM match_import " .
            "UNION ALL " .
            "SELECT boundary_umpire_1 FROM match_import " .
            "UNION ALL " .
            "SELECT boundary_umpire_2 FROM match_import " .
            "UNION ALL " .
            "SELECT boundary_umpire_3 FROM match_import " .
            "UNION ALL " .
            "SELECT boundary_umpire_4 FROM match_import " .
            "UNION ALL " .
            "SELECT boundary_umpire_5 FROM match_import " .
            "UNION ALL " .
            "SELECT boundary_umpire_6 FROM match_import " .
            "UNION ALL " .
            "SELECT goal_umpire_1 FROM match_import " .
            "UNION ALL " .
            "SELECT goal_umpire_2 FROM match_import) sub " .
            "WHERE umpire_full_name IS NOT NULL " .
            "ORDER BY umpire_full_name;"; 
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        /* Array format is:
         * [0][umpire_full_name] => "First Last"
         */
        /*
        if ($this->debugMode) {
            echo "getUniqueUmpiresFromImportedData:<BR/>";
            echo "<pre>";
            print_r($queryResultArray);
            echo "</pre>";
        }
        */
        return $queryResultArray;
         
    }
    
    private function getUniqueTeamsFromImportedData() {
        $queryString = "SELECT DISTINCT team FROM ( " .
            "SELECT home_team AS team FROM match_import " .
            "UNION ALL " .
            "SELECT away_team FROM match_import) sub " .
            "WHERE team IS NOT NULL " .
            "ORDER BY team;";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        /* Array format is:
         * [0][team] => "TeamName"
         */
        /*
        if ($this->debugMode) {
            echo "getUniqueTeamsFromImportedData:<BR/>";
            echo "<pre>";
            print_r($queryResultArray);
            echo "</pre>";
        }
        */
        return $queryResultArray;
        
    
    }
    
    private function getAllMatchCounts() {
        $queryString = "SELECT CONCAT(RIGHT(sub.umpire_full_name,Length(sub.umpire_full_name)-InStr(sub.umpire_full_name,' ')),', ', " .
            "LEFT(sub.umpire_full_name,InStr(sub.umpire_full_name,' ')-1)) AS umpire_full_name, " .
            "sub.team, l.short_league_name, ag.age_group, sub.umpire_type, sub.competition_name, COUNT(sub.id) AS match_count " .
            "FROM ( " .
            "SELECT 'Field' AS umpire_type, field_umpire_1 AS umpire_full_name, ID, competition_name, home_team AS team FROM match_import UNION ALL " .
            "SELECT 'Field', field_umpire_1, ID, competition_name, away_team FROM match_import UNION ALL " .
            "SELECT 'Field', field_umpire_2, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Field' AS umpire_type, field_umpire_2, ID, competition_name, away_team FROM match_import UNION ALL " .
            "SELECT 'Field', field_umpire_3, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Field' AS umpire_type, field_umpire_3, ID, competition_name, away_team FROM match_import UNION ALL ";
        
        $queryString .= "SELECT 'Boundary', boundary_umpire_1, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Boundary' AS umpire_type, boundary_umpire_1, ID, competition_name, away_team FROM match_import UNION ALL " .
            "SELECT 'Boundary', boundary_umpire_2, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Boundary' AS umpire_type, boundary_umpire_2, ID, competition_name, away_team FROM match_import UNION ALL " .
            "SELECT 'Boundary', boundary_umpire_3, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Boundary' AS umpire_type, boundary_umpire_3, ID, competition_name, away_team FROM match_import UNION ALL " .
            "SELECT 'Boundary', boundary_umpire_4, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Boundary' AS umpire_type, boundary_umpire_4, ID, competition_name, away_team FROM match_import UNION ALL " .
            "SELECT 'Boundary', boundary_umpire_5, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Boundary' AS umpire_type, boundary_umpire_5, ID, competition_name, away_team FROM match_import UNION ALL " .
            "SELECT 'Boundary', boundary_umpire_6, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Boundary' AS umpire_type, boundary_umpire_6, ID, competition_name, away_team FROM match_import UNION ALL ";
        
        $queryString .= "SELECT 'Goal', goal_umpire_1, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Goal' AS umpire_type, goal_umpire_1, ID, competition_name, away_team FROM match_import UNION ALL " .
            "SELECT 'Goal', goal_umpire_2, ID, competition_name, home_team FROM match_import UNION ALL " .
            "SELECT 'Goal' AS umpire_type, goal_umpire_2, ID, competition_name, away_team FROM match_import) sub " .
            "LEFT OUTER JOIN competition_lookup c ON sub.competition_name = c.competition_name " .
            "LEFT OUTER JOIN league l ON c.league_id = l.id " .
            "LEFT OUTER JOIN age_group_division agd ON l.age_group_division_id = agd.id " .
            "LEFT OUTER JOIN age_group ag ON agd.age_group_id = ag.id " .
            "WHERE sub.umpire_full_name IS NOT NULL " .
            "GROUP BY sub.umpire_full_name, sub.team, l.short_league_name, ag.age_group, sub.umpire_type, sub.competition_name " .
            "ORDER BY sub.umpire_full_name, sub.team, l.short_league_name, ag.age_group, sub.umpire_type, sub.competition_name";
        
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        /* Array format is:
         * [0][column_heading] => "Value"
         * e.g.:
         * [0][umpire_type] => "Boundary"
         */
        /*
        if ($this->debugMode) {
            echo "getAllMatchCounts:<BR/>";
            echo "<pre>";
            print_r($queryResultArray);
            echo "</pre>";
        }
        */
        return $queryResultArray;
        
    }
    
    private function getUmpireDataFromReport01() {
        $queryString = "SELECT full_name AS umpire_full_name, club_name, short_league_name, age_group, umpire_type_name AS umpire_type, " . 
            "IFNULL(`BFL|Anglesea`, 0) +  " . 
            "IFNULL(`BFL|Barwon_Heads`, 0) +  " . 
            "IFNULL(`BFL|Drysdale`, 0) +  " . 
            "IFNULL(`BFL|Geelong_Amateur`, 0) +  " . 
            "IFNULL(`BFL|Modewarre`, 0) +  " . 
            "IFNULL(`BFL|Newcomb_Power`, 0) +  " . 
            "IFNULL(`BFL|Ocean_Grove`, 0) +  " . 
            "IFNULL(`BFL|Portarlington`, 0) +  " . 
            "IFNULL(`BFL|Queenscliff`, 0) +  " . 
            "IFNULL(`BFL|Torquay`, 0) +  " . 
            "IFNULL(`GDFL|Anakie`, 0) +  " . 
            "IFNULL(`GDFL|Bannockburn`, 0) +  " . 
            "IFNULL(`GDFL|Bell_Post_Hill`, 0) +  " . 
            "IFNULL(`GDFL|Belmont_Lions`, 0) +  " . 
            "IFNULL(`GDFL|Corio`, 0) +  " . 
            "IFNULL(`GDFL|East_Geelong`, 0) +  " . 
            "IFNULL(`GDFL|Geelong_West`, 0) +  " . 
            "IFNULL(`GDFL|Inverleigh`, 0) +  " . 
            "IFNULL(`GDFL|North_Geelong`, 0) +  " . 
            "IFNULL(`GDFL|Thomson`, 0) +  " . 
            "IFNULL(`GDFL|Werribee_Centrals`, 0) +  " . 
            "IFNULL(`GDFL|Winchelsea`, 0) +  " . 
            "IFNULL(`GFL|Bell_Park`, 0) +  " . 
            "IFNULL(`GFL|Colac`, 0) +  " . 
            "IFNULL(`GFL|Grovedale`, 0) +  " . 
            "IFNULL(`GFL|Gwsp`, 0) +  " . 
            "IFNULL(`GFL|Lara`, 0) +  " . 
            "IFNULL(`GFL|Leopold`, 0) +  " . 
            "IFNULL(`GFL|Newtown_&_Chilwell`, 0) +  " . 
            "IFNULL(`GFL|North_Shore`, 0) +  " . 
            "IFNULL(`GFL|South_Barwon`, 0) +  " . 
            "IFNULL(`GFL|St_Albans`, 0) +  " . 
            "IFNULL(`GFL|St_Joseph's`, 0) +  " . 
            "IFNULL(`GFL|St_Mary's`, 0) +  " . 
            "IFNULL(`GJFL|Anakie`, 0) +  " . 
            "IFNULL(`GJFL|Anglesea`, 0) +  " . 
            "IFNULL(`GJFL|Bannockburn`, 0) +  " . 
            "IFNULL(`GJFL|Barwon_Heads`, 0) +  " . 
            "IFNULL(`GJFL|Bell_Park`, 0) +  " . 
            "IFNULL(`GJFL|Belmont_Lions_/_Newcomb`, 0) +  " . 
            "IFNULL(`GJFL|Belmont_Lions`, 0) +  " . 
            "IFNULL(`GJFL|Colac`, 0) +  " . 
            "IFNULL(`GJFL|Corio`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale_Bennett`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale_Byrne`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale_Eddy`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale_Hall`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale_Hector`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale`, 0) +  ";
                    
        $queryString .= "IFNULL(`GJFL|East_Geelong`, 0) +  " . 
            "IFNULL(`GJFL|Geelong_Amateur`, 0) +  " . 
            "IFNULL(`GJFL|Geelong_West_St_Peters`, 0) +  " . 
            "IFNULL(`GJFL|Grovedale`, 0) +  " . 
            "IFNULL(`GJFL|Gwsp_/_Bannockburn`, 0) +  " . 
            "IFNULL(`GJFL|Inverleigh`, 0) +  " . 
            "IFNULL(`GJFL|Lara`, 0) +  " . 
            "IFNULL(`GJFL|Leopold`, 0) +  " . 
            "IFNULL(`GJFL|Modewarre`, 0) +  " . 
            "IFNULL(`GJFL|Newcomb`, 0) +  " . 
            "IFNULL(`GJFL|Newtown_&_Chilwell`, 0) +  " . 
            "IFNULL(`GJFL|North_Geelong`, 0) +  " . 
            "IFNULL(`GJFL|North_Shore`, 0) +  " . 
            "IFNULL(`GJFL|Ocean_Grove`, 0) +  " . 
            "IFNULL(`GJFL|Ogcc`, 0) +  " . 
            "IFNULL(`GJFL|Portarlington`, 0) +  " . 
            "IFNULL(`GJFL|Queenscliff`, 0) +  " . 
            "IFNULL(`GJFL|South_Barwon_/_Geelong_Amateur`, 0) +  " . 
            "IFNULL(`GJFL|South_Barwon`, 0) +  " . 
            "IFNULL(`GJFL|St_Albans_Allthorpe`, 0) +  " . 
            "IFNULL(`GJFL|St_Albans_Reid`, 0) +  " . 
            "IFNULL(`GJFL|St_Albans`, 0) +  " . 
            "IFNULL(`GJFL|St_Joseph's_Hill`, 0) +  " . 
            "IFNULL(`GJFL|St_Joseph's_Podbury`, 0) +  " . 
            "IFNULL(`GJFL|St_Joseph's`, 0) +  " . 
            "IFNULL(`GJFL|St_Mary's`, 0) +  " . 
            "IFNULL(`GJFL|Tigers_Gold`, 0) +  " . 
            "IFNULL(`GJFL|Torquay_Bumpstead`, 0) +  " . 
            "IFNULL(`GJFL|Torquay_Coles`, 0) +  " . 
            "IFNULL(`GJFL|Torquay_Dunstan`, 0) +  " . 
            "IFNULL(`GJFL|Torquay_Jones`, 0) +  " . 
            "IFNULL(`GJFL|Torquay_Nairn`, 0) +  " . 
            "IFNULL(`GJFL|Torquay_Papworth`, 0) +  " . 
            "IFNULL(`GJFL|Torquay_Pyers`, 0) +  " . 
            "IFNULL(`GJFL|Torquay_Scott`, 0) +  " . 
            "IFNULL(`GJFL|Torquay`, 0) +  " . 
            "IFNULL(`GJFL|Werribee_Centrals`, 0) +  " . 
            "IFNULL(`GJFL|Winchelsea_/_Grovedale`, 0) +  " . 
            "IFNULL(`GJFL|Winchelsea`, 0) +  " . 
            "IFNULL(`CDFNL|Birregurra`, 0) +  " . 
            "IFNULL(`CDFNL|Lorne`, 0) +  " . 
            "IFNULL(`CDFNL|Apollo Bay`, 0) +  " . 
            "IFNULL(`CDFNL|Alvie`, 0) +  " . 
            "IFNULL(`CDFNL|Colac Imperials`, 0) +  " . 
            "IFNULL(`CDFNL|Irrewarra-beeac`, 0) +  " . 
            "IFNULL(`CDFNL|Otway Districts`, 0) +  " . 
            "IFNULL(`CDFNL|Simpson`, 0) +  " . 
            "IFNULL(`CDFNL|South Colac`, 0) +  " . 
            "IFNULL(`CDFNL|Western Eagles`, 0) +  ";
            
        $queryString .= "IFNULL(`GJFL|Aireys Inlet`, 0) +  " . 
            "IFNULL(`GJFL|Ammos Blue`, 0) +  " . 
            "IFNULL(`GJFL|Ammos Green`, 0) +  " . 
            "IFNULL(`GJFL|Ammos White`, 0) +  " . 
            "IFNULL(`GJFL|Bannockburn / South Barwon`, 0) +  " . 
            "IFNULL(`GJFL|Barwon Heads Gulls`, 0) +  " . 
            "IFNULL(`GJFL|Barwon Heads Heads`, 0) +  " . 
            "IFNULL(`GJFL|Dragons`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale 1`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale 2`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale Humphrey`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale Mcintyre`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale Mckeon`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale Scott`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale Smith`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale Taylor`, 0) +  " . 
            "IFNULL(`GJFL|Drysdale Wilson`, 0) +  " . 
            "IFNULL(`GJFL|Eagles Black`, 0) +  " . 
            "IFNULL(`GJFL|Eagles Red`, 0) +  " . 
            "IFNULL(`GJFL|East Newcomb Lions`, 0) +  " . 
            "IFNULL(`GJFL|East Tigers`, 0) +  " . 
            "IFNULL(`GJFL|Flying Joeys`, 0) +  " . 
            "IFNULL(`GJFL|Gdfl Raiders`, 0) +  " . 
            "IFNULL(`GJFL|Grovedale Broad`, 0) +  " . 
            "IFNULL(`GJFL|Grovedale Ford`, 0) +  " . 
            "IFNULL(`GJFL|Grovedale Mcneel`, 0) +  " . 
            "IFNULL(`GJFL|Grovedale Waldron`, 0) +  " . 
            "IFNULL(`GJFL|Grovedale Williams`, 0) +  " . 
            "IFNULL(`GJFL|Grovedale Young`, 0) +  " . 
            "IFNULL(`GJFL|Lara Batman`, 0) +  " . 
            "IFNULL(`GJFL|Lara Flinders`, 0) +  " . 
            "IFNULL(`GJFL|Lara Hume`, 0) +  " . 
            "IFNULL(`GJFL|Leopold Brown`, 0) +  " . 
            "IFNULL(`GJFL|Leopold Dowsett`, 0) +  " . 
            "IFNULL(`GJFL|Leopold Ruggles`, 0) +  " . 
            "IFNULL(`GJFL|Lethbridge`, 0) +  " . 
            "IFNULL(`GJFL|Newtown & Chilwell Eagles`, 0) +  " . 
            "IFNULL(`GJFL|Ogcc Blue`, 0) +  " . 
            "IFNULL(`GJFL|Ogcc Orange`, 0) +  " . 
            "IFNULL(`GJFL|Ogcc Red`, 0) +  " . 
            "IFNULL(`GJFL|Ogcc White`, 0) +  " . 
            "IFNULL(`GJFL|Queenscliff Blue`, 0) +  " . 
            "IFNULL(`GJFL|Queenscliff Red`, 0) +  " . 
            "IFNULL(`GJFL|Roosters`, 0) +  " . 
            "IFNULL(`GJFL|Saints White`, 0) +  " . 
            "IFNULL(`GJFL|Seagulls`, 0) +  " . 
            "IFNULL(`GJFL|South Barwon Blue`, 0) +  " . 
            "IFNULL(`GJFL|South Barwon Red`, 0) +  ";
            
        $queryString .= "IFNULL(`GJFL|South Barwon White`, 0) +  " . 
            "IFNULL(`GJFL|St Albans Butterworth`, 0) +  " . 
            "IFNULL(`GJFL|St Albans Grinter`, 0) +  " . 
            "IFNULL(`GJFL|St Albans Mcfarlane`, 0) +  " . 
            "IFNULL(`GJFL|St Albans Osborne`, 0) +  " . 
            "IFNULL(`GJFL|Surf Coast Suns`, 0) +  " . 
            "IFNULL(`GJFL|Teesdale Roos`, 0) +  " . 
            "IFNULL(`GJFL|Tigers`, 0) +  " . 
            "IFNULL(`GJFL|Torquay Boyse`, 0) +  " . 
            "IFNULL(`GJFL|Torquay Browning`, 0) +  " . 
            "IFNULL(`GJFL|Torquay Bruce`, 0) +  " . 
            "IFNULL(`GJFL|Torquay Coleman`, 0) +  " . 
            "IFNULL(`GJFL|Torquay Davey`, 0) +  " . 
            "IFNULL(`GJFL|Torquay Milliken`, 0) +  " . 
            "IFNULL(`GJFL|Torquay Stone`, 0) +  " . 
            "IFNULL(`GJFL|Torquay Watson`, 0) +  " . 
            "IFNULL(`GJFL|Winchelsea / Inverleigh`, 0) " . 
            "AS match_count " . 
            "FROM mv_report_01 " . 
            "WHERE season_year = '2016';";
        
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        /* Array format is:
         * [0][column_heading] => "Value"
         * e.g.:
         * [0][umpire_type] => "Boundary"
         */
        /*
        if ($this->debugMode) {
            echo "getAllMatchCounts:<BR/>";
            echo "<pre>";
            print_r($queryResultArray);
            echo "</pre>";
        }*/
        
        return $queryResultArray;
        
    }

}
?>
        
        
<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Tabletestmodel extends CI_Model
{
    
    var $debugMode;
    var $online = false;
    var $refreshLocalTables = false;
    var $startTime;
    
    public function __construct()
    {
        //parent::__construct();
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->library('Array_library');

        $this->startTime = time();
        echo "Time startTime: " . $this->startTime . "<br />";
        
    }
    
    public function runAllTests() {
        
        echo "Time runAllTests: " . (time() - $this->startTime) . "<br />";
        
        $this->debugMode = $this->config->item('debug_mode');
        
        //Refresh the local tables with the data from the prod database, if the flag is set to true
        if ($this->refreshLocalTables) {
            $this->refreshLocalTablesFromRemote();
        }
        
        echo "Time refreshLocalTablesFromRemote: " . (time() - $this->startTime) . "<br />";
        
        //Find a list of table names that exist in one database but not the other
        $outputArray['tableNames'] = $this->getMissingTableNames();
        
        //Find a list of columns that exist in tables in one database but not the other.
        //Helpful for finding code changes I haven't made on Prod
        $outputArray['missingColumns'] = $this->getMissingColumnsInTables();
        
        //Find the differences in data types between the two databases
        $outputArray['columnDifferences'] = $this->getColumnDifferences();
        
        //Find all differences between all tables. Long-running process
        //TODO: Update this to use a smarter matching and locally imported tables instead of federated tables
        //$outputArray['data_differences'] = $this->getAllDataDifferences();
        
        
        
        $outputArray['data_differences'] = $this->findDataDifferences();
        
        //Find differences in leagues and their age groups and divisions
        //$outputArray['league_differences'] = $this->getLeagueDifferences();
        
        return $outputArray;
        
    }
   
    
    private function runQueryIntoArray($queryString) {
        //Run query and store result in array
        $query = $this->db->query($queryString);
        $queryResultArray = $query->result_array();
        
        return $queryResultArray;
        
    }
    
    private function getMissingTableNames() {
        $queryResultArray = array();
        
        if ($this->online) {
            $resultArray = $this->runQueryIntoArray(GET_MISSING_TABLE_NAMES_ONLINE);
        } else {
            $resultArray = NULL;
        }
        
        echo "Time getMissingTableNames: " . (time() - $this->startTime) . "<br />";
        return $resultArray;
        
        
        
    }
    
    private function getMissingColumnsInTables() {
        $queryResultArray = array();
        
        if ($this->online) {
            $resultArray = $this->runQueryIntoArray(GET_MISSING_COLUMNS);
        } else {
            $resultArray = NULL;
        }
        echo "Time getMissingColumnsInTables: " . (time() - $this->startTime) . "<br />";
        return $resultArray;
        
        
        
    }
    
    private function getColumnDifferences() {
        $queryResultArray = array();
        
        if ($this->online) {
            $resultArray = $this->runQueryIntoArray(GET_COLUMN_DIFFERENCES);
        } else {
            $resultArray = NULL;
        }
        echo "Time getColumnDifferences: " . (time() - $this->startTime) . "<br />";
        return $resultArray;
        
        
    }
    
    
    private function getAllDataDifferences() {
        /*
         This function should start with a list of tables that I provide.
         Then it should look up the columns in each of these tables
         Then use these columns to build a SELECT query that finds the differences in data between two tables (local and remote)
         (perhaps using a NOT EXISTS with a correlated subquery)
         Then run the select query
         Then return the data
         Return should be a single array, with nested arrays, each of which contain the data differences in each table.
         
         This is done so I don't have to specify the columns in each table's query, as well as in the View file.
         It also makes it easier when I create new tables in the future.
         */
        
        //$tableDataToValidate = $this->getTableNamesAndColsToValidate();
        //$tableNamesAndColumnsToValidate = $this->transformToMultiArray($tableDataToValidate);
        
        //Turn table and columns into SELECT query
        //$selectQueryArray = $this->transformTablesAndColsIntoQuery($tableNamesAndColumnsToValidate);
        
        //Run SELECT queries and create array
        $tableDifferencesArray = $this->findDifferencesInTables();
        
        return $tableDifferencesArray;
        
        
    }
    
    private function getTableNamesAndColsToValidate() {
        $resultArray = $this->runQueryIntoArray(TABLES_TO_VALIDATE);
        echo "Time getTableNamesAndColsToValidate: " . (time() - $this->startTime) . "<br />";
        return $resultArray;
    }
    
    private function transformToMultiArray($tableDataArray) {
        $tableNameAndColumnArray = array();
        
        for ($i=0; $i < count($tableDataArray); $i++) {
            //Transform a (table)(col) array into a (table) parent and (col) child array
            /*
            echo "<pre>tableDataArray $i:<br />";
            print_r($tableDataArray[$i]);
            echo "</pre>";
            */
            $tableNameAndColumnArray[$tableDataArray[$i]['table_name']][] = $tableDataArray[$i]['column_name'];
            /*
            echo "<pre>tableNameAndColumnArray so far:<br />";
            print_r($tableNameAndColumnArray);
            echo "</pre>";
            */
        }
        
        return $tableNameAndColumnArray;
    }
    
    private function transformTablesAndColsIntoQuery($tablesAndColsArray) {
        foreach ($tablesAndColsArray as $key => $value) {
            //Loop through each table
            $localFocusQueryString = "SELECT ";
            $remoteFocusQueryString = "SELECT ";
            $whereClause = "WHERE ";
            
            for ($i=0; $i < count($tablesAndColsArray[$key]); $i++) {
                //Loop through each column
                $localFocusQueryString .= " l." . $tablesAndColsArray[$key][$i];
                $remoteFocusQueryString .= " r." . $tablesAndColsArray[$key][$i];
                //Exclude some columns for some tables from the WHERE clause (e.g. umpire.id),
                //because the data can match but the ID could be different, which is OK
                if (!($key == 'umpire' && $tablesAndColsArray[$key][$i] == 'id')) {
                    $whereClause .= "IFNULL(l." . $tablesAndColsArray[$key][$i] . ",'') = IFNULL(r." . $tablesAndColsArray[$key][$i] . ",'')";
                }
                if ($i < count($tablesAndColsArray[$key]) - 1) {
                    //Not yet last value, add comma
                    $localFocusQueryString .= ",";
                    $remoteFocusQueryString.= ",";
                    $whereClause .= " AND ";
                }
            }
            
            if ($this->online) {
                $localFocusQueryString.= " FROM " . $key . " l WHERE NOT EXISTS (SELECT 1 FROM fed_" . $key . " r " . $whereClause . ")";
                $remoteFocusQueryString.= " FROM fed_" . $key . " r WHERE NOT EXISTS (SELECT 1 FROM " . $key . " l " . $whereClause . ")";
            } else {
                //Add a limit to just get a small sample
                $localFocusQueryString.= " FROM " . $key . " l WHERE NOT EXISTS (SELECT 1 FROM " . $key . " r " . $whereClause . ") LIMIT 5;";
                $remoteFocusQueryString.= " FROM " . $key . " r WHERE NOT EXISTS (SELECT 1 FROM " . $key . " l " . $whereClause . ") LIMIT 5;";
            }
            
            $queryStringArray[$key]['local'] = $localFocusQueryString;
            $queryStringArray[$key]['remote'] = $remoteFocusQueryString;
            
            echo "- " . $key . " local: " . $localFocusQueryString . "<br />";
            echo "- " . $key . " remote: " . $remoteFocusQueryString . "<br />--<br />";
            
        }
        
        return $queryStringArray;
        
    }
    
    private function findDifferencesInTables($selectQueryArray) {
        foreach ($selectQueryArray AS $key => $value) {
            try {
                $queryResultsArray[$key]['local'] = $this->runQueryIntoArray($value['local']);
                echo "Time Differences Local ". $key .": " . (time() - $this->startTime) . "<br />";
                $queryResultsArray[$key]['remote'] = $this->runQueryIntoArray($value['remote']);
                echo "Time Differences Remote". $key .": " . (time() - $this->startTime) . "<br />";
            } catch (Exception $e) {
                echo "Caught exception",  $e->getMessage(), "\n";
            
            }
        }
        return $queryResultsArray;
        
    }
    
    
    private function refreshLocalTablesFromRemote() {
        //TODO Move to constant variables
        
        
        
        
        $queryString = "TRUNCATE TABLE rm_age_group;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_age_group_division;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_club;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_club;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_competition_lookup;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_division;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_ground;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_league;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_permission;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_permission_selection;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_region;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_report_grouping_structure;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_report_selection_parameter_values;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_report_selection_parameters;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_role;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_role_permission_selection;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_season;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_short_league_name;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_team;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_umpire;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_umpire_name_type;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_umpire_type;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_umpire_users;";
        $query = $this->db->query($queryString);
        
        $queryString = "TRUNCATE TABLE rm_user_permission_selection;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_age_group (ID, age_group, display_order) SELECT ID, age_group, display_order FROM fed_age_group;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_age_group_division (ID, age_group_id, division_id) SELECT ID, age_group_id, division_id FROM fed_age_group_division;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_club (id, club_name) SELECT id, club_name FROM fed_club;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_club (ID, club_name) SELECT ID, club_name FROM fed_club;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_competition_lookup (id, competition_name, season_id, league_id) SELECT id, competition_name, season_id, league_id FROM fed_competition_lookup;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_division (ID, division_name) SELECT ID, division_name FROM fed_division;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_ground (id, main_name, alternative_name) SELECT id, main_name, alternative_name FROM fed_ground;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_league (ID, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id) SELECT ID, league_name, sponsored_league_name, short_league_name, age_group_division_id, region_id FROM fed_league;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_permission (id, permission_name) SELECT id, permission_name FROM fed_permission;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_permission_selection (id, permission_id, category, selection_name, display_order) SELECT id, permission_id, category, selection_name, display_order FROM fed_permission_selection;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_region (id, region_name) SELECT id, region_name FROM fed_region;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_report_grouping_structure (report_grouping_structure_id, grouping_type, report_id, field_id, field_group_order, merge_field, group_heading) SELECT report_grouping_structure_id, grouping_type, report_id, field_id, field_group_order, merge_field, group_heading FROM fed_report_grouping_structure;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_report_selection_parameter_values (parameter_value_id, parameter_id, parameter_value_name, parameter_display_order) SELECT parameter_value_id, parameter_id, parameter_value_name, parameter_display_order FROM fed_report_selection_parameter_values;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_report_selection_parameters (parameter_id, parameter_name, parameter_display_order, allow_multiple_selections) SELECT parameter_id, parameter_name, parameter_display_order, allow_multiple_selections FROM fed_report_selection_parameters;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_role (id, role_name, display_order) SELECT id, role_name, display_order FROM fed_role;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_role_permission_selection (id, permission_selection_id, role_id) SELECT id, permission_selection_id, role_id FROM fed_role_permission_selection;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_season (ID, season_year) SELECT ID, season_year FROM fed_season;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_short_league_name (id, short_league_name, display_order) SELECT id, short_league_name, display_order FROM fed_short_league_name;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_team (ID, team_name, club_id) SELECT ID, team_name, club_id FROM fed_team;";
        
        $queryString = "INSERT INTO rm_umpire(id, first_name, last_name, games_prior, games_other_leagues)
            SELECT id, first_name, last_name, games_prior, games_other_leagues
            FROM fed_umpire;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_umpire_name_type (ID, umpire_id, umpire_type_id) SELECT ID, umpire_id, umpire_type_id FROM fed_umpire_name_type;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_umpire_type (ID, umpire_type_name) SELECT ID, umpire_type_name FROM fed_umpire_type;";
        $query = $this->db->query($queryString);
        
        $queryString = "INSERT INTO rm_umpire_users (id, user_name, user_email, user_password, first_name, last_name, activation_id, active, role_id) SELECT id, user_name, user_email, user_password, first_name, last_name, activation_id, active, role_id FROM fed_umpire_users;";
        $query = $this->db->query($queryString);
        $queryString = "INSERT INTO rm_user_permission_selection (id, user_id, permission_selection_id) SELECT id, user_id, permission_selection_id FROM fed_user_permission_selection;";
        
        
        
    }
    
    private function findDataDifferences() {
        //Finds umpires that are in one database but not the other
        /*$queryResultsArray = array(
            array(
                'name' => 'Missing Umpires',
                'desc' => 'Umpires in one database and not the other.',
                'results' => $this->runQueryIntoArray(QRY_MISSING_UMPIRES)
            ),
        );*/

        $queryResultsArray['missingUmpires']= $this->runQueryIntoArray(QRY_MISSING_UMPIRES);
        echo "Time missingUmpires: " . (time() - $this->startTime) . "<br />";
        
        //Find umpires that have different values in each databases
        $queryResultsArray['umpireDifferences']= $this->runQueryIntoArray(QRY_UMPIRE_DIFF);
        echo "Time umpireDifferences: " . (time() - $this->startTime) . "<br />";
        
        $queryResultsArray['missingLeagues']= $this->runQueryIntoArray(QRY_MISSING_LEAGUES);
        echo "Time missingLeagues: " . (time() - $this->startTime) . "<br />";
        
        $queryResultsArray['leagueDifferences']= $this->runQueryIntoArray(QRY_LEAGUE_DIFF);
        echo "Time leagueDifferences: " . (time() - $this->startTime) . "<br />";
        
        $queryResultsArray['missingTeams']= $this->runQueryIntoArray(QRY_MISSING_TEAMS);
        echo "Time missingTeams: " . (time() - $this->startTime) . "<br />";
        
        $queryResultsArray['teamClubDifferences']= $this->runQueryIntoArray(QRY_TEAMCLUB_DIFF);
        echo "Time teamClubDifferences: " . (time() - $this->startTime) . "<br />";
        
        $queryResultsArray['missingClubs']= $this->runQueryIntoArray(QRY_MISSING_CLUBS);
        echo "Time missingClubs: " . (time() - $this->startTime) . "<br />";
        
        $queryResultsArray['missingCompetitions']= $this->runQueryIntoArray(QRY_MISSING_COMPETITIONS);
        echo "Time missingCompetitions: " . (time() - $this->startTime) . "<br />";

        $queryResultsArray['missingGrounds']= $this->runQueryIntoArray(QRY_MISSING_GROUNDS);
        echo "Time missingGrounds: " . (time() - $this->startTime) . "<br />";

        $queryResultsArray['missingAgeGroups']= $this->runQueryIntoArray(QRY_MISSING_AGEGROUPS);
        echo "Time missingAgeGroups: " . (time() - $this->startTime) . "<br />";

        $queryResultsArray['mv01Differences']= $this->runQueryIntoArray(QRY_MV01_DIFF);
        echo "Time mv01Differences: " . (time() - $this->startTime) . "<br />";
        $queryResultsArray['mv02Differences']= $this->runQueryIntoArray(QRY_MV02_DIFF);
        echo "Time mv02Differences: " . (time() - $this->startTime) . "<br />";
        $queryResultsArray['mv04Differences']= $this->runQueryIntoArray(QRY_MV04_DIFF);
        echo "Time mv04Differences: " . (time() - $this->startTime) . "<br />";
        $queryResultsArray['mv05Differences']= $this->runQueryIntoArray(QRY_MV05_DIFF);
        echo "Time mv05Differences: " . (time() - $this->startTime) . "<br />";
        #$queryResultsArray['mv06Differences']= $this->runQueryIntoArray(QRY_MV06_DIFF);
        #echo "Time mv06Differences: " . (time() - $this->startTime) . "<br />";
        $queryResultsArray['mv07Differences']= $this->runQueryIntoArray(QRY_MV07_DIFF);
        echo "Time mv07Differences: " . (time() - $this->startTime) . "<br />";
        $queryResultsArray['mv08Differences']= $this->runQueryIntoArray(QRY_MV08_DIFF);
        echo "Time mv08Differences: " . (time() - $this->startTime) . "<br />";
        


        return $queryResultsArray;
        
    }
/*
    private function newFindDataDifferences() {
        $queryDataArray = array(
                array(
                    'name' => 'Missing Umpires',
                    'desc' => 'Umpires in one database and not the other.',
                    'query' => QRY_MISSING_UMPIRES
                ),
                array(
                    'name' => 'Umpire Differences',
                    'desc' => 'Umpires with data that is different between databases.',
                    'query' => QRY_UMPIRE_DIFF
                ),
            );
        $queryResultsArray = array();
        foreach ($queryDataArray AS $key => $subArray) {
            queryResultsArray[]['name'] = $subArray['name'];
            queryResultsArray[]['desc'] = $subArray['desc'];
            queryResultsArray[]['results'] = $this->runQueryIntoArray($subArray['query']);
        }


    }
    */

}


        